<?php

declare(strict_types=1);

/**
 * Two ways of changing a file:
 *
 *  1. schema-free  – edit nothing, get the exact bytes back (round trip)
 *  2. typed        – change a value, write a canonical, validated file
 *
 *   php examples/03-edit-and-save.php
 */

use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Exception\DsvWriteException;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Typed\TypedRecord;

require __DIR__ . '/../vendor/autoload.php';

$source = file_get_contents(__DIR__ . '/data/meeting.dsv7') ?: '';

// 1. Byte-identical round trip on the schema-free level.
$document = Dsv::parse($source)->document;
echo Dsv::write($document) === $source ? "round trip: identical\n" : "round trip: DIFFERENT\n";

// 2. Typed level: rename the event and save.
$list = Dsv::parseList($source, ListType::Wettkampfergebnisliste)->document;

$records = array_map(
    static fn(TypedRecord $r): TypedRecord => $r->element === 'VERANSTALTUNG'
        ? $r->with(['veranstaltungsbezeichnung' => 'Musterstaedter Winterpokal'])
        : $r,
    $list->records,
);

try {
    // Strict: the output is read back with the library's own parser and refused if it is not valid.
    $text = Dsv::writeList($records, ListType::Wettkampfergebnisliste);
    echo strtok($text, "\r\n"), " ... ", substr_count($text, "\r\n"), " lines written\n";
    echo 'new title: ', Dsv::parseList($text, ListType::Wettkampfergebnisliste)->document->first('VERANSTALTUNG')?->get('veranstaltungsbezeichnung'), "\n";
} catch (DsvWriteException $e) {
    foreach ($e->diagnostics as $d) {
        fwrite(STDERR, $d . "\n");
    }
    exit(1);
}

// A real file that brings an empty required field can only be saved via the explicit,
// reporting path - the defects it passes on are listed in `preservedDefects`.
$result = Dsv::writeListPreservingDefects($records, ListType::Wettkampfergebnisliste);
echo count($result->preservedDefects), " pre-existing defect(s) passed through\n";
