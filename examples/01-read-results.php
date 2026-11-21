<?php

declare(strict_types=1);

/**
 * Reads a result list and prints the placements of every competition.
 *
 *   php examples/01-read-results.php [file]
 */

use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Values\Zeit;

require __DIR__ . '/../vendor/autoload.php';

$path = $argv[1] ?? __DIR__ . '/data/meeting.dsv7';

$parsed = Dsv::parseList(file_get_contents($path) ?: '', ListType::Wettkampfergebnisliste);
if (!$parsed->ok) {
    foreach ($parsed->errors() as $error) {
        fwrite(STDERR, $error . "\n");
    }
    exit(1);
}

// The projection resolves the references between records into a navigable graph.
$graph = Dsv::projectResult($parsed->document)->graph;

echo $graph->veranstaltung->bezeichnung, ' in ', $graph->veranstaltung->ort, "\n";

foreach ($graph->abschnitte as $abschnitt) {
    echo "\nSection {$abschnitt->nummer}, ", $abschnitt->datum, "\n";

    foreach ($abschnitt->wettkaempfe as $wettkampf) {
        echo "  Event {$wettkampf->nummer}: {$wettkampf->einzelstrecke} m {$wettkampf->technik}\n";

        foreach ($wettkampf->starts as $start) {
            $platz = $start->platzierungen[0]->platz;
            $zeit = $start->endzeit === null ? '-' : Zeit::encode($start->endzeit);
            printf("    %2s. %-18s %-16s %s\n", $platz, $start->name, $start->verein, $zeit);
        }
    }
}
