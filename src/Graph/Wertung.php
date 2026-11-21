<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph;

/**
 * A ranking group within a competition.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Wertung
{
    public function __construct(
        /**
         * Event-wide unique id (`wertungsId`).
         */
        public ?int $id,
        /**
         * JG (year of birth) or AK (age class).
         */
        public string $wertungsklasseTyp,
        public string $mindestJgAk,
        public string $maximalJgAk,
        public string $geschlecht,
        public string $name,
        /**
         * 1-based source line.
         */
        public int $line,
    ) {}
}
