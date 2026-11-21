<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Validate;

use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Document\DsvDocument;
use SteffenR\Dsv\Document\Element;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Schema\ElementDef;
use SteffenR\Dsv\Schema\EnumValue;
use SteffenR\Dsv\Schema\FieldDef;
use SteffenR\Dsv\Schema\ListSchema;
use SteffenR\Dsv\Schema\ScalarType;
use SteffenR\Dsv\Support\Text;
use SteffenR\Dsv\Values\Datum;
use SteffenR\Dsv\Values\Uhrzeit;
use SteffenR\Dsv\Values\Zeit;

/**
 * Validates a {@see DsvDocument} against the schema of its list type: format
 * version, cardinalities, cross rules and per element field count, required
 * fields and values.
 */
final class Validator
{
    /** Upper bound of the type `Zahl` (unsigned 32 bit). Leading zeros are allowed. */
    private const int ZAHL_MAX = 4_294_967_295;

    /**
     * Competition kinds fed by a previous heat; they must name that heat.
     * Reported as warning only: real files in circulation omit it.
     */
    private const array QUALIFYING_KINDS = ['Z', 'F'];

    /**
     * List types the qualification rule applies to. Not the Vereinsmeldeliste:
     * it is created before the event, when nobody can have qualified yet.
     */
    private const array LISTS_WITH_QUALIFICATION = [
        'Wettkampfdefinitionsliste',
        'Wettkampfergebnisliste',
        'Vereinsergebnisliste',
    ];

    /** Kick exercises only exist for butterfly (technik S). */
    private const array KICK_EXERCISES = ['KB', 'KR'];

    private function __construct() {}

    /** @return list<Diagnostic> */
    public static function validate(DsvDocument $document, ListSchema $schema): array
    {
        $version = FormatVersion::fromNumber($document->version);
        if ($version === null) {
            return [FormatVersion::unsupported($document->version)];
        }

        $diagnostics = [];
        /** @var array<string, list<Element>> $byElement */
        $byElement = [];

        foreach ($document->elements() as $element) {
            $byElement[strtoupper($element->name)][] = $element;

            $def = $schema->find($element->name)?->def;
            if ($def === null || !$def->existsIn($version)) {
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::UnknownElement,
                    Severity::Error,
                    \sprintf('%s is not an element of %s in DSV%d', $element->name, $schema->listType, $version->value),
                    $element->line,
                    ['element' => $element->name],
                );
                continue;
            }

            array_push($diagnostics, ...self::validateFields($element, $def, $version));
            array_push($diagnostics, ...self::validateValues($element, $def, $version));
        }

        array_push($diagnostics, ...self::validateCardinalities($byElement, $schema, $version));
        array_push($diagnostics, ...self::validateCrossRules($byElement, $schema, $version));

