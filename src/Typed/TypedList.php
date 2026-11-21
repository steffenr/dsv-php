<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Typed;

use SteffenR\Dsv\Document\DsvDocument;
use SteffenR\Dsv\Format\FormatVersion;

/** A list read with its schema: records of any list type with named fields. */
final readonly class TypedList
{
    /** @param list<TypedRecord> $records */
    public function __construct(
        public string $listType,
        public FormatVersion $version,
        public array $records,
        /** The underlying schema-free document, for round trips and raw access. */
        public DsvDocument $document,
    ) {}

    /**
     * Records of one element (case-insensitive) or all records.
     *
     * @return list<TypedRecord>
     */
    public function records(?string $element = null): array
    {
        if ($element === null) {
            return $this->records;
        }

        return array_values(array_filter(
            $this->records,
            static fn(TypedRecord $r): bool => strcasecmp($r->element, $element) === 0,
        ));
    }

    /** The first record of an element, or null. */
    public function first(string $element): ?TypedRecord
    {
        return $this->records($element)[0] ?? null;
    }
}
