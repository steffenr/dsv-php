<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Definition;

use SteffenR\Dsv\Values\Datum;

/**
 * An event section with the competitions held in it.
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
        public ?int $einlass,
        /**
         * Minutes since midnight.
         */
        public ?int $kampfrichtersitzung,
        /**
         * Minutes since midnight.
         */
        public ?int $anfangszeit,
        /**
         * `J` if times are relative to the end of the previous section.
         */
        public string $relativeAngabe,
        /**
         * @var list<Wettkampf>
         */
        public array $wettkaempfe,
        /**
         * 1-based source line.
         */
        public int $line,
    ) {}
}
