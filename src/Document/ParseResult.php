<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Document;

use SteffenR\Dsv\Diagnostic\Diagnostic;

/**
 * Outcome of a parse: the value plus all findings. Parsing is lenient — real
 * files are often not spec-conformant — so a value is returned even when `ok`
 * is false; inspect {@see self::$diagnostics}.
 *
 * @template-covariant T
 */
final readonly class ParseResult
{
    /**
     * @param T              $document
     * @param list<Diagnostic> $diagnostics
     */
    public function __construct(
        public mixed $document,
        public array $diagnostics,
        public bool $ok,
    ) {}

    /** @return list<Diagnostic> */
    public function errors(): array
    {
        return array_values(array_filter($this->diagnostics, static fn(Diagnostic $d): bool => $d->severity->isFailure()));
    }
}
