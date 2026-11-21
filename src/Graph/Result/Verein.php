<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * A club.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Verein
{
    public function __construct(
        public string $bezeichnung,
        public ?int $kennzahl,
        public string $landesschwimmverband,
        public string $nationenkuerzel,
        public int $line,
    ) {}
}
