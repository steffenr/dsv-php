<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Graph\Result\Abloese;
use SteffenR\Dsv\Graph\Result\Abschnitt;
use SteffenR\Dsv\Graph\Result\Kampfrichter;
use SteffenR\Dsv\Graph\Result\Person;
use SteffenR\Dsv\Graph\Result\Platzierung;
use SteffenR\Dsv\Graph\Result\Reaktion;
use SteffenR\Dsv\Graph\Result\Staffel;
use SteffenR\Dsv\Graph\Result\StaffelPerson;
use SteffenR\Dsv\Graph\Result\StaffelZwischenzeit;
use SteffenR\Dsv\Graph\Result\Start;
use SteffenR\Dsv\Graph\Result\Verein;
use SteffenR\Dsv\Graph\Result\Wettkampf;
use SteffenR\Dsv\Graph\Result\Wettkampfergebnis;
use SteffenR\Dsv\Graph\Result\Zwischenzeit;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Typed\TypedList;
use SteffenR\Dsv\Typed\TypedRecord;
use SteffenR\Dsv\Values\Datum;
use SteffenR\Dsv\Values\Uhrzeit;
use SteffenR\Dsv\Values\Zeit;

/**
 * Builds the object graph of a Wettkampfergebnisliste with resolved
 * references: sections → competitions → starts/relays → placements, intermediate
 * times and reactions, plus an index of swimmers across all their starts.
 *
 * Never aborts. Contradicting or dangling references produce warnings and the
 * first occurrence wins.
 */
final class ResultProjector
{
    private RecordReader $r;

    /** @var list<Diagnostic> */
    private array $diagnostics = [];

    /** @var array<string, Wettkampf> */
    private array $wettkaempfe = [];

    /** @var array<int, Wertung> */
    private array $wertungById = [];

    /** @var array<int, string> wertungsId → key of the competition it belongs to */
    private array $wettkampfByWertungsId = [];

    private function __construct()
    {
        $this->r = new RecordReader(ListType::Wettkampfergebnisliste->schema());
    }

    /** @return ProjectionResult<Wettkampfergebnis> */
    public static function project(TypedList $list): ProjectionResult
    {
        return new self()->build($list);
    }

    /** @return ProjectionResult<Wettkampfergebnis> */
    private function build(TypedList $list): ProjectionResult
    {
        $r = $this->r;
        $records = $list->records;

        $first = $list->first('VERANSTALTUNG');
        $veranstaltung = $first === null
            ? new Veranstaltung('', '', '', '')
            : new Veranstaltung(
                $r->value($first, 'veranstaltungsbezeichnung'),
                $r->value($first, 'veranstaltungsort'),
                $r->value($first, 'bahnlaenge'),
                $r->value($first, 'zeitmessung'),
            );

        // Clubs
        /** @var list<Verein> $vereine */
        $vereine = [];
        /** @var array<int, Verein> $vereinByKennzahl */
        $vereinByKennzahl = [];
        foreach ($records as $record) {
            if ($record->element !== 'VEREIN') {
                continue;
            }
            $kennzahl = $r->number($record, 'vereinskennzahl');
            $verein = new Verein(
                $r->value($record, 'vereinsbezeichnung'),
                $kennzahl,
                $r->value($record, 'landesschwimmverband'),
                $r->value($record, 'nationenkuerzel'),
                $record->line,
            );
            $vereine[] = $verein;

            if ($kennzahl === null || $kennzahl === 0) {
                continue;
            }
            if (isset($vereinByKennzahl[$kennzahl])) {
                $this->warn(DiagnosticCode::AmbiguousReference, "Duplicate VEREIN number {$kennzahl}; the first one wins", $record, ['element' => 'VEREIN', 'vereinskennzahl' => $kennzahl]);
            } else {
                $vereinByKennzahl[$kennzahl] = $verein;
            }
        }

        // Sections and officials
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
                Uhrzeit::decode($r->value($record, 'anfangszeit')),
                $r->value($record, 'relativeAngabe'),
                [],
                [],
                $record->line,
            );
            $abschnitte[] = $abschnitt;

