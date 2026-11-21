<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

use SteffenR\Dsv\Values\Datum;

/**
 * An event section.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Abschnitt
{
    public function __construct(
        public ?int $nummer,
        public ?Datum $datum,
        /**
         * Minutes since midnight.
         */
        public ?int $anfangszeit,
        public string $relativeAngabe,
        /**
         * @var list<Kampfrichter>
         */
        public array $kampfrichter,
        /**
         * @var list<Wettkampf>
         */
        public array $wettkaempfe,
        public int $line,
    ) {}
}
