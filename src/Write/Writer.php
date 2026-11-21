<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Write;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Document\DsvDocument;
use SteffenR\Dsv\Exception\DsvWriteException;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Parser;
use SteffenR\Dsv\Schema\ListSchema;
use SteffenR\Dsv\Support\Text;
use SteffenR\Dsv\Typed\TypedList;
use SteffenR\Dsv\Typed\TypedRecord;

/**
 * Writes DSV text. Lenient on reading, strict on writing: the output of
 * {@see self::writeList()} is read back with the library's own parser and every
 * finding that is not explicitly allowed prevents the output.
 */
final class Writer
{
    private function __construct() {}

    /**
     * Writes a schema-free document. As long as it is unchanged the result is
     * byte-identical to the input.
     */
    public static function writeDocument(DsvDocument $document): string
    {
        $body = '';
        foreach ($document->items as $item) {
            $body .= $item->raw . $item->eol;
        }

        return $document->hasBom ? "\xEF\xBB\xBF" . $body : $body;
    }

    /**
     * Writes records strictly. Canonical output: no space after the colon, every
     * field terminated with ";", `DATEIENDE` bare, CRLF by default. Comments,
     * blank lines and quirks of the source are lost; use
     * {@see self::writeDocument()} for byte identity.
     *
     * @param list<TypedRecord>|TypedList $records
     *
     * @throws DsvWriteException when required fields are missing, values are
     *                           invalid, elements unknown or cardinalities violated
     */
    public static function writeList(array|TypedList $records, ListSchema $schema, ?WriteOptions $options = null): string
    {
        return self::write($records, $schema, $options ?? new WriteOptions(), false)->text;
    }

    /**
     * Like {@see self::writeList()} but passes pre-existing defects through
     * (read a real file, change one entry, save it again). What was passed on
     * is reported in {@see WriteResult::$preservedDefects}.
     *
     * Passed through is only what the file brought along and keeps readable:
     * empty required fields and tolerated enum values. Wrong field counts,
     * broken element order or unknown elements stay refused.
     *
     * @param list<TypedRecord>|TypedList $records
     *
     * @throws DsvWriteException for every finding that is not a pre-existing defect
     */
    public static function writeListPreservingDefects(array|TypedList $records, ListSchema $schema, ?WriteOptions $options = null): WriteResult
    {
        return self::write($records, $schema, $options ?? new WriteOptions(), true);
    }

    /** @param list<TypedRecord>|TypedList $records */
    private static function write(array|TypedList $records, ListSchema $schema, WriteOptions $options, bool $preserve): WriteResult
    {
        $list = $records instanceof TypedList ? $records->records : $records;
        $version = $options->version ?? self::declaredVersion($list);

        if ($version === null) {
            throw new DsvWriteException([new Diagnostic(
                DiagnosticCode::UnsupportedFormatVersion,
                Severity::Fatal,
                'No supported DSV format version given; pass WriteOptions::$version or a FORMAT record',
            )]);
        }

        [$text, $unknown] = self::serialize($list, $schema, $version, $options->eol);

        if ($unknown !== []) {
            throw new DsvWriteException(array_map(
                static fn(string $element): Diagnostic => new Diagnostic(
                    DiagnosticCode::UnknownElement,
                    Severity::Error,
                    \sprintf('%s is not an element of %s in DSV%d', $element, $schema->listType, $version->value),
                    1,
                    ['element' => $element],
                ),
                $unknown,
            ));
        }

        $check = Parser::parseTyped($text, $schema);
        $problems = array_values(array_filter($check->diagnostics, self::isWriteFailure(...)));

        $preserved = $preserve ? array_values(array_filter($problems, self::isPreexistingDefect(...))) : [];
        $blocking = array_values(array_filter($problems, static fn(Diagnostic $d): bool => !\in_array($d, $preserved, true)));

        if ($blocking !== []) {
            throw new DsvWriteException($blocking);
        }

        return new WriteResult($text, $preserved);
    }

    /** @param list<TypedRecord> $records */
    private static function declaredVersion(array $records): ?FormatVersion
    {
        foreach ($records as $record) {
            if (strcasecmp($record->element, 'FORMAT') === 0) {
                return FormatVersion::tryFrom((int) ($record->values['version'] ?? 0));
            }
        }

        return null;
    }

    /**
     * @param list<TypedRecord> $records
     *
     * @return array{0: string, 1: list<string>} text and the names of unknown elements
     */
    private static function serialize(array $records, ListSchema $schema, FormatVersion $version, string $eol): array
    {
        $text = '';
        $unknown = [];

        foreach ($records as $record) {
            $def = $schema->find($record->element)?->def;
            if ($def === null || !$def->existsIn($version)) {
                $unknown[] = $record->element;
                continue;
            }

            if ($def->bare) {
                $text .= $def->name . $eol;
                continue;
            }

            // FORMAT carries the version being written, not whatever the record holds.
            $isFormat = strcasecmp($def->name, 'FORMAT') === 0;
            $fields = '';
            foreach ($def->fieldsFor($version) as $field) {
                $raw = $isFormat && $field->name === 'version' ? (string) $version->value : ($record->values[$field->name] ?? '');
                $fields .= Text::trim($raw) . ';';
            }
            $text .= $def->name . ':' . $fields . $eol;
        }

        return [$text, $unknown];
    }

    /**
     * Whether a finding of the read-back prevents the output. Fatal and error
     * always; info never; for warnings only a few codes are tolerated in own
     * output — everything else is the writer's own responsibility.
     */
    private static function isWriteFailure(Diagnostic $d): bool
    {
        return match ($d->severity) {
            Severity::Fatal, Severity::Error => true,
            Severity::Info => false,
            Severity::Warning => !match ($d->code) {
                // Cross rules phrased as "should" that real DSV files violate.
                DiagnosticCode::InvalidValue, DiagnosticCode::ConditionalFieldRequired => true,
                default => false,
            },
        };
    }

    private static function isPreexistingDefect(Diagnostic $d): bool
    {
        if ($d->severity === Severity::Fatal) {
            return false;
        }

        return match ($d->code) {
            DiagnosticCode::MissingRequiredField => true,
            DiagnosticCode::InvalidEnumValue => ($d->data['tolerated'] ?? false) === true,
            default => false,
        };
    }
}