        return $diagnostics;
    }

    /**
     * Field count is exact (optional trailing attributes still need their
     * separators); required fields must not be blank.
     *
     * @return list<Diagnostic>
     */
    private static function validateFields(Element $element, ElementDef $def, FormatVersion $version): array
    {
        $expectedFields = $def->fieldsFor($version);
        $expected = \count($expectedFields);
        $actual = \count($element->fields);

        if ($actual !== $expected) {
            return [new Diagnostic(
                DiagnosticCode::UnexpectedFieldCount,
                Severity::Error,
                \sprintf('%s expects %d field(s), found %d', $def->name, $expected, $actual),
                $element->line,
                ['expected' => $expected, 'actual' => $actual],
            )];
        }

        $diagnostics = [];
        foreach ($expectedFields as $index => $field) {
            if ($field->required && Text::isBlank($element->fields[$index] ?? null)) {
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::MissingRequiredField,
                    Severity::Error,
                    \sprintf('%s requires a value for field %s', $def->name, $field->name),
                    $element->line,
                    ['field' => $field->name],
                );
            }
        }

        return $diagnostics;
    }

    /** @return list<Diagnostic> */
    private static function validateValues(Element $element, ElementDef $def, FormatVersion $version): array
    {
        $diagnostics = [];

        foreach ($def->fieldsFor($version) as $index => $field) {
            $value = $element->fields[$index] ?? null;
            if ($value === null || Text::isBlank($value)) {
                continue;
            }

            if (!self::matchesType($value, $field->type)) {
                $diagnostics[] = self::invalid(
                    DiagnosticCode::InvalidValue,
                    \sprintf('%s.%s: "%s" is not a valid %s', $def->name, $field->name, $value, $field->type->value),
                    $field,
                    $value,
                    $element->line,
                );
                continue;
            }

            if ($field->range !== null) {
                $numeric = (int) $value;
                if ($numeric < $field->range[0] || $numeric > $field->range[1]) {
                    $diagnostics[] = self::invalid(
                        DiagnosticCode::InvalidValue,
                        \sprintf('%s.%s: %s is outside %d..%d', $def->name, $field->name, $value, $field->range[0], $field->range[1]),
                        $field,
                        $value,
                        $element->line,
                    );
                    continue;
                }
            }

            if ($field->values === null) {
                continue;
            }

            $match = self::findEnumValue($value, $field, $version);
            $at = $element->line;

            if ($match === null) {
                $diagnostics[] = self::invalid(
                    DiagnosticCode::InvalidEnumValue,
                    \sprintf('%s.%s: "%s" is not an allowed value in DSV%d', $def->name, $field->name, $value, $version->value),
                    $field,
                    $value,
                    $at,
                );
            } elseif ($match->specGap) {
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::InvalidEnumValue,
                    Severity::Info,
                    \sprintf('%s.%s: "%s" is missing from the specification\'s list, accepted as a gap', $def->name, $field->name, $value),
                    $at,
                    ['field' => $field->name, 'value' => $value, 'specGap' => true],
                );
            } elseif ($field->caseInsensitive && $match->value !== $value) {
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::InvalidEnumValue,
                    Severity::Warning,
                    \sprintf('%s.%s: "%s" differs from the specified spelling "%s"', $def->name, $field->name, $value, $match->value),
                    $at,
                    ['field' => $field->name, 'value' => $value, 'expected' => $match->value, 'tolerated' => true],
                );
            } elseif ($match->tolerated) {
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::InvalidEnumValue,
                    Severity::Warning,
                    \sprintf('%s.%s: "%s" is not specified for this list type', $def->name, $field->name, $value),
                    $at,
                    ['field' => $field->name, 'value' => $value, 'tolerated' => true],
                );
            }
        }

        return $diagnostics;
    }

    private static function invalid(DiagnosticCode $code, string $message, FieldDef $field, string $value, int $line): Diagnostic
    {
        return new Diagnostic($code, Severity::Error, $message, $line, ['field' => $field->name, 'value' => $value]);
    }

    private static function matchesType(string $value, ScalarType $type): bool
    {
        return match ($type) {
            ScalarType::ZK => true,
            ScalarType::Zeichen => preg_match('/^.\z/su', $value) === 1,
            ScalarType::Zahl => preg_match('/^\d+$/D', $value) === 1 && (int) $value <= self::ZAHL_MAX
                && \strlen(ltrim($value, '0')) <= 10,
            ScalarType::Datum => Datum::decode($value) !== null,
            ScalarType::Uhrzeit => Uhrzeit::decode($value) !== null,
            ScalarType::Zeit => Zeit::decode($value) !== null,
            ScalarType::Betrag => preg_match('/^\d+,\d{2}$/D', $value) === 1,
            // Year, age-class letter, or Masters class with plus sign. \d{1,4} is
            // wider than the wording on purpose: "0" (open lower bound) is common.
            ScalarType::JGAK => preg_match('/^(?:\d{1,4}|[ABCDEJ]|\d{2,3}\+)$/D', $value) === 1,
        };
    }

    private static function findEnumValue(string $value, FieldDef $field, FormatVersion $version): ?EnumValue
    {
        foreach ($field->values ?? [] as $candidate) {
            if ($candidate->since !== null && $candidate->since > $version->value) {
                continue;
            }
            $same = $field->caseInsensitive ? strcasecmp($candidate->value, $value) === 0 : $candidate->value === $value;
            if ($same) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @param array<string, list<Element>> $byElement
     *
     * @return list<Diagnostic>
     */
    private static function validateCardinalities(array $byElement, ListSchema $schema, FormatVersion $version): array
    {
        $diagnostics = [];

        foreach ($schema->elements as $occurrence) {
            $def = $occurrence->def;
            if (!$def->existsIn($version)) {
                continue;
            }

            $records = $byElement[strtoupper($def->name)] ?? [];
            $actual = \count($records);
            $max = $occurrence->max;

            if ($actual >= $occurrence->min && ($max === null || $actual <= $max)) {
                continue;
            }

            $diagnostics[] = new Diagnostic(
                DiagnosticCode::CardinalityViolation,
                Severity::Error,
                \sprintf('%s occurs %d time(s), expected %d..%s', $def->name, $actual, $occurrence->min, $max === null ? 'n' : (string) $max),
                isset($records[0]) ? $records[0]->line : 1,
                ['element' => $def->name, 'min' => $occurrence->min, 'max' => $max, 'actual' => $actual],
            );
        }

        return $diagnostics;
    }

    /**
     * Finds all elements of the list type that carry every named field and
     * returns, per element, the field indices. Field rules thus hang on field
     * names instead of positions.
     *
     * @param list<string>      $fields
     * @param list<string>|null $onlyIn
     *
     * @return list<array{element: string, indices: list<int>}>
     */
    private static function targetsWithFields(ListSchema $schema, FormatVersion $version, array $fields, ?array $onlyIn = null): array
    {
        if ($onlyIn !== null && !\in_array($schema->listType, $onlyIn, true)) {
            return [];
        }

        $targets = [];
        foreach ($schema->elements as $occurrence) {
            $names = array_map(static fn(FieldDef $f): string => $f->name, $occurrence->def->fieldsFor($version));
            $indices = [];
            foreach ($fields as $name) {
                $index = array_search($name, $names, true);
                if ($index === false) {
                    continue 2;
                }
                $indices[] = $index;
            }
            $targets[] = ['element' => $occurrence->def->name, 'indices' => $indices];
        }

        return $targets;
    }

    private static function fieldAt(Element $element, int $index): string
    {
        return Text::trim($element->fields[$index] ?? '');
    }

    /**
     * @param array<string, list<Element>> $byElement
     *
     * @return list<Diagnostic>
     */
    private static function validateCrossRules(array $byElement, ListSchema $schema, FormatVersion $version): array
    {
        $diagnostics = [];

        if (isset($byElement['LASTSCHRIFT'], $byElement['BANKVERBINDUNG'])) {
            $diagnostics[] = new Diagnostic(
                DiagnosticCode::MutuallyExclusiveElements,
                Severity::Error,
                'LASTSCHRIFT and BANKVERBINDUNG must not both be present',
                $byElement['LASTSCHRIFT'][0]->line,
                ['elements' => ['LASTSCHRIFT', 'BANKVERBINDUNG']],
            );
        }

        // Kicks exist only as butterfly.
        foreach (self::targetsWithFields($schema, $version, ['technik', 'ausuebung']) as $target) {
            foreach ($byElement[strtoupper($target['element'])] ?? [] as $record) {
                $exercise = self::fieldAt($record, $target['indices'][1]);
                $technik = self::fieldAt($record, $target['indices'][0]);
                if (!\in_array($exercise, self::KICK_EXERCISES, true) || $technik === 'S') {
                    continue;
                }
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::InvalidValue,
                    Severity::Error,
                    \sprintf('%s.ausuebung: "%s" is only allowed with technik S, found "%s"', $target['element'], $exercise, $technik),
                    $record->line,
                    ['element' => $target['element'], 'field' => 'ausuebung', 'value' => $exercise, 'technik' => $technik],
                );
            }
        }

        // Intermediate heats and finals name the heat that qualified them.
        foreach (self::targetsWithFields($schema, $version, ['wettkampfart', 'qualifikationswettkampfnr'], self::LISTS_WITH_QUALIFICATION) as $target) {
            foreach ($byElement[strtoupper($target['element'])] ?? [] as $record) {
                $kind = self::fieldAt($record, $target['indices'][0]);
                if (!\in_array($kind, self::QUALIFYING_KINDS, true) || self::fieldAt($record, $target['indices'][1]) !== '') {
                    continue;
                }
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::ConditionalFieldRequired,
                    Severity::Warning,
                    \sprintf('%s of type %s should name a qualifikationswettkampfnr', $target['element'], $kind),
                    $record->line,
                    ['element' => $target['element'], 'field' => 'qualifikationswettkampfnr', 'condition' => $kind],
                );
            }
        }

        // Mixed competitions belong into the standard best list (warning only).
        foreach (self::targetsWithFields($schema, $version, ['geschlecht', 'zuordnungBestenliste']) as $target) {
            foreach ($byElement[strtoupper($target['element'])] ?? [] as $record) {
                if (strtoupper(self::fieldAt($record, $target['indices'][0])) !== 'X') {
                    continue;
                }
                $zuordnung = self::fieldAt($record, $target['indices'][1]);
                if (strtoupper($zuordnung) === 'SW') {
                    continue;
                }
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::InvalidValue,
                    Severity::Warning,
                    \sprintf('%s.zuordnungBestenliste should be SW for mixed competitions, found "%s"', $target['element'], $zuordnung),
                    $record->line,
                    ['element' => $target['element'], 'field' => 'zuordnungBestenliste', 'value' => $zuordnung, 'geschlecht' => 'X'],
                );
            }
        }

        // Whoever is not ranked holds no place.
        foreach (self::targetsWithFields($schema, $version, ['platz', 'grundDerNichtwertung']) as $target) {
            foreach ($byElement[strtoupper($target['element'])] ?? [] as $record) {
                $reason = self::fieldAt($record, $target['indices'][1]);
                if ($reason === '') {
                    continue;
                }
                $platz = self::fieldAt($record, $target['indices'][0]);
                if ($platz === '0') {
                    continue;
                }
                $diagnostics[] = new Diagnostic(
                    DiagnosticCode::InvalidValue,
                    Severity::Error,
                    \sprintf('%s.platz must be 0 when grundDerNichtwertung is set, found "%s"', $target['element'], $platz),
                    $record->line,
                    ['element' => $target['element'], 'field' => 'platz', 'value' => $platz, 'grundDerNichtwertung' => $reason],
                );
            }
        }

        foreach ($byElement['MELDEGELD'] ?? [] as $record) {
            if (strtolower($record->fields[0] ?? '') !== 'wkmeldegeld' || Text::trim($record->fields[2] ?? '') !== '') {
                continue;
            }
            $diagnostics[] = new Diagnostic(
                DiagnosticCode::ConditionalFieldRequired,
                Severity::Error,
                'MELDEGELD of type Wkmeldegeld requires a wettkampfnr',
                $record->line,
                ['element' => 'MELDEGELD', 'field' => 'wettkampfnr', 'condition' => 'Wkmeldegeld'],
            );
        }

        return $diagnostics;
    }
}
