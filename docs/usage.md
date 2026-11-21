# Usage guide

A tour of every feature. All snippets use the entry point `SteffenR\Dsv\Dsv`; runnable
versions live in [`examples/`](../examples).

```php
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Schema\ListType;
```

## 1. Reading a file

### Typed, validated

```php
$result = Dsv::parseList($contents, ListType::Wettkampfergebnisliste);

$result->ok;            // bool: no Error/Fatal finding
$result->diagnostics;   // list<Diagnostic>, every finding
$result->errors();      // list<Diagnostic>, only Error/Fatal
$result->document;      // TypedList
```

`$result->document` is a `TypedList`:

```php
$list = $result->document;

$list->listType;                 // 'Wettkampfergebnisliste' (as written in the file)
$list->version;                  // FormatVersion::V7 or ::V8
$list->records;                  // list<TypedRecord>, in file order
$list->records('PNERGEBNIS');    // only one element (case-insensitive)
$list->first('VERANSTALTUNG');   // first record of an element, or null
$list->document;                 // the schema-free DsvDocument underneath
```

A `TypedRecord` addresses values by their **schema field name**:

```php
$v = $list->first('VERANSTALTUNG');

$v->element;                               // 'VERANSTALTUNG'
$v->line;                                  // 1-based source line
$v->get('veranstaltungsbezeichnung');      // string, trimmed ('' if absent)
$v->has('veranstaltungsort');              // bool
$v->int('wettkampfnr');                    // ?int, never a guessed 0
$v->date('abschnittsdatum');               // ?Datum
$v->time('endzeit');                       // ?int  hundredths of a second
$v->clock('anfangszeit');                  // ?int  minutes since midnight
$v->values;                                // array<string,string> raw values
```

The field names of every element are in [`resources/schema/`](../resources/schema) and
available at runtime:

```php
foreach (ListType::Wettkampfergebnisliste->schema()->find('PNERGEBNIS')->def->fields as $f) {
    echo $f->name, ' ', $f->type->value, $f->required ? ' (required)' : '', "\n";
}
```

### Which list is it? — `parseAny`

```php
$result = Dsv::parseAny($contents);   // list type from the FORMAT element
```

The file name is never consulted: real files are named `…-Protokoll.dsv7`, `….txt` and
worse. An unknown list type gives the fatal `wrong-list-type`.

### From a file path

```php
$result = Dsv::parseFile('/data/uploads/meet.dsv7');   // throws DsvFileException if unreadable
// a packed .DSV8z archive (standard 8) works the same way; it needs ext-zip
```

### Throwing variant

`Dsv::parseOrThrow()` is the schema-free parse that returns the document and throws
`DsvParseException` when the result is not `ok`. There is intentionally **no** throwing
variant of the typed parse: findings in real files are the normal case there.

## 2. The object graph

The flat records reference each other by numbers (`wettkampfnr` + `wettkampfart`,
`wertungsId`, `veranstaltungsId`, …). The projections resolve those references:

```php
$projection = Dsv::projectResult($list);     // Wettkampfergebnisliste
$projection->graph;                          // Graph\Result\Wettkampfergebnis
$projection->diagnostics;                    // dangling / ambiguous references
```

```text
Wettkampfergebnis
├─ veranstaltung        (bezeichnung, ort, bahnlaenge, zeitmessung)
├─ abschnitte[]         Abschnitt  (nummer, datum, anfangszeit, kampfrichter[])
│   └─ wettkaempfe[]    Wettkampf  (nummer, art, einzelstrecke, technik, …)
│       ├─ wertungen[]  Wertung
│       ├─ starts[]     Start      (name, jahrgang, verein, endzeit, …)
│       │   ├─ platzierungen[]   zwischenzeiten[]   reaktionen[]
│       └─ staffeln[]   Staffel    (endzeit, personen[], zwischenzeiten[], abloesen[])
├─ vereine[]            Verein
├─ wettkampfByKey       'nummer:art'                  → Wettkampf
├─ wertungById          wertungsId                    → Wertung
├─ abschnittByNummer    abschnittsnr                  → Abschnitt
├─ vereinByKennzahl     vereinskennzahl               → Verein
├─ startByKey           'veranstaltungsId:nr:art'     → Start
├─ staffelByKey         'veranstaltungsId:nr:art'     → Staffel
└─ personById           veranstaltungsId              → Person  (all starts of a swimmer)
```

