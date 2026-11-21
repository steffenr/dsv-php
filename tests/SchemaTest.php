<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Schema\ListSchema;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Schema\SchemaRegistry;

#[CoversClass(ListType::class)]
#[CoversClass(ListSchema::class)]
#[CoversClass(SchemaRegistry::class)]
final class SchemaTest extends TestCase
{
    /** @return iterable<string, array{ListType}> */
    public static function types(): iterable
    {
        foreach (ListType::cases() as $case) {
            yield $case->value => [$case];
        }
    }

    #[DataProvider('types')]
    public function testSchemaFrame(ListType $type): void
    {
        $schema = $type->schema();

        self::assertSame($type->value, $schema->listType);
        self::assertSame($schema, $type->schema(), 'schemas are cached');
        self::assertSame('FORMAT', $schema->elements[0]->def->name);
        self::assertSame('DATEIENDE', $schema->elements[\count($schema->elements) - 1]->def->name);
        self::assertTrue($schema->elements[\count($schema->elements) - 1]->def->bare);
        self::assertNotNull($schema->find('format'), 'lookup is case-insensitive');
        self::assertNull($schema->find('GIBTESNICHT'));
    }

    #[DataProvider('types')]
    public function testFieldNamesAreUniquePerElement(ListType $type): void
    {
        foreach ($type->schema()->elements as $occurrence) {
            $names = array_map(static fn($f): string => $f->name, $occurrence->def->fields);
            self::assertSame($names, array_values(array_unique($names)), $occurrence->def->name);
            self::assertCount(\count($names), $occurrence->def->fieldsFor(FormatVersion::V8));
        }
    }

    public function testDsv8OnlyFieldsAreFilteredForDsv7(): void
    {
        $bank = ListType::Wettkampfdefinitionsliste->schema()->find('BANKVERBINDUNG');
        self::assertNotNull($bank);

        self::assertGreaterThan(\count($bank->def->fieldsFor(FormatVersion::V7)), \count($bank->def->fieldsFor(FormatVersion::V8)));
    }

    public function testListTypeFromName(): void
    {
        self::assertSame(ListType::Vereinsmeldeliste, ListType::fromName('VEREINSMELDELISTE'));
        self::assertNull(ListType::fromName('Unbekannt'));
        self::assertNull(ListType::fromName(null));
    }
}
