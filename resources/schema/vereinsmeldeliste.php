<?php

// GENERATED from the DSV standard (DSV7/DSV8) schema. Do not edit by hand.

declare(strict_types=1);

return [
    'listenart' => 'Vereinsmeldeliste',
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
                    'doc' => 'Konstant Vereinsmeldeliste.',
                    'specRef' => 'dsv8.md:1530',
                ],
                [
                    'name' => 'version',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Versionsnummer des DSV-Standards.',
                    'specRef' => 'dsv8.md:1532',
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
                    'specRef' => 'dsv8.md:1554',
                ],
                [
                    'name' => 'version',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Versionskennung der Software.',
                    'specRef' => 'dsv8.md:1556',
                ],
                [
                    'name' => 'kontakt',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse des Software-Herstellers.',
                    'specRef' => 'dsv8.md:1558',
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
                    'specRef' => 'dsv8.md:1588',
                ],
                [
                    'name' => 'veranstaltungsort',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Ort der Veranstaltung.',
                    'specRef' => 'dsv8.md:1591',
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
                    'specRef' => 'dsv8.md:1594',
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
                    'specRef' => 'dsv8.md:1602',
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
                    'doc' => 'Nummer des Abschnitts.',
                    'specRef' => 'dsv8.md:1641',
                ],
                [
                    'name' => 'abschnittsdatum',
                    'type' => 'Datum',
                    'required' => true,
                    'doc' => 'Datum des Abschnitts.',
                    'specRef' => 'dsv8.md:1645',
                ],
                [
                    'name' => 'anfangszeit',
                    'type' => 'Uhrzeit',
                    'required' => true,
                    'doc' => 'Beginn des Abschnitts.',
                    'specRef' => 'dsv8.md:1650',
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
                    'doc' => 'Ob die Zeitangabe relativ zum Abschnittsbeginn zu lesen ist.',
                    'specRef' => 'dsv8.md:1654',
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
                    'specRef' => 'dsv8.md:1693',
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
                    'specRef' => 'dsv8.md:1695',
                ],
                [
                    'name' => 'abschnittsnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        99,
                    ],
                    'doc' => 'Nummer des Abschnitts, in dem der Wettkampf stattfindet.',
                    'specRef' => 'dsv8.md:1697',
                ],
                [
                    'name' => 'anzahlStarter',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Anzahl der Staffelteilnehmer; für Einzeldisziplinen 1.',
                    'specRef' => 'dsv8.md:1699',
                ],
                [
                    'name' => 'einzelstrecke',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        25000,
                    ],
                    'doc' => 'Strecke in Metern; 0 steht für sonstige Strecken.',
                    'specRef' => 'dsv8.md:1701',
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
                    'specRef' => 'dsv8.md:1741',
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
                    'doc' => 'Ausübung der Schwimmart.',
                    'specRef' => 'dsv8.md:1749',
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
                    'doc' => 'Geschlecht der Startberechtigten.',
                    'specRef' => 'dsv8.md:1779',
                ],
                [
                    'name' => 'qualifikationswettkampfnr',
                    'type' => 'Zahl',
                    'required' => false,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des qualifizierenden Wettkampfes.',
                    'specRef' => 'dsv8.md:1793',
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
                    'specRef' => 'dsv8.md:1796',
                ],
            ],
        ],
        [
            'name' => 'VEREIN',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'vereinsbezeichnung',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name des Vereins.',
                    'specRef' => 'dsv8.md:1838',
                ],
                [
                    'name' => 'vereinskennzahl',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennzahl des Vereins.',
                    'specRef' => 'dsv8.md:1840',
                ],
                [
                    'name' => 'landesschwimmverband',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennzahl des Landesschwimmverbandes.',
                    'specRef' => 'dsv8.md:1846',
                ],
                [
                    'name' => 'nationenkuerzel',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:1857',
                ],
                [
                    'name' => 'lastschrift',
                    'type' => 'Zeichen',
                    'required' => false,
                    'since' => 8,
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
                    'doc' => 'Ob das Meldegeld per Lastschrift eingezogen werden darf.',
                    'specRef' => 'dsv8.md:1863',
                ],
            ],
        ],
        [
            'name' => 'ANSPRECHPARTNER',
            'min' => 1,
            'max' => 1,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname der Ansprechperson.',
                    'specRef' => 'dsv8.md:1903',
                ],
                [
                    'name' => 'strasse',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Strasse.',
                    'specRef' => 'dsv8.md:1905',
                ],
                [
                    'name' => 'plz',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Postleitzahl.',
                    'specRef' => 'dsv8.md:1907',
                ],
                [
                    'name' => 'ort',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Ort.',
                    'specRef' => 'dsv8.md:1909',
                ],
                [
                    'name' => 'land',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:1911',
                ],
                [
                    'name' => 'telefon',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Telefonnummer.',
                    'specRef' => 'dsv8.md:1913',
                ],
                [
                    'name' => 'fax',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Faxnummer.',
                    'specRef' => 'dsv8.md:1915',
                ],
                [
                    'name' => 'email',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse.',
                    'specRef' => 'dsv8.md:1917',
                ],
            ],
        ],
        [
            'name' => 'KARIMELDUNG',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nummerKampfrichter',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Laufende Nummer innerhalb dieser Meldung.',
                    'specRef' => 'dsv8.md:1977',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:1980',
                ],
                [
                    'name' => 'kampfrichtergruppe',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'WKR',
                            'doc' => 'Wettkampfrichter*in',
                        ],
                        [
                            'value' => 'AUS',
                            'doc' => 'Auswerter*in',
                        ],
                        [
                            'value' => 'SCH',
                            'doc' => 'Schiedsrichter*in',
                        ],
                        [
                            'value' => 'SPR',
                            'doc' => 'Sprecher*in',
                        ],
                    ],
                    'doc' => 'Gruppe, für die gemeldet wird.',
                    'specRef' => 'dsv8.md:1982',
                ],
                [
                    'name' => 'geschlecht',
                    'type' => 'Zeichen',
                    'required' => false,
                    'since' => 8,
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
                    'doc' => 'Geschlecht.',
                    'specRef' => 'dsv8.md:2018',
                ],
            ],
        ],
        [
            'name' => 'KARIABSCHNITT',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nummerKampfrichter',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Nummer der Kampfrichtermeldung, auf die sich der Abschnitt bezieht.',
                    'specRef' => 'dsv8.md:2055',
                ],
                [
                    'name' => 'abschnittsnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        99,
                    ],
                    'doc' => 'Nummer des Abschnitts.',
                    'specRef' => 'dsv8.md:2060',
                ],
                [
                    'name' => 'einsatzwunsch',
                    'type' => 'ZK',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'SCH',
                            'doc' => 'Schiedsrichter*in',
                        ],
                        [
                            'value' => 'STA',
                            'doc' => 'Starter*in',
                        ],
                        [
                            'value' => 'ZRO',
                            'doc' => 'Zielrichterobmann',
                        ],
                        [
                            'value' => 'ZR',
                            'doc' => 'Zielrichter*in',
                        ],
                        [
                            'value' => 'ZNO',
                            'doc' => 'Zeitnehmerobmann',
                        ],
                        [
                            'value' => 'ZN',
                            'doc' => 'Zeitnehmer*in',
                        ],
                        [
                            'value' => 'RZN',
                            'doc' => 'Reservezeitnehmer*in',
                        ],
                        [
                            'value' => 'SR',
                            'doc' => 'Schwimmrichter*in',
                        ],
                        [
                            'value' => 'WRO',
                            'doc' => 'Wenderichterobmann',
                        ],
                        [
                            'value' => 'WR',
                            'doc' => 'Wenderichter*in',
                        ],
                        [
                            'value' => 'AUS',
                            'doc' => 'Auswerter*in',
                        ],
                        [
                            'value' => 'SP',
                            'doc' => 'Sprecher*in',
                        ],
                        [
                            'value' => 'PKF',
                            'doc' => 'Protokollführer*in',
                        ],
                        [
                            'value' => 'STO',
                            'doc' => 'Startordner*in',
                        ],
                        [
                            'value' => 'ASCH',
                            'doc' => 'Assistenz-Schiedsrichter*in',
                        ],
                        [
                            'value' => 'SIB',
                            'doc' => 'Sicherheitsbeauftragte*r',
                        ],
                        [
                            'value' => 'SAUF',
                            'doc' => 'Streckenaufsicht',
                        ],
                        [
                            'value' => 'VER',
                            'doc' => 'Ordner Versorgungsstelle',
                        ],
                    ],
                    'doc' => 'Gewünschte Position im Kampfgericht.',
                    'specRef' => 'dsv8.md:2062',
                ],
            ],
        ],
        [
            'name' => 'TRAINER',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nummerTrainer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Laufende Nummer innerhalb dieser Meldung.',
                    'specRef' => 'dsv8.md:2127',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:2131',
                ],
                [
                    'name' => 'geschlecht',
                    'type' => 'Zeichen',
                    'required' => false,
                    'since' => 8,
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
                    'doc' => 'Geschlecht.',
                    'specRef' => 'dsv8.md:2145',
                ],
            ],
        ],
        [
            'name' => 'PNMELDUNG',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:2176',
                ],
                [
                    'name' => 'dsvId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'DSV-Identifikationsnummer.',
                    'specRef' => 'dsv8.md:2178',
                ],
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:2185',
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
                        ],
                    ],
                    'doc' => 'Geschlecht.',
                    'specRef' => 'dsv8.md:2206',
                ],
                [
                    'name' => 'jahrgang',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Geburtsjahrgang, vierstellig.',
                    'specRef' => 'dsv8.md:2221',
                ],
                [
                    'name' => 'altersklasse',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Altersklasse. Die Spezifikation liefert keine Beschreibung; aus der Spaltenreihenfolge rekonstruiert.',
                    'specRef' => 'dsv8.md:2223',
                ],
                [
                    'name' => 'nummerTrainer',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Nummer der zugeordneten Trainermeldung.',
                    'specRef' => 'dsv8.md:2225',
                ],
                [
                    'name' => 'nationalitaet1',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Erste Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:2233',
                ],
                [
                    'name' => 'nationalitaet2',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Zweite Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:2237',
                ],
                [
                    'name' => 'nationalitaet3',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Dritte Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:2241',
                ],
            ],
        ],
        [
            'name' => 'HANDICAP',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:2285',
                ],
                [
                    'name' => 'dbsId',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Identifikationsnummer beim Deutschen Behindertensportverband.',
                    'specRef' => 'dsv8.md:2290',
                ],
                [
                    'name' => 'ipcId',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Identifikationsnummer beim International Paralympic Committee.',
                    'specRef' => 'dsv8.md:2292',
                ],
                [
                    'name' => 'startklasse',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'AB',
                            'doc' => 'ohne Startklasse',
                        ],
                        [
                            'value' => 'S1',
                            'doc' => 'Startklasse S1',
                        ],
                        [
                            'value' => 'S2',
                            'doc' => 'Startklasse S2',
                        ],
                        [
                            'value' => 'S3',
                            'doc' => 'Startklasse S3',
                        ],
                        [
                            'value' => 'S4',
                            'doc' => 'Startklasse S4',
                        ],
                        [
                            'value' => 'S5',
                            'doc' => 'Startklasse S5',
                        ],
                        [
                            'value' => 'S6',
                            'doc' => 'Startklasse S6',
                        ],
                        [
                            'value' => 'S7',
                            'doc' => 'Startklasse S7',
                        ],
                        [
                            'value' => 'S8',
                            'doc' => 'Startklasse S8',
                        ],
                        [
                            'value' => 'S9',
                            'doc' => 'Startklasse S9',
                        ],
                        [
                            'value' => 'S10',
                            'doc' => 'Startklasse S10',
                        ],
                        [
                            'value' => 'S11',
                            'doc' => 'Startklasse S11',
                        ],
                        [
                            'value' => 'S12',
                            'doc' => 'Startklasse S12',
                        ],
                        [
                            'value' => 'S13',
                            'doc' => 'Startklasse S13',
                        ],
                        [
                            'value' => 'S14',
                            'doc' => 'Startklasse S14',
                        ],
                    ],
                    'doc' => 'Startklasse für Freistil, Rücken und Schmetterling.',
                    'specRef' => 'dsv8.md:2294',
                ],
                [
                    'name' => 'startklasseBrust',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'AB',
                            'doc' => 'ohne Startklasse',
                        ],
                        [
                            'value' => 'SB1',
                            'doc' => 'Startklasse SB1',
                        ],
                        [
                            'value' => 'SB2',
                            'doc' => 'Startklasse SB2',
                        ],
                        [
                            'value' => 'SB3',
                            'doc' => 'Startklasse SB3',
                        ],
                        [
                            'value' => 'SB4',
                            'doc' => 'Startklasse SB4',
                        ],
                        [
                            'value' => 'SB5',
                            'doc' => 'Startklasse SB5',
                        ],
                        [
                            'value' => 'SB6',
                            'doc' => 'Startklasse SB6',
                        ],
                        [
                            'value' => 'SB7',
                            'doc' => 'Startklasse SB7',
                        ],
                        [
                            'value' => 'SB8',
                            'doc' => 'Startklasse SB8',
                        ],
                        [
                            'value' => 'SB9',
                            'doc' => 'Startklasse SB9',
                        ],
                        [
                            'value' => 'SB10',
                            'doc' => 'Startklasse SB10',
                            'specGap' => true,
                        ],
                        [
                            'value' => 'SB11',
                            'doc' => 'Startklasse SB11',
                        ],
                        [
                            'value' => 'SB12',
                            'doc' => 'Startklasse SB12',
                        ],
                        [
                            'value' => 'SB13',
                            'doc' => 'Startklasse SB13',
                        ],
                        [
                            'value' => 'SB14',
                            'doc' => 'Startklasse SB14',
                        ],
                    ],
                    'doc' => 'Startklasse für Brust.',
                    'specRef' => 'dsv8.md:2302',
                ],
                [
                    'name' => 'startklasseLagen',
                    'type' => 'ZK',
                    'required' => true,
                    'values' => [
                        [
                            'value' => 'AB',
                            'doc' => 'ohne Startklasse',
                        ],
                        [
                            'value' => 'SM1',
                            'doc' => 'Startklasse SM1',
                        ],
                        [
                            'value' => 'SM2',
                            'doc' => 'Startklasse SM2',
                        ],
                        [
                            'value' => 'SM3',
                            'doc' => 'Startklasse SM3',
                        ],
                        [
                            'value' => 'SM4',
                            'doc' => 'Startklasse SM4',
                        ],
                        [
                            'value' => 'SM5',
                            'doc' => 'Startklasse SM5',
                        ],
                        [
                            'value' => 'SM6',
                            'doc' => 'Startklasse SM6',
                        ],
                        [
                            'value' => 'SM7',
                            'doc' => 'Startklasse SM7',
                        ],
                        [
                            'value' => 'SM8',
                            'doc' => 'Startklasse SM8',
                        ],
                        [
                            'value' => 'SM9',
                            'doc' => 'Startklasse SM9',
                        ],
                        [
                            'value' => 'SM10',
                            'doc' => 'Startklasse SM10',
                        ],
                        [
                            'value' => 'SM11',
                            'doc' => 'Startklasse SM11',
                        ],
                        [
                            'value' => 'SM12',
                            'doc' => 'Startklasse SM12',
                        ],
                        [
                            'value' => 'SM13',
                            'doc' => 'Startklasse SM13',
                        ],
                        [
                            'value' => 'SM14',
                            'doc' => 'Startklasse SM14',
                        ],
                    ],
                    'doc' => 'Startklasse für Lagen.',
                    'specRef' => 'dsv8.md:2306',
                ],
                [
                    'name' => 'exceptions',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Ausnahmeregelungen der Startklassifizierung.',
                    'specRef' => 'dsv8.md:2355',
                ],
            ],
        ],
        [
            'name' => 'STARTPN',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:2376',
                ],
                [
                    'name' => 'wettkampfnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:2381',
                ],
                [
                    'name' => 'meldezeit',
                    'type' => 'Zeit',
                    'required' => false,
                    'default' => '00:00:00,00',
                    'doc' => 'Gemeldete Zeit.',
                    'specRef' => 'dsv8.md:2383',
                ],
            ],
        ],
        [
            'name' => 'STMELDUNG',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nummerDerMannschaft',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Nummer der Mannschaft innerhalb des Vereins.',
                    'specRef' => 'dsv8.md:2410',
                ],
                [
                    'name' => 'veranstaltungsIdStaffel',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Staffel innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:2413',
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
                    'specRef' => 'dsv8.md:2420',
                ],
                [
                    'name' => 'mindestJgAk',
                    'type' => 'JGAK',
                    'required' => true,
                    'doc' => 'Untere Grenze des Jahrgangs oder der Altersklasse.',
                    'specRef' => 'dsv8.md:2426',
                ],
                [
                    'name' => 'maximalJgAk',
                    'type' => 'JGAK',
                    'required' => false,
                    'doc' => 'Obere Grenze; ohne Angabe gilt der Wert von mindestJgAk.',
                    'specRef' => 'dsv8.md:2458',
                ],
                [
                    'name' => 'nameDerStaffel',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Bezeichnung der Staffel.',
                    'specRef' => 'dsv8.md:2470',
                ],
            ],
        ],
        [
            'name' => 'STARTST',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsIdStaffel',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Staffel innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:2494',
                ],
                [
                    'name' => 'wettkampfnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:2499',
                ],
                [
                    'name' => 'meldezeit',
                    'type' => 'Zeit',
                    'required' => false,
                    'default' => '00:00:00,00',
                    'doc' => 'Gemeldete Zeit.',
                    'specRef' => 'dsv8.md:2501',
                ],
            ],
        ],
        [
            'name' => 'STAFFELPERSON',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsIdStaffel',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Staffel innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:2533',
                ],
                [
                    'name' => 'wettkampfnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:2538',
                ],
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:2544',
                ],
                [
                    'name' => 'startnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Startnummer innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:2547',
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
