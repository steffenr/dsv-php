# Security policy

## Supported versions

The latest released minor version receives fixes.

## Reporting a vulnerability

Please do **not** open a public issue for a security problem. Use GitHub's private
vulnerability reporting ("Security" tab → "Report a vulnerability") or contact the maintainers
privately. We aim to answer within a few days.

## Scope notes

The library parses untrusted text. Relevant properties:

- Parsing is linear in the input size (no catastrophic backtracking; covered by a test).
- Memory use is proportional to the input (roughly 50× for parse + objects + graph); limit
  upload sizes in your application.
- The library performs no I/O except `Dsv::parseFile()` / `bin/dsv` reading the file you name,
  and executes no code from its input.
- DSV files contain personal data. Handle them according to your data-protection obligations;
  never put real files into issues, logs or public test fixtures.
