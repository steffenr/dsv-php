<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Exception\DsvWriteException;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Typed\TypedRecord;
use SteffenR\Dsv\Write\WriteOptions;
use SteffenR\Dsv\Write\Writer;

#[CoversClass(Writer::class)]
final class WriterTest extends TestCase
{
    use FixtureTrait;

    /** @return list<TypedRecord> */
    private static function minimalMeldeliste(): array
    {
        $records = Dsv::parseList(self::fixture('synth/vereinsmeldung-knapp.dsv8'), ListType::Vereinsmeldeliste)->document->records;
        self::assertNotSame([], $records);

        return $records;
    }

    public function testRoundTripOfTypedListIsStableAndValid(): void
    {
        foreach (['synth/pflichtzeit.dsv7', 'synth/ergebnis-staffel.dsv7', 'synth/ergebnis-dsv8.dsv8'] as $path) {
            $first = Dsv::parseAny(self::fixture($path));
            self::assertTrue($first->ok, $path);

            $text = Dsv::writeList($first->document, ListType::from($first->document->listType));
            $second = Dsv::parseAny($text);

            self::assertTrue($second->ok, $path);
            self::assertEquals(
                array_map(static fn(TypedRecord $r): array => [$r->element, $r->values], $first->document->records),
                array_map(static fn(TypedRecord $r): array => [$r->element, $r->values], $second->document->records),
                $path,
            );
        }
    }

    public function testCanonicalOutputDropsQuirksButStaysValid(): void
    {
        $source = self::fixture('real/' . basename(self::fixturePaths('real')[0]));
        $parsed = Dsv::parseAny($source);
        $type = ListType::from($parsed->document->listType);

        $text = Dsv::writeListPreservingDefects($parsed->document, $type)->text;

        self::assertStringNotContainsString('(*', $text, 'comments are not part of the canonical form');
        self::assertStringNotContainsString(': ', $text, 'no space after the colon');
        self::assertStringStartsWith('FORMAT:', $text);
        self::assertStringEndsWith("DATEIENDE\r\n", $text);
    }

    public function testIncompleteListIsRefused(): void
    {
        $this->expectException(DsvWriteException::class);
        Dsv::writeList(
            [new TypedRecord('FORMAT', ['listart' => 'Vereinsmeldeliste', 'version' => '8']), new TypedRecord('DATEIENDE', [])],
            ListType::Vereinsmeldeliste,
        );
    }

    public function testUpgradeToDsv8RewritesFormat(): void
    {
        $list = Dsv::parseList(self::fixture('synth/delta-wettkampfergebnisliste-dsv7.dsv7'), ListType::Wettkampfergebnisliste);
        self::assertTrue($list->ok);

        $as8 = Dsv::writeList($list->document, ListType::Wettkampfergebnisliste, new WriteOptions(version: FormatVersion::V8));

        self::assertStringContainsString('FORMAT:Wettkampfergebnisliste;8;', $as8);
        self::assertTrue(Dsv::parseList($as8, ListType::Wettkampfergebnisliste)->ok);
    }

    public function testUpgradeIsRefusedWhenDsv8NeedsMoreData(): void
    {
        $list = Dsv::parseList(self::fixture('synth/delta-wettkampfdefinitionsliste-dsv7.dsv7'), ListType::Wettkampfdefinitionsliste);

        try {
            Dsv::writeList($list->document, ListType::Wettkampfdefinitionsliste, new WriteOptions(version: FormatVersion::V8));
            self::fail('DSV8 requires BANKVERBINDUNG.kontoinhaber');
        } catch (DsvWriteException $e) {
            self::assertSame('missing-required-field', $e->diagnostics[0]->code->value);
        }
    }

    public function testDowngradeOfDsv8ContentIsRefused(): void
    {
        $list = Dsv::parseList(self::fixture('synth/delta-wettkampfergebnisliste-dsv8.dsv8'), ListType::Wettkampfergebnisliste);

        $this->expectException(DsvWriteException::class);
        Dsv::writeList($list->document, ListType::Wettkampfergebnisliste, new WriteOptions(version: FormatVersion::V7));
    }

    public function testEolOptionIsApplied(): void
    {
        $list = Dsv::parseList(self::fixture('synth/delta-wettkampfergebnisliste-dsv7.dsv7'), ListType::Wettkampfergebnisliste);
        $text = Dsv::writeList($list->document, ListType::Wettkampfergebnisliste, new WriteOptions(eol: "\n"));

        self::assertStringNotContainsString("\r", $text);
        self::assertStringContainsString("\n", $text);
    }
    public function testWriteRefusesInvalidValues(): void
    {
        $records = self::minimalMeldeliste();
        $broken = array_map(
            static fn(TypedRecord $r): TypedRecord => $r->element === 'VERANSTALTUNG' ? $r->with(['bahnlaenge' => '77']) : $r,
            $records,
        );

        $this->expectException(DsvWriteException::class);
        Dsv::writeList($broken, ListType::Vereinsmeldeliste);
    }

    public function testWriteRefusesUnknownElementAndMissingVersion(): void
    {
        try {
            Dsv::writeList([new TypedRecord('FORMAT', ['listart' => 'Vereinsmeldeliste', 'version' => '8']), new TypedRecord('NOPE', [])], ListType::Vereinsmeldeliste);
            self::fail('expected exception');
        } catch (DsvWriteException $e) {
            self::assertSame('unknown-element', $e->diagnostics[0]->code->value);
        }

        $this->expectException(DsvWriteException::class);
        $this->expectExceptionMessage('No supported DSV format version');
        Dsv::writeList([new TypedRecord('DATEIENDE', [])], ListType::Vereinsmeldeliste);
    }

    public function testStrictWriteRefusesWhatPreservingWriteReports(): void
    {
        $realWithDefect = null;
        foreach (self::fixturePaths('real') as $path) {
            $r = Dsv::parseAny(self::fixture($path));
            $codes = array_map(static fn($d): string => $d->code->value, $r->errors());
            if ($codes !== [] && array_unique($codes) === ['missing-required-field'] && ListType::fromName($r->document->listType) !== null) {
                $realWithDefect = [$path, $r];
                break;
            }
        }
        self::assertNotNull($realWithDefect, 'expected a real file whose only defect is an empty required field');
        [, $r] = $realWithDefect;
        $type = ListType::from($r->document->listType);

        $result = Dsv::writeListPreservingDefects($r->document, $type);
        self::assertNotSame([], $result->preservedDefects);
        foreach ($result->preservedDefects as $defect) {
            self::assertSame('missing-required-field', $defect->code->value);
        }

        $this->expectException(DsvWriteException::class);
        Dsv::writeList($r->document, $type);
    }

    public function testDocumentWriteIsByteIdentical(): void
    {
        foreach (["A:1;\r\nB:2;\nC:3;", "\xEF\xBB\xBFFORMAT:X;7;\n\n(* x *)\n", ''] as $input) {
            self::assertSame($input, Dsv::write(Dsv::parse($input)->document));
        }
    }
}
