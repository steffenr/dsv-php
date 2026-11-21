<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests\Graph;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Graph\DefinitionProjector;
use SteffenR\Dsv\Graph\RecordReader;
use SteffenR\Dsv\Graph\ResultProjector;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Tests\FixtureTrait;
use SteffenR\Dsv\Values\Datum;

#[CoversClass(DefinitionProjector::class)]
#[CoversClass(ResultProjector::class)]
#[CoversClass(RecordReader::class)]
final class ProjectorsTest extends TestCase
{
    use FixtureTrait;

    public function testDefinitionGraph(): void
    {
        $list = Dsv::parseList(self::fixture('synth/pflichtzeit.dsv7'), ListType::Wettkampfdefinitionsliste)->document;
        $p = Dsv::projectDefinition($list);
        $g = $p->graph;

        self::assertSame([], $p->diagnostics);
        self::assertSame('Synthetischer Wettkampf', $g->veranstaltung->bezeichnung);
        self::assertCount(1, $g->abschnitte);
        self::assertEquals(new Datum(15, 3, 2026), $g->abschnitte[0]->datum);
        self::assertSame(480, $g->abschnitte[0]->einlass);
        self::assertSame(540, $g->abschnitte[0]->anfangszeit);

        $w = $g->wettkampfByKey['1:V'];
        self::assertSame($w, $g->abschnitte[0]->wettkaempfe[0]);
        self::assertSame('F', $w->technik);
        self::assertCount(1, $w->wertungen);
        self::assertSame($w->wertungen[0], $g->wertungById[1]);
        self::assertCount(4, $w->pflichtzeiten);
        self::assertSame(5500, $w->pflichtzeiten[0]->zeit);
        self::assertNull($w->qualifikationAus);
    }

    public function testDefinitionGraphReportsDanglingReferences(): void
    {
        $text = "FORMAT:Wettkampfdefinitionsliste;7;\r\n"
            . "ABSCHNITT:1;15.03.2026;;;09:00;N;\r\nABSCHNITT:1;16.03.2026;;;09:00;N;\r\n"
            . "WETTKAMPF:1;V;9;;100;F;GL;W;SW;;;\r\nWETTKAMPF:1;V;1;;100;F;GL;W;SW;;;\r\n"
            . "WETTKAMPF:2;F;1;;100;F;GL;W;SW;7;V;\r\n"
            . "WERTUNG:5;V;1;JG;0;9999;;x;\r\nPFLICHTZEIT:8;V;JG;0;1990;00:00:55,00;;\r\nDATEIENDE\r\n";
        $list = Dsv::parseList($text, ListType::Wettkampfdefinitionsliste)->document;

        $p = Dsv::projectDefinition($list);
        $codes = array_map(static fn($d): string => $d->code->value, $p->diagnostics);

        self::assertContains('ambiguous-reference', $codes, 'duplicate ABSCHNITT / WETTKAMPF');
        self::assertContains('dangling-reference', $codes);
        self::assertCount(1, $p->graph->wettkaempfeOhneAbschnitt);
        self::assertSame(['nummer' => 7, 'art' => 'V'], $p->graph->wettkampfByKey['2:F']->qualifikationAus);
        self::assertCount(1, $p->graph->abschnittByNummer);
        self::assertSame(15, $p->graph->abschnittByNummer[1]->datum?->day, 'the first duplicate wins');
    }

