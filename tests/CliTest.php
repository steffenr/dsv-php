<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Cli\Application;

#[CoversClass(Application::class)]
final class CliTest extends TestCase
{
    use FixtureTrait;

    /**
     * @param list<string> $args
     *
     * @return array{int, string, string}
     */
    private static function invoke(array $args): array
    {
        $out = fopen('php://memory', 'w+b');
        $err = fopen('php://memory', 'w+b');
        self::assertNotFalse($out);
        self::assertNotFalse($err);

        $code = new Application()->run($args, $out, $err);
        rewind($out);
        rewind($err);

        return [$code, (string) stream_get_contents($out), (string) stream_get_contents($err)];
    }

    private static function path(string $relative): string
    {
        return __DIR__ . '/' . $relative;
    }

    public function testValidateOk(): void
    {
        [$code, $out] = self::invoke(['validate', self::path('../examples/data/meeting.dsv7')]);

        self::assertSame(0, $code);
        self::assertStringContainsString(': ok (0 error(s), 0 warning(s))', $out);
    }

    public function testValidateOkForDsv8Sample(): void
    {
        [$code, $out] = self::invoke(['validate', self::path('../examples/data/meeting.dsv8')]);

        self::assertSame(0, $code);
        self::assertStringContainsString(': ok (0 error(s), 0 warning(s))', $out);
    }

    public function testValidateFailsOnErrors(): void
    {
        [$code, $out] = self::invoke(['validate', self::path('fixtures/synth/delta-vereinsmeldeliste-verstoss.dsv7')]);

        self::assertSame(1, $code);
        self::assertStringContainsString('FAILED', $out);
        self::assertStringContainsString('error', $out);
    }

    public function testStrictTreatsWarningsAsFailures(): void
    {
        $file = null;
        foreach (self::fixturePaths('real') as $path) {
            [$code] = self::invoke(['validate', self::path('fixtures/' . $path)]);
            [$strict, $out] = self::invoke(['validate', '--strict', '--quiet', self::path('fixtures/' . $path)]);
            if ($code === 0 && $strict === 1) {
                $file = $path;
                self::assertStringContainsString('FAILED', $out);
                break;
            }
        }

        self::assertNotNull($file, 'expected a real file that is ok but has warnings');
    }

    public function testQuietPrintsOnlySummary(): void
    {
        [, $out] = self::invoke(['validate', '--quiet', self::path('fixtures/synth/delta-vereinsmeldeliste-verstoss.dsv7')]);

        self::assertSame(1, substr_count($out, "\n"));
    }

    public function testInfoAndJson(): void
    {
        [$code, $out] = self::invoke(['info', self::path('../examples/data/meeting.dsv7')]);
        self::assertSame(0, $code);
        self::assertStringContainsString('Wettkampfergebnisliste', $out);
        self::assertMatchesRegularExpression('/PNERGEBNIS\s+4/', $out);

        [$code, $json] = self::invoke(['json', self::path('../examples/data/meeting.dsv7')]);
        self::assertSame(0, $code);
        /** @var array{listType: string, version: int, records: list<array{element: string}>} $data */
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('Wettkampfergebnisliste', $data['listType']);
        self::assertSame(7, $data['version']);
        self::assertSame('FORMAT', $data['records'][0]['element']);
    }

    public function testUsageAndErrors(): void
    {
        [$code, , $err] = self::invoke([]);
        self::assertSame(2, $code);
        self::assertStringContainsString('Usage:', $err);

        [$code, $out] = self::invoke(['--help']);
        self::assertSame(0, $code);
        self::assertStringContainsString('Usage:', $out);

        [$code] = self::invoke(['frobnicate', 'x']);
        self::assertSame(2, $code);

        [$code, , $err] = self::invoke(['validate', self::path('fixtures/missing.dsv7')]);
        self::assertSame(2, $code);
        self::assertStringContainsString('Cannot read', $err);
    }
}
