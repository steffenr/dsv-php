<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * An official of a section.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Kampfrichter
{
    public function __construct(
        public string $position,
        public string $name,
        public string $verein,
        public int $line,
    ) {}
}
