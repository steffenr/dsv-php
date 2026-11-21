<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Values;

/** Time of day `HH:MM`, represented as minutes since midnight. */
final class Uhrzeit
{
    public const int MAX_MINUTE = 1439;

    private function __construct() {}

    /** Reads a time of day as minutes since midnight; null if invalid. */
    public static function decode(string $value): ?int
    {
        if (preg_match('/^(\d{2}):(\d{2})$/D', $value, $m) !== 1) {
            return null;
        }
        $hours = (int) $m[1];
        $minutes = (int) $m[2];

        return $hours > 23 || $minutes > 59 ? null : $hours * 60 + $minutes;
    }

    /**
     * @throws \SteffenR\Dsv\Exception\DsvWriteException outside 0..1439
     */
    public static function encode(int $minutesOfDay): string
    {
        Encodable::require(
            $minutesOfDay >= 0 && $minutesOfDay <= self::MAX_MINUTE,
            'Uhrzeit',
            (string) $minutesOfDay,
            'an integer between 0 and ' . self::MAX_MINUTE . ' (23:59)',
        );

        return \sprintf('%02d:%02d', intdiv($minutesOfDay, 60), $minutesOfDay % 60);
    }
}
