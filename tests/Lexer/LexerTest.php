<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Tests\Lexer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SteffenR\Dsv\Document\Blank;
use SteffenR\Dsv\Document\Comment;
use SteffenR\Dsv\Document\Element;
use SteffenR\Dsv\Lexer\Lexer;

#[CoversClass(Lexer::class)]
final class LexerTest extends TestCase
{
    public function testSplitsLinesWithMixedEndings(): void
    {
        $lines = Lexer::splitLines("A:1;\r\nB:2;\nC:3;");

        self::assertSame(
            [
                ['content' => 'A:1;', 'eol' => "\r\n", 'number' => 1],
                ['content' => 'B:2;', 'eol' => "\n", 'number' => 2],
                ['content' => 'C:3;', 'eol' => '', 'number' => 3],
            ],
            $lines,
        );
    }

    public function testEmptyInputHasNoLines(): void
    {
        self::assertSame([], Lexer::splitLines(''));
    }

    public function testBlankAndCommentLines(): void
    {
        self::assertInstanceOf(Blank::class, Lexer::lexLine('   ', 1, "\n"));
        self::assertInstanceOf(Blank::class, Lexer::lexLine('', 1, "\n"));
        self::assertInstanceOf(Comment::class, Lexer::lexLine('(* erzeugt mit EasyWk *)', 1, "\n"));
    }

    public function testElementFieldsAreTerminatedNotSeparated(): void
    {
        $e = Lexer::lexLine('VERANSTALTUNG: Cup ; Ort;25;AUTOMATISCH;', 3, "\r\n");

        self::assertInstanceOf(Element::class, $e);
        self::assertSame('VERANSTALTUNG', $e->name);
        self::assertSame(['Cup', 'Ort', '25', 'AUTOMATISCH'], $e->fields);
        self::assertSame([' Cup ', ' Ort', '25', 'AUTOMATISCH'], $e->rawFields);
        self::assertTrue($e->terminated);
        self::assertFalse($e->bare);
        self::assertSame(3, $e->line);
    }

    public function testMissingFinalSemicolonKeepsLastFieldAndIsMarked(): void
    {
        $e = Lexer::lexLine('A:1;2', 1, '');

        self::assertInstanceOf(Element::class, $e);
        self::assertSame(['1', '2'], $e->fields);
        self::assertFalse($e->terminated);
    }

    public function testBareElement(): void
    {
        $e = Lexer::lexLine('DATEIENDE', 9, '');

        self::assertInstanceOf(Element::class, $e);
        self::assertTrue($e->bare);
        self::assertTrue($e->terminated);
        self::assertSame([], $e->fields);
    }

    public function testTrailingCommentIsSeparatedOnlyAfterLastSemicolon(): void
    {
        $e = Lexer::lexLine('A:x;y; (* Hinweis *)', 1, '');

        self::assertInstanceOf(Element::class, $e);
        self::assertSame(['x', 'y'], $e->fields);
        self::assertSame(' (* Hinweis *)', $e->comment);
    }

    public function testCommentMarkerInsideFieldIsData(): void
    {
        $e = Lexer::lexLine('A:foo (* bar *);', 1, '');

        self::assertInstanceOf(Element::class, $e);
        self::assertNull($e->comment);
        self::assertSame(['foo (* bar *)'], $e->fields);
    }

    public function testManyOpeningCommentMarkersStayLinear(): void
    {
        $line = 'A:' . str_repeat(';(*', 150_000) . ' x*)';

        $start = microtime(true);
        $e = Lexer::lexLine($line, 1, '');
        $elapsed = microtime(true) - $start;

        self::assertInstanceOf(Element::class, $e);
        self::assertLessThan(2.0, $elapsed, 'comment detection must be linear');
    }

    public function testUnicodeWhitespaceIsTrimmed(): void
    {
        $e = Lexer::lexLine("A:\u{00A0}x\u{00A0};", 1, '');

        self::assertInstanceOf(Element::class, $e);
        self::assertSame(['x'], $e->fields);
    }
}
