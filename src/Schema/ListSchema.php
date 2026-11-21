<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

/**
 * Elements of one list type in specification order.
 *
 * @phpstan-type FieldData array{name: string, type: string, required: bool, since?: int, caseInsensitive?: bool, default?: string, range?: array{int, int}, values?: list<array{value: string, doc: string, since?: int, tolerated?: bool, specGap?: bool}>, doc: string, specRef: string}
 * @phpstan-type ElementData array{name: string, min: int, max: int|null, bare: bool, since?: int, fields: list<FieldData>}
 * @phpstan-type SchemaData array{listenart: string, elements: list<ElementData>}
 */
final class ListSchema
{
    /** @var array<string, ElementOccurrence> */
    private array $byName = [];

    /** @param list<ElementOccurrence> $elements */
    public function __construct(
        public readonly string $listType,
        public readonly array $elements,
    ) {
        foreach ($elements as $occurrence) {
            $this->byName[strtoupper($occurrence->def->name)] = $occurrence;
        }
    }

    /** Case-insensitive element lookup; real files write names in upper case. */
    public function find(string $name): ?ElementOccurrence
    {
        return $this->byName[strtoupper($name)] ?? null;
    }

    /**
     * Builds a schema from the generated data files in `resources/schema/`.
     *
     * @param SchemaData $data
     *
     * @internal
     */
    public static function fromArray(array $data): self
    {
        $elements = [];
        foreach ($data['elements'] as $e) {
            $fields = [];
            foreach ($e['fields'] as $f) {
                $values = null;
                if (isset($f['values'])) {
                    $values = [];
                    foreach ($f['values'] as $v) {
                        $values[] = new EnumValue(
                            $v['value'],
                            $v['doc'],
                            $v['since'] ?? null,
                            $v['tolerated'] ?? false,
                            $v['specGap'] ?? false,
                        );
                    }
                }
                $fields[] = new FieldDef(
                    $f['name'],
                    ScalarType::from($f['type']),
                    $f['required'],
                    $f['doc'],
                    $f['specRef'],
                    $f['since'] ?? null,
                    $values,
                    $f['default'] ?? null,
                    $f['range'] ?? null,
                    $f['caseInsensitive'] ?? false,
                );
            }
            $elements[] = new ElementOccurrence(
                new ElementDef($e['name'], $e['bare'], $fields, $e['since'] ?? null),
                $e['min'],
                $e['max'],
            );
        }

        return new self($data['listenart'], $elements);
    }
}
