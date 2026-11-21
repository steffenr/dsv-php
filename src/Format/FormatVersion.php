<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Format;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;

/** Version of the DSV standard. Only DSV7 and DSV8 are supported. */
enum FormatVersion: int
{
    case V7 = 7;
    case V8 = 8;

    /** The version for a number read from a file, or `null` if unsupported. */
    public static function fromNumber(?int $number): ?self
    {
        return $number === null ? null : self::tryFrom($number);
    }

    /**
     * The shared diagnostic for an unsupported version. Both API levels report
     * it identically so they say the same about the same file.
     */
    public static function unsupported(?int $version): Diagnostic
    {
        return new Diagnostic(
            DiagnosticCode::UnsupportedFormatVersion,
            Severity::Fatal,
            'Unsupported DSV format version: ' . ($version === null ? 'missing' : (string) $version),
            1,
            ['version' => $version],
        );
    }
}
