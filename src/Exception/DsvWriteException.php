<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Exception;

use RuntimeException;
use SteffenR\Dsv\Diagnostic\Diagnostic;

/** Thrown when the writer refuses to produce a file that would not be valid. */
final class DsvWriteException extends RuntimeException implements DsvException
{
    /** @param list<Diagnostic> $diagnostics */
    public function __construct(public readonly array $diagnostics)
    {
        $first = $diagnostics[0] ?? null;
        $count = \count($diagnostics);
        parent::__construct(
            $first === null
                ? 'DSV write failed'
                : $first->code->value . ': ' . $first->message . ($count > 1 ? \sprintf(' (and %d more)', $count - 1) : ''),
        );
    }
}
