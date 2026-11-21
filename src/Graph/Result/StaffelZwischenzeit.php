<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * An intermediate time of a relay swimmer.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class StaffelZwischenzeit
{
    public function __construct(
        public ?int $startnummer,
        public ?int $distanz,
        /**
         * Hundredths; null if invalid.
         */
        public ?int $zeit,
        public int $line,
    ) {}
}
