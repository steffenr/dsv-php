# Test fixtures

All fixtures are taken from the upstream TypeScript project
[schroeer-haren/dsv](https://github.com/schroeer-haren/dsv) (MIT) so that this PHP
port is checked against exactly the same inputs.

| Folder   | Content                                                                 | Personal data |
| -------- | ----------------------------------------------------------------------- | ------------- |
| `real/`  | 142 DSV files written by real software (EasyWk, WebClub, Splash, …)      | anonymised    |
| `synth/` | Synthetic files that cover every schema field and the DSV7/DSV8 delta   | none          |

## `real/`

Real files are anonymised: names of swimmers, officials and contacts, DSV ids,
streets, phone numbers, e-mail addresses and bank details are replaced by
deterministic pseudonyms. Structure, element order, whitespace, line endings,
times, years of birth and club names are untouched byte for byte — the test
value lies in the format quirks, not in the names.

What real files do differently from the specification (all of it is handled by
the parser and asserted in the tests):

- `FORMAT:` is never on line 1; a generator comment comes first.
- Blank after the colon (`FORMAT: Wettkampfergebnisliste;7;`) from EasyWk.
- UTF-8 throughout, CRLF mostly, LF sometimes.
- File names do not follow the convention — the list type is read from `FORMAT:` only.
- 5 files are DSV6 and are rejected as unsupported on purpose.
- Some lists omit the trailing `;` (warning `unterminated-field-list`).

**Never add original files with real names of people (especially minors) here.**

## `synth/`

Derived from the specification. For every list type three files differ only in
one respect:

| Suffix               | Content                              | Expectation |
| -------------------- | ------------------------------------ | ----------- |
| `delta-…-dsv7.dsv7`  | no DSV8 content, declared as DSV7    | accepted    |
| `delta-…-dsv8.dsv8`  | with DSV8 content, declared as DSV8  | accepted    |
| `delta-…-verstoss.dsv7` | with DSV8 content, declared as DSV7 | **rejected** |

Files with `verstoss` (violation) or `leer` (empty) in their name are
intentionally invalid or special and are excluded from the "must be error-free"
sweep in `tests/RoundTripTest.php`.

No real DSV8 file exists: the standard applies from 2026-08-01 and DSV7 may be
used until 2026-12-31, so DSV8 support rests on the specification and on these
fixtures.
