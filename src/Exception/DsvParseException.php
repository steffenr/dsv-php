<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Exception;

use RuntimeException;
use SteffenR\Dsv\Diagnostic\Diagnostic;

/** Thrown by the `…OrThrow` parse functions when the result is not `ok`. */
final class DsvParseException extends RuntimeException implements DsvException
{
    /** @param list<Diagnostic> $diagnostics */
    public function __construct(public readonly array $diagnostics)
    {
        $first = $diagnostics[0] ?? null;
        parent::__construct($first === null ? 'DSV parse failed' : $first->code->value . ': ' . $first->message);
    }
}
