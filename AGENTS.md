# AGENTS.md

Guidance for AI coding agents and human contributors working in this repository.

## What this is

`steffenr/dsv-php` — a PHP **≥ 8.4** library to parse, validate and write DSV7/DSV8 files
(German swimming federation data exchange format). It is a port of the TypeScript library
[`@schroeer-haren/dsv`](https://github.com/schroeer-haren/dsv); schemas and fixtures come from
there. No runtime dependencies. Public entry point: `src/Dsv.php`.

## Commands

```bash
composer install
composer test        # PHPUnit
composer stan        # PHPStan, level max (src, tests, examples)
composer cs          # php-cs-fixer, dry run
composer cs:fix      # apply code style
composer check       # cs + stan + test — must be green before every commit
vendor/bin/phpunit --filter RoundTripTest     # one class
php bin/dsv validate examples/data/meeting.dsv7
```

## Layout

```
src/Dsv.php            public facade (static)
src/Lexer, Document    schema-free level
src/Schema             ListType, ListSchema, ElementDef, FieldDef, EnumValue
src/Validate           Validator (all rules)
src/Typed, Parser.php  typed level
src/Write              strict writer
src/Graph              projectors + node classes
src/Values             Zeit / Datum / Uhrzeit
src/Cli                bin/dsv engine
resources/schema/      GENERATED schema data (4 files) — do not hand-edit
tests/                 PHPUnit; tests/fixtures/{real,synth}
examples/              runnable docs, executed by tests/ExamplesTest.php
docs/                  usage, integration, api, formats, diagnostics, architecture
```

Read `docs/architecture.md` before changing parsing, validation or writing.

## Rules that matter

1. **Lenient reading, strict writing.** Reading reports problems as `Diagnostic`s and keeps
   going; real files are not spec-conformant. Writing re-parses its own output and refuses on
   anything not explicitly allowed (`Writer::isWriteFailure`). Do not relax one to fix the other.
2. **Don't throw on file content** when parsing. Return diagnostics. Exceptions are for caller
   mistakes (writing invalid data, encoding out-of-range values) and I/O.
3. **The list type comes from `FORMAT`**, never from a file name.
4. **Round trip must stay byte-identical** at the schema-free level
   (`RoundTripTest` over every fixture). Keep `raw` and `eol` of items untouched.
5. **List-specific knowledge belongs in `resources/schema/*.php`**, not in code. The only
   per-list logic is the cross rules in `Validator`, written against field names.
6. **Numbers are `?int`; invalid is `null`, never `0`.** Times are integer hundredths;
   `Datum` is a triple (no `DateTime`).
7. **Defaults of the specification are applied in the graph only** (`RecordReader`), never to
   `TypedRecord`s — they are the basis for writing.
8. **Diagnostic `message` is not API.** Tests and callers assert on `code`/`severity`/`data`.
9. **No runtime dependencies**, no `ext-*` requirements. Dev tools only.
10. **Never commit real personal data.** DSV files contain names/years of birth of (often
    young) swimmers. Only the anonymised `tests/fixtures/real/` and synthetic data are allowed.
    New sample files must be synthetic or anonymised.

## Code conventions

- `declare(strict_types=1);` in every file. PER-CS 3.0 via php-cs-fixer (`.php-cs-fixer.dist.php`).
- `final` classes, `readonly` wherever the object is a value; enums for closed sets.
- Property hooks / asymmetric visibility are allowed (PHP 8.4); don't use anything newer than 8.4.
- PHPStan **level max** with no baseline and no `@phpstan-ignore`. Fix the types.
- English for code, identifiers, comments and docs. DSV domain terms stay German
  (`Wettkampf`, `Abschnitt`, `Zwischenzeit`, field names from the standard).
- Public API additions need: a test, an entry in `docs/api.md`, a line in `CHANGELOG.md`.
- Commits: [Conventional Commits](https://www.conventionalcommits.org/) in English, imperative,
  lower case: `feat: …`, `fix: …`, `docs: …`, `test: …`, `refactor: …`, `chore: …`, `ci: …`.

## Testing

- Every fixture in `tests/fixtures/` is exercised by `RoundTripTest` (byte-identical round
  trip; synthetic files must parse without errors; DSV6 files must be rejected).
- Prefer small inline strings for rule tests (see `ValidatorTest::HEAD` for a valid header).
- Fixture files are byte-exact (mixed CRLF/LF on purpose, `-text` in `.gitattributes`): never
  re-save them through an editor that normalises line endings.
- After changing the parser, run the whole suite; the real fixtures encode many quirks.
- Examples in `examples/` are run by `tests/ExamplesTest.php`; keep them working.
- Expected numbers from the 142 real fixtures: 5 DSV6 files rejected, 53 empty required fields
  in 28 files, 78 unterminated field lists (these match the upstream project's measurements).

## Updating the schema

`resources/schema/*.php` are generated from the TypeScript schema
(`src/schema/*.ts` upstream, one `ListSchema` per list type). To update: change the upstream
definition, export `{listenart, elements: [{min, max, def: {name, bare, since, fields: […]}}]}`
as JSON, convert to PHP arrays in the shape documented in `ListSchema::fromArray()`
(`@phpstan-type SchemaData`), then run `composer check`. Keep the `// GENERATED` header.

## Releasing

SemVer; `0.x` while the API settles. Move the `Unreleased` section of `CHANGELOG.md` to the new
version, make sure `main` is green, then tag: `git tag v0.1.0 && git push --tags`. The
`Release` workflow re-runs all checks and creates the GitHub release; Packagist picks up the tag.
There is no version field in `composer.json` on purpose.

## Don't

- Don't add a runtime dependency or a framework integration into `src/`.
- Don't "fix" fixtures to make a test pass — fix the code or document the quirk.
- Don't catch and swallow `DsvWriteException`; surface the diagnostics.
- Don't edit generated schema files by hand.
