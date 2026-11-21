<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Lexer;

use SteffenR\Dsv\Document\Blank;
use SteffenR\Dsv\Document\Comment;
use SteffenR\Dsv\Document\Element;
use SteffenR\Dsv\Document\Item;
use SteffenR\Dsv\Support\Text;

/** Splits text into lines and lines into {@see Item}s. */
final class Lexer
{
    private function __construct() {}

    /**
     * Splits text (BOM already removed) into lines. Handles CRLF, LF and a missing
     * final line ending.
     *
     * @return list<array{content: string, eol: string, number: int}>
     */
    public static function splitLines(string $text): array
    {
        $lines = [];
        $index = 0;
        $number = 1;
        $length = \strlen($text);

        while ($index < $length) {
            $next = strpos($text, "\n", $index);

            if ($next === false) {
                $lines[] = ['content' => substr($text, $index), 'eol' => '', 'number' => $number];
                break;
            }

            $hasCr = $next > $index && $text[$next - 1] === "\r";
            $lines[] = [
                'content' => substr($text, $index, ($hasCr ? $next - 1 : $next) - $index),
                'eol' => $hasCr ? "\r\n" : "\n",
                'number' => $number,
            ];

            $index = $next + 1;
            ++$number;
        }

        return $lines;
    }

    /** Lexes a single line (without line ending). */
    public static function lexLine(string $raw, int $line, string $eol): Item
    {
        $trimmed = Text::trim($raw);

        if ($trimmed === '') {
            return new Blank($raw, $line, $eol);
        }
        if (str_starts_with($trimmed, '(*')) {
            return new Comment($raw, $line, $eol);
        }

        $colon = strpos($raw, ':');

        if ($colon === false) {
            return new Element($trimmed, [], [], null, true, true, $line, $raw, $eol);
        }

        $name = Text::trim(substr($raw, 0, $colon));
        [$body, $comment] = self::splitTrailingComment(substr($raw, $colon + 1));

        // Attributes are terminated by ";", not separated: the empty trailing
        // piece after the last ";" is not a field and is dropped exactly once.
        // A missing final ";" leaves a real last field, which is read as-is.
        $parts = explode(';', $body);
        $terminated = Text::trim($body) === '' || str_ends_with(Text::rtrim($body), ';');
        if (\count($parts) > 1 && end($parts) === '') {
            array_pop($parts);
        }

        return new Element(
            $name,
            array_map(Text::trim(...), $parts),
            $parts,
            $comment,
            false,
            $terminated,
            $line,
            $raw,
            $eol,
        );
    }

    /**
     * Separates a comment at the end of the line. Only a `(* … *)` after the last
     * semicolon is a comment; inside a field `(*` is ordinary text. Implemented
     * without regular expressions to stay linear on pathological input.
     *
     * @return array{0: string, 1: ?string}
     */
    private static function splitTrailingComment(string $rest): array
    {
        $end = \strlen(Text::rtrim($rest));
        if ($end < 4 || substr($rest, $end - 2, 2) !== '*)') {
            return [$rest, null];
        }

        // Latest "(*" starting at or before $end - 4, preceded only by whitespace and a ";".
        $length = \strlen($rest);
        $maxStart = $end - 4;
        $pos = strrpos($rest, '(*', $maxStart - $length);
        while ($pos !== false) {
            $before = $pos;
            while ($before > 0 && ctype_space($rest[$before - 1])) {
                --$before;
            }
            if ($before > 0 && $rest[$before - 1] === ';') {
                return [substr($rest, 0, $before), substr($rest, $before)];
            }
            if ($pos === 0) {
                break;
            }
            $pos = strrpos($rest, '(*', ($pos - 1) - $length);
        }

        return [$rest, null];
    }
}
