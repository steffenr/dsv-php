<?php

declare(strict_types=1);

/**
 * Validates an untrusted upload and reacts to findings by their stable code.
 *
 *   php examples/02-validate-upload.php [file]
 */

use SteffenR\Dsv\Diagnostic\DiagnosticCode;
use SteffenR\Dsv\Diagnostic\Severity;
use SteffenR\Dsv\Dsv;

require __DIR__ . '/../vendor/autoload.php';

$path = $argv[1] ?? __DIR__ . '/data/meeting.dsv7';

// Never rely on the file name: the list type comes from the FORMAT element.
$result = Dsv::parseAny(file_get_contents($path) ?: '');

foreach ($result->diagnostics as $d) {
    // Switch on `code` and `data`; the human-readable message is not a stable API.
    $hint = match ($d->code) {
        DiagnosticCode::UnsupportedFormatVersion => 'Please export the file as DSV7 or DSV8.',
        DiagnosticCode::MissingRequiredField => 'Field ' . (is_string($d->data['field'] ?? null) ? $d->data['field'] : '?') . ' is empty.',
        DiagnosticCode::UnterminatedFieldList => 'Harmless: the exporting software omitted the final ";".',
        default => $d->message,
    };
    printf("line %-4d %-8s %s\n", $d->line, $d->severity->value, $hint);
}

$warnings = count(array_filter($result->diagnostics, static fn($d): bool => $d->severity === Severity::Warning));

echo $result->ok ? "Accepted" : "Rejected", " ({$warnings} warning(s)); list type: {$result->document->listType}, DSV{$result->document->version->value}\n";

exit($result->ok ? 0 : 1);
