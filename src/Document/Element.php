<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Document;

/** An element line of the form `ELEMENTNAME:attr1;attr2;` (or bare `DATEIENDE`). */
final readonly class Element implements Item
{
    /**
     * @param list<string> $fields    Field values without surrounding whitespace.
     * @param list<string> $rawFields Field values exactly as written.
     */
    public function __construct(
        public string $name,
        public array $fields,
        public array $rawFields,
        public ?string $comment,
        public bool $bare,
        public bool $terminated,
        public int $line,
        public string $raw,
        public string $eol,
    ) {}

    /** Case-insensitive element name comparison. */
    public function is(string $name): bool
    {
        return strcasecmp($this->name, $name) === 0;
    }
}
