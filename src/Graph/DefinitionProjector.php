<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Graph\Definition\Abschnitt;
use SteffenR\Dsv\Graph\Definition\Pflichtzeit;
use SteffenR\Dsv\Graph\Definition\Wettkampf;
use SteffenR\Dsv\Graph\Definition\Wettkampfdefinition;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Typed\TypedList;
use SteffenR\Dsv\Typed\TypedRecord;
use SteffenR\Dsv\Values\Datum;
use SteffenR\Dsv\Values\Uhrzeit;
use SteffenR\Dsv\Values\Zeit;

/**
 * Builds the object graph of a Wettkampfdefinitionsliste with resolved
 * references: competitions hang under their section, ranking groups and
 * qualifying times under their competition.
 *
 * Never aborts: a reference into the void or a duplicate key yields a warning,
 * and the graph is built as completely as possible (the first duplicate wins).
 */
final class DefinitionProjector
{
    private function __construct() {}

    /** @return ProjectionResult<Wettkampfdefinition> */
    public static function project(TypedList $list): ProjectionResult
    {
        $r = new RecordReader(ListType::Wettkampfdefinitionsliste->schema());
        $records = $list->records;
        $diagnostics = [];

        $first = $list->first('VERANSTALTUNG');
        $veranstaltung = $first === null
            ? new Veranstaltung('', '', '', '')
            : new Veranstaltung(
                $r->value($first, 'veranstaltungsbezeichnung'),
                $r->value($first, 'veranstaltungsort'),
                $r->value($first, 'bahnlaenge'),
                $r->value($first, 'zeitmessung'),
            );

        /** @var list<Abschnitt> $abschnitte */
        $abschnitte = [];
        /** @var array<int, Abschnitt> $abschnittByNummer */
        $abschnittByNummer = [];

        foreach ($records as $record) {
            if ($record->element !== 'ABSCHNITT') {
                continue;
            }
            $nummer = $r->number($record, 'abschnittsnr');
            $abschnitt = new Abschnitt(
                $nummer,
                Datum::decode($r->value($record, 'abschnittsdatum')),
                Uhrzeit::decode($r->value($record, 'einlass')),
                Uhrzeit::decode($r->value($record, 'kampfrichtersitzung')),
                Uhrzeit::decode($r->value($record, 'anfangszeit')),
                $r->value($record, 'relativeAngabe'),
                [],
                $record->line,
            );
            $abschnitte[] = $abschnitt;

            if ($nummer !== null && isset($abschnittByNummer[$nummer])) {
                $diagnostics[] = self::ambiguous("Duplicate ABSCHNITT number {$nummer}; the first one wins", $record, ['element' => 'ABSCHNITT', 'abschnittsnr' => $nummer]);
            } elseif ($nummer !== null) {
                $abschnittByNummer[$nummer] = $abschnitt;
            }
        }

        /** @var array<string, Wettkampf> $wettkaempfe */
        $wettkaempfe = [];
        /** @var list<Wettkampf> $ohneAbschnitt */
        $ohneAbschnitt = [];

        foreach ($records as $record) {
            if ($record->element !== 'WETTKAMPF') {
                continue;
            }
            $nummer = $r->number($record, 'wettkampfnr');
            $art = $r->value($record, 'wettkampfart');
            $abschnittsnr = $r->number($record, 'abschnittsnr');
            $qualNummer = $r->number($record, 'qualifikationswettkampfnr');
            $qualArt = $r->value($record, 'qualifikationswettkampfart');

            $wettkampf = new Wettkampf(
                $nummer,
                $art,
                $abschnittsnr,
                $r->value($record, 'anzahlStarter'),
                $r->number($record, 'einzelstrecke'),
                $r->value($record, 'technik'),
                $r->value($record, 'ausuebung'),
                $r->value($record, 'geschlecht'),
                $r->value($record, 'zuordnungBestenliste'),
                [],
                [],
                $qualNummer !== null || $qualArt !== '' ? ['nummer' => $qualNummer, 'art' => $qualArt] : null,
                $record->line,
            );

            $key = RecordReader::key($nummer) . ':' . $art;
            if (isset($wettkaempfe[$key])) {
                $diagnostics[] = self::ambiguous("Duplicate WETTKAMPF {$key}; the first one wins", $record, ['element' => 'WETTKAMPF', 'key' => $key]);
            } else {
                $wettkaempfe[$key] = $wettkampf;
            }

            if ($abschnittsnr !== null && isset($abschnittByNummer[$abschnittsnr])) {
                $abschnittByNummer[$abschnittsnr]->wettkaempfe[] = $wettkampf;
            } else {
                $diagnostics[] = self::dangling(
                    "WETTKAMPF {$key} refers to unknown ABSCHNITT " . $r->value($record, 'abschnittsnr'),
                    $record,
                    ['element' => 'WETTKAMPF', 'key' => $key, 'abschnittsnr' => $abschnittsnr],
                );
                $ohneAbschnitt[] = $wettkampf;
            }
        }

        // Check qualification references only once all competitions are known:
        // a heat may stand behind its final in the file.
        foreach ($records as $record) {
            if ($record->element !== 'WETTKAMPF') {
                continue;
            }
            $qualNummer = $r->number($record, 'qualifikationswettkampfnr');
            $qualArt = $r->value($record, 'qualifikationswettkampfart');
            if ($qualNummer === null && $qualArt === '') {
                continue;
            }
            $qualKey = RecordReader::key($qualNummer) . ':' . $qualArt;
            if (isset($wettkaempfe[$qualKey])) {
                continue;
            }
            $own = RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
            $diagnostics[] = self::dangling(
                "WETTKAMPF {$own} refers to unknown qualifying WETTKAMPF {$qualKey}",
                $record,
                ['element' => 'WETTKAMPF', 'reference' => 'qualifikation', 'wettkampf' => $qualKey],
            );
        }

        /** @var array<int, Wertung> $wertungById */
        $wertungById = [];

        foreach ($records as $record) {
            if ($record->element !== 'WERTUNG') {
                continue;
            }
            $id = $r->number($record, 'wertungsId');
            $wertung = new Wertung(
                $id,
                $r->value($record, 'wertungsklasseTyp'),
                $r->value($record, 'mindestJgAk'),
                $r->value($record, 'maximalJgAk'),
                $r->value($record, 'geschlecht'),
                $r->value($record, 'wertungsname'),
                $record->line,
            );

            if ($id !== null && isset($wertungById[$id])) {
                $diagnostics[] = self::ambiguous("Duplicate WERTUNG id {$id}; the first one wins", $record, ['element' => 'WERTUNG', 'wertungsId' => $id]);
            } elseif ($id !== null) {
                $wertungById[$id] = $wertung;
            }

            $key = RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
            if (!isset($wettkaempfe[$key])) {
                $diagnostics[] = self::dangling(
                    'WERTUNG ' . RecordReader::key($id) . " refers to unknown WETTKAMPF {$key}",
                    $record,
                    ['element' => 'WERTUNG', 'wertungsId' => $id, 'wettkampf' => $key],
                );
                continue;
            }
            $wettkaempfe[$key]->wertungen[] = $wertung;
        }

        foreach ($records as $record) {
            if ($record->element !== 'PFLICHTZEIT') {
                continue;
            }
            $key = RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
            if (!isset($wettkaempfe[$key])) {
                $diagnostics[] = self::dangling(
                    "PFLICHTZEIT refers to unknown WETTKAMPF {$key}",
                    $record,
                    ['element' => 'PFLICHTZEIT', 'wettkampf' => $key],
                );
                continue;
            }
            $wettkaempfe[$key]->pflichtzeiten[] = new Pflichtzeit(
                $r->value($record, 'wertungsklasseTyp'),
                $r->value($record, 'mindestJgAk'),
                $r->value($record, 'maximalJgAk'),
                Zeit::decode($r->value($record, 'pflichtzeit')),
                $r->value($record, 'geschlecht'),
                $record->line,
            );
        }

        return new ProjectionResult(
            new Wettkampfdefinition($veranstaltung, $abschnitte, $ohneAbschnitt, $wettkaempfe, $wertungById, $abschnittByNummer),
            $diagnostics,
        );
    }

    /** @param array<string, mixed> $data */
    private static function ambiguous(string $message, TypedRecord $record, array $data): Diagnostic
    {
        return new Diagnostic(DiagnosticCode::AmbiguousReference, Severity::Warning, $message, $record->line, $data);
    }

    /** @param array<string, mixed> $data */
    private static function dangling(string $message, TypedRecord $record, array $data): Diagnostic
    {
        return new Diagnostic(DiagnosticCode::DanglingReference, Severity::Warning, $message, $record->line, $data);
    }
}
