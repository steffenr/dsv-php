<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Schema;

/** The four list types of the DSV standard handled by this library. */
enum ListType: string
{
    case Wettkampfdefinitionsliste = 'Wettkampfdefinitionsliste';
    case Wettkampfergebnisliste = 'Wettkampfergebnisliste';
    case Vereinsmeldeliste = 'Vereinsmeldeliste';
    case Vereinsergebnisliste = 'Vereinsergebnisliste';

    /** Resolves the value of a FORMAT element's first field, case-insensitively. */
    public static function fromName(?string $name): ?self
    {
        if ($name === null) {
            return null;
        }
        foreach (self::cases() as $case) {
            if (strcasecmp($case->value, $name) === 0) {
                return $case;
            }
        }

        return null;
    }

    public function schema(): ListSchema
    {
        return SchemaRegistry::get($this);
    }
}
