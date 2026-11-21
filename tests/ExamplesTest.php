<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** The scripts in `examples/` are documentation; they must keep working. */
#[CoversNothing]
final class ExamplesTest extends TestCase
{
    /** @return iterable<string, array{string, string}> */
    public static function examples(): iterable
    {
        yield 'read results' => ['01-read-results.php', 'Beispiel, Anna'];
        yield 'validate upload' => ['02-validate-upload.php', 'Accepted'];
        yield 'edit and save' => ['03-edit-and-save.php', 'round trip: identical'];
        yield 'create definition' => ['04-create-definition.php', 'FORMAT:Wettkampfdefinitionsliste;8;'];
        yield 'export csv' => ['05-export-csv.php', '"Beispiel, Anna",2008'];
        yield 'time helpers' => ['06-time-helpers.php', '00:01:02,45'];
    }

    #[DataProvider('examples')]
    public function testExampleRuns(string $script, string $expected): void
    {
        $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/../examples/' . $script) . ' 2>&1';
        exec($command, $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
        self::assertStringContainsString($expected, implode("\n", $output));
    }
}
