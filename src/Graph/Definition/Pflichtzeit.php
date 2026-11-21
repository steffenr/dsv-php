<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Definition;

/**
 * A qualifying time of a competition.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Pflichtzeit
{
    public function __construct(
        public string $wertungsklasseTyp,
        public string $mindestJgAk,
        public string $maximalJgAk,
        /**
         * Hundredths of a second; null if invalid.
         */
        public ?int $zeit,
        public string $geschlecht,
        /**
         * 1-based source line.
         */
        public int $line,
    ) {}
}
