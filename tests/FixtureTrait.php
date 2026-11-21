<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

trait FixtureTrait
{
    /** Contents of a fixture below `tests/fixtures/`, e.g. `synth/nachweis.dsv7`. */
    private static function fixture(string $path): string
    {
        $content = file_get_contents(__DIR__ . '/fixtures/' . $path);
        self::assertIsString($content, "Fixture $path is not readable");

        return $content;
    }

    /** @return list<string> paths relative to `tests/fixtures/` */
    public static function fixturePaths(string $dir): array
    {
        $paths = [];
        foreach (glob(__DIR__ . '/fixtures/' . $dir . '/*') ?: [] as $file) {
            if (basename($file) !== 'README.md') {
                $paths[] = $dir . '/' . basename($file);
            }
        }
        sort($paths);

        return $paths;
    }
}
