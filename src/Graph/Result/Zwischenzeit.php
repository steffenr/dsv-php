<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * An intermediate time.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Zwischenzeit
{
    public function __construct(
        public ?int $distanz,
        /**
         * Hundredths; null if invalid.
         */
        public ?int $zeit,
        public int $line,
    ) {}
}
