<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * A member of a relay team.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class StaffelPerson
{
    public function __construct(
        public string $name,
        public string $dsvId,
        public ?int $startnummer,
        public string $geschlecht,
        public string $jahrgang,
        public string $altersklasse,
        /**
         * @var list<string>
         */
        public array $nationalitaeten,
        public int $line,
    ) {}
}
