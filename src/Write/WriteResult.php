<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Write;

use SteffenR\Dsv\Diagnostic\Diagnostic;

/** Result of a write that passes pre-existing defects through. */
final readonly class WriteResult
{
    /** @param list<Diagnostic> $preservedDefects Defects that were passed through, in read-back order. */
    public function __construct(
        public string $text,
        public array $preservedDefects,
    ) {}
}
