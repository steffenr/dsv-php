<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

/** Scalar data types of the DSV standard. */
enum ScalarType: string
{
    case ZK = 'ZK';
    case Zahl = 'Zahl';
    case Zeichen = 'Zeichen';
    case Zeit = 'Zeit';
    case Datum = 'Datum';
    case Uhrzeit = 'Uhrzeit';
    case Betrag = 'Betrag';
    case JGAK = 'JGAK';
}
