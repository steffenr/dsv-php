<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph;

use SteffenR\Dsv\Diagnostic\Diagnostic;

/**
 * An object graph together with the reference findings found while building it.
 *
 * @template T of object
 */
final readonly class ProjectionResult
{
    /**
     * @param T                $graph
     * @param list<Diagnostic> $diagnostics Dangling or ambiguous references.
     */
    public function __construct(
        public object $graph,
        public array $diagnostics,
    ) {}
}
