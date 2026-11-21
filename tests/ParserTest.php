<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Document\DsvDocument;
use SteffenR\Dsv\Document\ParseResult;
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Exception\DsvParseException;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Parser;
use SteffenR\Dsv\Schema\ListType;

#[CoversClass(Parser::class)]
#[CoversClass(Dsv::class)]
final class ParserTest extends TestCase
{
    use FixtureTrait;

    /**
     * @param ParseResult<mixed> $result
     *
     * @return list<string>
     */
    private static function codes(ParseResult $result): array
    {
        return array_map(static fn(Diagnostic $d): string => $d->code->value, $result->diagnostics);
    }

    public function testEmptyInputIsFatal(): void
    {
        $r = Dsv::parse('');

        self::assertFalse($r->ok);
        self::assertSame(Severity::Fatal, $r->diagnostics[0]->severity);
        self::assertSame(DiagnosticCode::EmptyInput, $r->diagnostics[0]->code);
    }

    public function testMissingFormat(): void
    {
        $r = Dsv::parse("ERZEUGER:a;b;c;\nDATEIENDE\n");

        self::assertFalse($r->ok);
        self::assertContains('missing-format-element', self::codes($r));
    }

    public function testCommentsBeforeFormatAreNormal(): void
    {
        $r = Dsv::parse("(* erzeugt mit EasyWk *)\nFORMAT: Vereinsmeldeliste;7;\nDATEIENDE\n");

        self::assertTrue($r->ok);
        /** @var DsvDocument $doc */
        $doc = $r->document;
        self::assertSame('Vereinsmeldeliste', $doc->listType);
        self::assertSame(7, $doc->version);
        self::assertSame([], self::codes($r));
    }

    public function testFormatNotFirstElementWarns(): void
    {
        $r = Dsv::parse("ERZEUGER:a;b;c;\nFORMAT:Vereinsmeldeliste;7;\nDATEIENDE\n");

        self::assertTrue($r->ok);
        self::assertSame(['format-not-first-element'], self::codes($r));
    }

    public function testDateiendeChecks(): void
    {
        self::assertSame(['missing-dateiende-element'], self::codes(Dsv::parse("FORMAT:X;7;\n")));
        self::assertSame(['element-order-violation'], self::codes(Dsv::parse("FORMAT:X;7;\nDATEIENDE\nERZEUGER:a;b;c;\n")));
        self::assertSame([], self::codes(Dsv::parse("FORMAT:X;7;\nDATEIENDE\n(* tail comment *)\n\n")));
    }

    public function testBomIsRemovedReportedAndPreserved(): void
    {
        $input = "\xEF\xBB\xBFFORMAT:X;7;\nDATEIENDE\n";
        $r = Dsv::parse($input);

        self::assertContains('unexpected-bom', self::codes($r));
        /** @var DsvDocument $doc */
        $doc = $r->document;
        self::assertTrue($doc->hasBom);
        self::assertSame('X', $doc->listType);
        self::assertSame($input, Dsv::write($doc));
    }

    public function testReplacementCharacterAndInvalidUtf8Warn(): void
    {
        self::assertContains('unknown-encoding-replacement-character', self::codes(Dsv::parse("FORMAT:X;7;\nA:\u{FFFD};\nDATEIENDE")));
        self::assertContains('unknown-encoding-replacement-character', self::codes(Dsv::parse("FORMAT:X;7;\nA:\xE4;\nDATEIENDE")));
    }

    /** @return iterable<string, array{string, int}> */
    public static function unsupportedVersions(): iterable
    {
        yield 'dsv6' => ["FORMAT:Wettkampfergebnisliste;6;\nDATEIENDE\n", 6];
        yield 'dsv9' => ["FORMAT:Wettkampfergebnisliste;9;\nDATEIENDE\n", 9];
    }

    #[DataProvider('unsupportedVersions')]
    public function testUnsupportedVersionIsFatalAtBothLevels(string $input, int $version): void
    {
        $plain = Dsv::parse($input);
        self::assertFalse($plain->ok);
        self::assertSame(['unsupported-format-version'], array_values(array_filter(self::codes($plain), static fn(string $c): bool => $c === 'unsupported-format-version')));

        $typed = Dsv::parseList($input, ListType::Wettkampfergebnisliste);
        self::assertFalse($typed->ok);
        self::assertSame([], $typed->document->records);
        self::assertSame(1, \count(array_filter(self::codes($typed), static fn(string $c): bool => $c === 'unsupported-format-version')), 'reported exactly once');
        self::assertSame($version, $plain->diagnostics[\count($plain->diagnostics) - 1]->data['version']);
    }

    public function testNoReadableVersionIsReportedOnTypedLevel(): void
    {
        $typed = Dsv::parseList("FORMAT:Wettkampfergebnisliste;;\nDATEIENDE\n", ListType::Wettkampfergebnisliste);

        self::assertFalse($typed->ok);
        self::assertContains('unsupported-format-version', self::codes($typed));
    }

