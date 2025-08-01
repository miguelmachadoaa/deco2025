<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder {

    public function run()
    {
       DB::table('pais')->truncate();
        $statement = "INSERT INTO  `pais` (`id`, `sortname`, `country_name` ) VALUES
            (1, 'AF', 'Afghanistan'),
            (2, 'AL', 'Albania'),
            (3, 'DZ', 'Algeria'),
            (4, 'AS', 'American Samoa'),
            (5, 'AD', 'Andorra'),
            (6, 'AO', 'Angola'),
            (7, 'AI', 'Anguilla'),
            (8, 'AQ', 'Antarctica'),
            (9, 'AG', 'Antigua And Barbuda'),
            (10, 'AR', 'Argentina'),
            (11, 'AM', 'Armenia'),
            (12, 'AW', 'Aruba'),
            (13, 'AU', 'Australia'),
            (14, 'AT', 'Austria'),
            (15, 'AZ', 'Azerbaijan'),
            (16, 'BS', 'Bahamas The'),
            (17, 'BH', 'Bahrain'),
            (18, 'BD', 'Bangladesh'),
            (19, 'BB', 'Barbados'),
            (20, 'BY', 'Belarus'),
            (21, 'BE', 'Belgium'),
            (22, 'BZ', 'Belize'),
            (23, 'BJ', 'Benin'),
            (24, 'BM', 'Bermuda'),
            (25, 'BT', 'Bhutan'),
            (26, 'BO', 'Bolivia'),
            (27, 'BA', 'Bosnia and Herzegovina'),
            (28, 'BW', 'Botswana'),
            (29, 'BV', 'Bouvet Island'),
            (30, 'BR', 'Brazil'),
            (31, 'IO', 'British Indian Ocean Territory'),
            (32, 'BN', 'Brunei'),
            (33, 'BG', 'Bulgaria'),
            (34, 'BF', 'Burkina Faso'),
            (35, 'BI', 'Burundi'),
            (36, 'KH', 'Cambodia'),
            (37, 'CM', 'Cameroon'),
            (38, 'CA', 'Canada'),
            (39, 'CV', 'Cape Verde'),
            (40, 'KY', 'Cayman Islands'),
            (41, 'CF', 'Central African Republic'),
            (42, 'TD', 'Chad'),
            (43, 'CL', 'Chile'),
            (44, 'CN', 'China'),
            (45, 'CX', 'Christmas Island'),
            (46, 'CC', 'Cocos (Keeling) Islands'),
            (47, 'CO', 'Colombia'),
            (48, 'KM', 'Comoros'),
            (49, 'CG', 'Congo'),
            (50, 'CD', 'Congo The Democratic Republic Of The'),
            (51, 'CK', 'Cook Islands'),
            (52, 'CR', 'Costa Rica'),
            (53, 'CI', 'Cote D''Ivoire (Ivory Coast)'),
            (54, 'HR', 'Croatia (Hrvatska)'),
            (55, 'CU', 'Cuba'),
            (56, 'CY', 'Cyprus'),
            (57, 'CZ', 'Czech Republic'),
            (58, 'DK', 'Denmark'),
            (59, 'DJ', 'Djibouti'),
            (60, 'DM', 'Dominica'),
            (61, 'DO', 'Dominican Republic'),
            (62, 'TP', 'East Timor'),
            (63, 'EC', 'Ecuador'),
            (64, 'EG', 'Egypt'),
            (65, 'SV', 'El Salvador'),
            (66, 'GQ', 'Equatorial Guinea'),
            (67, 'ER', 'Eritrea'),
            (68, 'EE', 'Estonia'),
            (69, 'ET', 'Ethiopia'),
            (70, 'XA', 'External Territories of Australia'),
            (71, 'FK', 'Falkland Islands'),
            (72, 'FO', 'Faroe Islands'),
            (73, 'FJ', 'Fiji Islands'),
            (74, 'FI', 'Finland'),
            (75, 'FR', 'France'),
            (76, 'GF', 'French Guiana'),
            (77, 'PF', 'French Polynesia'),
            (78, 'TF', 'French Southern Territories'),
            (79, 'GA', 'Gabon'),
            (80, 'GM', 'Gambia The'),
            (81, 'GE', 'Georgia'),
            (82, 'DE', 'Germany'),
            (83, 'GH', 'Ghana'),
            (84, 'GI', 'Gibraltar'),
            (85, 'GR', 'Greece'),
            (86, 'GL', 'Greenland'),
            (87, 'GD', 'Grenada'),
            (88, 'GP', 'Guadeloupe'),
            (89, 'GU', 'Guam'),
            (90, 'GT', 'Guatemala'),
            (91, 'XU', 'Guernsey and Alderney'),
            (92, 'GN', 'Guinea'),
            (93, 'GW', 'Guinea-Bissau'),
            (94, 'GY', 'Guyana'),
            (95, 'HT', 'Haiti'),
            (96, 'HM', 'Heard and McDonald Islands'),
            (97, 'HN', 'Honduras'),
            (98, 'HK', 'Hong Kong S.A.R.'),
            (99, 'HU', 'Hungary'),
            (100, 'IS', 'Iceland'),
            (101, 'IN', 'India'),
            (102, 'ID', 'Indonesia'),
            (103, 'IR', 'Iran'),
            (104, 'IQ', 'Iraq'),
            (105, 'IE', 'Ireland'),
            (106, 'IL', 'Israel'),
            (107, 'IT', 'Italy'),
            (108, 'JM', 'Jamaica'),
            (109, 'JP', 'Japan'),
            (110, 'XJ', 'Jersey'),
            (111, 'JO', 'Jordan'),
            (112, 'KZ', 'Kazakhstan'),
            (113, 'KE', 'Kenya'),
            (114, 'KI', 'Kiribati'),
            (115, 'KP', 'Korea North'),
            (116, 'KR', 'Korea South'),
            (117, 'KW', 'Kuwait'),
            (118, 'KG', 'Kyrgyzstan'),
            (119, 'LA', 'Laos'),
            (120, 'LV', 'Latvia'),
            (121, 'LB', 'Lebanon'),
            (122, 'LS', 'Lesotho'),
            (123, 'LR', 'Liberia'),
            (124, 'LY', 'Libya'),
            (125, 'LI', 'Liechtenstein'),
            (126, 'LT', 'Lithuania'),
            (127, 'LU', 'Luxembourg'),
            (128, 'MO', 'Macau S.A.R.'),
            (129, 'MK', 'Macedonia'),
            (130, 'MG', 'Madagascar'),
            (131, 'MW', 'Malawi'),
            (132, 'MY', 'Malaysia'),
            (133, 'MV', 'Maldives'),
            (134, 'ML', 'Mali'),
            (135, 'MT', 'Malta'),
            (136, 'XM', 'Man (Isle of)'),
            (137, 'MH', 'Marshall Islands'),
            (138, 'MQ', 'Martinique'),
            (139, 'MR', 'Mauritania'),
            (140, 'MU', 'Mauritius'),
            (141, 'YT', 'Mayotte'),
            (142, 'MX', 'Mexico'),
            (143, 'FM', 'Micronesia'),
            (144, 'MD', 'Moldova'),
            (145, 'MC', 'Monaco'),
            (146, 'MN', 'Mongolia'),
            (147, 'MS', 'Montserrat'),
            (148, 'MA', 'Morocco'),
            (149, 'MZ', 'Mozambique'),
            (150, 'MM', 'Myanmar'),
            (151, 'NA', 'Namibia'),
            (152, 'NR', 'Nauru'),
            (153, 'NP', 'Nepal'),
            (154, 'AN', 'Netherlands Antilles'),
            (155, 'NL', 'Netherlands The'),
            (156, 'NC', 'New Caledonia'),
            (157, 'NZ', 'New Zealand'),
            (158, 'NI', 'Nicaragua'),
            (159, 'NE', 'Niger'),
            (160, 'NG', 'Nigeria'),
            (161, 'NU', 'Niue'),
            (162, 'NF', 'Norfolk Island'),
            (163, 'MP', 'Northern Mariana Islands'),
            (164, 'NO', 'Norway'),
            (165, 'OM', 'Oman'),
            (166, 'PK', 'Pakistan'),
            (167, 'PW', 'Palau'),
            (168, 'PS', 'Palestinian Territory Occupied'),
            (169, 'PA', 'Panama'),
            (170, 'PG', 'Papua new Guinea'),
            (171, 'PY', 'Paraguay'),
            (172, 'PE', 'Peru'),
            (173, 'PH', 'Philippines'),
            (174, 'PN', 'Pitcairn Island'),
            (175, 'PL', 'Poland'),
            (176, 'PT', 'Portugal'),
            (177, 'PR', 'Puerto Rico'),
            (178, 'QA', 'Qatar'),
            (179, 'RE', 'Reunion'),
            (180, 'RO', 'Romania'),
            (181, 'RU', 'Russia'),
            (182, 'RW', 'Rwanda'),
            (183, 'SH', 'Saint Helena'),
            (184, 'KN', 'Saint Kitts And Nevis'),
            (185, 'LC', 'Saint Lucia'),
            (186, 'PM', 'Saint Pierre and Miquelon'),
            (187, 'VC', 'Saint Vincent And The Grenadines'),
            (188, 'WS', 'Samoa'),
            (189, 'SM', 'San Marino'),
            (190, 'ST', 'Sao Tome and Principe'),
            (191, 'SA', 'Saudi Arabia'),
            (192, 'SN', 'Senegal'),
            (193, 'RS', 'Serbia'),
            (194, 'SC', 'Seychelles'),
            (195, 'SL', 'Sierra Leone'),
            (196, 'SG', 'Singapore'),
            (197, 'SK', 'Slovakia'),
            (198, 'SI', 'Slovenia'),
            (199, 'XG', 'Smaller Territories of the UK'),
            (200, 'SB', 'Solomon Islands'),
            (201, 'SO', 'Somalia'),
            (202, 'ZA', 'South Africa'),
            (203, 'GS', 'South Georgia'),
            (204, 'SS', 'South Sudan'),
            (205, 'ES', 'Spain'),
            (206, 'LK', 'Sri Lanka'),
            (207, 'SD', 'Sudan'),
            (208, 'SR', 'Suriname'),
            (209, 'SJ', 'Svalbard And Jan Mayen Islands'),
            (210, 'SZ', 'Swaziland'),
            (211, 'SE', 'Sweden'),
            (212, 'CH', 'Switzerland'),
            (213, 'SY', 'Syria'),
            (214, 'TW', 'Taiwan'),
            (215, 'TJ', 'Tajikistan'),
            (216, 'TZ', 'Tanzania'),
            (217, 'TH', 'Thailand'),
            (218, 'TG', 'Togo'),
            (219, 'TK', 'Tokelau'),
            (220, 'TO', 'Tonga'),
            (221, 'TT', 'Trinidad And Tobago'),
            (222, 'TN', 'Tunisia'),
            (223, 'TR', 'Turkey'),
            (224, 'TM', 'Turkmenistan'),
            (225, 'TC', 'Turks And Caicos Islands'),
            (226, 'TV', 'Tuvalu'),
            (227, 'UG', 'Uganda'),
            (228, 'UA', 'Ukraine'),
            (229, 'AE', 'United Arab Emirates'),
            (230, 'GB', 'United Kingdom'),
            (231, 'US', 'United States'),
            (232, 'UM', 'United States Minor Outlying Islands'),
            (233, 'UY', 'Uruguay'),
            (234, 'UZ', 'Uzbekistan'),
            (235, 'VU', 'Vanuatu'),
            (236, 'VA', 'Vatican City State (Holy See)'),
            (237, 'VE', 'Venezuela'),
            (238, 'VN', 'Vietnam'),
            (239, 'VG', 'Virgin Islands (British)'),
            (240, 'VI', 'Virgin Islands (US)'),
            (241, 'WF', 'Wallis And Futuna Islands'),
            (242, 'EH', 'Western Sahara'),
            (243, 'YE', 'Yemen'),
            (244, 'YU', 'Yugoslavia'),
            (245, 'ZM', 'Zambia'),
            (246, 'ZW', 'Zimbabwe');";
        DB::unprepared($statement);

    }

}