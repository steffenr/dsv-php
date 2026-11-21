<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Diagnostic;

/**
 * One finding about a DSV file.
 *
 * DSV is line oriented: every finding refers to a 1-based source line (line 1
 * for findings about the file as a whole). The {@see self::$message} is an
 * English description for humans and is explicitly not part of the stable API;
 * program logic must use {@see self::$code} and {@see self::$data}.
 */
final readonly class Diagnostic
{
    /**
     * @param array<string, mixed> $data Machine-readable details, e.g. `element`, `field`, `value`.
     */
    public function __construct(
        public DiagnosticCode $code,
        public Severity $severity,
        public string $message,
        public int $line = 1,
        public array $data = [],
    ) {}

    public function __toString(): string
    {
        return \sprintf('[%s] %s (line %d): %s', $this->severity->value, $this->code->value, $this->line, $this->message);
    }
}
