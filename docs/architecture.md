# Architecture

The design is carried over from the TypeScript original (see its long
[architecture notes](https://github.com/schroeer-haren/dsv/blob/main/docs/architecture.md))
and adapted to PHP idioms. This page records the decisions that matter when you use or
change the code.

## Layers

```text
 bytes ──► Lexer ──► DsvDocument (items: Element | Comment | Blank)       schema-free
                          │            └─► Writer::writeDocument  → identical bytes
                          ▼
              Validator (ListSchema) ──► Diagnostic[]
                          │
                          ▼
                TypedList (TypedRecord[])  ◄── ListType / ListSchema (resources/schema/*.php)
                   │             │
                   │             └─► Writer::writeList  (strict, reads back with the parser)
                   ▼
        DefinitionProjector / ResultProjector ──► object graph + reference diagnostics
```

| Namespace | Responsibility |
| --- | --- |
| `Lexer` | split lines, split a line into `Element` / `Comment` / `Blank`; linear time |
| `Document` | immutable schema-free model, `ParseResult` |
| `Schema` | `ListType`, `ListSchema`, `ElementDef`, `FieldDef`, `EnumValue`, loaded lazily from `resources/schema/` |
| `Validate` | `Validator`: structure, field rules, value types, cardinalities, cross rules |
| `Typed` | `TypedList`, `TypedRecord` (named values + typed accessors) |
| `Parser` | the two parse functions (`parseDocument`, `parseTyped`) |
| `Write` | `Writer`, `WriteOptions`, `WriteResult` |
| `Graph` | projectors and the node classes (`Graph\Result\*`, `Graph\Definition\*`) |
| `Values` | `Zeit`, `Datum`, `Uhrzeit` codecs |
| `Diagnostic`, `Exception`, `Format` | findings, exceptions, `FormatVersion` |
| `Cli` | `vendor/bin/dsv` |
| `Dsv` | the single, static public facade |

## Decisions

### Schema driven

Everything list specific — elements, field order, types, required flags, value lists,
cardinalities, defaults, version availability (`since`) — lives in **data**
(`resources/schema/*.php`, plain arrays, opcache friendly). The parser, validator and writer
contain no per-list logic. Adding a list type or fixing a value list means editing data.

The data files are generated from the schema of the TypeScript project (which in turn
cites the line in the specification for every field). They carry `doc` and `specRef` for each
field. Do not edit them by hand; to change a rule, change the upstream schema and
regenerate.

### Lenient reading, strict writing

- **Reading** accepts what real exporters produce and reports deviations as `Warning`.
- **Writing** (`Writer::writeList`) serialises to the canonical form, parses that text again
  with the same parser, and refuses on any finding that is not explicitly allowed. So the
  library can never produce a file its own reader would reject.

#### Strict writing

What may remain in *own* output:

| Finding | In own output |
| --- | --- |
| `info` severity | allowed |
| `invalid-value`, `conditional-field-required` warnings (specification "should" rules that real files break) | allowed |
| `missing-required-field` | only through `writeListPreservingDefects` |
| `invalid-enum-value` with `tolerated` | only through `writeListPreservingDefects` |
| everything else, every `error`/`fatal` | refused |

This table is `Writer::isWriteFailure()` / `Writer::isPreexistingDefect()`.

### Round trip is a property of the schema-free level

`Dsv::write(Dsv::parse($x)->document) === $x` for every input — the writer replays the raw
line and its line ending. The typed writer re-creates text and cannot promise that; it
promises *validity*.

### Fields are positional, versions subtract fields

Attributes are identified by position. A field with `since: 8` does not exist in DSV7 files at
all (not even its `;`), so a version-aware `ElementDef::fieldsFor()` decides which fields a
file has. The field count must match exactly, which is what makes DSV8 content declared as
DSV7 detectable.

### `Datum` is a triple, times are integers

The format carries no time zone and no floating point. `Datum` (day, month, year) avoids
`DateTime` shifting 31.02. to 03.03.; swim times are integer hundredths so sums and
comparisons are exact. Decoders return `null` for invalid input, encoders throw — see
[usage](usage.md#3-values).

### Projections are separate from records

`TypedRecord`s stay flat and faithful to the file (they are the basis of writing). The object
graph is an *interpretation*: it resolves references, decodes times, drops empty
nationalities, applies defaults.

#### Defaults

The specification names default values for some fields (for example `N` for the relative
time flag of a section, `+` for the sign of a reaction time). They are applied **only in the
graph** (`RecordReader`). Applied in records they would be written out and turn an omitted
field into a given one.

#### No parent pointers, no lazy loading

Nodes are plain data holders: no cycles (so `var_dump`/`json_encode` work and no
`WeakMap`s are needed), all references resolved eagerly (errors appear at projection time
in `diagnostics`, not later). Index arrays on the root give O(1) lookups.

#### Never abort

Dangling or duplicate references are warnings; the first duplicate wins; orphaned events
land in `wettkaempfeOhneAbschnitt`. A half-readable file is more useful than none.

### Numbers are `?int`

A missing or malformed number is `null`, never `0` (a real number and a common value such
as "no place").

### Single facade, no globals

`Dsv` has only static methods with no state beyond a schema cache
(`SchemaRegistry`). The classes underneath are `final` and mostly `readonly`.

## Error handling

| Kind | Mechanism |
| --- | --- |
| content of the file | `Diagnostic` in the `ParseResult` |
| misuse by the caller (invalid value to encode, invalid data to write) | `DsvWriteException` |
| I/O | `DsvFileException` |

Rationale: with *foreign* data problems are normal and belong into the result; with *your
own* data a problem is a bug and should stop you. See [diagnostics](diagnostics.md).

## Performance

Lexing is linear; the comment detection avoids backtracking regular expressions (the
TypeScript original once needed 38 s for a 469 KB line of `;(*`; a regression test with
150 000 markers guards the PHP code). A 0.94 MB result list: ~0.2 s to parse and validate,
~50 ms to project, ~55 MB peak memory.

## Differences from the TypeScript library

| TypeScript | PHP |
| --- | --- |
| plain objects, `Map` indices | readonly value objects, mutable graph nodes, arrays as indices |
| `parseWettkampfergebnisliste()` etc. per list | `Dsv::parseList($bytes, ListType::…)` (and `parseAny`) |
| `NaN` for unreadable numbers in the graph | `null` |
| `projectVereinsmeldeliste` / `projectVereinsergebnisliste` | **not ported** (records only) |
| `Diagnostic.data` untyped object | `array<string, mixed>` |
| dual ESM/CJS package | Composer package, PSR-4 |

## Not included

- Streaming reader; DSV7↔DSV8 conversion; file-name helper; fee calculation; encoding
  conversion; object graphs for `Vereinsmeldeliste` / `Vereinsergebnisliste`.
