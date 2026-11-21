<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

/** One allowed value of a field with a fixed value list. */
final readonly class EnumValue
{
    public function __construct(
        public string $value,
        public string $doc,
        /** Set when the value is only allowed from this format version on. */
        public ?int $since = null,
        /** Not specified for this list type but found in real files: read as warning, rejected on write. */
        public bool $tolerated = false,
        /** Gap in the specification's list: read as info, allowed on write. */
        public bool $specGap = false,
    ) {}
}
