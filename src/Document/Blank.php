<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Document;

/** An empty line or a line of whitespace only. */
final readonly class Blank implements Item
{
    public function __construct(
        public string $raw,
        public int $line,
        public string $eol,
    ) {}
}
