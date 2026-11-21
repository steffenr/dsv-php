<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Document;

/** One line of a DSV file: an {@see Element}, a {@see Comment} or a {@see Blank}. */
interface Item
{
    /** The original line without its line ending; basis of byte-identical round trips. */
    public string $raw { get; }

    /** 1-based source line number. */
    public int $line { get; }

    /** The actual line ending: `"\r\n"`, `"\n"` or `''` for an unterminated last line. */
    public string $eol { get; }
}
