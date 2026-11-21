# steffenr/dsv-php (PHP)

PHP library for **reading, validating and writing DSV files** — the data exchange
format of the *Deutscher Schwimm-Verband* (German swimming federation) for
announcements, entries and results of swim meets. Supports **DSV7 and DSV8**.

[![CI](https://github.com/steffenr/dsv-php/actions/workflows/ci.yml/badge.svg)](https://github.com/steffenr/dsv-php/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/badge/php-%3E%3D8.4-8892bf.svg)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

This is a PHP port of the TypeScript library
[`@schroeer-haren/dsv`](https://github.com/schroeer-haren/dsv) by Philipp Schröer. The
schemas, validation rules and fixtures are carried over so both libraries agree on what
a valid file is.

- **Built for real files.** Files written by EasyWk, WebClub, Splash Meet Manager & co.
  are rarely spec-conformant. The parser is lenient and reports findings as
  *diagnostics* instead of failing; the writer is strict.
- **Two levels.** Schema-free (every line, byte-identical round trip) and typed (records
  with named fields, validated against the DSV7/DSV8 schema of the list type).
- **Object graphs.** Result and definition lists can be projected into a navigable tree:
  sections → events → starts → placements and times.
- **Modern PHP.** PHP ≥ 8.4, `declare(strict_types=1)`, readonly classes, enums, PHPStan
  level `max`, no runtime dependencies.

## Installation

```bash
composer require steffenr/dsv-php
```

Requires PHP 8.4 or newer. There are no runtime dependencies.

## Quick start

```php
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Values\Zeit;

$result = Dsv::parseList(file_get_contents('results.dsv7'), ListType::Wettkampfergebnisliste);

if (!$result->ok) {
    foreach ($result->errors() as $diagnostic) {
        echo $diagnostic, "\n";            // [error] missing-required-field (line 41): …
    }
}

// Even a file with findings yields records — inspect the diagnostics, then decide.
$graph = Dsv::projectResult($result->document)->graph;

foreach ($graph->wettkampfByKey as $wettkampf) {
    foreach ($wettkampf->starts as $start) {
        printf("%s: %s\n", $start->name, Zeit::encode($start->endzeit ?? 0));
    }
}
```

Don't know the list type? Let the `FORMAT` element decide (never the file name):

```php
$result = Dsv::parseAny($contents);
echo $result->document->listType;          // Wettkampfergebnisliste
```

More in [docs/usage.md](docs/usage.md) and the runnable scripts in [`examples/`](examples).

## The two levels

| | Schema-free | Typed |
| --- | --- | --- |
| Entry | `Dsv::parse()` | `Dsv::parseList()` / `Dsv::parseAny()` |
| You get | `DsvDocument` with every line (elements, comments, blanks) | `TypedList` of `TypedRecord`s keyed by schema field names |
| Validation | only file structure (`FORMAT`, `DATEIENDE`, BOM, version) | schema: field counts, required fields, value types, enums, cardinalities, cross rules |
| Write back | `Dsv::write()` — **byte-identical** | `Dsv::writeList()` — canonical and strictly validated |
| Use for | patching, diffing, forwarding files unchanged | reading data, creating files, editing values |

```php
$source = file_get_contents('in.dsv7');
Dsv::write(Dsv::parse($source)->document) === $source;   // always true
```

### The four list types

| `ListType` | Purpose |
| --- | --- |
| `Wettkampfdefinitionsliste` | announcement: events, sections, rankings, fees |
| `Wettkampfergebnisliste` | complete results of a meet (protocol) |
| `Vereinsmeldeliste` | a club's entries for a meet |
| `Vereinsergebnisliste` | a club's results, sent to the organiser |

## Diagnostics instead of exceptions

Parsing never throws on content problems. Every finding is a `Diagnostic` with a stable
`code` (enum `DiagnosticCode`), a `Severity` (`Fatal`, `Error`, `Warning`, `Info`), the
1-based source `line` and machine-readable `data`. `ParseResult::$ok` is `false` as soon as
there is an `Error` or `Fatal`.

```php
foreach ($result->diagnostics as $d) {
    match ($d->code) {
        DiagnosticCode::MissingRequiredField => …,   // $d->data['field']
        DiagnosticCode::UnsupportedFormatVersion => …,
        default => …,
    };
}
```

Never match on `$d->message` — the wording is not part of the stable API. See
[docs/diagnostics.md](docs/diagnostics.md) for all codes, and for why a file such as an
EasyWk export with an empty required field is a *warning-worthy reality* rather than a crash.

## Writing files

```php
$text = Dsv::writeList($records, ListType::Wettkampfdefinitionsliste, new WriteOptions(version: FormatVersion::V8));
```

The writer is **strict**: the output is read back with the library's own parser and every
finding that is not explicitly allowed throws a `DsvWriteException` with the diagnostics.
A value the parser would reject is never emitted.

To open a real file, change one entry and save it again, use the explicit
`Dsv::writeListPreservingDefects()`. It passes on defects the *file brought along* (an
empty required field, a tolerated enum value) and reports them in
`WriteResult::$preservedDefects`; defects you introduce are still refused.

## Command line

```bash
vendor/bin/dsv validate meeting.dsv7 [--strict] [--quiet]   # exit 1 on errors
vendor/bin/dsv info meeting.dsv7                              # list type, version, element counts
vendor/bin/dsv json meeting.dsv7                              # all records as JSON
```

Handy in CI to check files before they are uploaded or published.

## Integration

[docs/integration.md](docs/integration.md) shows complete, copy-pasteable recipes for
Laravel, Symfony, plain PSR-7 / PSR-15 uploads, queue workers, CSV/JSON export, database
import and CI validation.

## Documentation

| Document | Content |
| --- | --- |
| [docs/usage.md](docs/usage.md) | guided tour of every feature |
| [docs/integration.md](docs/integration.md) | framework recipes (Laravel, Symfony, PSR-7, CLI, CI) |
| [docs/api.md](docs/api.md) | API reference |
| [docs/formats.md](docs/formats.md) | DSV7 vs DSV8, list types, quirks of real files |
| [docs/diagnostics.md](docs/diagnostics.md) | every diagnostic code and how to react |
| [docs/architecture.md](docs/architecture.md) | design decisions |
| [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](CONTRIBUTING.md), [SECURITY.md](SECURITY.md) | project |

## Limits

- Only **DSV7 and DSV8** are read. DSV6 and older (and not-yet-existing newer versions) are
  rejected with `unsupported-format-version`; the formats are not backward compatible.
- Object-graph projections exist for the **Wettkampfergebnisliste** and the
  **Wettkampfdefinitionsliste**. `Vereinsmeldeliste` and `Vereinsergebnisliste` are fully
  parsed, validated and written, but exposed as typed records (`TypedList`) only.
- The library does **not** convert character encodings. The standard prescribes UTF-8; files
  in other encodings are reported (`unknown-encoding-replacement-character`) but not
  transcoded. Convert with `mb_convert_encoding()` first if you must.
- Complete files are read into memory (a 1 MB result list parses in about 0.2 s). There is no streaming reader.
- No DSV7 ↔ DSV8 *converter*. You can write a parsed list with another `WriteOptions::$version`, but the strict writer refuses when the target version needs data the source lacks (for example `BANKVERBINDUNG.kontoinhaber` in DSV8) or does not allow it (DSV8-only enum values in DSV7).
- No helper for the file name convention of the specification (`JJJJ-MM-TT-Ort-Zusatz.DSV8`). The list type is always read from `FORMAT`.
- No fee calculation: `MELDEGELD` is read, validated and written, not summed.

## Development

```bash
composer install
composer check        # php-cs-fixer (dry run) + PHPStan max + PHPUnit
composer cs:fix       # apply code style
```

See [AGENTS.md](AGENTS.md) for the conventions (also useful for human contributors).

## Credits & license

PHP port of [`@schroeer-haren/dsv`](https://github.com/schroeer-haren/dsv) by Philipp
Schröer, whose schemas and fixtures it reuses. The DSV standards themselves belong to the
Deutscher Schwimm-Verband and are available at
[dsv.de](https://www.dsv.de/de/service/formulare/schwimmen/).

[MIT](LICENSE)
