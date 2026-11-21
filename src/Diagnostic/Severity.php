<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Diagnostic;

/**
 * Severity of a {@see Diagnostic}.
 *
 * `Fatal` means the input is not a usable DSV file. `Error` and `Fatal` make a
 * parse result not `ok`; `Warning` and `Info` never do.
 */
enum Severity: string
{
    case Fatal = 'fatal';
    case Error = 'error';
    case Warning = 'warning';
    case Info = 'info';

    /** Whether this severity makes a parse result not `ok`. */
    public function isFailure(): bool
    {
        return $this === self::Fatal || $this === self::Error;
    }
}
