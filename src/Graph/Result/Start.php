<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * The start of one person in one competition.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Start
{
    public function __construct(
        public ?int $veranstaltungsId,
        public ?int $wettkampfnr,
        public string $wettkampfart,
        public string $name,
        public string $dsvId,
        public string $geschlecht,
        public string $jahrgang,
        public string $altersklasse,
        public string $verein,
        public ?int $vereinskennzahl,
        /**
         * @var list<string>
         */
        public array $nationalitaeten,
        /**
         * Hundredths; null if invalid.
         */
        public ?int $endzeit,
        /**
         * @var list<Platzierung>
         */
        public array $platzierungen,
        /**
         * @var list<Zwischenzeit>
         */
        public array $zwischenzeiten,
        /**
         * @var list<Reaktion>
         */
        public array $reaktionen,
        public int $line,
    ) {}
}
