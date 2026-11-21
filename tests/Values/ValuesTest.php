<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Exception\DsvWriteException;
use SteffenR\Dsv\Values\Datum;
use SteffenR\Dsv\Values\Uhrzeit;
use SteffenR\Dsv\Values\Zeit;

#[CoversClass(Datum::class)]
#[CoversClass(Zeit::class)]
#[CoversClass(Uhrzeit::class)]
final class ValuesTest extends TestCase
{
    public function testDatumRoundTrip(): void
    {
        $d = Datum::decode('29.02.2024');

        self::assertNotNull($d);
        self::assertEquals(new Datum(29, 2, 2024), $d);
        self::assertSame('29.02.2024', $d->encode());
        self::assertSame('2024-02-29', $d->toDateTimeImmutable()->format('Y-m-d'));
    }

    /** @return iterable<string, array{string}> */
    public static function invalidDates(): iterable
    {
        yield 'no leap year' => ['29.02.2025'];
        yield 'century not leap' => ['29.02.1900'];
        yield 'april 31' => ['31.04.2026'];
        yield 'month 13' => ['01.13.2026'];
        yield 'day 0' => ['00.01.2026'];
        yield 'short year' => ['01.01.26'];
        yield 'iso' => ['2026-01-01'];
        yield 'trailing newline' => ["01.01.2026\n"];
        yield 'empty' => [''];
    }

    #[DataProvider('invalidDates')]
    public function testDatumRejectsInvalid(string $value): void
    {
        self::assertNull(Datum::decode($value));
    }

    public function testDatumCenturyLeapYear(): void
    {
        self::assertNotNull(Datum::decode('29.02.2000'));
    }

    public function testDatumEncodeRefusesNonExistentDay(): void
    {
        $this->expectException(DsvWriteException::class);
        new Datum(31, 2, 2026)->encode();
    }

    public function testDatumFromDateTime(): void
    {
        self::assertEquals(new Datum(5, 3, 2026), Datum::fromDateTime(new \DateTimeImmutable('2026-03-05 23:59:59')));
    }

    public function testZeitDecodeAndEncode(): void
    {
        self::assertSame(6523, Zeit::decode('00:01:05,23'));
        self::assertSame('00:01:05,23', Zeit::encode(6523));
        self::assertSame(0, Zeit::decode('00:00:00,00'));
        self::assertTrue(Zeit::isZero(0));
        self::assertSame(65.23, Zeit::toSeconds(6523));
        self::assertSame('99:59:59,99', Zeit::encode(Zeit::MAX_HUNDREDTHS));
    }

    /** @return iterable<string, array{string}> */
    public static function invalidTimes(): iterable
    {
        yield 'minutes 60' => ['00:60:00,00'];
        yield 'seconds 60' => ['00:00:60,00'];
        yield 'dot separator' => ['00:00:10.00'];
        yield 'no hundredths' => ['00:00:10'];
        yield 'short' => ['1:05,23'];
    }

    #[DataProvider('invalidTimes')]
    public function testZeitRejectsInvalid(string $value): void
    {
        self::assertNull(Zeit::decode($value));
    }

    public function testZeitEncodeRejectsOutOfRange(): void
    {
        $this->expectException(DsvWriteException::class);
        Zeit::encode(-1);
    }

    public function testZeitEncodeRejectsTooLarge(): void
    {
        $this->expectException(DsvWriteException::class);
        Zeit::encode(Zeit::MAX_HUNDREDTHS + 1);
    }

    public function testUhrzeit(): void
    {
        self::assertSame(570, Uhrzeit::decode('09:30'));
        self::assertSame('09:30', Uhrzeit::encode(570));
        self::assertSame('23:59', Uhrzeit::encode(Uhrzeit::MAX_MINUTE));
        self::assertNull(Uhrzeit::decode('24:00'));
        self::assertNull(Uhrzeit::decode('12:60'));
        self::assertNull(Uhrzeit::decode('9:30'));
    }

    public function testUhrzeitEncodeRejectsOutOfRange(): void
    {
        $this->expectException(DsvWriteException::class);
        Uhrzeit::encode(1440);
    }
}
