<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Support;

/** Unicode-aware helpers; linear time, safe on very long lines. */
final class Text
{
    private function __construct() {}

    /** Trims whitespace (including Unicode spaces) from both ends. */
    public static function trim(string $value): string
    {
        $out = @preg_replace('/\A\s++|(?<!\s)\s++\z/u', '', $value);

        return \is_string($out) ? $out : trim($value);
    }

    /** Trims whitespace from the end only. */
    public static function rtrim(string $value): string
    {
        $out = @preg_replace('/(?<!\s)\s++\z/u', '', $value);

        return \is_string($out) ? $out : rtrim($value);
    }

    public static function isBlank(?string $value): bool
    {
        return $value === null || self::trim($value) === '';
    }

    /** Whether the string is valid UTF-8. */
    public static function isUtf8(string $value): bool
    {
        return preg_match('//u', $value) === 1;
    }
}
