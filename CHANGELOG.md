# Changelog

All notable changes are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), versions follow
[Semantic Versioning](https://semver.org/). While the major version is `0`,
breaking changes raise the minor version.

## [Unreleased]

### Added

- Initial PHP 8.4+ port of the TypeScript library
  [`@schroeer-haren/dsv`](https://github.com/schroeer-haren/dsv).
- Synthetic sample files `examples/data/meeting.dsv7` and `meeting.dsv8`; the DSV8 sample is the
  Wettkampfergebnisliste example of the DSV Standard 8.
- `Dsv::parseFile()` reads packed `.DSV8z` archives (standard 8: ZIP with exactly one DSV file;
  needs `ext-zip`, suggested in `composer.json`).
- Fixtures `tests/fixtures/synth/spec8-*.dsv8`: the four examples of the DSV Standard 8, with the
  typos of the printed examples corrected.
- Schema-free parser (`Dsv::parse`) with byte-identical write-back (`Dsv::write`).
- Typed, validating parser for the four list types `Wettkampfdefinitionsliste`,
  `Wettkampfergebnisliste`, `Vereinsmeldeliste` and `Vereinsergebnisliste`, DSV7 and DSV8.
- Diagnostics (`Diagnostic`, `DiagnosticCode`, `Severity`) instead of exceptions for
  findings in real-world files.
- Strict writer (`Dsv::writeList`) and the explicit `Dsv::writeListPreservingDefects`.
- Object-graph projections for result lists (`Dsv::projectResult`) and definition lists
  (`Dsv::projectDefinition`).
- Value codecs `Zeit`, `Datum`, `Uhrzeit`.
- `vendor/bin/dsv` command line tool (`validate`, `info`, `json`).
- Runnable examples in `examples/`, guides in `docs/`.
- One-button `Release` workflow: derives the version from Conventional Commits, updates this
  changelog, tags and creates the GitHub release.
