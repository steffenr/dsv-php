<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Values;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Exception\DsvWriteException;

/**
 * Guards the encoders. Decoders read foreign input, where invalid values are
 * normal and return null; an encoder receives the caller's own value, so an
 * invalid one is a programming error and throws instead of returning null.
 *
 * @internal
 */
final class Encodable
{
    private function __construct() {}

    /** @throws DsvWriteException */
    public static function require(bool $ok, string $type, string $value, string $expected): void
    {
        if ($ok) {
            return;
        }

        throw new DsvWriteException([new Diagnostic(
            DiagnosticCode::InvalidValue,
            Severity::Error,
            \sprintf('Cannot encode %s from %s; expected %s', $type, $value, $expected),
            1,
            ['value' => $value],
        )]);
    }
}
