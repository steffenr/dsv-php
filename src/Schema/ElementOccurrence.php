<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

/** An element of a list type together with its allowed cardinality. */
final readonly class ElementOccurrence
{
    public function __construct(
        public ElementDef $def,
        /** Minimum count; 0 means optional. */
        public int $min,
        /** Maximum count; null means unbounded. */
        public ?int $max,
    ) {}
}
