<?php

declare(strict_types=1);

/**
 * The value codecs: dates, swim times and times of day.
 *
 *   php examples/06-time-helpers.php
 */

use SteffenR\Dsv\Values\Datum;
use SteffenR\Dsv\Values\Uhrzeit;
use SteffenR\Dsv\Values\Zeit;

require __DIR__ . '/../vendor/autoload.php';

// Swim times are integers in hundredths of a second - no float rounding.
$hundredths = Zeit::decode('00:01:02,45');          // 6245
echo $hundredths, ' = ', Zeit::toSeconds((int) $hundredths), " s\n";
echo Zeit::encode(6245), "\n";                       // 00:01:02,45

// Dates are a plain triple, not a DateTime: 31.02. is an error, never silently 03.03.
var_dump(Datum::decode('31.02.2026'));               // NULL
$date = Datum::decode('15.11.2026');
echo $date, ' -> ', $date?->toDateTimeImmutable()->format('l, j F Y'), "\n";

// Times of day are minutes since midnight.
echo Uhrzeit::decode('09:30'), ' min = ', Uhrzeit::encode(570), "\n";
