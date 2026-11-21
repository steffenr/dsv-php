<?php

// GENERATED from the DSV standard (DSV7/DSV8) schema. Do not edit by hand.

declare(strict_types=1);

return [
    'listenart' => 'Wettkampfdefinitionsliste',
    'elements' => [
        [
            'name' => 'FORMAT',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'listart',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Konstant Wettkampfdefinitionsliste.',
                    'specRef' => 'dsv8.md:361',
                ],
                [
                    'name' => 'version',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Versionsnummer des DSV-Standards.',
                    'specRef' => 'dsv8.md:363',
                ],
            ],
        ],
        [
            'name' => 'ERZEUGER',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'software',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name der erzeugenden Software.',
                    'specRef' => 'dsv8.md:387',
                ],
                [
                    'name' => 'version',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Versionskennung der Software.',
                    'specRef' => 'dsv8.md:389',
                ],
                [
                    'name' => 'kontakt',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse des Software-Herstellers.',
                    'specRef' => 'dsv8.md:391',
                ],
            ],
        ],
        [
            'name' => 'VERANSTALTUNG',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsbezeichnung',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name der Veranstaltung.',
                    'specRef' => 'dsv8.md:421',
                ],
                [
                    'name' => 'veranstaltungsort',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Ort der Veranstaltung.',
                    'specRef' => 'dsv8.md:424',
                ],
                [
                    'name' => 'bahnlaenge',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => '16',
                            'doc' => '16⅔ m',
                        ],
                        [
                            'value' => '20',
                            'doc' => '20 m',
                        ],
                        [
                            'value' => '25',
                            'doc' => '25 m',
                        ],
                        [
                            'value' => '33',
                            'doc' => '33⅓ m',
                        ],
                        [
                            'value' => '50',
                            'doc' => '50 m',
                        ],
                        [
                            'value' => 'FW',
                            'doc' => 'Freiwasser',
                        ],
                        [
                            'value' => 'X',
                            'doc' => 'sonstige Bahnlänge',
                        ],
                    ],
                    'doc' => 'Bahnlänge des Wettkampfbeckens.',
                    'specRef' => 'dsv8.md:427',
                ],
                [
                    'name' => 'zeitmessung',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'HANDZEIT',
                            'doc' => 'Handzeit',
                        ],
                        [
                            'value' => 'AUTOMATISCH',
                            'doc' => 'automatische Zeitmessung',
                        ],
                        [
                            'value' => 'HALBAUTOMATISCH',
                            'doc' => 'halbautomatische Zeitmessung',
                        ],
                    ],
                    'doc' => 'Art der Zeitmessung.',
                    'specRef' => 'dsv8.md:435',
                ],
            ],
        ],
        [
            'name' => 'VERANSTALTUNGSORT',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nameSchwimmhalle',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name der Schwimmhalle.',
                    'specRef' => 'dsv8.md:474',
                ],
                [
                    'name' => 'strasse',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Strasse.',
                    'specRef' => 'dsv8.md:477',
                ],
                [
                    'name' => 'plz',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Postleitzahl.',
                    'specRef' => 'dsv8.md:479',
                ],
                [
                    'name' => 'ort',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Ort.',
                    'specRef' => 'dsv8.md:481',
                ],
                [
                    'name' => 'land',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:483',
                ],
                [
                    'name' => 'telefon',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Telefonnummer.',
                    'specRef' => 'dsv8.md:485',
                ],
                [
                    'name' => 'fax',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Faxnummer.',
                    'specRef' => 'dsv8.md:487',
                ],
                [
                    'name' => 'email',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'E-Mail-Adresse.',
                    'specRef' => 'dsv8.md:489',
                ],
            ],
        ],
        [
            'name' => 'AUSSCHREIBUNGIMNETZ',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'internetadresse',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Internetadresse der Ausschreibung.',
                    'specRef' => 'dsv8.md:555',
                ],
            ],
        ],
        [
            'name' => 'VERANSTALTER',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nameDesVeranstalters',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name des Veranstalters.',
                    'specRef' => 'dsv8.md:572',
                ],
            ],
        ],
        [
            'name' => 'AUSRICHTER',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nameDesAusrichters',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name des Ausrichters.',
                    'specRef' => 'dsv8.md:591',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname der Ansprechperson.',
                    'specRef' => 'dsv8.md:594',
                ],
                [
                    'name' => 'strasse',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Strasse.',
                    'specRef' => 'dsv8.md:596',
                ],
                [
                    'name' => 'plz',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Postleitzahl.',
                    'specRef' => 'dsv8.md:598',
                ],
                [
                    'name' => 'ort',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Ort.',
                    'specRef' => 'dsv8.md:600',
                ],
                [
                    'name' => 'land',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:602',
                ],
                [
                    'name' => 'telefon',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Telefonnummer.',
                    'specRef' => 'dsv8.md:604',
                ],
                [
                    'name' => 'fax',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Faxnummer.',
                    'specRef' => 'dsv8.md:606',
                ],
                [
                    'name' => 'email',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse.',
                    'specRef' => 'dsv8.md:608',
                ],
            ],
        ],
        [
            'name' => 'MELDEADRESSE',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name der Meldeadresse.',
                    'specRef' => 'dsv8.md:674',
                ],
                [
                    'name' => 'strasse',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Strasse.',
                    'specRef' => 'dsv8.md:676',
                ],
                [
                    'name' => 'plz',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Postleitzahl.',
                    'specRef' => 'dsv8.md:678',
                ],
                [
                    'name' => 'ort',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Ort.',
                    'specRef' => 'dsv8.md:680',
                ],
                [
                    'name' => 'land',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:682',
                ],
                [
                    'name' => 'telefon',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Telefonnummer.',
                    'specRef' => 'dsv8.md:684',
                ],
                [
                    'name' => 'fax',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Faxnummer.',
                    'specRef' => 'dsv8.md:686',
                ],
                [
                    'name' => 'email',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse.',
                    'specRef' => 'dsv8.md:688',
                ],
            ],
        ],
        [
            'name' => 'MELDESCHLUSS',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'datum',
                    'type' => 'Datum',
                    'required' => true,
                    'doc' => 'Datum des Meldeschlusses.',
                    'specRef' => 'dsv8.md:748',
                ],
                [
                    'name' => 'uhrzeit',
                    'type' => 'Uhrzeit',
                    'required' => true,
                    'doc' => 'Uhrzeit des Meldeschlusses.',
                    'specRef' => 'dsv8.md:750',
                ],
            ],
        ],
        [
            'name' => 'BANKVERBINDUNG',
            'min' => 0,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nameDerBank',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Name der Bank.',
                    'specRef' => 'dsv8.md:778',
                ],
                [
                    'name' => 'iban',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'IBAN für die Überweisung der Meldegelder.',
                    'specRef' => 'dsv8.md:781',
                ],
                [
                    'name' => 'bic',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'BIC.',
                    'specRef' => 'dsv8.md:783',
                ],
                [
                    'name' => 'kontoinhaber',
                    'type' => 'ZK',
                    'required' => true,
                    'since' => 8,
                    'doc' => 'Name des Kontoinhabers.',
                    'specRef' => 'dsv8.md:791',
                ],
            ],
        ],
        [
            'name' => 'LASTSCHRIFT',
            'min' => 0,
            'max' => 1,
            'bare' => false,
            'since' => 8,
            'fields' => [
                [
                    'name' => 'hinweis',
                    'type' => 'Zeichen',
                    'required' => false,
                    'default' => 'N',
                    'values' => [
                        [
                            'value' => 'J',
                            'doc' => 'ja',
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'nein',
                        ],
                    ],
                    'doc' => 'Veranstaltung arbeitet ausschliesslich mit Lastschriftverfahren.',
                    'specRef' => 'dsv8.md:813',
                ],
            ],
        ],
        [
            'name' => 'BESONDERES',
            'min' => 0,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'anmerkungen',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Besondere Anmerkungen zur Veranstaltung.',
                    'specRef' => 'dsv8.md:843',
                ],
            ],
        ],
        [
            'name' => 'NACHWEIS',
            'min' => 0,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nachweisVon',
                    'type' => 'Datum',
                    'required' => true,
                    'doc' => 'Ab wann Zeiten für den Pflichtzeitennachweis gelten.',
                    'specRef' => 'dsv8.md:861',
                ],
                [
                    'name' => 'nachweisBis',
                    'type' => 'Datum',
                    'required' => false,
                    'doc' => 'Bis wann Zeiten für den Pflichtzeitennachweis gelten.',
                    'specRef' => 'dsv8.md:863',
                ],
                [
                    'name' => 'bahnlaenge',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => '25',
                            'doc' => 'nur 25-m-Bahn',
                        ],
                        [
                            'value' => '50',
                            'doc' => 'nur 50-m-Bahn',
                        ],
                        [
                            'value' => 'FW',
                            'doc' => 'Freiwasser',
                        ],
                        [
                            'value' => 'AL',
                            'doc' => 'alle Bahnlängen',
                        ],
                    ],
                    'doc' => 'Auf welcher Bahnlänge Zeiten berücksichtigt werden.',
                    'specRef' => 'dsv8.md:865',
                ],
            ],
        ],
        [
            'name' => 'ABSCHNITT',
            'min' => 1,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'abschnittsnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        99,
                    ],
                    'doc' => 'Nummer des Abschnitts, maximal zweistellig.',
                    'specRef' => 'dsv8.md:904',
                ],
                [
                    'name' => 'abschnittsdatum',
                    'type' => 'Datum',
                    'required' => true,
                    'doc' => 'Datum, an dem der Abschnitt stattfindet.',
                    'specRef' => 'dsv8.md:918',
                ],
                [
                    'name' => 'einlass',
                    'type' => 'Uhrzeit',
                    'required' => false,
                    'doc' => 'Uhrzeit des Einlasses.',
                    'specRef' => 'dsv8.md:922',
                ],
                [
                    'name' => 'kampfrichtersitzung',
                    'type' => 'Uhrzeit',
                    'required' => false,
                    'doc' => 'Uhrzeit der Kampfrichtersitzung.',
                    'specRef' => 'dsv8.md:926',
                ],
                [
                    'name' => 'anfangszeit',
                    'type' => 'Uhrzeit',
                    'required' => true,
                    'doc' => 'Uhrzeit, zu der der Abschnitt beginnt.',
                    'specRef' => 'dsv8.md:928',
                ],
                [
                    'name' => 'relativeAngabe',
                    'type' => 'Zeichen',
                    'required' => false,
                    'default' => 'N',
                    'values' => [
                        [
                            'value' => 'J',
                            'doc' => 'relative Angabe',
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'echte Uhrzeit',
                        ],
                    ],
                    'doc' => 'J, wenn die Zeiten relativ zum Ende des Vorabschnitts zu verstehen sind.',
                    'specRef' => 'dsv8.md:932',
                ],
            ],
        ],
        [
            'name' => 'WETTKAMPF',
            'min' => 1,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes, maximal dreistellig.',
                    'specRef' => 'dsv8.md:979',
                ],
                [
                    'name' => 'wettkampfart',
                    'type' => 'Zeichen',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'V',
                            'doc' => 'Vorlauf',
                        ],
                        [
                            'value' => 'Z',
                            'doc' => 'Zwischenlauf',
                        ],
                        [
                            'value' => 'F',
                            'doc' => 'Finale',
                        ],
                        [
                            'value' => 'E',
                            'doc' => 'Entscheidung',
                        ],
                        [
                            'value' => 'A',
                            'doc' => 'Ausschwimmen',
                            'specGap' => true,
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                            'specGap' => true,
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:981',
                ],
                [
                    'name' => 'abschnittsnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        99,
                    ],
                    'doc' => 'Nummer des Abschnitts, in dem der Wettkampf stattfindet, maximal zweistellig.',
                    'specRef' => 'dsv8.md:997',
                ],
                [
                    'name' => 'anzahlStarter',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Anzahl der Starter; bei Staffeln die Zahl der Teilnehmenden.',
                    'specRef' => 'dsv8.md:999',
                ],
                [
                    'name' => 'einzelstrecke',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        25000,
                    ],
                    'doc' => 'Strecke in Metern, 1 bis 25000, oder 0 für sonstige.',
                    'specRef' => 'dsv8.md:1001',
                ],
                [
                    'name' => 'technik',
                    'type' => 'Zeichen',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'F',
                            'doc' => 'Freistil',
                        ],
                        [
                            'value' => 'R',
                            'doc' => 'Rücken',
                        ],
                        [
                            'value' => 'B',
                            'doc' => 'Brust',
                        ],
                        [
                            'value' => 'S',
                            'doc' => 'Schmetterling',
                        ],
                        [
                            'value' => 'L',
                            'doc' => 'Lagen',
                        ],
                        [
                            'value' => 'X',
                            'doc' => 'beliebige Sonderform',
                        ],
                    ],
                    'doc' => 'Schwimmart.',
                    'specRef' => 'dsv8.md:1029',
                ],
                [
                    'name' => 'ausuebung',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'GL',
                            'doc' => 'ganze Lage',
                        ],
                        [
                            'value' => 'BE',
                            'doc' => 'Beine',
                        ],
                        [
                            'value' => 'AR',
                            'doc' => 'Arme',
                        ],
                        [
                            'value' => 'ST',
                            'doc' => 'Start',
                        ],
                        [
                            'value' => 'WE',
                            'doc' => 'Wende',
                        ],
                        [
                            'value' => 'GB',
                            'doc' => 'Gleitübung',
                        ],
                        [
                            'value' => 'KB',
                            'doc' => 'Kicks Bauchlage, nur bei Technik S',
                            'since' => 8,
                        ],
                        [
                            'value' => 'KR',
                            'doc' => 'Kicks Rückenlage, nur bei Technik S',
                            'since' => 8,
                        ],
                        [
                            'value' => 'X',
                            'doc' => 'beliebige Sonderform',
                        ],
                    ],
                    'doc' => 'Art der Ausübung.',
                    'specRef' => 'dsv8.md:1037',
                ],
                [
                    'name' => 'geschlecht',
                    'type' => 'Zeichen',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'M',
                            'doc' => 'männlich',
                        ],
                        [
                            'value' => 'W',
                            'doc' => 'weiblich',
                        ],
                        [
                            'value' => 'D',
                            'doc' => 'divers',
                            'since' => 8,
                        ],
                        [
                            'value' => 'X',
                            'doc' => 'gemischte Wettkämpfe',
                        ],
                    ],
                    'doc' => 'Geschlecht der Teilnehmenden.',
                    'specRef' => 'dsv8.md:1062',
                ],
                [
                    'name' => 'zuordnungBestenliste',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'SW',
                            'doc' => 'Schwimmen für Jugend und offene Klasse',
                        ],
                        [
                            'value' => 'EW',
                            'doc' => 'vereinfachter Wettkampf',
                        ],
                        [
                            'value' => 'PA',
                            'doc' => 'Wettkämpfe für Para-Schwimmer',
                        ],
                        [
                            'value' => 'MS',
                            'doc' => 'Schwimmen der Masters',
                        ],
                        [
                            'value' => 'KG',
                            'doc' => 'reiner kindgerechter Wettkampf',
                        ],
                        [
                            'value' => 'XX',
                            'doc' => 'Andere',
                        ],
                    ],
                    'doc' => 'Zuordnung für Bestenlistenauswertungen.',
                    'specRef' => 'dsv8.md:1075',
                ],
                [
                    'name' => 'qualifikationswettkampfnr',
                    'type' => 'Zahl',
                    'required' => false,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des qualifizierenden Vor- oder Zwischenlaufs.',
                    'specRef' => 'dsv8.md:1081',
                ],
                [
                    'name' => 'qualifikationswettkampfart',
                    'type' => 'Zeichen',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'V',
                            'doc' => 'Vorlauf',
                        ],
                        [
                            'value' => 'Z',
                            'doc' => 'Zwischenlauf',
                        ],
                        [
                            'value' => 'F',
                            'doc' => 'Finale',
                        ],
                        [
                            'value' => 'E',
                            'doc' => 'Entscheidung',
                        ],
                    ],
                    'doc' => 'Art des qualifizierenden Wettkampfes.',
                    'specRef' => 'dsv8.md:1119',
                ],
            ],
        ],
        [
            'name' => 'WERTUNG',
            'min' => 1,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes, maximal dreistellig.',
                    'specRef' => 'dsv8.md:1151',
                ],
                [
                    'name' => 'wettkampfart',
                    'type' => 'Zeichen',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'V',
                            'doc' => 'Vorlauf',
                        ],
                        [
                            'value' => 'Z',
                            'doc' => 'Zwischenlauf',
                        ],
                        [
                            'value' => 'F',
                            'doc' => 'Finale',
                        ],
                        [
                            'value' => 'E',
                            'doc' => 'Entscheidung',
                        ],
                        [
                            'value' => 'A',
                            'doc' => 'Ausschwimmen',
                            'specGap' => true,
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                            'specGap' => true,
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:1153',
                ],
                [
                    'name' => 'wertungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Veranstaltungsweit eindeutige Kennung der Wertung.',
                    'specRef' => 'dsv8.md:1161',
                ],
                [
                    'name' => 'wertungsklasseTyp',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'JG',
                            'doc' => 'Jahrgang',
                        ],
                        [
                            'value' => 'AK',
                            'doc' => 'Altersklasse',
                        ],
                    ],
                    'doc' => 'Art der Wertungsklasse.',
                    'specRef' => 'dsv8.md:1165',
                ],
                [
                    'name' => 'mindestJgAk',
                    'type' => 'JGAK',
                    'required' => true,
                    'doc' => 'Kleinster Jahrgang oder grösste Altersklasse; 0 für die offene Klasse.',
                    'specRef' => 'dsv8.md:1171',
                ],
                [
                    'name' => 'maximalJgAk',
                    'type' => 'JGAK',
                    'required' => false,
                    'doc' => 'Grösster Jahrgang oder kleinste Altersklasse; ohne Angabe gilt der Wert von mindestJgAk.',
                    'specRef' => 'dsv8.md:1174',
                ],
                [
                    'name' => 'geschlecht',
                    'type' => 'Zeichen',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'M',
                            'doc' => 'männlich',
                        ],
                        [
                            'value' => 'W',
                            'doc' => 'weiblich',
                        ],
                        [
                            'value' => 'X',
                            'doc' => 'mixed',
                        ],
                        [
                            'value' => 'D',
                            'doc' => 'divers',
                        ],
                    ],
                    'doc' => 'Geschlecht der Wertung; ohne Angabe gilt das Geschlecht des Wettkampfes.',
                    'specRef' => 'dsv8.md:1220',
                ],
                [
                    'name' => 'wertungsname',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Textliche Bezeichnung der Wertung.',
                    'specRef' => 'dsv8.md:1236',
                ],
            ],
        ],
        [
            'name' => 'PFLICHTZEIT',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes, maximal dreistellig.',
                    'specRef' => 'dsv8.md:1258',
                ],
                [
                    'name' => 'wettkampfart',
                    'type' => 'Zeichen',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'V',
                            'doc' => 'Vorlauf',
                        ],
                        [
                            'value' => 'Z',
                            'doc' => 'Zwischenlauf',
                        ],
                        [
                            'value' => 'F',
                            'doc' => 'Finale',
                        ],
                        [
                            'value' => 'E',
                            'doc' => 'Entscheidung',
                        ],
                        [
                            'value' => 'A',
                            'doc' => 'Ausschwimmen',
                            'specGap' => true,
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                            'specGap' => true,
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:1260',
                ],
                [
                    'name' => 'wertungsklasseTyp',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'JG',
                            'doc' => 'Jahrgang',
                        ],
                        [
                            'value' => 'AK',
                            'doc' => 'Altersklasse',
                        ],
                    ],
                    'doc' => 'Art der Wertungsklasse.',
                    'specRef' => 'dsv8.md:1281',
                ],
                [
                    'name' => 'mindestJgAk',
                    'type' => 'JGAK',
                    'required' => true,
                    'doc' => 'Kleinster Jahrgang oder grösste Altersklasse; 0 für die offene Klasse.',
                    'specRef' => 'dsv8.md:1287',
                ],
                [
                    'name' => 'maximalJgAk',
                    'type' => 'JGAK',
                    'required' => false,
                    'doc' => 'Grösster Jahrgang oder kleinste Altersklasse; ohne Angabe gilt der Wert von mindestJgAk.',
                    'specRef' => 'dsv8.md:1290',
                ],
                [
                    'name' => 'pflichtzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Zu erfüllende Pflichtzeit.',
                    'specRef' => 'dsv8.md:1321',
                ],
                [
                    'name' => 'geschlecht',
                    'type' => 'Zeichen',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'M',
                            'doc' => 'männlich',
                        ],
                        [
                            'value' => 'W',
                            'doc' => 'weiblich',
                        ],
                        [
                            'value' => 'D',
                            'doc' => 'divers',
                        ],
                    ],
                    'doc' => 'Geschlecht, für das die Pflichtzeit gilt.',
                    'specRef' => 'dsv8.md:1323',
                ],
            ],
        ],
        [
            'name' => 'MELDEGELD',
            'min' => 1,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'meldegeldTyp',
                    'type' => 'ZK',
                    'required' => true,
                    'caseInsensitive' => true,
                    'values' => [
                        [
                            'value' => 'Meldegeldpauschale',
                            'doc' => 'pauschaler Betrag; in DSV7 je Meldung, ab DSV8 je Verein',
                        ],
                        [
                            'value' => 'Einzelmeldegeld',
                            'doc' => 'je Einzelwettkampf',
                        ],
                        [
                            'value' => 'Staffelmeldegeld',
                            'doc' => 'je Staffelwettkampf',
                        ],
                        [
                            'value' => 'Wkmeldegeld',
                            'doc' => 'je einzelnem Wettkampf, hat Vorrang',
                        ],
                        [
                            'value' => 'Mannschaftmeldegeld',
                            'doc' => 'für Mannschaftswettkämpfe',
                        ],
                        [
                            'value' => 'Teilnehmermeldegeld',
                            'doc' => 'pauschal je Teilnehmer mit Einzelmeldung',
                            'since' => 8,
                        ],
                        [
                            'value' => 'Abschnittspauschale',
                            'doc' => 'pauschal je Verein und gemeldetem Abschnitt',
                            'since' => 8,
                        ],
                    ],
                    'doc' => 'Art des Meldegeldes.',
                    'specRef' => 'dsv8.md:1360',
                ],
                [
                    'name' => 'betrag',
                    'type' => 'Betrag',
                    'required' => true,
                    'doc' => 'Betrag des Meldegeldes.',
                    'specRef' => 'dsv8.md:1382',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => false,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes, maximal dreistellig; Pflicht bei Typ Wkmeldegeld.',
                    'specRef' => 'dsv8.md:1389',
                ],
            ],
        ],
        [
            'name' => 'DATEIENDE',
            'min' => 1,
            'max' => 1,
            'bare' => true,
            'fields' => [],
        ],
    ],
];