For an announcement use `Dsv::projectDefinition($list)` → `Graph\Definition\Wettkampfdefinition`
(sections → competitions → ranking groups and qualifying times).

Design notes:

- **Never aborts.** A dangling reference or a duplicate key yields a *warning*
  (`dangling-reference`, `ambiguous-reference`, `incomplete-relay`) in
  `$projection->diagnostics`; the first duplicate wins and nothing is lost.
- **Identification of an event is `nummer` + `art`**: heats and finals share a number.
- **No back references.** Nodes have no parent pointers (no cycles, `var_dump` and
  `json_encode` stay finite); use the index maps instead.
- **Numbers are `?int`**: `null` means absent or unreadable, never a guessed `0`.
- **Times**: `?int` hundredths of a second; `?Datum` for dates; `?int` minutes since
  midnight for times of day.
- Specification defaults (for example `+` for the sign of a reaction time) are applied in
  the graph, **not** in `TypedRecord`s — see [architecture](architecture.md#defaults).
- Treat nodes as read-only; they are plain data holders filled by the projector.

## 3. Values

```php
use SteffenR\Dsv\Values\{Datum, Uhrzeit, Zeit};

Zeit::decode('00:01:02,45');     // 6245 (hundredths) — null if invalid
Zeit::encode(6245);              // '00:01:02,45'     — throws DsvWriteException if out of range
Zeit::toSeconds(6245);           // 62.45
Zeit::isZero(0);                 // 00:00:00,00 is the specified "no time", not null

$d = Datum::decode('29.02.2024');        // Datum(day: 29, month: 2, year: 2024), null for 31.02.
$d->encode();                            // '29.02.2024'
$d->toDateTimeImmutable();               // midnight UTC; pass a DateTimeZone to change
Datum::fromDateTime(new DateTimeImmutable('2026-03-05'));

Uhrzeit::decode('09:30');        // 570
Uhrzeit::encode(570);            // '09:30'
```

**Decoders return `null`** for invalid input (foreign input, where invalid is normal).
**Encoders throw** `DsvWriteException` (your own data; an invalid value is a programming error).

`Datum` is a triple, not a `DateTime`, on purpose: the format has no time zone and an
invalid date must never be shifted silently (31.02. would become 03.03.).

## 4. Writing

### Schema-free: byte-identical

```php
$document = Dsv::parse($contents)->document;
Dsv::write($document) === $contents;        // true, including BOM, comments, CRLF/LF mix
```

### Typed: canonical and strict

```php
use SteffenR\Dsv\Typed\TypedRecord;
use SteffenR\Dsv\Write\WriteOptions;
use SteffenR\Dsv\Format\FormatVersion;

$records = [
    new TypedRecord('FORMAT', ['listart' => 'Wettkampfdefinitionsliste', 'version' => '8']),
    new TypedRecord('ERZEUGER', ['software' => 'my-app', 'version' => '1.0', 'kontakt' => 'me@example.org']),
    // … see examples/04-create-definition.php
    new TypedRecord('DATEIENDE', []),
];

$text = Dsv::writeList($records, ListType::Wettkampfdefinitionsliste, new WriteOptions(
    eol: "\r\n",                      // default; "\n" is also allowed
    version: FormatVersion::V8,       // default: version of the FORMAT record
));
```

Canonical output: no blank after the colon, every field terminated by `;`, `DATEIENDE`
without colon, CRLF by default, no BOM. Comments and quirks of a source file are not kept;
use the schema-free level when you need byte identity.

`writeList` also accepts a `TypedList` directly, so *read → modify → write* is:

```php
$list = Dsv::parseList($contents, ListType::Vereinsmeldeliste)->document;

$records = array_map(
    fn (TypedRecord $r) => $r->element === 'VERANSTALTUNG'
        ? $r->with(['veranstaltungsort' => 'Musterstadt'])
        : $r,
    $list->records,
);

$text = Dsv::writeList($records, ListType::Vereinsmeldeliste, new WriteOptions(version: $list->version));
```

### Why does the writer refuse?

The output text is parsed again with the library's own parser. Every finding that is not
explicitly allowed (see [architecture](architecture.md#strict-writing)) throws a
`DsvWriteException` carrying the diagnostics:

```php
try {
    Dsv::writeList($records, $type);
} catch (DsvWriteException $e) {
    foreach ($e->diagnostics as $d) { … }     // list<Diagnostic>
}
```

### Passing defects through: `writeListPreservingDefects`

Real files often come with an empty required field (28 of the 142 sample files have at
least one, most often `KAMPFGERICHT.vereinDesKampfrichters`). Reading such a file, changing one entry and saving would be
impossible with the strict writer although you did not cause the defect.

```php
$result = Dsv::writeListPreservingDefects($list, $type);
$result->text;                // the file
$result->preservedDefects;    // list<Diagnostic> — report these to the user!
```

Passed on are only defects the *file brought along* and which keep it readable: empty
required fields and *tolerated* enum values. Wrong field counts, a broken element order,
unknown elements, or a value you invented are still refused.

### DSV7 ↔ DSV8

`WriteOptions::$version` selects the version to write; DSV8-only fields are dropped for DSV7
and added (empty) for DSV8. The strict check decides whether that works: upgrading a result
list usually does, upgrading a definition list fails if the new required field
`BANKVERBINDUNG.kontoinhaber` is missing, downgrading DSV8-only values fails.

## 5. Validating

Validation happens during every typed parse. What is checked:

- **File**: `FORMAT` first element, `DATEIENDE` last, BOM, encoding, version 7/8.
- **Per element**: known in this list type and version; exact field count; required fields;
  value types (`Zahl`, `Zeichen`, `Zeit`, `Datum`, `Uhrzeit`, `Betrag`, `JGAK`); numeric
  ranges; enum values.
- **Per list**: cardinality of every element (e.g. `WETTKAMPF` 1..n); mutually exclusive
  elements (`LASTSCHRIFT` / `BANKVERBINDUNG`).
- **Cross rules**: kicks only with butterfly; qualifying heats should name their source;
  mixed events belong to `SW`; not ranked ⇒ place 0; `Wkmeldegeld` needs a `wettkampfnr`.

See [formats](formats.md) and [diagnostics](diagnostics.md).

## 6. The command line

```bash
vendor/bin/dsv validate files/*.dsv7 --quiet   # exit code 1 if any file has errors
vendor/bin/dsv validate --strict file.dsv8     # warnings fail as well
vendor/bin/dsv info file.dsv7
vendor/bin/dsv json file.dsv7 | jq '.records[] | select(.element=="PNERGEBNIS")'
```

Exit codes: `0` ok, `1` findings, `2` usage error or unreadable file.

## 7. Error handling cheat sheet

| Situation | What happens |
| --- | --- |
| Empty required field, bad value, unknown element | `ok === false`, records still returned |
| DSV6 / DSV9 / no version | fatal `unsupported-format-version`, `records` empty |
| Wrong list type for `parseList` | fatal `wrong-list-type`, `records` empty |
| File missing / unreadable | `DsvFileException` from `parseFile` |
| `parseOrThrow` on a bad file | `DsvParseException` (has `->diagnostics`) |
| Writing something invalid | `DsvWriteException` (has `->diagnostics`) |
| Encoding an out-of-range time/date | `DsvWriteException` |

Every exception implements the marker interface `SteffenR\Dsv\Exception\DsvException`.
