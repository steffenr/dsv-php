# DSV formats

The *DSV Standard* of the Deutscher Schwimm-Verband defines the text files exchanged
between swim-meet software. This library implements **DSV7** (standard of 2022-08-31, valid
from 2023-01-01) and **DSV8** (standard of 2026-03-14, valid from 2026-08-01). DSV7 may be
used until **2026-12-31**; from 2027-01-01 only DSV8 applies.

The standards themselves are published by the DSV
(<https://www.dsv.de/de/service/formulare/schwimmen/>) and are not part of this repository.

## File structure

```text
(* optional comments *)
FORMAT:Wettkampfergebnisliste;7;
ERZEUGER:EasyWk;5.28;info@easywk.de;
VERANSTALTUNG:Cup;Musterstadt;25;AUTOMATISCH;
…
DATEIENDE
```

- One element per line: `NAME:value;value;` — values are **terminated** by `;`, not separated.
  Empty optional values keep their separator: `A:x;;y;`.
- `FORMAT` is the first *element*; comment lines `(* … *)` may precede it. `DATEIENDE`
  is the last element and has no colon.
- Element order follows the list type's definition; some elements occur 1×, others 0..1 or
  1..n (the *cardinality*).
- UTF-8, no BOM. Line endings CRLF or LF.
- The list type is **only** read from `FORMAT`.
- Quoting does not exist: `"` is data.

## List types

| `ListType` | Elements (excerpt, in file order) |
| --- | --- |
| `Wettkampfdefinitionsliste` | `VERANSTALTUNG`, `VERANSTALTUNGSORT`, `AUSRICHTER`, `MELDEADRESSE`, `MELDESCHLUSS`, `BANKVERBINDUNG` / `LASTSCHRIFT`, `ABSCHNITT`, `WETTKAMPF`, `WERTUNG`, `PFLICHTZEIT`, `MELDEGELD` |
| `Wettkampfergebnisliste` | `VERANSTALTUNG`, `ABSCHNITT`, `KAMPFGERICHT`, `WETTKAMPF`, `WERTUNG`, `VEREIN`, `PNERGEBNIS`, `PNZWISCHENZEIT`, `PNREAKTION`, `STERGEBNIS`, `STAFFELPERSON`, `STZWISCHENZEIT`, `STABLOESE` |
| `Vereinsmeldeliste` | `VERANSTALTUNG`, `ABSCHNITT`, `WETTKAMPF`, `VEREIN`, `ANSPRECHPARTNER`, `KARIMELDUNG`, `KARIABSCHNITT`, `TRAINER`, `PNMELDUNG`, `HANDICAP`, `STARTPN`, `STMELDUNG`, `STARTST`, `STAFFELPERSON` |
| `Vereinsergebnisliste` | `VERANSTALTUNG`, `KAMPFGERICHT`, `WETTKAMPF`, `WERTUNG`, `VEREIN`, `PERSON`, `PERSONENERGEBNIS`, `PNZWISCHENZEIT`, `PNREAKTION`, `STAFFEL`, `STAFFELPERSON`, `STAFFELERGEBNIS`, `STZWISCHENZEIT`, `STABLOESE` |
The authoritative list with every field, type, required flag and value list is generated
data: [`resources/schema/`](../resources/schema) (and at runtime
`ListType::…->schema()`).

## DSV7 → DSV8 changes

The changes are additive. The schema marks them with `since: 8` (elements, fields and enum
values):

- Fields and elements that exist only in DSV8 are **absent** from a DSV7 file — including their
  separator, so DSV8 content declared as DSV7 shows up as `unexpected-field-count` or
  `unknown-element`.
- New enum values (for example gender `D` = divers, new exercise values `KB`, `KR`).
- New required fields (for example `BANKVERBINDUNG.kontoinhaber`).

`tests/fixtures/synth/delta-*` hold, for each list type, a DSV7 file, a DSV8 file and a
"violation" file (DSV8 content declared as DSV7) that must be rejected.

## Quirks of real files

All handled; most are warnings, never crashes.

| Observation | Handling |
| --- | --- |
| `FORMAT:` is never on line 1 (generator comment first) | comments don't count, no finding |
| blank after the colon (`FORMAT: Wettkampfergebnisliste;7;`) | values are trimmed |
| CRLF mostly, sometimes LF, mixed possible | both accepted; round trip keeps each line's ending |
| file names don't follow the convention (`-Protokoll.dsv7`, `.txt`) | list type from `FORMAT` only |
| last field lacks its `;` | `unterminated-field-list` warning, field is read |
| list type written in upper case (`WETTKAMPFERGEBNISLISTE`) | case-insensitive |
| empty required fields (`KAMPFGERICHT.vereinDesKampfrichters`) | `missing-required-field` error, records still returned |
| enum values outside the specification (`A`, `N`, `SPR`, upper-case fee types) | warning `tolerated` |
| year-of-birth fields with one digit (`0` = open lower bound) | accepted as `JGAK` |
| DSV6 files (5 of the 142 samples) | fatal `unsupported-format-version`; the lines remain readable via `Dsv::parse()` |

## Types

| Type | Format | PHP representation |
| --- | --- | --- |
| `ZK` | free text | `string` |
| `Zahl` | unsigned integer ≤ 4 294 967 295, leading zeros allowed | `?int` via `TypedRecord::int()` |
| `Zeichen` | exactly one character (Unicode code point) | `string` |
| `Zeit` | `HH:MM:SS,hh` | `int` hundredths, `Zeit::decode/encode` |
| `Datum` | `TT.MM.JJJJ`, real calendar days only | `Datum` |
| `Uhrzeit` | `HH:MM` 24 h | `int` minutes since midnight |
| `Betrag` | `n,nn` (exactly two decimals) | `string` |
| `JGAK` | year (`1–4 digits`), age class `A B C D E J`, or masters `25`, `80+` | `string` |
