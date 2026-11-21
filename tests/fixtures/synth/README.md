# Synthetic fixtures

Derived from the DSV specification, copied unchanged from the upstream project.
Do not edit by hand. See `../README.md` for the meaning of the file name suffixes
(`delta-…-dsv7`, `delta-…-dsv8`, `delta-…-verstoss`).

## `spec8-*.dsv8`

The four examples printed in the DSV Standard 8 (valid from 01.08.2026), one per list type,
added here (not from upstream). The printed examples contain typos that make them invalid
(`,` or `:` instead of `;` in `WERTUNG`, `STZWISCHENZEIT` and `KARIABSCHNITT`, and `SPR`
instead of `SP` as the referee position, and a `WERTUNG` pointing to event `5:F` instead of `101:F`); these were corrected and the file starts with a
comment saying so. The Wettkampfdefinitionsliste example writes the `MELDEGELD` types in
upper case, which is read case-insensitively but reported as a spelling warning.

## `spec7-*.dsv7`

The examples published for the DSV Standard 7 (Wettkampfdefinitionsliste, Vereinsmeldeliste, Vereinsergebnisliste, Wettkampfergebnisliste), also not from upstream. Corrected in the definition list
typos: `:` or `,` instead of `;` in three `WERTUNG` lines, one superfluous empty field in every
`WERTUNG` line, and a `WERTUNG` pointing to event `5:F` instead of `101:F`.
In the Vereinsmeldeliste example one `KARIABSCHNITT:2;1;:` was corrected to `KARIABSCHNITT:2;1;;`.
In the Vereinsergebnisliste example `V,2` and `F,7` became `V;2` and `F;7`, `WERTUNG:5;F` became `WERTUNG:101;F`, and the referee position `SPR` became `SP`.
In the Wettkampfergebnisliste example the same `V,2` / `F,7` / `WERTUNG:5;F` / `SPR` / `E,1` corrections apply; in addition one `PNERGEBNIS` had an extra empty field (`V;3;;2;`) and two times were written in a short form (`1:01,44`, `0:30,00`) instead of `HH:MM:SS,hh`.
