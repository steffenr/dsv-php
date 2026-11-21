<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * A competition with its ranking groups, starts and relays.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Wettkampf
{
    public function __construct(
        public ?int $nummer,
        public string $art,
        public ?int $abschnittsnr,
        public string $anzahlStarter,
        public ?int $einzelstrecke,
        public string $technik,
        public string $ausuebung,
        public string $geschlecht,
        public string $zuordnungBestenliste,
        /**
         * @var list<\SteffenR\Dsv\Graph\Wertung>
         */
        public array $wertungen,
        /**
         * @var list<Start>
         */
        public array $starts,
        /**
         * @var list<Staffel>
         */
        public array $staffeln,
        /**
         * @var array{nummer: ?int, art: string}|null
         */
        public ?array $qualifikationAus,
        public int $line,
    ) {}
}