    public function testResultGraphOfRelays(): void
    {
        $list = Dsv::parseList(self::fixture('synth/ergebnis-staffel.dsv7'), ListType::Wettkampfergebnisliste)->document;
        $p = Dsv::projectResult($list);
        $g = $p->graph;

        // The fixture deliberately names only one member in four of its five relays.
        self::assertSame(['incomplete-relay'], array_values(array_unique(array_map(static fn($d): string => $d->code->value, $p->diagnostics))));
        self::assertCount(5, $g->wettkampfByKey);
        self::assertCount(5, $g->staffelByKey);
        self::assertCount(2, $g->vereine);
        self::assertArrayHasKey(1234, $g->vereinByKennzahl);
        self::assertArrayNotHasKey(0, $g->vereinByKennzahl, 'club number 0 is "foreign club", not a key');

        $staffel = $g->staffelByKey['9001:10:V'];
        self::assertSame($staffel, $g->wettkampfByKey['10:V']->staffeln[0]);
        self::assertSame(25234, $staffel->endzeit);
        self::assertCount(4, $staffel->personen);
        self::assertSame(['GER', 'POL', 'UKR'], $staffel->personen[0]->nationalitaeten);
        self::assertCount(2, $staffel->zwischenzeiten);
        self::assertCount(4, $staffel->abloesen);
        self::assertSame(['nummer' => 10, 'art' => 'V'], $g->wettkampfByKey['10:F']->qualifikationAus);
        self::assertNull($g->wettkampfByKey['10:F']->staffeln[0]->startnummerDisqualifiziert, 'N is not a number');
    }

    public function testRelayWithOnlySomeMembersIsIncomplete(): void
    {
        $text = "FORMAT:Wettkampfergebnisliste;7;\r\nABSCHNITT:1;15.03.2026;09:00;N;\r\n"
            . "WETTKAMPF:10;V;1;4;100;F;GL;X;SW;;;\r\nWERTUNG:10;V;1;AK;100;;X;S;\r\n"
            . "STERGEBNIS:10;V;1;1;;1;9001;SV;1234;00:04:12,34;;;;\r\n"
            . "STAFFELPERSON:9001;10;V;A, B;1;1;W;2008;;GER;;;\r\nDATEIENDE\r\n";
        $p = Dsv::projectResult(Dsv::parseList($text, ListType::Wettkampfergebnisliste)->document);

        self::assertSame(['incomplete-relay'], array_map(static fn($d): string => $d->code->value, $p->diagnostics));
        self::assertCount(1, $p->graph->staffelByKey);
    }

    /** @return iterable<string, array{string}> */
    public static function realResultFiles(): iterable
    {
        foreach (self::fixturePaths('real') as $path) {
            if (preg_match('/^FORMAT:\\s*Wettkampfergebnisliste/mi', self::fixture($path)) === 1) {
                yield $path => [$path];
            }
        }
    }

    #[DataProvider('realResultFiles')]
    public function testEveryStartOfARealFileLandsInTheGraph(string $path): void
    {
        $parsed = Dsv::parseAny(self::fixture($path));
        if ($parsed->document->records === []) {
            self::markTestSkipped('unsupported version');
        }

        $g = Dsv::projectResult($parsed->document)->graph;

        $inCompetitions = 0;
        foreach ($g->wettkampfByKey as $w) {
            $inCompetitions += \count($w->starts);
        }
        $keys = [];
        foreach ($parsed->document->records('PNERGEBNIS') as $r) {
            $keys[(string) $r->get('veranstaltungsId') . ':' . (string) $r->get('wettkampfnr') . ':' . $r->get('wettkampfart')] = true;
        }

        self::assertCount(\count($keys), $g->startByKey);
        self::assertLessThanOrEqual(\count($g->startByKey), $inCompetitions);
        foreach ($g->personById as $person) {
            self::assertNotSame([], $person->starts);
        }
    }

    public function testProjectionIsAvailableForDiagnosticsFromRealFiles(): void
    {
        $count = 0;
        foreach (self::fixturePaths('real') as $path) {
            $parsed = Dsv::parseAny(self::fixture($path));
            if ($parsed->document->listType === 'Wettkampfdefinitionsliste' && $parsed->document->records !== []) {
                $g = Dsv::projectDefinition($parsed->document)->graph;
                self::assertNotSame([], $g->abschnitte, $path);
                ++$count;
            }
        }

        self::assertGreaterThan(0, $count);
        self::assertTrue(DiagnosticCode::DanglingReference->value === 'dangling-reference');
    }
}
