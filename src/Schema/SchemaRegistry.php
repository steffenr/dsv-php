<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

/**
 * Lazily loads and caches the generated schema of each list type.
 *
 * @phpstan-import-type SchemaData from ListSchema
 *
 * @internal use {@see ListType::schema()}
 */
final class SchemaRegistry
{
    /** @var array<string, ListSchema> */
    private static array $schemas = [];

    private function __construct() {}

    public static function get(ListType $type): ListSchema
    {
        if (!isset(self::$schemas[$type->value])) {
            /** @var SchemaData $data */
            $data = require \dirname(__DIR__, 2) . '/resources/schema/' . strtolower($type->value) . '.php';
            self::$schemas[$type->value] = ListSchema::fromArray($data);
        }

        return self::$schemas[$type->value];
    }
}
