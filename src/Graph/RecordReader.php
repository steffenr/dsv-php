<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph;

use SteffenR\Dsv\Schema\ListSchema;
use SteffenR\Dsv\Support\Text;
use SteffenR\Dsv\Typed\TypedRecord;

/**
 * Reads field values for the projections.
 *
 * An empty value means "not given"; then the specification's default applies
 * where the field defines one. Defaults are applied only here — never to the
 * {@see TypedRecord}s themselves, which are the basis of writing: an applied
 * default would turn an omitted field into a written one.
 *
 * @internal
 */
final class RecordReader
{
    /** @var array<string, array<string, string>> */
    private array $defaults = [];

    public function __construct(ListSchema $schema)
    {
        foreach ($schema->elements as $occurrence) {
            foreach ($occurrence->def->fields as $field) {
                if ($field->default !== null) {
                    $this->defaults[strtoupper($occurrence->def->name)][$field->name] = $field->default;
                }
            }
        }
    }

    public function value(TypedRecord $record, string $name): string
    {
        $raw = Text::trim($record->values[$name] ?? '');

        return $raw === '' ? ($this->defaults[strtoupper($record->element)][$name] ?? '') : $raw;
    }

    /** Integer value; null if absent or unreadable (never a guessed 0). */
    public function number(TypedRecord $record, string $name): ?int
    {
        $raw = $this->value($record, $name);

        return preg_match('/^[+-]?\d{1,18}$/D', $raw) === 1 ? (int) $raw : null;
    }

    /** Key part for a possibly missing number; mirrors "NaN" of the reference implementation. */
    public static function key(?int $number): string
    {
        return $number === null ? 'NaN' : (string) $number;
    }
}
