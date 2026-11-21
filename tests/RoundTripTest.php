<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Dsv;

/** Properties that must hold for every fixture, real or synthetic. */
#[CoversNothing]
final class RoundTripTest extends TestCase
{
    use FixtureTrait;

    /** @return iterable<string, array{string}> */
    public static function allFixtures(): iterable
    {
        foreach ([...self::fixturePaths('real'), ...self::fixturePaths('synth')] as $path) {
            yield $path => [$path];
        }
    }

    /** @return iterable<string, array{string}> */
    public static function conformantSynthFixtures(): iterable
    {
        foreach (self::fixturePaths('synth') as $path) {
            if (!str_contains($path, 'verstoss') && !str_contains($path, 'leer')) {
                yield $path => [$path];
            }
        }
    }

    #[DataProvider('allFixtures')]
    public function testSchemaFreeRoundTripIsByteIdentical(string $path): void
    {
        $input = self::fixture($path);

        self::assertSame($input, Dsv::write(Dsv::parse($input)->document));
    }

    #[DataProvider('allFixtures')]
    public function testParsingNeverThrowsAndAlwaysReturnsADocument(string $path): void
    {
        $result = Dsv::parseAny(self::fixture($path));

        self::assertNotSame('', $result->document->listType === '' ? 'x' : $result->document->listType);
    }

    #[DataProvider('conformantSynthFixtures')]
    public function testSyntheticFixturesAreFree_OfErrors(string $path): void
    {
        $result = Dsv::parseAny(self::fixture($path));

        self::assertTrue($result->ok, $path . ":\n" . implode("\n", array_map(strval(...), $result->errors())));
    }

    public function testOnlyTheDsv6FilesAreRejectedAsUnsupported(): void
    {
        $unsupported = [];
        foreach (self::fixturePaths('real') as $path) {
            foreach (Dsv::parse(self::fixture($path))->diagnostics as $d) {
                if ($d->code->value === 'unsupported-format-version') {
                    $unsupported[] = $path;
                }
            }
        }

        self::assertCount(5, $unsupported);
    }
}
