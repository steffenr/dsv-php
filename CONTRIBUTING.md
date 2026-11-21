# Contributing

Thanks for helping! The short version:

1. Fork, create a branch.
2. `composer install`
3. Make your change **with a test**.
4. `composer check` must pass (code style, PHPStan level max, PHPUnit).
5. Open a pull request using a [Conventional Commit](https://www.conventionalcommits.org/)
   style title (`fix: handle …`, `feat: add …`).

Conventions, architecture rules and the do's and don'ts are in [AGENTS.md](AGENTS.md) —
they apply to humans as well.

## Reporting a problem with a file

Parsing problems are best reported with a small file that reproduces them.

**Do not attach real DSV files.** They contain names and years of birth of swimmers, often
children. Reduce the file to the few lines that matter and replace names, ids and club
information by made-up values, or attach the output of
`vendor/bin/dsv validate --quiet your-file.dsv7` plus the diagnostic `code`s and `line`s.

## Schema or specification questions

List-specific rules (fields, enum values, cardinalities) are generated from the TypeScript
sibling project. If a rule looks wrong, please open an issue there as well as here; see
"Updating the schema" in [AGENTS.md](AGENTS.md).
