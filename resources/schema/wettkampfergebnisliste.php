<?php

// GENERATED from the DSV standard (DSV7/DSV8) schema. Do not edit by hand.

declare(strict_types=1);

return [
    'listenart' => 'Wettkampfergebnisliste',
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
                    'doc' => 'Konstant Wettkampfergebnisliste.',
                    'specRef' => 'dsv8.md:4330',
                ],
                [
                    'name' => 'version',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Versionsnummer des DSV-Standards.',
                    'specRef' => 'dsv8.md:4332',
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
                    'specRef' => 'dsv8.md:4354',
                ],
                [
                    'name' => 'version',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Versionskennung der Software.',
                    'specRef' => 'dsv8.md:4356',
                ],
                [
                    'name' => 'kontakt',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse des Software-Herstellers.',
                    'specRef' => 'dsv8.md:4358',
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
                    'specRef' => 'dsv8.md:4388',
                ],
                [
                    'name' => 'veranstaltungsort',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Ort der Veranstaltung.',
                    'specRef' => 'dsv8.md:4391',
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
                    'specRef' => 'dsv8.md:4394',
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
                    'specRef' => 'dsv8.md:4402',
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
                    'specRef' => 'dsv8.md:4440',
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
                    'specRef' => 'dsv8.md:4465',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname der Ansprechperson.',
                    'specRef' => 'dsv8.md:4468',
                ],
                [
                    'name' => 'strasse',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Strasse.',
                    'specRef' => 'dsv8.md:4470',
                ],
                [
                    'name' => 'plz',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Postleitzahl.',
                    'specRef' => 'dsv8.md:4472',
                ],
                [
                    'name' => 'ort',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Ort.',
                    'specRef' => 'dsv8.md:4474',
                ],
                [
                    'name' => 'land',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:4476',
                ],
                [
                    'name' => 'telefon',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Telefonnummer.',
                    'specRef' => 'dsv8.md:4478',
                ],
                [
                    'name' => 'fax',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Faxnummer.',
                    'specRef' => 'dsv8.md:4480',
                ],
                [
                    'name' => 'email',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'E-Mail-Adresse.',
                    'specRef' => 'dsv8.md:4482',
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
                    'specRef' => 'dsv8.md:4547',
                ],
                [
                    'name' => 'abschnittsdatum',
                    'type' => 'Datum',
                    'required' => true,
                    'doc' => 'Datum, an dem der Abschnitt stattfindet.',
                    'specRef' => 'dsv8.md:4551',
                ],
                [
                    'name' => 'anfangszeit',
                    'type' => 'Uhrzeit',
                    'required' => true,
                    'doc' => 'Uhrzeit, zu der der Abschnitt beginnt.',
                    'specRef' => 'dsv8.md:4556',
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
                    'doc' => 'J, wenn die Zeit relativ zum Ende des Vorabschnitts zu verstehen ist.',
                    'specRef' => 'dsv8.md:4560',
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
                    'specRef' => 'dsv8.md:4595',
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
                            'doc' => 'Sprecher*in; die Werteliste der Spezifikation (dsv8.md:4611–4655) kennt nur SP, ihr eigenes Beispiel (dsv8.md:5852) und eine echte Datei schreiben aber SPR — beim Lesen Warnung, beim Schreiben unzulässig',
                            'tolerated' => true,
                        ],
                    ],
                    'doc' => 'Funktion im Kampfgericht.',
                    'specRef' => 'dsv8.md:4597',
                ],
                [
                    'name' => 'nameKampfrichter',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:4661',
                ],
                [
                    'name' => 'vereinDesKampfrichters',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Verein der Kampfrichterin oder des Kampfrichters.',
                    'specRef' => 'dsv8.md:4664',
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
                    'specRef' => 'dsv8.md:4689',
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
                    'specRef' => 'dsv8.md:4691',
                ],
                [
                    'name' => 'abschnittsnr',
                    'type' => 'Zahl',
                    'required' => true,
                    'range' => [
                        0,
                        99,
                    ],
                    'doc' => 'Nummer des Abschnitts.',
                    'specRef' => 'dsv8.md:4703',
                ],
                [
                    'name' => 'anzahlStarter',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Anzahl der Starter; bei Staffeln die Zahl der Teilnehmenden.',
                    'specRef' => 'dsv8.md:4705',
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
                    'specRef' => 'dsv8.md:4707',
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
                    'specRef' => 'dsv8.md:4715',
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
                    'specRef' => 'dsv8.md:4727',
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
                        [
                            'value' => 'X',
                            'doc' => 'gemischte Wettkämpfe',
                        ],
                    ],
                    'doc' => 'Geschlecht der Teilnehmenden.',
                    'specRef' => 'dsv8.md:4771',
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
                    'specRef' => 'dsv8.md:4784',
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
                    'specRef' => 'dsv8.md:4788',
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
                    'specRef' => 'dsv8.md:4826',
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
                    'specRef' => 'dsv8.md:4855',
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
                    'specRef' => 'dsv8.md:4857',
                ],
                [
                    'name' => 'wertungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Veranstaltungsweit eindeutige Kennung der Wertung.',
                    'specRef' => 'dsv8.md:4873',
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
                    'specRef' => 'dsv8.md:4877',
                ],
                [
                    'name' => 'mindestJgAk',
                    'type' => 'JGAK',
                    'required' => true,
                    'doc' => 'Kleinster Jahrgang oder grösste Altersklasse; 0 für die offene Klasse.',
                    'specRef' => 'dsv8.md:4883',
                ],
                [
                    'name' => 'maximalJgAk',
                    'type' => 'JGAK',
                    'required' => false,
                    'doc' => 'Grösster Jahrgang oder kleinste Altersklasse; ohne Angabe gilt der Wert von mindestJgAk.',
                    'specRef' => 'dsv8.md:4886',
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
                            'doc' => 'mixed',
                        ],
                    ],
                    'doc' => 'Geschlecht der Wertung; ohne Angabe gilt das Geschlecht des Wettkampfes.',
                    'specRef' => 'dsv8.md:4901',
                ],
                [
                    'name' => 'wertungsname',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Textliche Bezeichnung der Wertung.',
                    'specRef' => 'dsv8.md:4907',
                ],
            ],
        ],
        [
            'name' => 'VEREIN',
            'min' => 1,
            'max' => NULL,
            'bare' => false,
            'fields' => [
                [
                    'name' => 'vereinsbezeichnung',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name des Vereins.',
                    'specRef' => 'dsv8.md:4958',
                ],
                [
                    'name' => 'vereinskennzahl',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Vierstellige Vereinskennzahl; 0 bei nicht dem DSV angehörenden Vereinen.',
                    'specRef' => 'dsv8.md:4960',
                ],
                [
                    'name' => 'landesschwimmverband',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennzahl des Landesschwimmverbands; 0 bei ausländischen Vereinen, 99 bei Auswahlmannschaften.',
                    'specRef' => 'dsv8.md:4966',
                ],
                [
                    'name' => 'nationenkuerzel',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'WA-Nationenkürzel, dreistellig.',
                    'specRef' => 'dsv8.md:4994',
                ],
            ],
        ],
        [
            'name' => 'PNERGEBNIS',
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
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:5026',
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
                    'specRef' => 'dsv8.md:5028',
                ],
                [
                    'name' => 'wertungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Wertung.',
                    'specRef' => 'dsv8.md:5044',
                ],
                [
                    'name' => 'platz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Erreichte Platzierung; bei gesetztem Grund der Nichtwertung muss hier 0 stehen.',
                    'specRef' => 'dsv8.md:5046',
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
                    'doc' => 'Grund, warum das Ergebnis nicht gewertet wird.',
                    'specRef' => 'dsv8.md:5052',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:5056',
                ],
                [
                    'name' => 'dsvId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Sechsstellige DSV-ID; 0 wenn unbekannt.',
                    'specRef' => 'dsv8.md:5058',
                ],
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Veranstaltungsweit eindeutige Kennung der Person.',
                    'specRef' => 'dsv8.md:5064',
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
                    'doc' => 'Geschlecht der Person.',
                    'specRef' => 'dsv8.md:5119',
                ],
                [
                    'name' => 'jahrgang',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Vierstelliger Jahrgang.',
                    'specRef' => 'dsv8.md:5122',
                ],
                [
                    'name' => 'altersklasse',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Altersklasse. Die Spezifikation liefert für dieses Feld keine Beschreibung; Datentyp und Pflichtangabe sind aus der Spaltenreihenfolge rekonstruiert. In den 72 echten Ergebnislisten ist es in 9982 von 95756 Zeilen gesetzt (51 der 72 Dateien).',
                    'specRef' => 'dsv8.md:5124',
                ],
                [
                    'name' => 'verein',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name des Vereins.',
                    'specRef' => 'dsv8.md:5126',
                ],
                [
                    'name' => 'vereinskennzahl',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Vereinskennzahl; 0 bei nicht dem DSV angehörenden Vereinen.',
                    'specRef' => 'dsv8.md:5128',
                ],
                [
                    'name' => 'endzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Erreichte Endzeit; bei Nichtwertung 00:00:00,00.',
                    'specRef' => 'dsv8.md:5130',
                ],
                [
                    'name' => 'disqualifikationsbemerkung',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Erläuterung zur Disqualifikation.',
                    'specRef' => 'dsv8.md:5132',
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
                    'doc' => 'Kennzeichen zum erhöhten nachträglichen Meldegeld.',
                    'specRef' => 'dsv8.md:5136',
                ],
                [
                    'name' => 'nationalitaet1',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'WA-Nationenkürzel; leer wenn unbekannt.',
                    'specRef' => 'dsv8.md:5202',
                ],
                [
                    'name' => 'nationalitaet2',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Zweite Staatsangehörigkeit.',
                    'specRef' => 'dsv8.md:5208',
                ],
                [
                    'name' => 'nationalitaet3',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Dritte Staatsangehörigkeit.',
                    'specRef' => 'dsv8.md:5210',
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
                    'doc' => 'Kennung der Person, definiert in PNERGEBNIS.',
                    'specRef' => 'dsv8.md:5245',
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
                    'specRef' => 'dsv8.md:5250',
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
                    'specRef' => 'dsv8.md:5254',
                ],
                [
                    'name' => 'distanz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Zurückgelegte Strecke in Metern.',
                    'specRef' => 'dsv8.md:5258',
                ],
                [
                    'name' => 'zwischenzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Zwischenzeit bei dieser Distanz.',
                    'specRef' => 'dsv8.md:5260',
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
                    'doc' => 'Kennung der Person, definiert in PNERGEBNIS.',
                    'specRef' => 'dsv8.md:5306',
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
                    'specRef' => 'dsv8.md:5311',
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
                    'specRef' => 'dsv8.md:5315',
                ],
                [
                    'name' => 'art',
                    'type' => 'Zeichen',
                    'required' => false,
                    'default' => '+',
                    'values' => [
                        [
                            'value' => '+',
                            'doc' => 'Start nach dem Startsignal',
                        ],
                        [
                            'value' => '-',
                            'doc' => 'Start vor dem Startsignal',
                        ],
                    ],
                    'doc' => 'Vorzeichen der Reaktionszeit.',
                    'specRef' => 'dsv8.md:5341',
                ],
                [
                    'name' => 'reaktionszeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Gemessene Reaktionszeit.',
                    'specRef' => 'dsv8.md:5355',
                ],
            ],
        ],
        [
            'name' => 'STERGEBNIS',
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
                    'doc' => 'Nummer des Wettkampfes.',
                    'specRef' => 'dsv8.md:5377',
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
                    'specRef' => 'dsv8.md:5379',
                ],
                [
                    'name' => 'wertungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Kennung der Wertung.',
                    'specRef' => 'dsv8.md:5395',
                ],
                [
                    'name' => 'platz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Erreichte Platzierung; bei gesetztem Grund der Nichtwertung muss hier 0 stehen.',
                    'specRef' => 'dsv8.md:5397',
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
                    'doc' => 'Grund, warum das Ergebnis nicht gewertet wird.',
                    'specRef' => 'dsv8.md:5403',
                ],
                [
                    'name' => 'nummerDerMannschaft',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Laufende Nummer der Mannschaft des Vereins.',
                    'specRef' => 'dsv8.md:5407',
                ],
                [
                    'name' => 'veranstaltungsId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Veranstaltungsweit eindeutige Kennung der Staffel.',
                    'specRef' => 'dsv8.md:5411',
                ],
                [
                    'name' => 'verein',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name des Vereins.',
                    'specRef' => 'dsv8.md:5414',
                ],
                [
                    'name' => 'vereinskennzahl',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Vereinskennzahl; 0 bei nicht dem DSV angehörenden Vereinen.',
                    'specRef' => 'dsv8.md:5416',
                ],
                [
                    'name' => 'endzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Erreichte Endzeit; bei Nichtwertung 00:00:00,00.',
                    'specRef' => 'dsv8.md:5418',
                ],
                [
                    'name' => 'startnummerDisqualifiziert',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Startnummer der disqualifizierten Person innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:5420',
                ],
                [
                    'name' => 'disqualifikationsbemerkung',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Erläuterung zur Disqualifikation.',
                    'specRef' => 'dsv8.md:5435',
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
                    'doc' => 'Kennzeichen zum erhöhten nachträglichen Meldegeld.',
                    'specRef' => 'dsv8.md:5437',
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
                    'doc' => 'Kennung der Staffel, definiert in STERGEBNIS.',
                    'specRef' => 'dsv8.md:5576',
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
                    'specRef' => 'dsv8.md:5581',
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
                    'specRef' => 'dsv8.md:5585',
                ],
                [
                    'name' => 'name',
                    'type' => 'ZK',
                    'required' => true,
                    'doc' => 'Name und Vorname.',
                    'specRef' => 'dsv8.md:5595',
                ],
                [
                    'name' => 'dsvId',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Sechsstellige DSV-ID; 0 wenn unbekannt.',
                    'specRef' => 'dsv8.md:5597',
                ],
                [
                    'name' => 'startnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Startnummer innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:5600',
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
                    'doc' => 'Geschlecht der Person.',
                    'specRef' => 'dsv8.md:5605',
                ],
                [
                    'name' => 'jahrgang',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Vierstelliger Jahrgang.',
                    'specRef' => 'dsv8.md:5620',
                ],
                [
                    'name' => 'altersklasse',
                    'type' => 'Zahl',
                    'required' => false,
                    'doc' => 'Altersklasse. Die Spezifikation liefert keine Beschreibung; aus der Spaltenreihenfolge rekonstruiert.',
                    'specRef' => 'dsv8.md:5622',
                ],
                [
                    'name' => 'nationalitaet1',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'WA-Nationenkürzel.',
                    'specRef' => 'dsv8.md:5624',
                ],
                [
                    'name' => 'nationalitaet2',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Zweite Staatsangehörigkeit.',
                    'specRef' => 'dsv8.md:5632',
                ],
                [
                    'name' => 'nationalitaet3',
                    'type' => 'ZK',
                    'required' => false,
                    'doc' => 'Dritte Staatsangehörigkeit.',
                    'specRef' => 'dsv8.md:5636',
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
                    'doc' => 'Kennung der Staffel.',
                    'specRef' => 'dsv8.md:5678',
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
                    'specRef' => 'dsv8.md:5683',
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
                    'specRef' => 'dsv8.md:5687',
                ],
                [
                    'name' => 'startnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Startnummer innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:5693',
                ],
                [
                    'name' => 'distanz',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Zurückgelegte Strecke in Metern.',
                    'specRef' => 'dsv8.md:5698',
                ],
                [
                    'name' => 'zwischenzeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Zwischenzeit bei dieser Distanz.',
                    'specRef' => 'dsv8.md:5700',
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
                    'doc' => 'Kennung der Staffel.',
                    'specRef' => 'dsv8.md:5751',
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
                    'specRef' => 'dsv8.md:5756',
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
                    'specRef' => 'dsv8.md:5760',
                ],
                [
                    'name' => 'startnummer',
                    'type' => 'Zahl',
                    'required' => true,
                    'doc' => 'Startnummer innerhalb der Staffel.',
                    'specRef' => 'dsv8.md:5786',
                ],
                [
                    'name' => 'art',
                    'type' => 'Zeichen',
                    'required' => false,
                    'default' => '+',
                    'values' => [
                        [
                            'value' => '+',
                            'doc' => 'positive Zeit',
                        ],
                        [
                            'value' => '-',
                            'doc' => 'negative Zeit',
                        ],
                    ],
                    'doc' => 'Vorzeichen der Ablösezeit.',
                    'specRef' => 'dsv8.md:5798',
                ],
                [
                    'name' => 'reaktionszeit',
                    'type' => 'Zeit',
                    'required' => true,
                    'doc' => 'Gemessene Ablösezeit.',
                    'specRef' => 'dsv8.md:5812',
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
