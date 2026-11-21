# API reference

Public API = everything in `SteffenR\Dsv` documented here. **Not public**: classes marked
`@internal` (`SchemaRegistry`, `RecordReader`, `Encodable`, `Lexer`, `Parser`, `Validator`,
`Text`, `ListSchema::fromArray()`), the generated schema data, and the wording of
`Diagnostic::$message`. Versioning follows [SemVer](https://semver.org/); while `0.x`, breaking
changes raise the minor version.

## `Dsv` (facade)

| Method | Returns | Notes |
| --- | --- | --- |
| `parse(string $input)` | `ParseResult<DsvDocument>` | schema-free; keeps every line |
| `parseOrThrow(string $input)` | `DsvDocument` | throws `DsvParseException` unless `ok` |
| `parseList(string $input, ListType $type)` | `ParseResult<TypedList>` | typed + validated |
| `parseAny(string $input)` | `ParseResult<TypedList>` | list type from `FORMAT` |
| `parseFile(string $path)` | `ParseResult<TypedList>` | `parseAny` on a file; unpacks `.DSV8z` (needs `ext-zip`); throws `DsvFileException` |
| `write(DsvDocument $document)` | `string` | byte-identical if unchanged |
| `writeList(array\|TypedList $records, ListType $type, ?WriteOptions $options = null)` | `string` | strict; throws `DsvWriteException` |
| `writeListPreservingDefects(…same…)` | `WriteResult` | passes pre-existing defects through |
| `projectResult(TypedList $list)` | `ProjectionResult<Wettkampfergebnis>` | |
| `projectDefinition(TypedList $list)` | `ProjectionResult<Wettkampfdefinition>` | |

## Results

### `Document\ParseResult<T>`

| Member | |
| --- | --- |
| `$document: T` | the value (also present when not `ok`) |
| `$diagnostics: list<Diagnostic>` | all findings |
| `$ok: bool` | no `Error`/`Fatal` |
| `errors(): list<Diagnostic>` | only `Error`/`Fatal` |

### `Diagnostic\Diagnostic`

`code: DiagnosticCode`, `severity: Severity`, `message: string`, `line: int`,
`data: array<string,mixed>`; `__toString()` → `[severity] code (line N): message`.

`Severity`: `Fatal`, `Error`, `Warning`, `Info`; `isFailure(): bool`.
`DiagnosticCode`: 21 cases, see [diagnostics](diagnostics.md).

### `Graph\ProjectionResult<T>`

`$graph: T`, `$diagnostics: list<Diagnostic>`.

## Schema-free model (`Document\`)

| Class | Members |
| --- | --- |
| `DsvDocument` | `?string $listType`, `?int $version`, `list<Item> $items`, `bool $hasBom`, `elements(?string $name = null): list<Element>` |
| `Item` (interface) | `string $raw`, `int $line`, `string $eol` |
| `Element` | `$name`, `list<string> $fields` (trimmed), `list<string> $rawFields`, `?string $comment`, `bool $bare`, `bool $terminated`, `is(string $name): bool` |
| `Comment`, `Blank` | `$raw`, `$line`, `$eol` |

## Typed model (`Typed\`)

### `TypedList`

`string $listType`, `FormatVersion $version`, `list<TypedRecord> $records`,
`DsvDocument $document`; `records(?string $element = null): list<TypedRecord>`,
`first(string $element): ?TypedRecord`.

### `TypedRecord`

`new TypedRecord(string $element, array<string,string> $values, int $line = 0)`

| Method | |
| --- | --- |
| `get(string $field): string` | trimmed value, `''` if absent |
| `has(string $field): bool` | non-blank |
| `int(string $field): ?int` | |
| `date(string $field): ?Datum` | |
| `time(string $field): ?int` | hundredths of a second |
| `clock(string $field): ?int` | minutes since midnight |
| `with(array $changes): self` | copy with replaced values |

## Schema (`Schema\`)

| Class | |
| --- | --- |
| `ListType` (enum) | `Wettkampfdefinitionsliste`, `Wettkampfergebnisliste`, `Vereinsmeldeliste`, `Vereinsergebnisliste`; `fromName(?string): ?self` (case-insensitive), `schema(): ListSchema` |
| `ListSchema` | `string $listType`, `list<ElementOccurrence> $elements`, `find(string $name): ?ElementOccurrence` |
| `ElementOccurrence` | `ElementDef $def`, `int $min`, `?int $max` (null = unbounded) |
| `ElementDef` | `$name`, `$bare`, `list<FieldDef> $fields`, `?int $since`, `fieldsFor(FormatVersion): list<FieldDef>`, `existsIn(FormatVersion): bool` |
| `FieldDef` | `$name`, `ScalarType $type`, `$required`, `$doc`, `$specRef`, `?int $since`, `?list<EnumValue> $values`, `?string $default`, `?array{int,int} $range`, `bool $caseInsensitive` |
| `EnumValue` | `$value`, `$doc`, `?int $since`, `bool $tolerated`, `bool $specGap` |
| `ScalarType` (enum) | `ZK`, `Zahl`, `Zeichen`, `Zeit`, `Datum`, `Uhrzeit`, `Betrag`, `JGAK` |

## Writing (`Write\`)

`WriteOptions(string $eol = "\r\n", ?FormatVersion $version = null)`,
`WriteResult(string $text, list<Diagnostic> $preservedDefects)`.

## Values (`Values\`)

| | |
| --- | --- |
| `Zeit::decode(string): ?int`, `::encode(int): string`, `::toSeconds(int): float`, `::isZero(int): bool`, `Zeit::MAX_HUNDREDTHS` | |
| `Datum(int $day, int $month, int $year)`, `Datum::decode(string): ?self`, `->encode()`, `->toDateTimeImmutable(?DateTimeZone)`, `Datum::fromDateTime(DateTimeInterface)`, `__toString()` | |
| `Uhrzeit::decode(string): ?int`, `::encode(int): string`, `Uhrzeit::MAX_MINUTE` | |

## Format

`Format\FormatVersion` enum: `V7 = 7`, `V8 = 8`; `fromNumber(?int): ?self`.

## Object graph

`Graph\Result\` — `Wettkampfergebnis`, `Abschnitt`, `Kampfrichter`, `Wettkampf`, `Start`,
`Platzierung`, `Zwischenzeit`, `Reaktion`, `Staffel`, `StaffelPerson`, `StaffelZwischenzeit`,
`Abloese`, `Verein`, `Person`.
`Graph\Definition\` — `Wettkampfdefinition`, `Abschnitt`, `Wettkampf`, `Pflichtzeit`.
`Graph\` — `Veranstaltung`, `Wertung` (shared).

All nodes expose public properties named after the fields of the file (German, as the
standard) plus `line`. See [usage](usage.md#2-the-object-graph) for the tree and the
property classes in `src/Graph/` for the exact fields.

## Exceptions

`DsvException` (interface) implemented by `DsvParseException`, `DsvWriteException` (both with
`array $diagnostics`) and `DsvFileException`.

## CLI

`Cli\Application::run(array $argv, resource $out, resource $err): int` — the engine behind
`bin/dsv`; see [usage](usage.md#6-the-command-line).