    public function testWrongListTypeIsFatal(): void
    {
        $r = Dsv::parseList("FORMAT:Vereinsmeldeliste;7;\nDATEIENDE\n", ListType::Wettkampfergebnisliste);

        self::assertFalse($r->ok);
        self::assertContains('wrong-list-type', self::codes($r));
        self::assertSame([], $r->document->records);
    }

    public function testListTypeIsMatchedCaseInsensitively(): void
    {
        $r = Dsv::parseList("FORMAT:VEREINSMELDELISTE;7;\nDATEIENDE\n", ListType::Vereinsmeldeliste);

        self::assertSame('VEREINSMELDELISTE', $r->document->listType);
        self::assertSame(FormatVersion::V7, $r->document->version);
    }

    public function testParseOrThrow(): void
    {
        self::assertInstanceOf(DsvDocument::class, Dsv::parseOrThrow("FORMAT:X;7;\nDATEIENDE\n"));

        $this->expectException(DsvParseException::class);
        Dsv::parseOrThrow("FORMAT:X;6;\nDATEIENDE\n");
    }

    public function testParseOrThrowCarriesDiagnostics(): void
    {
        try {
            Dsv::parseOrThrow('');
            self::fail('expected exception');
        } catch (DsvParseException $e) {
            self::assertSame(DiagnosticCode::EmptyInput, $e->diagnostics[0]->code);
            self::assertStringContainsString('empty-input', $e->getMessage());
        }
    }

    public function testTypedRecordsAreKeyedBySchemaName(): void
    {
        $r = Dsv::parseList(self::fixture('synth/pflichtzeit.dsv7'), ListType::Wettkampfdefinitionsliste);

        self::assertTrue($r->ok, implode("\n", array_map(strval(...), $r->diagnostics)));
        $v = $r->document->first('veranstaltung');
        self::assertNotNull($v);
        self::assertSame('VERANSTALTUNG', $v->element);
        self::assertSame('Synthetischer Wettkampf', $v->get('veranstaltungsbezeichnung'));
        self::assertSame('25', $v->get('bahnlaenge'));
        self::assertCount(4, $r->document->records('PFLICHTZEIT'));
        self::assertSame(5500, $r->document->records('PFLICHTZEIT')[0]->time('pflichtzeit'));
    }

    public function testDsv8FieldsExistOnlyInDsv8(): void
    {
        $dsv8 = Dsv::parseList(self::fixture('synth/delta-wettkampfdefinitionsliste-dsv8.dsv8'), ListType::Wettkampfdefinitionsliste);
        $verstoss = Dsv::parseList(self::fixture('synth/delta-wettkampfdefinitionsliste-verstoss.dsv7'), ListType::Wettkampfdefinitionsliste);

        self::assertTrue($dsv8->ok, implode("\n", array_map(strval(...), $dsv8->errors())));
        self::assertFalse($verstoss->ok, 'DSV8 content declared as DSV7 must be rejected');
    }

    public function testParseAnyDetectsListTypeFromFormat(): void
    {
        $r = Dsv::parseAny(self::fixture('synth/ergebnis-staffel.dsv7'));

        self::assertSame('Wettkampfergebnisliste', $r->document->listType);
        self::assertTrue($r->ok);
    }

    public function testParseAnyRejectsUnknownListType(): void
    {
        $r = Dsv::parseAny("FORMAT:Meisterschaftsliste;7;\nDATEIENDE\n");

        self::assertFalse($r->ok);
        self::assertContains('wrong-list-type', self::codes($r));
    }

    public function testParseFile(): void
    {
        $r = Dsv::parseFile(__DIR__ . '/fixtures/synth/nachweis.dsv7');
        self::assertTrue($r->ok);
    }

    public function testParseFileUnpacksDsv8zArchive(): void
    {
        $path = $this->archive(['2002-03-10-Duisburg-Pr.DSV8' => (string) file_get_contents(__DIR__ . '/../examples/data/meeting.dsv8')]);

        $r = Dsv::parseFile($path);

        self::assertTrue($r->ok);
        self::assertSame('WETTKAMPFERGEBNISLISTE', $r->document->listType);
    }

    public function testParseFileRejectsArchiveWithSeveralFiles(): void
    {
        $path = $this->archive(['a.DSV8' => 'x', 'b.DSV8' => 'y']);

        $this->expectException(\SteffenR\Dsv\Exception\DsvFileException::class);
        $this->expectExceptionMessage('exactly one DSV file');
        Dsv::parseFile($path);
    }

    /** @param array<string,string> $entries */
    private function archive(array $entries): string
    {
        if (!class_exists(\ZipArchive::class)) {
            self::markTestSkipped('ext-zip is not available');
        }

        $path = tempnam(sys_get_temp_dir(), 'dsv8z');
        self::assertNotFalse($path);
        $zip = new \ZipArchive();
        self::assertTrue($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE));
        foreach ($entries as $name => $content) {
            $zip->addFromString($name, $content);
        }
        $zip->close();

        return $path;
    }

    public function testParseFileMissing(): void
    {
        $this->expectException(\SteffenR\Dsv\Exception\DsvFileException::class);
        Dsv::parseFile(__DIR__ . '/fixtures/does-not-exist.dsv7');
    }
}
