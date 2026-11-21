<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Document;

/** A line that consists entirely of a `(* … *)` comment. */
final readonly class Comment implements Item
{
    public function __construct(
        public string $raw,
        public int $line,
        public string $eol,
    ) {}
}
