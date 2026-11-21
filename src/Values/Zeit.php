<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Values;

/**
 * Swim time `HH:MM:SS,hh`, represented as an integer number of hundredths of a
 * second. `00:00:00,00` is the specified "no time" value and is deliberately
 * not mapped to null.
 */
final class Zeit
{
    /** Largest representable time: 99:59:59,99. */
    public const int MAX_HUNDREDTHS = 35_999_999;

    private function __construct() {}

    /** Reads a time as hundredths; null if invalid. */
    public static function decode(string $value): ?int
    {
        if (preg_match('/^(\d{2}):(\d{2}):(\d{2}),(\d{2})$/D', $value, $m) !== 1) {
            return null;
        }
        $minutes = (int) $m[2];
        $seconds = (int) $m[3];
        if ($minutes > 59 || $seconds > 59) {
            return null;
        }

        return ((((int) $m[1]) * 60 + $minutes) * 60 + $seconds) * 100 + (int) $m[4];
    }

    /**
     * @throws \SteffenR\Dsv\Exception\DsvWriteException outside 0..35999999
     */
    public static function encode(int $hundredths): string
    {
        Encodable::require(
            $hundredths >= 0 && $hundredths <= self::MAX_HUNDREDTHS,
            'Zeit',
            (string) $hundredths,
            'an integer between 0 and ' . self::MAX_HUNDREDTHS . ' (99:59:59,99)',
        );

        $cs = $hundredths % 100;
        $total = intdiv($hundredths, 100);
        $ss = $total % 60;
        $totalMinutes = intdiv($total, 60);

        return \sprintf('%02d:%02d:%02d,%02d', intdiv($totalMinutes, 60), $totalMinutes % 60, $ss, $cs);
    }

    /** Hundredths as seconds, e.g. 6523 → 65.23. */
    public static function toSeconds(int $hundredths): float
    {
        return $hundredths / 100;
    }

    /** Whether the value is the "no time" default. */
    public static function isZero(int $hundredths): bool
    {
        return $hundredths === 0;
    }
}
