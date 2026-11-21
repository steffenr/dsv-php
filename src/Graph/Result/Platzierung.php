<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * A placement of a start within one ranking group.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Platzierung
{
    public function __construct(
        public ?int $wertungsId,
        /**
         * 0 when not ranked.
         */
        public ?int $platz,
        public string $grundDerNichtwertung,
        public string $disqualifikationsbemerkung,
        public string $erhoehtesNachtraeglichesMeldegeld,
        public int $line,
    ) {}
}
