<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

/** Definition of one attribute of an element. */
final readonly class FieldDef
{
    /** @param list<EnumValue>|null $values Allowed values, if the field has a fixed list. */
    public function __construct(
        public string $name,
        public ScalarType $type,
        public bool $required,
        public string $doc,
        public string $specRef,
        public ?int $since = null,
        public ?array $values = null,
        public ?string $default = null,
        /** @var array{0: int, 1: int}|null Inclusive numeric range. */
        public ?array $range = null,
        public bool $caseInsensitive = false,
    ) {}
}
