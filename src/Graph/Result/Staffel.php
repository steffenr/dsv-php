<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * A relay team result in one competition.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Staffel
{
    public function __construct(
        public ?int $veranstaltungsId,
        public ?int $wettkampfnr,
        public string $wettkampfart,
        public string $nummerDerMannschaft,
        public string $verein,
        public ?int $vereinskennzahl,
        /**
         * Hundredths; null if invalid.
         */
        public ?int $endzeit,
        public ?int $startnummerDisqualifiziert,
        /**
         * @var list<Platzierung>
         */
        public array $platzierungen,
        /**
         * @var list<StaffelPerson>
         */
        public array $personen,
        /**
         * @var list<StaffelZwischenzeit>
         */
        public array $zwischenzeiten,
        /**
         * @var list<Abloese>
         */
        public array $abloesen,
        public int $line,
    ) {}
}
