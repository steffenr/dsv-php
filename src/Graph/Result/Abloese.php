<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * A relay takeover (reaction) time.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Abloese
{
    public function __construct(
        public ?int $startnummer,
        public string $art,
        /**
         * Hundredths; null if invalid.
         */
        public ?int $zeit,
        public int $line,
    ) {}
}
