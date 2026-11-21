<?php

// GENERATED from the DSV standard (DSV7/DSV8) schema. Do not edit by hand.

declare(strict_types=1);

return [
    'listenart' => 'Vereinsergebnisliste',
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
                    'doc' => 'Konstant Vereinsergebnisliste.',
                    'specRef' => 'dsv8.md:2680',
                ],
                [
                    'name' => 'version',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Versionsnummer des DSV-Standards.',
                    'specRef' => 'dsv8.md:2682',
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
                    'specRef' => 'dsv8.md:2704',
                ],
                [
                    'name' => 'version',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Versionskennung der Software.',
                    'specRef' => 'dsv8.md:2708',
                ],
                [
                    'name' => 'kontakt',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse des Software-Herstellers.',
                    'specRef' => 'dsv8.md:2710',
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
                    'specRef' => 'dsv8.md:2738',
                ],
                [
                    'name' => 'veranstaltungsort',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Ort der Veranstaltung.',
                    'specRef' => 'dsv8.md:2743',
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
                    'specRef' => 'dsv8.md:2745',
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
                    'specRef' => 'dsv8.md:2749',
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
                    'specRef' => 'dsv8.md:2788',
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
                    'specRef' => 'dsv8.md:2813',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname der Ansprechperson.',
                    'specRef' => 'dsv8.md:2816',
                ],
                [
                    'name' => 'strasse',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Strasse.',
                    'specRef' => 'dsv8.md:2818',
                ],
                [
                    'name' => 'plz',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Postleitzahl.',
                    'specRef' => 'dsv8.md:2820',
                ],
                [
                    'name' => 'ort',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Ort.',
                    'specRef' => 'dsv8.md:2822',
                ],
                [
                    'name' => 'land',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:2824',
                ],
                [
                    'name' => 'telefon',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Telefonnummer.',
                    'specRef' => 'dsv8.md:2826',
                ],
                [
                    'name' => 'fax',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Faxnummer.',
                    'specRef' => 'dsv8.md:2828',
                ],
                [
                    'name' => 'email',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse.',
                    'specRef' => 'dsv8.md:2830',
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
                    'specRef' => 'dsv8.md:2896',
                ],
                [
                    'name' => 'abschnittsdatum',
                    'type' => 'Datum',
                    'required' => true,
                    'doc' => 'Datum des Abschnitts.',
                    'specRef' => 'dsv8.md:2900',
                ],
                [
                    'name' => 'anfangszeit',
                    'type' => 'Uhrzeit',
                    'required' => true,
                    'doc' => 'Beginn des Abschnitts.',
                    'specRef' => 'dsv8.md:2902',
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
                    'specRef' => 'dsv8.md:2906',
                ],
            ],
        ],
        [
            'name' => 'KAMPFGERICHT',
            'min' => 0,
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
                    'specRef' => 'dsv8.md:2941',
                ],
                [
                    'name' => 'position',
                    'type' => 'ZK',
                    'required' => true,
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
                            'value' => 'WKH',
                            'doc' => 'Wettkampfhelfer*in',
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
                        [
                            'value' => 'ZBV',
                            'doc' => 'sonstige Kampfrichter',
                        ],
                        [
                            'value' => 'SPR',
                            'doc' => 'Sprecher*in; die Werteliste der Spezifikation kennt nur SP, ihr eigenes Beispiel in diesem Kapitel (dsv8.md:4255) schreibt aber SPR — beim Lesen Warnung, beim Schreiben unzulässig',
                            'tolerated' => true,
                        ],
                    ],
                    'doc' => 'Position im Kampfgericht.',
                    'specRef' => 'dsv8.md:2943',
                ],
                [
                    'name' => 'nameKampfrichter',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:2993',
                ],
                [
                    'name' => 'vereinDesKampfrichters',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Verein, für den die Person antritt.',
                    'specRef' => 'dsv8.md:2996',
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
                    'specRef' => 'dsv8.md:3037',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:3039',
                ],
                [
                    'name' => 'abschnittsnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        99,
                    ],
                    'doc' => 'Nummer des Abschnitts, in dem der Wettkampf stattfand.',
                    'specRef' => 'dsv8.md:3069',
                ],
                [
                    'name' => 'anzahlStarter',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Anzahl der Staffelteilnehmer; für Einzeldisziplinen 1.',
                    'specRef' => 'dsv8.md:3071',
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
                    'specRef' => 'dsv8.md:3073',
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
                    'specRef' => 'dsv8.md:3087',
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
                    'specRef' => 'dsv8.md:3102',
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
                    'specRef' => 'dsv8.md:3125',
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
                    'specRef' => 'dsv8.md:3133',
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
                    'specRef' => 'dsv8.md:3161',
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
                    'specRef' => 'dsv8.md:3177',
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
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:3205',
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
                    'specRef' => 'dsv8.md:3207',
                ],
                [
                    'name' => 'wertungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Wertung innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3253',
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
                    'specRef' => 'dsv8.md:3257',
                ],
                [
                    'name' => 'mindestJgAk',
                    'type' => 'JGAK',
                    'required' => true,
                    'doc' => 'Untere Grenze des Jahrgangs oder der Altersklasse.',
                    'specRef' => 'dsv8.md:3263',
                ],
                [
                    'name' => 'maximalJgAk',
                    'type' => 'JGAK',
                    'required' => false,
                    'doc' => 'Obere Grenze; ohne Angabe gilt der Wert von mindestJgAk.',
                    'specRef' => 'dsv8.md:3266',
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
                            'since' => 8,
                        ],
                        [
                            'value' => 'X',
                            'doc' => 'gemischte Wertung',
                        ],
                    ],
                    'doc' => 'Geschlecht der gewerteten Startberechtigten.',
                    'specRef' => 'dsv8.md:3273',
                ],
                [
                    'name' => 'wertungsname',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Bezeichnung der Wertung.',
                    'specRef' => 'dsv8.md:3289',
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
                    'specRef' => 'dsv8.md:3311',
                ],
                [
                    'name' => 'vereinskennzahl',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennzahl des Vereins.',
                    'specRef' => 'dsv8.md:3313',
                ],
                [
                    'name' => 'landesschwimmverband',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennzahl des Landesschwimmverbandes.',
                    'specRef' => 'dsv8.md:3319',
                ],
                [
                    'name' => 'nationenkuerzel',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:3346',
                ],
            ],
        ],
        [
            'name' => 'PERSON',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:3376',
                ],
                [
                    'name' => 'dsvId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'DSV-Identifikationsnummer.',
                    'specRef' => 'dsv8.md:3378',
                ],
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3385',
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
                    'specRef' => 'dsv8.md:3406',
                ],
                [
                    'name' => 'jahrgang',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Geburtsjahrgang, vierstellig.',
                    'specRef' => 'dsv8.md:3421',
                ],
                [
                    'name' => 'altersklasse',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Altersklasse. Die Spezifikation liefert keine Beschreibung; aus der Spaltenreihenfolge rekonstruiert.',
                    'specRef' => 'dsv8.md:3423',
                ],
                [
                    'name' => 'nationalitaet1',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Erste Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:3425',
                ],
                [
                    'name' => 'nationalitaet2',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Zweite Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:3433',
                ],
                [
                    'name' => 'nationalitaet3',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Dritte Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:3437',
                ],
            ],
        ],
        [
            'name' => 'PERSONENERGEBNIS',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3480',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:3485',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:3487',
                ],
                [
                    'name' => 'wertungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Wertung innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3493',
                ],
                [
                    'name' => 'platz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Erreichter Platz; bei gesetztem Grund der Nichtwertung muss hier 0 stehen.',
                    'specRef' => 'dsv8.md:3495',
                ],
                [
                    'name' => 'endzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Geschwommene Endzeit.',
                    'specRef' => 'dsv8.md:3497',
                ],
                [
                    'name' => 'grundDerNichtwertung',
                    'type' => 'ZK',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'DS',
                            'doc' => 'Disqualifikation',
                        ],
                        [
                            'value' => 'NA',
                            'doc' => 'nicht am Start',
                        ],
                        [
                            'value' => 'AB',
                            'doc' => 'vom Wettkampf abgemeldet',
                        ],
                        [
                            'value' => 'AU',
                            'doc' => 'aufgegeben',
                        ],
                        [
                            'value' => 'ZU',
                            'doc' => 'Zeitüberschreitung, nur bei Langstrecken',
                        ],
                    ],
                    'doc' => 'Grund, aus dem das Ergebnis nicht gewertet wird.',
                    'specRef' => 'dsv8.md:3499',
                ],
                [
                    'name' => 'disqualifikationsbemerkung',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Erläuterung zur Disqualifikation.',
                    'specRef' => 'dsv8.md:3569',
                ],
                [
                    'name' => 'erhoehtesNachtraeglichesMeldegeld',
                    'type' => 'Zeichen',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'E',
                            'doc' => 'Norm erreicht',
                        ],
                        [
                            'value' => 'F',
                            'doc' => 'erhöhtes Meldegeld fällig',
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Norm nicht erreicht, aber nachgewiesen',
                        ],
                    ],
                    'doc' => 'Kennzeichen für erhöhtes oder nachträgliches Meldegeld.',
                    'specRef' => 'dsv8.md:3579',
                ],
            ],
        ],
        [
            'name' => 'PNZWISCHENZEIT',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3602',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:3607',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:3611',
                ],
                [
                    'name' => 'distanz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Distanz in Metern, auf die sich die Zwischenzeit bezieht.',
                    'specRef' => 'dsv8.md:3615',
                ],
                [
                    'name' => 'zwischenzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Gemessene Zwischenzeit.',
                    'specRef' => 'dsv8.md:3617',
                ],
            ],
        ],
        [
            'name' => 'PNREAKTION',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Person innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3663',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:3668',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:3672',
                ],
                [
                    'name' => 'art',
                    'type' => 'Zeichen',
                    'required' => false,
                    'default' => '+',
                    'values' => [
                        [
                            'value' => '+',
                            'doc' => 'Reaktion nach dem Startsignal',
                        ],
                        [
                            'value' => '-',
                            'doc' => 'Reaktion vor dem Startsignal',
                        ],
                    ],
                    'doc' => 'Vorzeichen der Reaktionszeit.',
                    'specRef' => 'dsv8.md:3698',
                ],
                [
                    'name' => 'reaktionszeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Gemessene Reaktionszeit.',
                    'specRef' => 'dsv8.md:3712',
                ],
            ],
        ],
        [
            'name' => 'STAFFEL',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'nummerDerMannschaft',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Nummer der Mannschaft innerhalb des Vereins.',
                    'specRef' => 'dsv8.md:3736',
                ],
                [
                    'name' => 'veranstaltungsIdStaffel',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Staffel innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3739',
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
                    'specRef' => 'dsv8.md:3746',
                ],
                [
                    'name' => 'mindestJgAk',
                    'type' => 'JGAK',
                    'required' => true,
                    'doc' => 'Untere Grenze des Jahrgangs oder der Altersklasse.',
                    'specRef' => 'dsv8.md:3752',
                ],
                [
                    'name' => 'maximalJgAk',
                    'type' => 'JGAK',
                    'required' => false,
                    'doc' => 'Obere Grenze; ohne Angabe gilt der Wert von mindestJgAk.',
                    'specRef' => 'dsv8.md:3758',
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
                    'specRef' => 'dsv8.md:3848',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:3853',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:3857',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:3867',
                ],
                [
                    'name' => 'dsvId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'DSV-Identifikationsnummer.',
                    'specRef' => 'dsv8.md:3869',
                ],
                [
                    'name' => 'startnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Startnummer innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:3872',
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
                    'specRef' => 'dsv8.md:3877',
                ],
                [
                    'name' => 'jahrgang',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Geburtsjahrgang, vierstellig.',
                    'specRef' => 'dsv8.md:3892',
                ],
                [
                    'name' => 'altersklasse',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Altersklasse. Die Spezifikation liefert keine Beschreibung; aus der Spaltenreihenfolge rekonstruiert.',
                    'specRef' => 'dsv8.md:3894',
                ],
                [
                    'name' => 'nationalitaet1',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Erste Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:3896',
                ],
                [
                    'name' => 'nationalitaet2',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Zweite Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:3904',
                ],
                [
                    'name' => 'nationalitaet3',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Dritte Staatsangehörigkeit, WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:3908',
                ],
            ],
        ],
        [
            'name' => 'STAFFELERGEBNIS',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsIdStaffel',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Staffel innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3944',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:3947',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:3949',
                ],
                [
                    'name' => 'wertungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Wertung innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:3979',
                ],
                [
                    'name' => 'platz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Erreichter Platz; bei gesetztem Grund der Nichtwertung muss hier 0 stehen.',
                    'specRef' => 'dsv8.md:3981',
                ],
                [
                    'name' => 'endzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Geschwommene Endzeit.',
                    'specRef' => 'dsv8.md:3983',
                ],
                [
                    'name' => 'grundDerNichtwertung',
                    'type' => 'ZK',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'DS',
                            'doc' => 'Disqualifikation',
                        ],
                        [
                            'value' => 'NA',
                            'doc' => 'nicht am Start',
                        ],
                        [
                            'value' => 'AB',
                            'doc' => 'vom Wettkampf abgemeldet',
                        ],
                        [
                            'value' => 'AU',
                            'doc' => 'aufgegeben',
                        ],
                        [
                            'value' => 'ZU',
                            'doc' => 'Zeitüberschreitung, nur bei Langstrecken',
                        ],
                    ],
                    'doc' => 'Grund, aus dem das Ergebnis nicht gewertet wird.',
                    'specRef' => 'dsv8.md:3985',
                ],
                [
                    'name' => 'startnummerDisqualifiziert',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Startnummer der disqualifizierten Person; 0 bei allgemeinem Disqualifikationsgrund.',
                    'specRef' => 'dsv8.md:4003',
                ],
                [
                    'name' => 'disqualifikationsbemerkung',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Erläuterung zur Disqualifikation.',
                    'specRef' => 'dsv8.md:4044',
                ],
                [
                    'name' => 'erhoehtesNachtraeglichesMeldegeld',
                    'type' => 'Zeichen',
                    'required' => false,
                    'values' => [
                        [
                            'value' => 'E',
                            'doc' => 'Norm erreicht',
                        ],
                        [
                            'value' => 'F',
                            'doc' => 'erhöhtes Meldegeld fällig',
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Norm nicht erreicht, aber nachgewiesen',
                        ],
                    ],
                    'doc' => 'Kennzeichen für erhöhtes oder nachträgliches Meldegeld.',
                    'specRef' => 'dsv8.md:4054',
                ],
            ],
        ],
        [
            'name' => 'STZWISCHENZEIT',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsIdStaffel',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Staffel innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:4077',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:4082',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:4086',
                ],
                [
                    'name' => 'startnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Startnummer innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:4092',
                ],
                [
                    'name' => 'distanz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Distanz in Metern, auf die sich die Zwischenzeit bezieht.',
                    'specRef' => 'dsv8.md:4097',
                ],
                [
                    'name' => 'zwischenzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Gemessene Zwischenzeit.',
                    'specRef' => 'dsv8.md:4099',
                ],
            ],
        ],
        [
            'name' => 'STABLOESE',
            'min' => 0,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'veranstaltungsIdStaffel',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Staffel innerhalb dieser Veranstaltung.',
                    'specRef' => 'dsv8.md:4155',
                ],
                [
                    'name' => 'wettkampfnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        999,
                    ],
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:4160',
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
                        ],
                        [
                            'value' => 'N',
                            'doc' => 'Nachschwimmen',
                        ],
                    ],
                    'doc' => 'Art des Wettkampfes.',
                    'specRef' => 'dsv8.md:4164',
                ],
                [
                    'name' => 'startnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Startnummer innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:4196',
                ],
                [
                    'name' => 'art',
                    'type' => 'Zeichen',
                    'required' => false,
                    'default' => '+',
                    'values' => [
                        [
                            'value' => '+',
                            'doc' => 'Reaktion nach dem Startsignal',
                        ],
                        [
                            'value' => '-',
                            'doc' => 'Reaktion vor dem Startsignal',
                        ],
                    ],
                    'doc' => 'Vorzeichen der Ablösezeit.',
                    'specRef' => 'dsv8.md:4201',
                ],
                [
                    'name' => 'reaktionszeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Gemessene Ablösezeit.',
                    'specRef' => 'dsv8.md:4215',
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
