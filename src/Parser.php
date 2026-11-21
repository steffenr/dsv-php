<?php

declare(strict_types=1);

namespace SteffenR\Dsv;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Document\DsvDocument;
use SteffenR\Dsv\Document\Element;
use SteffenR\Dsv\Document\ParseResult;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Lexer\Lexer;
use SteffenR\Dsv\Schema\ListSchema;
use SteffenR\Dsv\Support\Text;
use SteffenR\Dsv\Typed\TypedList;
use SteffenR\Dsv\Typed\TypedRecord;
use SteffenR\Dsv\Validate\Validator;

/**
 * Lenient parser: real files are often not spec-conformant, so findings are
 * reported as {@see Diagnostic}s and a value is returned wherever possible.
 */
final class Parser
{
    private const string BOM = "\xEF\xBB\xBF";

    private function __construct() {}

    /**
     * Schema-free parse; every line is kept for byte-identical round trips.
     *
     * @return ParseResult<DsvDocument>
     */
    public static function parseDocument(string $input): ParseResult
    {
        $hasBom = str_starts_with($input, self::BOM);
        $text = $hasBom ? substr($input, \strlen(self::BOM)) : $input;

        $items = [];
        $lines = Lexer::splitLines($text);
        foreach ($lines as $line) {
            $items[] = Lexer::lexLine($line['content'], $line['number'], $line['eol']);
        }

        $diagnostics = [];
        $elements = array_values(array_filter($items, static fn($i): bool => $i instanceof Element));
        $format = null;
        $dateiende = null;
        foreach ($elements as $element) {
            $format ??= $element->is('FORMAT') ? $element : null;
            $dateiende ??= $element->is('DATEIENDE') ? $element : null;
        }

        if ($lines === []) {
            $diagnostics[] = new Diagnostic(DiagnosticCode::EmptyInput, Severity::Fatal, 'Input is empty');
        } elseif ($format === null) {
            $diagnostics[] = new Diagnostic(DiagnosticCode::MissingFormatElement, Severity::Error, 'FORMAT element is missing');
        } elseif ($elements[0] !== $format) {
            // Comment lines before FORMAT are normal: FORMAT must be the first *element*.
            $diagnostics[] = new Diagnostic(DiagnosticCode::FormatNotFirstElement, Severity::Warning, 'FORMAT is not the first element in the file', $format->line);
        }

        if ($lines !== [] && $dateiende === null) {
            $diagnostics[] = new Diagnostic(DiagnosticCode::MissingDateiendeElement, Severity::Warning, 'DATEIENDE element is missing');
        } elseif ($dateiende !== null && $elements[\count($elements) - 1] !== $dateiende) {
            $diagnostics[] = new Diagnostic(
                DiagnosticCode::ElementOrderViolation,
                Severity::Warning,
                'DATEIENDE is not the last element in the file',
                $dateiende->line,
                ['element' => 'DATEIENDE'],
            );
        }

        // Real files often omit the final ";": a warning, the line is still read in full.
        foreach ($elements as $element) {
            if (!$element->terminated) {
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::UnterminatedFieldList,
                    Severity::Warning,
                    \sprintf('%s: the attribute list does not end with ";"', $element->name),
                    $element->line,
                    ['element' => $element->name],
                );
            }
        }

        if ($hasBom) {
            $diagnostics[] = new Diagnostic(
                DiagnosticCode::UnexpectedBom,
                Severity::Warning,
                'Input starts with a UTF-8 BOM; the format requires UTF-8 without BOM',
            );
        }

        if (str_contains($input, "\xEF\xBF\xBD") || !Text::isUtf8($input)) {
            $diagnostics[] = new Diagnostic(
                DiagnosticCode::UnknownEncodingReplacementCharacter,
                Severity::Warning,
                'Input contains U+FFFD or invalid UTF-8; it was probably decoded with the wrong encoding',
            );
        }

        $raw = $format?->fields[1] ?? null;
        $version = $raw !== null && preg_match('/^\d+$/D', $raw) === 1 ? (int) $raw : null;

        // Only DSV7 and DSV8 are accepted. A version that is not readable at all is
        // a different case (missing FORMAT / field check) and is not reported here.
        if ($version !== null && FormatVersion::tryFrom($version) === null) {
            $diagnostics[] = FormatVersion::unsupported($version);
        }

        return new ParseResult(
            new DsvDocument($format?->fields[0] ?? null, $version, $items, $hasBom),
            $diagnostics,
            self::isOk($diagnostics),
        );
    }

    /**
     * Reads a list of the given list type and keys every field value by its
     * schema name. Missing fields or invalid values still yield records; only a
     * wrong list type or an unsupported version prevents evaluation.
     *
     * @return ParseResult<TypedList>
     */
    public static function parseTyped(string $input, ListSchema $schema): ParseResult
    {
        $parsed = self::parseDocument($input);
        /** @var DsvDocument $document */
        $document = $parsed->document;
        $actual = $document->listType;

        if ($actual !== null && strcasecmp($actual, $schema->listType) !== 0) {
            return self::rejected($document, [
                ...$parsed->diagnostics,
                new Diagnostic(
                    DiagnosticCode::WrongListType,
                    Severity::Fatal,
                    \sprintf('Expected list type %s, found %s', $schema->listType, $actual),
                    1,
                    ['expected' => $schema->listType, 'actual' => $actual],
                ),
            ]);
        }

        $version = FormatVersion::fromNumber($document->version);
        if ($version === null) {
            $reported = array_filter(
                $parsed->diagnostics,
                static fn(Diagnostic $d): bool => $d->code === DiagnosticCode::UnsupportedFormatVersion,
            );

            return self::rejected($document, $reported !== []
                ? $parsed->diagnostics
                : [...$parsed->diagnostics, FormatVersion::unsupported($document->version)]);
        }

        $diagnostics = [...$parsed->diagnostics, ...Validator::validate($document, $schema)];

        $records = [];
        foreach ($document->elements() as $element) {
            $def = $schema->find($element->name)?->def;
            if ($def === null) {
                continue;
            }
            $values = [];
            foreach ($def->fieldsFor($version) as $index => $field) {
                $values[$field->name] = $element->fields[$index] ?? '';
            }
            $records[] = new TypedRecord($def->name, $values, $element->line);
        }

        return new ParseResult(
            new TypedList($actual ?? $schema->listType, $version, $records, $document),
            $diagnostics,
            self::isOk($diagnostics),
        );
    }

    /**
     * @param list<Diagnostic> $diagnostics
     *
     * @return ParseResult<TypedList>
     */
    private static function rejected(DsvDocument $document, array $diagnostics): ParseResult
    {
        // Placeholder version: records are empty, so it carries no meaning.
        return new ParseResult(
            new TypedList($document->listType ?? '', FormatVersion::V7, [], $document),
            $diagnostics,
            false,
        );
    }

    /** @param list<Diagnostic> $diagnostics */
    private static function isOk(array $diagnostics): bool
    {
        foreach ($diagnostics as $d) {
            if ($d->severity->isFailure()) {
                return false;
            }
        }

        return true;
    }
}
