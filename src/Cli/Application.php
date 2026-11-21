<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Cli;

use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Document\ParseResult;
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Exception\DsvException;
use SteffenR\Dsv\Typed\TypedList;

/**
 * The `vendor/bin/dsv` command line tool: validate, inspect and export DSV files.
 *
 * Exit codes: 0 = all files ok, 1 = at least one file has errors (or warnings
 * with `--strict`), 2 = usage error or unreadable file.
 */
final class Application
{
    private const string USAGE = <<<'TXT'
        Usage:
          dsv validate [--strict] [--quiet] <file>...   Check files; exit 1 on errors
          dsv info <file>                                Show list type, version and element counts
          dsv json <file>                                Export all records as JSON

        Options:
          --strict   treat warnings like errors (validate)
          --quiet    print only the summary line per file (validate)
          --help     show this help

        TXT;

    /**
     * @param list<string> $argv Arguments without the script name.
     * @param resource     $out
     * @param resource     $err
     */
    public function run(array $argv, $out, $err): int
    {
        $flags = array_values(array_filter($argv, static fn(string $a): bool => str_starts_with($a, '--')));
        $args = array_values(array_filter($argv, static fn(string $a): bool => !str_starts_with($a, '--')));
        $command = array_shift($args);

        if ($command === null || \in_array('--help', $flags, true)) {
            fwrite($command === null && !\in_array('--help', $flags, true) ? $err : $out, self::USAGE);

            return $command === null && !\in_array('--help', $flags, true) ? 2 : 0;
        }

        if ($args === [] || !\in_array($command, ['validate', 'info', 'json'], true)) {
            fwrite($err, self::USAGE);

            return 2;
        }

        try {
            return match ($command) {
                'validate' => $this->validate($args, $flags, $out),
                'info' => $this->info($args[0], $out),
                default => $this->json($args[0], $out),
            };
        } catch (DsvException|\RuntimeException $e) {
            fwrite($err, 'dsv: ' . $e->getMessage() . "\n");

            return 2;
        }
    }

    /**
     * @param list<string> $files
     * @param list<string> $flags
     * @param resource     $out
     */
    private function validate(array $files, array $flags, $out): int
    {
        $strict = \in_array('--strict', $flags, true);
        $quiet = \in_array('--quiet', $flags, true);
        $failed = false;

        foreach ($files as $file) {
            $result = Dsv::parseFile($file);
            $warnings = 0;
            $errors = 0;

            foreach ($result->diagnostics as $d) {
                match ($d->severity) {
                    Severity::Fatal, Severity::Error => ++$errors,
                    Severity::Warning => ++$warnings,
                    Severity::Info => null,
                };
                if (!$quiet && $d->severity !== Severity::Info) {
                    fwrite($out, \sprintf("%s:%d: %s %s: %s\n", $file, $d->line, $d->severity->value, $d->code->value, $d->message));
                }
            }

            $bad = !$result->ok || ($strict && $warnings > 0);
            $failed = $failed || $bad;
            fwrite($out, \sprintf("%s: %s (%d error(s), %d warning(s))\n", $file, $bad ? 'FAILED' : 'ok', $errors, $warnings));
        }

        return $failed ? 1 : 0;
    }

    /** @param resource $out */
    private function info(string $file, $out): int
    {
        $result = Dsv::parseFile($file);
        $list = $result->document;

        fwrite($out, \sprintf("File:      %s\n", $file));
        fwrite($out, \sprintf("List:      %s\n", $list->listType === '' ? '(unknown)' : $list->listType));
        fwrite($out, \sprintf("Version:   DSV%d\n", $list->version->value));
        fwrite($out, \sprintf("Valid:     %s\n", $result->ok ? 'yes' : 'no'));

        $counts = [];
        foreach ($list->records as $record) {
            $counts[$record->element] = ($counts[$record->element] ?? 0) + 1;
        }
        foreach ($counts as $element => $count) {
            fwrite($out, \sprintf("  %-22s %d\n", $element, $count));
        }

        return $result->ok ? 0 : 1;
    }

    /** @param resource $out */
    private function json(string $file, $out): int
    {
        /** @var ParseResult<TypedList> $result */
        $result = Dsv::parseFile($file);
        $list = $result->document;

        $records = array_map(
            static fn($r): array => ['element' => $r->element, 'line' => $r->line, 'values' => $r->values],
            $list->records,
        );

        fwrite($out, json_encode([
            'listType' => $list->listType,
            'version' => $list->version->value,
            'ok' => $result->ok,
            'records' => $records,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");

        return $result->ok ? 0 : 1;
    }
}
