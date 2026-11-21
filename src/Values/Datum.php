<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Values;

use DateTimeImmutable;
use DateTimeZone;

/**
 * A calendar date in the format `TT.MM.JJJJ`.
 *
 * Kept as a triple, not a `DateTimeImmutable`: no time zone appears in the
 * format and invalid dates must not be silently shifted (31.02. → 03.03.).
 */
final readonly class Datum implements \Stringable
{
    public function __construct(
        public int $day,
        public int $month,
        public int $year,
    ) {}

    /** Reads a date; `null` for malformed or non-existent calendar days. */
    public static function decode(string $value): ?self
    {
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/D', $value, $m) !== 1) {
            return null;
        }
        $day = (int) $m[1];
        $month = (int) $m[2];
        $year = (int) $m[3];

        return self::isValid($day, $month, $year) ? new self($day, $month, $year) : null;
    }

    /**
     * Formats as `TT.MM.JJJJ`.
     *
     * @throws \SteffenR\Dsv\Exception\DsvWriteException for non-existent days
     */
    public function encode(): string
    {
        Encodable::require(
            self::isValid($this->day, $this->month, $this->year),
            'Datum',
            \sprintf('%d-%d-%d', $this->year, $this->month, $this->day),
            'an existing calendar day with a year between 0 and 9999',
        );

        return \sprintf('%02d.%02d.%04d', $this->day, $this->month, $this->year);
    }

    public function __toString(): string
    {
        return $this->encode();
    }

    /** Midnight of this date in the given zone (default UTC). */
    public function toDateTimeImmutable(?DateTimeZone $zone = null): DateTimeImmutable
    {
        return new DateTimeImmutable(
            \sprintf('%04d-%02d-%02d 00:00:00', $this->year, $this->month, $this->day),
            $zone ?? new DateTimeZone('UTC'),
        );
    }

    public static function fromDateTime(\DateTimeInterface $date): self
    {
        return new self((int) $date->format('j'), (int) $date->format('n'), (int) $date->format('Y'));
    }

    private static function isValid(int $day, int $month, int $year): bool
    {
        return $year >= 0 && $year <= 9999 && $month >= 1 && $month <= 12 && $day >= 1
            && $day <= self::daysInMonth($month, $year);
    }

    private static function daysInMonth(int $month, int $year): int
    {
        if ($month === 2) {
            return ($year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0)) ? 29 : 28;
        }

        return [4 => 30, 6 => 30, 9 => 30, 11 => 30][$month] ?? 31;
    }
}
