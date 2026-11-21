<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

use SteffenR\Dsv\Format\FormatVersion;

/** Definition of an element: its name and ordered attributes. */
final readonly class ElementDef
{
    /** @param list<FieldDef> $fields */
    public function __construct(
        public string $name,
        public bool $bare,
        public array $fields,
        public ?int $since = null,
    ) {}

    /**
     * The fields that exist in the given version. Fields introduced in DSV8 do
     * not exist in a DSV7 file — neither the value nor its separator.
     *
     * @return list<FieldDef>
     */
    public function fieldsFor(FormatVersion $version): array
    {
        return array_values(array_filter(
            $this->fields,
            static fn(FieldDef $f): bool => $f->since === null || $f->since <= $version->value,
        ));
    }

    public function existsIn(FormatVersion $version): bool
    {
        return $this->since === null || $this->since <= $version->value;
    }
}
