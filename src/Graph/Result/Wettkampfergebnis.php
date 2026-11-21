<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph\Result;

/**
 * Object graph of a Wettkampfergebnisliste.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Wettkampfergebnis
{
    public function __construct(
        public \SteffenR\Dsv\Graph\Veranstaltung $veranstaltung,
        /**
         * @var list<Abschnitt>
         */
        public array $abschnitte,
        /**
         * @var list<Verein>
         */
        public array $vereine,
        /**
         * @var list<Wettkampf>
         */
        public array $wettkaempfeOhneAbschnitt,
        /**
         * Key `nummer:art`.
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
        /**
         * @var array<int, Verein>
         */
        public array $vereinByKennzahl,
        /**
         * Key `veranstaltungsId:nummer:art`.
         * @var array<string, Start>
         */
        public array $startByKey,
        /**
         * Key `veranstaltungsId:nummer:art`.
         * @var array<string, Staffel>
         */
        public array $staffelByKey,
        /**
         * @var array<int, Person>
         */
        public array $personById,
    ) {}
}