            if ($nummer !== null && isset($abschnittByNummer[$nummer])) {
                $this->warn(DiagnosticCode::AmbiguousReference, "Duplicate ABSCHNITT number {$nummer}; the first one wins", $record, ['element' => 'ABSCHNITT', 'abschnittsnr' => $nummer]);
            } elseif ($nummer !== null) {
                $abschnittByNummer[$nummer] = $abschnitt;
            }
        }

        foreach ($records as $record) {
            if ($record->element !== 'KAMPFGERICHT') {
                continue;
            }
            $nr = $r->number($record, 'abschnittsnr');
            if ($nr === null || !isset($abschnittByNummer[$nr])) {
                $this->warn(DiagnosticCode::DanglingReference, 'KAMPFGERICHT refers to unknown ABSCHNITT ' . $r->value($record, 'abschnittsnr'), $record, ['element' => 'KAMPFGERICHT', 'abschnittsnr' => $nr]);
                continue;
            }
            $abschnittByNummer[$nr]->kampfrichter[] = new Kampfrichter(
                $r->value($record, 'position'),
                $r->value($record, 'nameKampfrichter'),
                $r->value($record, 'vereinDesKampfrichters'),
                $record->line,
            );
        }

        // Competitions
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
                [],
                $qualNummer !== null || $qualArt !== '' ? ['nummer' => $qualNummer, 'art' => $qualArt] : null,
                $record->line,
            );

            $key = RecordReader::key($nummer) . ':' . $art;
            if (isset($this->wettkaempfe[$key])) {
                $this->warn(DiagnosticCode::AmbiguousReference, "Duplicate WETTKAMPF {$key}; the first one wins", $record, ['element' => 'WETTKAMPF', 'key' => $key]);
            } else {
                $this->wettkaempfe[$key] = $wettkampf;
            }

            if ($abschnittsnr !== null && isset($abschnittByNummer[$abschnittsnr])) {
                $abschnittByNummer[$abschnittsnr]->wettkaempfe[] = $wettkampf;
            } else {
                $this->warn(DiagnosticCode::DanglingReference, "WETTKAMPF {$key} refers to unknown ABSCHNITT " . $r->value($record, 'abschnittsnr'), $record, ['element' => 'WETTKAMPF', 'key' => $key, 'abschnittsnr' => $abschnittsnr]);
                $ohneAbschnitt[] = $wettkampf;
            }
        }

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
            if (isset($this->wettkaempfe[$qualKey])) {
                continue;
            }
            $own = RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
            $this->warn(DiagnosticCode::DanglingReference, "WETTKAMPF {$own} refers to unknown qualifying WETTKAMPF {$qualKey}", $record, ['element' => 'WETTKAMPF', 'reference' => 'qualifikation', 'wettkampf' => $qualKey]);
        }

        // Ranking groups
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

            if ($id !== null && isset($this->wertungById[$id])) {
                $this->warn(DiagnosticCode::AmbiguousReference, "Duplicate WERTUNG id {$id}; the first one wins", $record, ['element' => 'WERTUNG', 'wertungsId' => $id]);
            } elseif ($id !== null) {
                $this->wertungById[$id] = $wertung;
            }

            $key = RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
            if ($id !== null && !isset($this->wettkampfByWertungsId[$id])) {
                $this->wettkampfByWertungsId[$id] = $key;
            }
            if (!isset($this->wettkaempfe[$key])) {
                $this->warn(DiagnosticCode::DanglingReference, 'WERTUNG ' . RecordReader::key($id) . " refers to unknown WETTKAMPF {$key}", $record, ['element' => 'WERTUNG', 'wertungsId' => $id, 'wettkampf' => $key]);
                continue;
            }
            $this->wettkaempfe[$key]->wertungen[] = $wertung;
        }

        [$startByKey] = $this->buildStarts($records);
        [$staffelByKey, $named] = $this->buildStaffeln($records);
        $this->checkRelays($staffelByKey, $named);
        $personById = $this->buildPersons($startByKey);

        return new ProjectionResult(
            new Wettkampfergebnis(
                $veranstaltung,
                $abschnitte,
                $vereine,
                $ohneAbschnitt,
                $this->wettkaempfe,
                $this->wertungById,
                $abschnittByNummer,
                $vereinByKennzahl,
                $startByKey,
                $staffelByKey,
                $personById,
            ),
            $this->diagnostics,
        );
    }

    /**
     * @param list<TypedRecord> $records
     *
     * @return array{0: array<string, Start>, 1: array<string, string>}
     */
    private function buildStarts(array $records): array
    {
        $r = $this->r;
        /** @var array<string, Start> $starts */
        $starts = [];
        /** @var array<string, string> $signatures */
        $signatures = [];

        foreach ($records as $record) {
            if ($record->element !== 'PNERGEBNIS') {
                continue;
            }
            $vid = $r->number($record, 'veranstaltungsId');
            $nummer = $r->number($record, 'wettkampfnr');
            $art = $r->value($record, 'wettkampfart');
            $key = RecordReader::key($vid) . ':' . RecordReader::key($nummer) . ':' . $art;

            $signature = implode('|', array_map(
                fn(string $f): string => $r->value($record, $f),
                ['name', 'dsvId', 'geschlecht', 'jahrgang', 'verein', 'vereinskennzahl', 'endzeit'],
            ));

            if (isset($starts[$key])) {
                if ($signatures[$key] !== $signature) {
                    $this->warn(DiagnosticCode::AmbiguousReference, "PNERGEBNIS for {$key} contradicts an earlier line; the first one wins", $record, ['element' => 'PNERGEBNIS', 'key' => $key]);
                }
                $this->addPlacement($starts[$key]->platzierungen, $this->placement($record), 'PNERGEBNIS', $key, $record->line);
                $this->checkWertung($record, $vid);
                continue;
            }

            $start = new Start(
                $vid,
                $nummer,
                $art,
                $r->value($record, 'name'),
                $r->value($record, 'dsvId'),
                $r->value($record, 'geschlecht'),
                $r->value($record, 'jahrgang'),
                $r->value($record, 'altersklasse'),
                $r->value($record, 'verein'),
                $r->number($record, 'vereinskennzahl'),
                $this->nationalities($record),
                Zeit::decode($r->value($record, 'endzeit')),
                [$this->placement($record)],
                [],
                [],
                $record->line,
            );
            $starts[$key] = $start;
            $signatures[$key] = $signature;
            $this->checkWertung($record, $vid);

            $ckey = RecordReader::key($nummer) . ':' . $art;
            if (!isset($this->wettkaempfe[$ckey])) {
                $this->warn(DiagnosticCode::DanglingReference, "PNERGEBNIS for {$key} refers to unknown WETTKAMPF {$ckey}", $record, ['element' => 'PNERGEBNIS', 'key' => $key]);
                continue;
            }
            $this->wettkaempfe[$ckey]->starts[] = $start;
        }

        foreach ($records as $record) {
            if ($record->element !== 'PNZWISCHENZEIT' && $record->element !== 'PNREAKTION') {
                continue;
            }
            $key = RecordReader::key($r->number($record, 'veranstaltungsId')) . ':'
                . RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
            if (!isset($starts[$key])) {
                $this->warn(DiagnosticCode::DanglingReference, "{$record->element} refers to unknown PNERGEBNIS {$key}", $record, ['element' => $record->element, 'key' => $key]);
                continue;
            }
            if ($record->element === 'PNZWISCHENZEIT') {
                $starts[$key]->zwischenzeiten[] = new Zwischenzeit(
                    $r->number($record, 'distanz'),
                    Zeit::decode($r->value($record, 'zwischenzeit')),
                    $record->line,
                );
            } else {
                $starts[$key]->reaktionen[] = new Reaktion(
                    $r->value($record, 'art'),
                    Zeit::decode($r->value($record, 'reaktionszeit')),
                    $record->line,
                );
            }
        }

        return [$starts, $signatures];
    }

    /**
     * @param list<TypedRecord> $records
     *
     * @return array{0: array<string, Staffel>, 1: array<string, int>} the relays and, per relay, how many members were named
     */
    private function buildStaffeln(array $records): array
    {
        $r = $this->r;
        /** @var array<string, Staffel> $staffeln */
        $staffeln = [];
        /** @var array<string, string> $signatures */
        $signatures = [];
        /** @var array<string, true> $seen identities already taken, keyed `relay|kind|identity` */
        $seen = [];
        /** @var array<string, int> $named */
        $named = [];

        foreach ($records as $record) {
            if ($record->element !== 'STERGEBNIS') {
                continue;
            }
            $vid = $r->number($record, 'veranstaltungsId');
            $nummer = $r->number($record, 'wettkampfnr');
            $art = $r->value($record, 'wettkampfart');
            $key = RecordReader::key($vid) . ':' . RecordReader::key($nummer) . ':' . $art;

            $signature = implode('|', array_map(
                fn(string $f): string => $r->value($record, $f),
                ['nummerDerMannschaft', 'verein', 'vereinskennzahl', 'endzeit'],
            ));

            if (isset($staffeln[$key])) {
                if ($signatures[$key] !== $signature) {
                    $this->warn(DiagnosticCode::AmbiguousReference, "STERGEBNIS for {$key} contradicts an earlier line; the first one wins", $record, ['element' => 'STERGEBNIS', 'key' => $key]);
                }
                $this->addPlacement($staffeln[$key]->platzierungen, $this->placement($record), 'STERGEBNIS', $key, $record->line);
                $this->checkWertung($record, $vid);
                continue;
            }

            $staffel = new Staffel(
                $vid,
                $nummer,
                $art,
                $r->value($record, 'nummerDerMannschaft'),
                $r->value($record, 'verein'),
                $r->number($record, 'vereinskennzahl'),
                Zeit::decode($r->value($record, 'endzeit')),
                $r->number($record, 'startnummerDisqualifiziert'),
                [$this->placement($record)],
                [],
                [],
                [],
                $record->line,
            );
            $staffeln[$key] = $staffel;
            $signatures[$key] = $signature;
            $named[$key] = 0;
            $this->checkWertung($record, $vid);

            $ckey = RecordReader::key($nummer) . ':' . $art;
            if (!isset($this->wettkaempfe[$ckey])) {
                $this->warn(DiagnosticCode::DanglingReference, "STERGEBNIS for {$key} refers to unknown WETTKAMPF {$ckey}", $record, ['element' => 'STERGEBNIS', 'key' => $key]);
                continue;
            }
            $this->wettkaempfe[$ckey]->staffeln[] = $staffel;
        }

        foreach ($records as $record) {
            if (!\in_array($record->element, ['STAFFELPERSON', 'STZWISCHENZEIT', 'STABLOESE'], true)) {
                continue;
            }
            $key = RecordReader::key($r->number($record, 'veranstaltungsIdStaffel')) . ':'
                . RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
            if (!isset($staffeln[$key])) {
                $this->warn(DiagnosticCode::DanglingReference, "{$record->element} refers to unknown STERGEBNIS {$key}", $record, ['element' => $record->element, 'key' => $key]);
                continue;
            }

            $startnummer = $r->number($record, 'startnummer');
            $sn = RecordReader::key($startnummer);

            if ($record->element === 'STAFFELPERSON') {
                if (isset($seen["{$key}|p|{$sn}"])) {
                    continue;
                }
                $seen["{$key}|p|{$sn}"] = true;
                ++$named[$key];
                $staffeln[$key]->personen[] = new StaffelPerson(
                    $r->value($record, 'name'),
                    $r->value($record, 'dsvId'),
                    $startnummer,
                    $r->value($record, 'geschlecht'),
                    $r->value($record, 'jahrgang'),
                    $r->value($record, 'altersklasse'),
                    $this->nationalities($record),
                    $record->line,
                );
            } elseif ($record->element === 'STZWISCHENZEIT') {
                $distanz = $r->number($record, 'distanz');
                $identity = $sn . ':' . RecordReader::key($distanz);
                if (isset($seen["{$key}|z|{$identity}"])) {
                    continue;
                }
                $seen["{$key}|z|{$identity}"] = true;
                $staffeln[$key]->zwischenzeiten[] = new StaffelZwischenzeit(
                    $startnummer,
                    $distanz,
                    Zeit::decode($r->value($record, 'zwischenzeit')),
                    $record->line,
                );
            } else {
                $kind = $r->value($record, 'art');
                $identity = $sn . ':' . $kind;
                if (isset($seen["{$key}|a|{$identity}"])) {
                    continue;
                }
                $seen["{$key}|a|{$identity}"] = true;
                $staffeln[$key]->abloesen[] = new Abloese(
                    $startnummer,
                    $kind,
                    Zeit::decode($r->value($record, 'reaktionszeit')),
                    $record->line,
                );
            }
        }

        return [$staffeln, $named];
    }

    /**
     * Either all relay members are named or none; fewer than announced is incomplete.
     *
     * @param array<string, Staffel> $staffeln
     * @param array<string, int>     $named
     */
    private function checkRelays(array $staffeln, array $named): void
    {
        foreach ($staffeln as $key => $staffel) {
            $count = $named[$key] ?? 0;
            if ($count === 0) {
                continue;
            }
            $wettkampf = $this->wettkaempfe[RecordReader::key($staffel->wettkampfnr) . ':' . $staffel->wettkampfart] ?? null;
            $expected = $wettkampf !== null && preg_match('/^\d+$/D', $wettkampf->anzahlStarter) === 1 ? (int) $wettkampf->anzahlStarter : 0;
            if ($expected <= 0 || $count >= $expected) {
                continue;
            }
            $this->diagnostics[] = new Diagnostic(
                DiagnosticCode::IncompleteRelay,
                Severity::Warning,
                "STERGEBNIS {$key} names {$count} of {$expected} relay members; either all or none are expected",
                $staffel->line,
                ['element' => 'STERGEBNIS', 'key' => $key, 'genannt' => $count, 'erwartet' => $expected],
            );
        }
    }

    /**
     * @param array<string, Start> $starts
     *
     * @return array<int, Person>
     */
    private function buildPersons(array $starts): array
    {
        /** @var array<int, Person> $persons */
        $persons = [];
        foreach ($starts as $start) {
            $id = $start->veranstaltungsId;
            if ($id === null) {
                continue;
            }
            if (!isset($persons[$id])) {
                $persons[$id] = new Person($id, $start->name, $start->dsvId, $start->geschlecht, $start->jahrgang, $start->verein, $start->vereinskennzahl, [$start]);
                continue;
            }
            $p = $persons[$id];
            if ($p->name !== $start->name || $p->dsvId !== $start->dsvId || $p->geschlecht !== $start->geschlecht
                || $p->jahrgang !== $start->jahrgang || $p->verein !== $start->verein || $p->vereinskennzahl !== $start->vereinskennzahl) {
                $this->diagnostics[] = new Diagnostic(
                    DiagnosticCode::AmbiguousReference,
                    Severity::Warning,
                    "PNERGEBNIS for person {$id} contradicts an earlier start; the first one wins",
                    $start->line,
                    ['element' => 'PNERGEBNIS', 'veranstaltungsId' => $id],
                );
            }
            $p->starts[] = $start;
        }

        return $persons;
    }

    /** A placement must refer to an existing ranking group of its own competition. */
    private function checkWertung(TypedRecord $record, ?int $vid): void
    {
        $r = $this->r;
        $wertungsId = $r->number($record, 'wertungsId');
        if ($wertungsId === null || !isset($this->wertungById[$wertungsId])) {
            $this->warn(
                DiagnosticCode::DanglingReference,
                "{$record->element} for " . RecordReader::key($vid) . ' refers to unknown WERTUNG ' . $r->value($record, 'wertungsId'),
                $record,
                ['element' => $record->element, 'wertungsId' => $wertungsId],
            );

            return;
        }

        $own = RecordReader::key($r->number($record, 'wettkampfnr')) . ':' . $r->value($record, 'wettkampfart');
        $theirs = $this->wettkampfByWertungsId[$wertungsId] ?? null;
        if ($theirs === null || $theirs === $own) {
            return;
        }
        $this->warn(
            DiagnosticCode::DanglingReference,
            "{$record->element} for {$own} refers to WERTUNG {$wertungsId} of WETTKAMPF {$theirs}",
            $record,
            ['element' => $record->element, 'wertungsId' => $wertungsId, 'wettkampf' => $own, 'wertungWettkampf' => $theirs],
        );
    }

    private function placement(TypedRecord $record): Platzierung
    {
        $r = $this->r;

        return new Platzierung(
            $r->number($record, 'wertungsId'),
            $r->number($record, 'platz'),
            $r->value($record, 'grundDerNichtwertung'),
            $r->value($record, 'disqualifikationsbemerkung'),
            $r->value($record, 'erhoehtesNachtraeglichesMeldegeld'),
            $record->line,
        );
    }

    /** @param list<Platzierung> $target */
    private function addPlacement(array &$target, Platzierung $new, string $element, string $key, int $line): void
    {
        foreach ($target as $existing) {
            if ($existing->wertungsId === $new->wertungsId) {
                $this->diagnostics[] = new Diagnostic(
                    DiagnosticCode::AmbiguousReference,
                    Severity::Warning,
                    "{$element} for {$key} repeats Wertung " . RecordReader::key($new->wertungsId) . '; the first placement wins',
                    $line,
                    ['element' => $element, 'key' => $key, 'wertungsId' => $new->wertungsId],
                );

                return;
            }
        }
        $target[] = $new;
    }

    /** @return list<string> */
    private function nationalities(TypedRecord $record): array
    {
        return array_values(array_filter(
            array_map(fn(string $f): string => $this->r->value($record, $f), ['nationalitaet1', 'nationalitaet2', 'nationalitaet3']),
            static fn(string $n): bool => $n !== '',
        ));
    }

    /** @param array<string, mixed> $data */
    private function warn(DiagnosticCode $code, string $message, TypedRecord $record, array $data): void
    {
        $this->diagnostics[] = new Diagnostic($code, Severity::Warning, $message, $record->line, $data);
    }
}
