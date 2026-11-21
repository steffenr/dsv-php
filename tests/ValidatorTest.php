<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Diagnostic\Diagnostic;
use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Validate\Validator;

#[CoversClass(Validator::class)]
final class ValidatorTest extends TestCase
{
    use FixtureTrait;

    private const string HEAD = "FORMAT:Wettkampfdefinitionsliste;7;\r\nERZEUGER:t;1;t@example.org;\r\n"
        . "VERANSTALTUNG:Cup;Ort;25;AUTOMATISCH;\r\nVERANSTALTUNGSORT:Halle;;;Ort;GER;;;;\r\nAUSSCHREIBUNGIMNETZ:https://example.org;\r\nVERANSTALTER:V;\r\n"
        . "AUSRICHTER:A;N;;;;;;;a@example.org;\r\nMELDEADRESSE:N;;;;;;;a@example.org;\r\nMELDESCHLUSS:01.03.2026;18:00;\r\n"
        . "ABSCHNITT:1;15.03.2026;08:00;08:30;09:00;N;\r\nMELDEGELD:Meldegeldpauschale;10,00;;\r\n";

    /** @return list<Diagnostic> */
    private static function validate(string $body): array
    {
        return Dsv::parseList(self::HEAD . $body . "DATEIENDE\r\n", ListType::Wettkampfdefinitionsliste)->diagnostics;
    }

    /** @param list<Diagnostic> $diagnostics */
    private static function has(array $diagnostics, DiagnosticCode $code, ?Severity $severity = null): bool
    {
        foreach ($diagnostics as $d) {
            if ($d->code === $code && ($severity === null || $d->severity === $severity)) {
                return true;
            }
        }

        return false;
    }

    public function testValidFileHasNoFailures(): void
    {
        $d = self::validate("WETTKAMPF:1;V;1;;100;F;GL;W;SW;;;\r\nWERTUNG:1;V;1;JG;0;9999;;Offen;\r\n");

        self::assertSame([], array_filter($d, static fn(Diagnostic $x): bool => $x->severity->isFailure()));
    }

    public function testFieldCountMustBeExact(): void
    {
        $d = self::validate("WETTKAMPF:1;V;1;;100;F;GL;W;SW;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::UnexpectedFieldCount, Severity::Error));
    }

    public function testUnknownElement(): void
    {
        $d = self::validate("SIEGEREHRUNG:1;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::UnknownElement, Severity::Error));
    }

    public function testRequiredFieldMissing(): void
    {
        $d = self::validate("WETTKAMPF:;V;1;;100;F;GL;W;SW;;;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::MissingRequiredField));
    }

    public function testInvalidValueAndEnum(): void
    {
        $d = self::validate("WETTKAMPF:eins;V;1;;100;Q;GL;W;SW;;;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::InvalidValue, Severity::Error));
        self::assertTrue(self::has($d, DiagnosticCode::InvalidEnumValue, Severity::Error));
    }

    public function testCardinalityViolation(): void
    {
        $d = Dsv::parseList("FORMAT:Wettkampfdefinitionsliste;7;\r\nDATEIENDE\r\n", ListType::Wettkampfdefinitionsliste)->diagnostics;

        self::assertTrue(self::has($d, DiagnosticCode::CardinalityViolation, Severity::Error));
    }

    public function testBankverbindungAndLastschriftExcludeEachOther(): void
    {
        $d = self::validate("BANKVERBINDUNG:Bank;DE23870962140370002800;GENODEF1CH1;Inhaber;\r\nLASTSCHRIFT:J;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::MutuallyExclusiveElements, Severity::Error));
    }

    public function testQualifyingHeatWithoutReferenceWarns(): void
    {
        $d = self::validate("WETTKAMPF:2;F;1;;100;F;GL;W;SW;;;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::ConditionalFieldRequired, Severity::Warning));
    }

    public function testKickExerciseNeedsButterfly(): void
    {
        $d = self::validate("WETTKAMPF:3;V;1;;100;F;KB;W;SW;;;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::InvalidValue, Severity::Error));
    }

    public function testMixedCompetitionShouldBeSw(): void
    {
        $d = self::validate("WETTKAMPF:4;V;1;;100;F;GL;X;MS;;;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::InvalidValue, Severity::Warning));
    }

    public function testMeldegeldWkmeldegeldNeedsCompetition(): void
    {
        $d = self::validate("MELDEGELD:Wkmeldegeld;5,00;;\r\n");

        self::assertTrue(self::has($d, DiagnosticCode::ConditionalFieldRequired, Severity::Error));
    }

    public function testNotRankedMeansPlaceZero(): void
    {
        $r = Dsv::parseList(self::fixture('synth/ergebnis-nichtwertung.dsv7'), ListType::Wettkampfergebnisliste);

        self::assertTrue($r->ok, implode("\n", array_map(strval(...), $r->errors())));
    }

    public function testNumberTypeBounds(): void
    {
        $d = self::validate("WETTKAMPF:4294967296;V;1;;100;F;GL;W;SW;;;\r\n");
        self::assertTrue(self::has($d, DiagnosticCode::InvalidValue, Severity::Error));

        $ok = self::validate("WETTKAMPF:0001;V;1;;100;F;GL;W;SW;;;\r\n");
        self::assertFalse(self::has($ok, DiagnosticCode::InvalidValue, Severity::Error));
    }

    public function testDiagnosticStringRepresentation(): void
    {
        $d = new Diagnostic(DiagnosticCode::InvalidValue, Severity::Warning, 'msg', 7);

        self::assertSame('[warning] invalid-value (line 7): msg', (string) $d);
    }
}
