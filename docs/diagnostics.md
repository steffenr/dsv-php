# Diagnostics

Real DSV files are rarely perfect, and a library that throws on the first oddity would
reject files the federation itself ships. So parsing **reports** instead of throwing:

```php
final readonly class Diagnostic
{
    public DiagnosticCode $code;     // stable identifier (enum)
    public Severity $severity;       // Fatal | Error | Warning | Info
    public string $message;          // English text for humans — NOT a stable API
    public int $line;                // 1-based source line (1 = whole file)
    public array $data;              // machine readable details
}
```

`ParseResult::$ok` is `false` when at least one finding is `Error` or `Fatal`.

## Severities

| Severity | Meaning | Makes `ok` false |
| --- | --- | --- |
| `Fatal` | not a usable DSV file (empty, unsupported version, wrong list type); typed `records` are empty | yes |
| `Error` | violates the specification; data is still read | yes |
| `Warning` | deviates from the specification but is common in real files; data is read | no |
| `Info` | a deliberate decision of the library, e.g. a gap in the specification's value list | no |

`line` always refers to a line of the *input*. Columns or character offsets are not
tracked; highlight the whole line.

## Codes

### File level (both parse levels)

| Code | Severity | Meaning / `data` |
| --- | --- | --- |
| `empty-input` | fatal | the input has no lines |
| `missing-format-element` | error | no `FORMAT` element |
| `format-not-first-element` | warning | something other than a comment precedes `FORMAT` |
| `missing-dateiende-element` | warning | no `DATEIENDE` |
| `element-order-violation` | warning | an element follows `DATEIENDE` (`element`) |
| `unterminated-field-list` | warning | the line lacks the final `;` (`element`); the last field is still read |
| `unexpected-bom` | warning | UTF-8 BOM present; the standard requires none; the BOM is preserved by `Dsv::write` |
| `unknown-encoding-replacement-character` | warning | U+FFFD or invalid UTF-8; probably decoded with the wrong charset |
| `unsupported-format-version` | fatal | not 7 or 8, or no readable version (`version`: `int` or `null`) |

### Typed level (schema validation)

| Code | Severity | Meaning / `data` |
| --- | --- | --- |
| `wrong-list-type` | fatal | `FORMAT` names another list than requested (`expected`, `actual`) |
| `unknown-element` | error | element not part of this list type in this version (`element`) |
| `unexpected-field-count` | error | wrong number of fields; the count is exact (`expected`, `actual`) |
| `missing-required-field` | error | a required field is blank (`field`) |
| `invalid-value` | error / warning | value does not match its type or range, or violates a cross rule (`field`, `value`) |
| `invalid-enum-value` | error / warning / info | value not in the list of allowed values (`field`, `value`; `tolerated`, `specGap`, `expected`) |
| `cardinality-violation` | error | element occurs too often or too rarely (`element`, `min`, `max`, `actual`) |
| `mutually-exclusive-elements` | error | `LASTSCHRIFT` and `BANKVERBINDUNG` together |
| `conditional-field-required` | error / warning | a field is needed because of another one (`element`, `field`, `condition`) |

### Projection level (`Dsv::projectResult()` / `projectDefinition()`)

| Code | Severity | Meaning |
| --- | --- | --- |
| `dangling-reference` | warning | a reference points to nothing (unknown section, ranking group, event, …) |
| `ambiguous-reference` | warning | a key is defined twice, or two lines of one start contradict each other; the first wins |
| `incomplete-relay` | warning | a relay names only some of its members (`genannt`, `erwartet`) |

## The `invalid-enum-value` shades

One code, four situations, distinguished by severity and `data`:

| Severity | `data` | Situation | Writer |
| --- | --- | --- | --- |
| error | — | value is not allowed | refuses |
| warning | `tolerated: true` | real files carry it although this list type does not specify it (e.g. `WETTKAMPF.wettkampfart` `A`/`N` in a result list) | refuses, unless passed through by `writeListPreservingDefects` |
| warning | `tolerated: true`, `expected` | spelling differs only by case (`MELDEGELD.meldegeldTyp` `EINZELMELDEGELD`) | refuses (same) |
| info | `specGap: true` | the specification evidently forgot the value in its list | accepts |

## Reacting by code

```php
use SteffenR\Dsv\Diagnostic\DiagnosticCode as C;

foreach ($result->diagnostics as $d) {
    match ($d->code) {
        C::UnsupportedFormatVersion => $ui->say('Please export as DSV7 or DSV8.'),
        C::MissingRequiredField     => $ui->say("Field {$d->data['field']} is empty (line {$d->line})."),
        C::UnterminatedFieldList    => null,                        // harmless, ignore
        default                     => $log->notice((string) $d),
    };
}
```

Translate texts in your application by `code` + `data`. Wording of `message` may change in any
release.

## Why some "errors" are only warnings

Several rules are `Warning` although the specification states them, because of how real files
look. Examples (measured on the 142 sample files):

- A missing trailing `;` appears in 73 lines of 3 files, all from the same exporter chain.
- `qualifikationswettkampfnr` empty for heats/finals: 22 of 67 cases in two files.
- Mixed events (`geschlecht` X) not assigned to `SW`: 74 of 244 events (children's meets
  with `KG`, masters with `MS`).

Rejecting them would reject files in circulation. The rule of thumb: what real files do is
tolerated while *reading*; what the library *writes* must follow the specification
(see [architecture](architecture.md#strict-writing)).

## Exceptions

| Exception | Thrown by |
| --- | --- |
| `DsvParseException` (`->diagnostics`) | `Dsv::parseOrThrow()` |
| `DsvWriteException` (`->diagnostics`) | `Dsv::writeList()`, `writeListPreservingDefects()`, `Zeit::encode()`, `Datum::encode()`, `Uhrzeit::encode()` |
| `DsvFileException` | `Dsv::parseFile()` |

All implement `SteffenR\Dsv\Exception\DsvException`.
