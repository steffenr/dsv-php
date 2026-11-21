<?php

declare(strict_types=1);

/**
 * Creates a Wettkampfdefinitionsliste (an announcement) from scratch as DSV8.
 *
 *   php examples/04-create-definition.php > ausschreibung.dsv8
 */

use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Exception\DsvWriteException;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Typed\TypedRecord;
use SteffenR\Dsv\Write\WriteOptions;

require __DIR__ . '/../vendor/autoload.php';

$records = [
    new TypedRecord('FORMAT', ['listart' => 'Wettkampfdefinitionsliste', 'version' => '8']),
    new TypedRecord('ERZEUGER', ['software' => 'my-app', 'version' => '1.0', 'kontakt' => 'support@example.org']),
    new TypedRecord('VERANSTALTUNG', ['veranstaltungsbezeichnung' => 'Frühjahrsschwimmfest', 'veranstaltungsort' => 'Musterstadt', 'bahnlaenge' => '25', 'zeitmessung' => 'AUTOMATISCH']),
    new TypedRecord('VERANSTALTUNGSORT', ['nameSchwimmhalle' => 'Hallenbad', 'ort' => 'Musterstadt', 'land' => 'GER']),
    new TypedRecord('AUSSCHREIBUNGIMNETZ', ['internetadresse' => 'https://example.org/ausschreibung']),
    new TypedRecord('VERANSTALTER', ['nameDesVeranstalters' => 'SV Musterstadt']),
    new TypedRecord('AUSRICHTER', ['nameDesAusrichters' => 'SV Musterstadt', 'name' => 'Muster, Max', 'email' => 'ausrichter@example.org']),
    new TypedRecord('MELDEADRESSE', ['name' => 'Muster, Max', 'email' => 'meldung@example.org']),
    new TypedRecord('MELDESCHLUSS', ['datum' => '01.03.2027', 'uhrzeit' => '18:00']),
    new TypedRecord('ABSCHNITT', ['abschnittsnr' => '1', 'abschnittsdatum' => '15.03.2027', 'anfangszeit' => '09:00', 'relativeAngabe' => 'N']),
    new TypedRecord('WETTKAMPF', ['wettkampfnr' => '1', 'wettkampfart' => 'E', 'abschnittsnr' => '1', 'einzelstrecke' => '100', 'technik' => 'F', 'ausuebung' => 'GL', 'geschlecht' => 'W', 'zuordnungBestenliste' => 'SW']),
    new TypedRecord('WERTUNG', ['wettkampfnr' => '1', 'wettkampfart' => 'E', 'wertungsId' => '1', 'wertungsklasseTyp' => 'JG', 'mindestJgAk' => '0', 'maximalJgAk' => '9999', 'wertungsname' => 'Offene Wertung']),
    new TypedRecord('MELDEGELD', ['meldegeldTyp' => 'Meldegeldpauschale', 'betrag' => '10,00']),
    new TypedRecord('DATEIENDE', []),
];

try {
    echo Dsv::writeList($records, ListType::Wettkampfdefinitionsliste, new WriteOptions(version: FormatVersion::V8));
} catch (DsvWriteException $e) {
    // Writing is strict: anything the library would not read back as valid is refused.
    foreach ($e->diagnostics as $d) {
        fwrite(STDERR, $d . "\n");
    }
    exit(1);
}
