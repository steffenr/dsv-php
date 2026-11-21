<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Write;

use SteffenR\Dsv\Format\FormatVersion;

/** Options for writing a list. */
final readonly class WriteOptions
{
    public function __construct(
        /** Line ending; CRLF by default, as used by the common DSV software. */
        public string $eol = "\r\n",
        /** Format version to write; defaults to the one in the FORMAT record. */
        public ?FormatVersion $version = null,
    ) {}
}
