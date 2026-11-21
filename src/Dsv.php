<?php

declare(strict_types=1);

namespace SteffenR\Dsv;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Document\DsvDocument;
use SteffenR\Dsv\Document\ParseResult;
use SteffenR\Dsv\Exception\DsvFileException;
use SteffenR\Dsv\Exception\DsvParseException;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Graph\Definition\Wettkampfdefinition;
use SteffenR\Dsv\Graph\DefinitionProjector;
use SteffenR\Dsv\Graph\ProjectionResult;
use SteffenR\Dsv\Graph\Result\Wettkampfergebnis;
use SteffenR\Dsv\Graph\ResultProjector;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Typed\TypedList;
use SteffenR\Dsv\Typed\TypedRecord;
use SteffenR\Dsv\Write\WriteOptions;
use SteffenR\Dsv\Write\Writer;
use SteffenR\Dsv\Write\WriteResult;

/**
 * Entry point of the library.
 *
 * ```php
 * $result = Dsv::parseList(file_get_contents('wk.dsv7'), ListType::Wettkampfergebnisliste);
 * foreach ($result->document->records('VERANSTALTUNG') as $record) { … }
 * ```
 */
final class Dsv
{
    private function __construct() {}

    /**
     * Schema-free parse (every line retained for byte-identical write-back).
     *
     * @return ParseResult<DsvDocument>
     */
    public static function parse(string $input): ParseResult
    {
        return Parser::parseDocument($input);
    }

    /**
     * Like {@see self::parse()} but returns the document and throws on failure.
     *
     * @throws DsvParseException when the result is not ok
     */
    public static function parseOrThrow(string $input): DsvDocument
    {
        $result = Parser::parseDocument($input);
        if (!$result->ok) {
            throw new DsvParseException($result->errors());
        }

        /** @var DsvDocument */
        return $result->document;
    }

    /**
     * Parses a list of a known type with validation.
     *
     * @return ParseResult<TypedList>
     */
    public static function parseList(string $input, ListType $type): ParseResult
    {
        return Parser::parseTyped($input, $type->schema());
    }

    /**
     * Detects the list type from the FORMAT element (never from the file name)
     * and parses accordingly.
     *
     * @return ParseResult<TypedList>
     */
    public static function parseAny(string $input): ParseResult
    {
        $probe = Parser::parseDocument($input);
        /** @var DsvDocument $document */
        $document = $probe->document;
        $type = ListType::fromName($document->listType);

        if ($type === null) {
            $diagnostics = $probe->diagnostics;
            if ($document->listType !== null) {
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::WrongListType,
                    Severity::Fatal,
                    \sprintf('Unsupported list type: %s', $document->listType),
                    1,
                    ['actual' => $document->listType],
                );
            }

            return new ParseResult(
                new TypedList($document->listType ?? '', FormatVersion::V7, [], $document),
                $diagnostics,
                false,
            );
        }

        return self::parseList($input, $type);
    }

    /**
     * Reads a file and parses it with {@see self::parseAny()}.
     *
     * A packed `.DSV8z` archive (standard 8: a ZIP with exactly one DSV file) is unpacked
     * transparently; this needs `ext-zip`.
     *
     * @throws DsvFileException
     *
     * @return ParseResult<TypedList>
     */
    public static function parseFile(string $path): ParseResult
    {
        return self::parseAny(self::read($path));
    }

    /** Writes a schema-free document, byte-identical to the input if unchanged. */
    public static function write(DsvDocument $document): string
    {
        return Writer::writeDocument($document);
    }

    /**
     * Writes records strictly as a list of the given type.
     *
     * @param list<TypedRecord>|TypedList $records
     *
     * @throws \SteffenR\Dsv\Exception\DsvWriteException
     */
    public static function writeList(array|TypedList $records, ListType $type, ?WriteOptions $options = null): string
    {
        return Writer::writeList($records, $type->schema(), $options);
    }

    /**
     * Writes records and passes pre-existing defects of a real file through.
     *
     * @param list<TypedRecord>|TypedList $records
     *
     * @throws \SteffenR\Dsv\Exception\DsvWriteException
     */
    public static function writeListPreservingDefects(array|TypedList $records, ListType $type, ?WriteOptions $options = null): WriteResult
    {
        return Writer::writeListPreservingDefects($records, $type->schema(), $options);
    }

    /**
     * Builds the object graph of a Wettkampfdefinitionsliste (sections →
     * competitions → ranking groups and qualifying times).
     *
     * @return ProjectionResult<Wettkampfdefinition>
     */
    public static function projectDefinition(TypedList $list): ProjectionResult
    {
        return DefinitionProjector::project($list);
    }

    /**
     * Builds the object graph of a Wettkampfergebnisliste (sections →
     * competitions → starts/relays → placements, times).
     *
     * @return ProjectionResult<Wettkampfergebnis>
     */
    public static function projectResult(TypedList $list): ProjectionResult
    {
        return ResultProjector::project($list);
    }

    private static function read(string $path): string
    {
        $content = @file_get_contents($path);
        if ($content === false) {
            throw new DsvFileException(\sprintf('Cannot read DSV file "%s"', $path));
        }

        // The content, not the extension, decides: archives get renamed by mail programs and browsers.
        if (str_starts_with($content, "PK\x03\x04")) {
            return self::readArchive($path);
        }

        return $content;
    }

    /** Extracts the single DSV file of a packed .DSV8z archive. */
    private static function readArchive(string $path): string
    {
        if (!class_exists(\ZipArchive::class)) {
            throw new DsvFileException(\sprintf('"%s" is a ZIP archive (.DSV8z) but ext-zip is not available', $path));
        }

        $zip = new \ZipArchive();
        if ($zip->open($path, \ZipArchive::RDONLY) !== true) {
            throw new DsvFileException(\sprintf('Cannot open ZIP archive "%s"', $path));
        }

        try {
            if ($zip->numFiles !== 1) {
                throw new DsvFileException(\sprintf(
                    'The archive "%s" must contain exactly one DSV file, found %d',
                    $path,
                    $zip->numFiles,
                ));
            }

            $content = $zip->getFromIndex(0);
        } finally {
            $zip->close();
        }

        if ($content === false) {
            throw new DsvFileException(\sprintf('Cannot read the DSV file inside "%s"', $path));
        }

        return $content;
    }
}
