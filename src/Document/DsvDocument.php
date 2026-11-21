<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Document;

/**
 * Schema-free view of a DSV file: every line as an {@see Item}, in order.
 *
 * Writing it back with {@see \SteffenR\Dsv\Write\Writer::writeDocument()}
 * reproduces the input byte for byte.
 */
final readonly class DsvDocument
{
    /** @param list<Item> $items */
    public function __construct(
        /** Raw list type from FORMAT (compare case-insensitively), or null. */
        public ?string $listType,
        /** Version number from FORMAT, or null if not readable. */
        public ?int $version,
        public array $items,
        public bool $hasBom,
    ) {}

    /** @return list<Element> */
    public function elements(?string $name = null): array
    {
        $out = [];
        foreach ($this->items as $item) {
            if ($item instanceof Element && ($name === null || $item->is($name))) {
                $out[] = $item;
            }
        }

        return $out;
    }
}
