<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Definition;

/**
 * Object graph of a Wettkampfdefinitionsliste.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Wettkampfdefinition
{
    public function __construct(
        public \SteffenR\Dsv\Graph\Veranstaltung $veranstaltung,
        /**
         * @var list<Abschnitt>
         */
        public array $abschnitte,
        /**
         * Competitions whose `abschnittsnr` matches no section.
         * @var list<Wettkampf>
         */
        public array $wettkaempfeOhneAbschnitt,
        /**
         * Key is `nummer:art`.
         * @var array<string, Wettkampf>
         */
        public array $wettkampfByKey,
        /**
         * @var array<int, \SteffenR\Dsv\Graph\Wertung>
         */
        public array $wertungById,
        /**
         * @var array<int, Abschnitt>
         */
        public array $abschnittByNummer,
    ) {}
}
