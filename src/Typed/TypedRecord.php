<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Typed;

use SteffenR\Dsv\Support\Text;
use SteffenR\Dsv\Values\Datum;
use SteffenR\Dsv\Values\Uhrzeit;
use SteffenR\Dsv\Values\Zeit;

/**
 * One element with its field values addressed by schema name
 * (e.g. `veranstaltungsbezeichnung`). Missing optional fields are `''`.
 *
 * Build one yourself to write a file: `new TypedRecord('FORMAT', ['listart' => …, 'version' => '8'])`.
 */
final readonly class TypedRecord
{
    /** @param array<string, string> $values */
    public function __construct(
        public string $element,
        public array $values,
        /** 1-based source line; 0 for records not read from a file. */
        public int $line = 0,
    ) {}

    /** Raw value, trimmed; `''` if absent. */
    public function get(string $field): string
    {
        return Text::trim($this->values[$field] ?? '');
    }

    /** Whether the field has a non-blank value. */
    public function has(string $field): bool
    {
        return $this->get($field) !== '';
    }

    /** Integer value, or null if absent or not a plain integer (never a guessed 0). */
    public function int(string $field): ?int
    {
        $raw = $this->get($field);

        return preg_match('/^[+-]?\d{1,18}$/D', $raw) === 1 ? (int) $raw : null;
    }

    /** Date value, or null if absent/invalid. */
    public function date(string $field): ?Datum
    {
        return Datum::decode($this->get($field));
    }

    /** Swim time in hundredths of a second, or null if absent/invalid. */
    public function time(string $field): ?int
    {
        return Zeit::decode($this->get($field));
    }

    /** Time of day in minutes since midnight, or null if absent/invalid. */
    public function clock(string $field): ?int
    {
        return Uhrzeit::decode($this->get($field));
    }

    /**
     * A copy with some values replaced.
     *
     * @param array<string, string> $changes
     */
    public function with(array $changes): self
    {
        return new self($this->element, [...$this->values, ...$changes], $this->line);
    }
}
