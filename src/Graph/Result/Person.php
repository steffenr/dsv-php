<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * A swimmer across all their starts.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Person
{
    public function __construct(
        public ?int $veranstaltungsId,
        public string $name,
        public string $dsvId,
        public string $geschlecht,
        public string $jahrgang,
        public string $verein,
        public ?int $vereinskennzahl,
        /**
         * @var list<Start>
         */
        public array $starts,
    ) {}
}
