<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Graph;

/**
 * Core data of the event.
 *
 * A plain data node: treat as read-only. Built by the projector, which fills the child lists.
 */
final class Veranstaltung
{
    public function __construct(
        /**
         * Event name.
         */
        public string $bezeichnung,
        /**
         * Venue town.
         */
        public string $ort,
        /**
         * Pool length value (e.g. `25`, `50`, `FW`).
         */
        public string $bahnlaenge,
        /**
         * Timekeeping: HANDZEIT, AUTOMATISCH or HALBAUTOMATISCH.
         */
        public string $zeitmessung,
    ) {}
}
