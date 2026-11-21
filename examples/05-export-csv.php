<?php

declare(strict_types=1);

/**
 * Exports the starts of a result list as CSV (swimmers, events, times).
 *
 *   php examples/05-export-csv.php [file] > results.csv
 */

use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Values\Zeit;

require __DIR__ . '/../vendor/autoload.php';

$path = $argv[1] ?? __DIR__ . '/data/meeting.dsv7';
$parsed = Dsv::parseList(file_get_contents($path) ?: '', ListType::Wettkampfergebnisliste);
$graph = Dsv::projectResult($parsed->document)->graph;

$out = fopen('php://output', 'wb');
if ($out === false) {
    exit(1);
}
fputcsv($out, ['event', 'distance', 'stroke', 'name', 'year', 'club', 'place', 'seconds'], ',', '"', '');

foreach ($graph->wettkampfByKey as $wettkampf) {
    foreach ($wettkampf->starts as $start) {
        fputcsv($out, [
            $wettkampf->nummer,
            $wettkampf->einzelstrecke,
            $wettkampf->technik,
            $start->name,
            $start->jahrgang,
            $start->verein,
            $start->platzierungen[0]->platz,
            $start->endzeit === null ? '' : number_format(Zeit::toSeconds($start->endzeit), 2, '.', ''),
        ], ',', '"', '');
    }
}
