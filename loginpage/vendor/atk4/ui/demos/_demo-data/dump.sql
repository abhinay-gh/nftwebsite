DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `addresses` blob,
  `accounts` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `client` VALUES
    (1,'John',null,null),(2,'Jane',null,null);


DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `country` VALUES
    (1,'AF','AFGHANISTAN','Afghanistan','AFG',4,93),(2,'AL','ALBANIA','Albania','ALB',8,355),(3,'DZ','ALGERIA','Algeria','DZA',12,213),(4,'AS','AMERICAN SAMOA','American Samoa','ASM',16,1684),(5,'AD','ANDORRA','Andorra','AND',20,376),(6,'AO','ANGOLA','Angola','AGO',24,244),
    (7,'AI','ANGUILLA','Anguilla','AIA',660,1264),(8,'AQ','ANTARCTICA','Antarctica',NULL,NULL,0),(9,'AG','ANTIGUA AND BARBUDA','Antigua and Barbuda','ATG',28,1268),(10,'AR','ARGENTINA','Argentina','ARG',32,54),(11,'AM','ARMENIA','Armenia','ARM',51,374),(12,'AW','ARUBA','Aruba','ABW',533,297),
    (13,'AU','AUSTRALIA','Australia','AUS',36,61),(14,'AT','AUSTRIA','Austria','AUT',40,43),(15,'AZ','AZERBAIJAN','Azerbaijan','AZE',31,994),(16,'BS','BAHAMAS','Bahamas','BHS',44,1242),(17,'BH','BAHRAIN','Bahrain','BHR',48,973),(18,'BD','BANGLADESH','Bangladesh','BGD',50,880),
    (19,'BB','BARBADOS','Barbados','BRB',52,1246),(20,'BY','BELARUS','Belarus','BLR',112,375),(21,'BE','BELGIUM','Belgium','BEL',56,32),(22,'BZ','BELIZE','Belize','BLZ',84,501),(23,'BJ','BENIN','Benin','BEN',204,229),(24,'BM','BERMUDA','Bermuda','BMU',60,1441),
    (25,'BT','BHUTAN','Bhutan','BTN',64,975),(26,'BO','BOLIVIA','Bolivia','BOL',68,591),(27,'BA','BOSNIA AND HERZEGOVINA','Bosnia and Herzegovina','BIH',70,387),(28,'BW','BOTSWANA','Botswana','BWA',72,267),(29,'BV','BOUVET ISLAND','Bouvet Island',NULL,NULL,0),(30,'BR','BRAZIL','Brazil','BRA',76,55),
    (31,'IO','BRITISH INDIAN OCEAN TERRITORY','British Indian Ocean Territory',NULL,NULL,246),(32,'BN','BRUNEI DARUSSALAM','Brunei Darussalam','BRN',96,673),(33,'BG','BULGARIA','Bulgaria','BGR',100,359),(34,'BF','BURKINA FASO','Burkina Faso','BFA',854,226),(35,'BI','BURUNDI','Burundi','BDI',108,257),(36,'KH','CAMBODIA','Cambodia','KHM',116,855),
    (37,'CM','CAMEROON','Cameroon','CMR',120,237),(38,'CA','CANADA','Canada','CAN',124,1),(39,'CV','CAPE VERDE','Cape Verde','CPV',132,238),(40,'KY','CAYMAN ISLANDS','Cayman Islands','CYM',136,1345),(41,'CF','CENTRAL AFRICAN REPUBLIC','Central African Republic','CAF',140,236),(42,'TD','CHAD','Chad','TCD',148,235),
    (43,'CL','CHILE','Chile','CHL',152,56),(44,'CN','CHINA','China','CHN',156,86),(45,'CX','CHRISTMAS ISLAND','Christmas Island',NULL,NULL,61),(46,'CC','COCOS (KEELING) ISLANDS','Cocos (Keeling) Islands',NULL,NULL,672),(47,'CO','COLOMBIA','Colombia','COL',170,57),(48,'KM','COMOROS','Comoros','COM',174,269),
    (49,'CG','CONGO','Congo','COG',178,242),(50,'CD','CONGO, THE DEMOCRATIC REPUBLIC OF THE','Congo, the Democratic Republic of the','COD',180,243),(51,'CK','COOK ISLANDS','Cook Islands','COK',184,682),(52,'CR','COSTA RICA','Costa Rica','CRI',188,506),(53,'CI','COTE D\'IVOIRE','Cote D\'Ivoire','CIV',384,225),(54,'HR','CROATIA','Croatia','HRV',191,385),
    (55,'CU','CUBA','Cuba','CUB',192,53),(56,'CY','CYPRUS','Cyprus','CYP',196,357),(57,'CZ','CZECH REPUBLIC','Czech Republic','CZE',203,420),(58,'DK','DENMARK','Denmark','DNK',208,45),(59,'DJ','DJIBOUTI','Djibouti','DJI',262,253),(60,'DM','DOMINICA','Dominica','DMA',212,1767),
    (61,'DO','DOMINICAN REPUBLIC','Dominican Republic','DOM',214,1809),(62,'EC','ECUADOR','Ecuador','ECU',218,593),(63,'EG','EGYPT','Egypt','EGY',818,20),(64,'SV','EL SALVADOR','El Salvador','SLV',222,503),(65,'GQ','EQUATORIAL GUINEA','Equatorial Guinea','GNQ',226,240),(66,'ER','ERITREA','Eritrea','ERI',232,291),
    (67,'EE','ESTONIA','Estonia','EST',233,372),(68,'ET','ETHIOPIA','Ethiopia','ETH',231,251),(69,'FK','FALKLAND ISLANDS (MALVINAS)','Falkland Islands (Malvinas)','FLK',238,500),(70,'FO','FAROE ISLANDS','Faroe Islands','FRO',234,298),(71,'FJ','FIJI','Fiji','FJI',242,679),(72,'FI','FINLAND','Finland','FIN',246,358),
    (73,'FR','FRANCE','France','FRA',250,33),(74,'GF','FRENCH GUIANA','French Guiana','GUF',254,594),(75,'PF','FRENCH POLYNESIA','French Polynesia','PYF',258,689),(76,'TF','FRENCH SOUTHERN TERRITORIES','French Southern Territories',NULL,NULL,0),(77,'GA','GABON','Gabon','GAB',266,241),(78,'GM','GAMBIA','Gambia','GMB',270,220),
    (79,'GE','GEORGIA','Georgia','GEO',268,995),(80,'DE','GERMANY','Germany','DEU',276,49),(81,'GH','GHANA','Ghana','GHA',288,233),(82,'GI','GIBRALTAR','Gibraltar','GIB',292,350),(83,'GR','GREECE','Greece','GRC',300,30),(84,'GL','GREENLAND','Greenland','GRL',304,299),
    (85,'GD','GRENADA','Grenada','GRD',308,1473),(86,'GP','GUADELOUPE','Guadeloupe','GLP',312,590),(87,'GU','GUAM','Guam','GUM',316,1671),(88,'GT','GUATEMALA','Guatemala','GTM',320,502),(89,'GN','GUINEA','Guinea','GIN',324,224),(90,'GW','GUINEA-BISSAU','Guinea-Bissau','GNB',624,245),
    (91,'GY','GUYANA','Guyana','GUY',328,592),(92,'HT','HAITI','Haiti','HTI',332,509),(93,'HM','HEARD ISLAND AND MCDONALD ISLANDS','Heard Island and Mcdonald Islands',NULL,NULL,0),(94,'VA','HOLY SEE (VATICAN CITY STATE)','Holy See (Vatican City State)','VAT',336,39),(95,'HN','HONDURAS','Honduras','HND',340,504),(96,'HK','HONG KONG','Hong Kong','HKG',344,852),
    (97,'HU','HUNGARY','Hungary','HUN',348,36),(98,'IS','ICELAND','Iceland','ISL',352,354),(99,'IN','INDIA','India','IND',356,91),(100,'ID','INDONESIA','Indonesia','IDN',360,62),(101,'IR','IRAN, ISLAMIC REPUBLIC OF','Iran, Islamic Republic of','IRN',364,98),(102,'IQ','IRAQ','Iraq','IRQ',368,964),
    (103,'IE','IRELAND','Ireland','IRL',372,353),(104,'IL','ISRAEL','Israel','ISR',376,972),(105,'IT','ITALY','Italy','ITA',380,39),(106,'JM','JAMAICA','Jamaica','JAM',388,1876),(107,'JP','JAPAN','Japan','JPN',392,81),(108,'JO','JORDAN','Jordan','JOR',400,962),
    (109,'KZ','KAZAKHSTAN','Kazakhstan','KAZ',398,7),(110,'KE','KENYA','Kenya','KEN',404,254),(111,'KI','KIRIBATI','Kiribati','KIR',296,686),(112,'KP','KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','Korea, Democratic People\'s Republic of','PRK',408,850),(113,'KR','KOREA, REPUBLIC OF','Korea, Republic of','KOR',410,82),(114,'KW','KUWAIT','Kuwait','KWT',414,965),
    (115,'KG','KYRGYZSTAN','Kyrgyzstan','KGZ',417,996),(116,'LA','LAO PEOPLE\'S DEMOCRATIC REPUBLIC','Lao People\'s Democratic Republic','LAO',418,856),(117,'LV','LATVIA','Latvia','LVA',428,371),(118,'LB','LEBANON','Lebanon','LBN',422,961),(119,'LS','LESOTHO','Lesotho','LSO',426,266),(120,'LR','LIBERIA','Liberia','LBR',430,231),
    (121,'LY','LIBYAN ARAB JAMAHIRIYA','Libyan Arab Jamahiriya','LBY',434,218),(122,'LI','LIECHTENSTEIN','Liechtenstein','LIE',438,423),(123,'LT','LITHUANIA','Lithuania','LTU',440,370),(124,'LU','LUXEMBOURG','Luxembourg','LUX',442,352),(125,'MO','MACAO','Macao','MAC',446,853),(126,'MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','Macedonia, the Former Yugoslav Republic of','MKD',807,389),
    (127,'MG','MADAGASCAR','Madagascar','MDG',450,261),(128,'MW','MALAWI','Malawi','MWI',454,265),(129,'MY','MALAYSIA','Malaysia','MYS',458,60),(130,'MV','MALDIVES','Maldives','MDV',462,960),(131,'ML','MALI','Mali','MLI',466,223),(132,'MT','MALTA','Malta','MLT',470,356),
    (133,'MH','MARSHALL ISLANDS','Marshall Islands','MHL',584,692),(134,'MQ','MARTINIQUE','Martinique','MTQ',474,596),(135,'MR','MAURITANIA','Mauritania','MRT',478,222),(136,'MU','MAURITIUS','Mauritius','MUS',480,230),(137,'YT','MAYOTTE','Mayotte',NULL,NULL,269),(138,'MX','MEXICO','Mexico','MEX',484,52),
    (139,'FM','MICRONESIA, FEDERATED STATES OF','Micronesia, Federated States of','FSM',583,691),(140,'MD','MOLDOVA, REPUBLIC OF','Moldova, Republic of','MDA',498,373),(141,'MC','MONACO','Monaco','MCO',492,377),(142,'MN','MONGOLIA','Mongolia','MNG',496,976),(143,'MS','MONTSERRAT','Montserrat','MSR',500,1664),(144,'MA','MOROCCO','Morocco','MAR',504,212),
    (145,'MZ','MOZAMBIQUE','Mozambique','MOZ',508,258),(146,'MM','MYANMAR','Myanmar','MMR',104,95),(147,'NA','NAMIBIA','Namibia','NAM',516,264),(148,'NR','NAURU','Nauru','NRU',520,674),(149,'NP','NEPAL','Nepal','NPL',524,977),(150,'NL','NETHERLANDS','Netherlands','NLD',528,31),
    (151,'AN','NETHERLANDS ANTILLES','Netherlands Antilles','ANT',530,599),(152,'NC','NEW CALEDONIA','New Caledonia','NCL',540,687),(153,'NZ','NEW ZEALAND','New Zealand','NZL',554,64),(154,'NI','NICARAGUA','Nicaragua','NIC',558,505),(155,'NE','NIGER','Niger','NER',562,227),(156,'NG','NIGERIA','Nigeria','NGA',566,234),
    (157,'NU','NIUE','Niue','NIU',570,683),(158,'NF','NORFOLK ISLAND','Norfolk Island','NFK',574,672),(159,'MP','NORTHERN MARIANA ISLANDS','Northern Mariana Islands','MNP',580,1670),(160,'NO','NORWAY','Norway','NOR',578,47),(161,'OM','OMAN','Oman','OMN',512,968),(162,'PK','PAKISTAN','Pakistan','PAK',586,92),
    (163,'PW','PALAU','Palau','PLW',585,680),(164,'PS','PALESTINIAN TERRITORY, OCCUPIED','Palestinian Territory, Occupied',NULL,NULL,970),(165,'PA','PANAMA','Panama','PAN',591,507),(166,'PG','PAPUA NEW GUINEA','Papua New Guinea','PNG',598,675),(167,'PY','PARAGUAY','Paraguay','PRY',600,595),(168,'PE','PERU','Peru','PER',604,51),
    (169,'PH','PHILIPPINES','Philippines','PHL',608,63),(170,'PN','PITCAIRN','Pitcairn','PCN',612,0),(171,'PL','POLAND','Poland','POL',616,48),(172,'PT','PORTUGAL','Portugal','PRT',620,351),(173,'PR','PUERTO RICO','Puerto Rico','PRI',630,1787),(174,'QA','QATAR','Qatar','QAT',634,974),
    (175,'RE','REUNION','Reunion','REU',638,262),(176,'RO','ROMANIA','Romania','ROM',642,40),(177,'RU','RUSSIAN FEDERATION','Russian Federation','RUS',643,7),(178,'RW','RWANDA','Rwanda','RWA',646,250),(179,'SH','SAINT HELENA','Saint Helena','SHN',654,290),(180,'KN','SAINT KITTS AND NEVIS','Saint Kitts and Nevis','KNA',659,1869),
    (181,'LC','SAINT LUCIA','Saint Lucia','LCA',662,1758),(182,'PM','SAINT PIERRE AND MIQUELON','Saint Pierre and Miquelon','SPM',666,508),(183,'VC','SAINT VINCENT AND THE GRENADINES','Saint Vincent and the Grenadines','VCT',670,1784),(184,'WS','SAMOA','Samoa','WSM',882,684),(185,'SM','SAN MARINO','San Marino','SMR',674,378),(186,'ST','SAO TOME AND PRINCIPE','Sao Tome and Principe','STP',678,239),
    (187,'SA','SAUDI ARABIA','Saudi Arabia','SAU',682,966),(188,'SN','SENEGAL','Senegal','SEN',686,221),(190,'SC','SEYCHELLES','Seychelles','SYC',690,248),(191,'SL','SIERRA LEONE','Sierra Leone','SLE',694,232),(192,'SG','SINGAPORE','Singapore','SGP',702,65),(193,'SK','SLOVAKIA','Slovakia','SVK',703,421),
    (194,'SI','SLOVENIA','Slovenia','SVN',705,386),(195,'SB','SOLOMON ISLANDS','Solomon Islands','SLB',90,677),(196,'SO','SOMALIA','Somalia','SOM',706,252),(197,'ZA','SOUTH AFRICA','South Africa','ZAF',710,27),(198,'GS','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','South Georgia and the South Sandwich Islands',NULL,NULL,0),(199,'ES','SPAIN','Spain','ESP',724,34),
    (200,'LK','SRI LANKA','Sri Lanka','LKA',144,94),(201,'SD','SUDAN','Sudan','SDN',736,249),(202,'SR','SURINAME','Suriname','SUR',740,597),(203,'SJ','SVALBARD AND JAN MAYEN','Svalbard and Jan Mayen','SJM',744,47),(204,'SZ','SWAZILAND','Swaziland','SWZ',748,268),(205,'SE','SWEDEN','Sweden','SWE',752,46),
    (206,'CH','SWITZERLAND','Switzerland','CHE',756,41),(207,'SY','SYRIAN ARAB REPUBLIC','Syrian Arab Republic','SYR',760,963),(208,'TW','TAIWAN, PROVINCE OF CHINA','Taiwan, Province of China','TWN',158,886),(209,'TJ','TAJIKISTAN','Tajikistan','TJK',762,992),(210,'TZ','TANZANIA, UNITED REPUBLIC OF','Tanzania, United Republic of','TZA',834,255),(211,'TH','THAILAND','Thailand','THA',764,66),
    (212,'TL','TIMOR-LESTE','Timor-Leste',NULL,NULL,670),(213,'TG','TOGO','Togo','TGO',768,228),(214,'TK','TOKELAU','Tokelau','TKL',772,690),(215,'TO','TONGA','Tonga','TON',776,676),(216,'TT','TRINIDAD AND TOBAGO','Trinidad and Tobago','TTO',780,1868),(217,'TN','TUNISIA','Tunisia','TUN',788,216),
    (218,'TR','TURKEY','Turkey','TUR',792,90),(219,'TM','TURKMENISTAN','Turkmenistan','TKM',795,7370),(220,'TC','TURKS AND CAICOS ISLANDS','Turks and Caicos Islands','TCA',796,1649),(221,'TV','TUVALU','Tuvalu','TUV',798,688),(222,'UG','UGANDA','Uganda','UGA',800,256),(223,'UA','UKRAINE','Ukraine','UKR',804,380),
    (224,'AE','UNITED ARAB EMIRATES','United Arab Emirates','ARE',784,971),(225,'GB','UNITED KINGDOM','United Kingdom','GBR',826,44),(226,'US','UNITED STATES','United States','USA',840,1),(227,'UM','UNITED STATES MINOR OUTLYING ISLANDS','United States Minor Outlying Islands',NULL,NULL,1),(228,'UY','URUGUAY','Uruguay','URY',858,598),(229,'UZ','UZBEKISTAN','Uzbekistan','UZB',860,998),
    (230,'VU','VANUATU','Vanuatu','VUT',548,678),(231,'VE','VENEZUELA','Venezuela','VEN',862,58),(232,'VN','VIET NAM','Viet Nam','VNM',704,84),(233,'VG','VIRGIN ISLANDS, BRITISH','Virgin Islands, British','VGB',92,1284),(234,'VI','VIRGIN ISLANDS, U.S.','Virgin Islands, U.s.','VIR',850,1340),(235,'WF','WALLIS AND FUTUNA','Wallis and Futuna','WLF',876,681),
    (236,'EH','WESTERN SAHARA','Western Sahara','ESH',732,212),(237,'YE','YEMEN','Yemen','YEM',887,967),(238,'ZM','ZAMBIA','Zambia','ZMB',894,260),(239,'ZW','ZIMBABWE','Zimbabwe','ZWE',716,263),(240,'RS','SERBIA','Serbia','SRB',688,381),(241,'AP','ASIA PACIFIC REGION','Asia / Pacific Region','0',0,0),
    (242,'ME','MONTENEGRO','Montenegro','MNE',499,382),(243,'AX','ALAND ISLANDS','Aland Islands','ALA',248,358),(244,'BQ','BONAIRE, SINT EUSTATIUS AND SABA','Bonaire, Sint Eustatius and Saba','BES',535,599),(245,'CW','CURACAO','Curacao','CUW',531,599),(246,'GG','GUERNSEY','Guernsey','GGY',831,44),(247,'IM','ISLE OF MAN','Isle of Man','IMN',833,44),
    (248,'JE','JERSEY','Jersey','JEY',832,44),(249,'XK','KOSOVO','Kosovo','---',0,381),(250,'BL','SAINT BARTHELEMY','Saint Barthelemy','BLM',652,590),(251,'MF','SAINT MARTIN','Saint Martin','MAF',663,590),(252,'SX','SINT MAARTEN','Sint Maarten','SXM',534,1),(253,'SS','SOUTH SUDAN','South Sudan','SSD',728,211);


DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `is_folder` tinyint(1) DEFAULT NULL,
  `parent_folder_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_file_file_idx` (`parent_folder_id`)
) ENGINE=InnoDB;

INSERT INTO `file` VALUES
    (1,'phpunit.xml','xml',0,NULL),(2,'LICENSE','',0,NULL),(3,'Makefile','',0,NULL),(4,'tests','',1,NULL),(5,'TemplateTest.php','php',0,4),(6,'template','',1,NULL),
    (7,'semantic-ui','',1,6),(8,'tree.html','html',0,7),(9,'element.html','html',0,7),(10,'button.html','html',0,7),(11,'icon.html','html',0,7),(12,'element.jade','jade',0,7),
    (13,'tree.jade','jade',0,7),(14,'icon.jade','jade',0,7),(15,'button.jade','jade',0,7),(16,'docs','',1,NULL),(17,'index.rst','rst',0,16),(18,'login.png','png',0,16),
    (19,'requirements.txt','txt',0,16),(20,'crud2.png','png',0,16),(21,'images','',1,16),(22,'folders.png','png',0,21),(23,'layout.png','png',0,21),(24,'menu.png','png',0,21),
    (25,'Makefile','',0,16),(26,'conf.py','py',0,16),(27,'README.md','md',0,16),(28,'quickstart.rst','rst',0,16),(29,'crud.png','png',0,16),(30,'layouts.png','png',0,16),
    (31,'template.rst','rst',0,16),(32,'README.md','md',0,NULL),(33,'demos','',1,NULL),(34,'index.php','php',0,33),(35,'layout.php','php',0,33),(36,'templates','',1,33),
    (37,'fixed.jade','jade',0,36),(38,'layout1.html','html',0,36),(39,'layout2.jade','jade',0,36),(40,'layout1.jade','jade',0,36),(41,'fixed.html','html',0,36),(42,'index1.html','html',0,36),
    (43,'layout2.html','html',0,36),(44,'button.php','php',0,33),(45,'composer.json','json',0,NULL),(46,'src','',1,NULL),(47,'Icon.php','php',0,46),(48,'App.php','php',0,46),
    (49,'Label.php','php',0,46),(50,'Layout','',1,46),(51,'App.php','php',0,50),(52,'MiniApp.php','php',0,46),(53,'Lister.php','php',0,46),(54,'Layout.php','php',0,46),
    (55,'Buttons.php','php',0,46),(56,'View.php','php',0,46),(57,'Tree.php','php',0,46),(58,'Template.php','php',0,46),(59,'Exception.php','php',0,46),(60,'Text.php','php',0,46),
    (61,'Button.php','php',0,46);


DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(100) DEFAULT NULL,
  `project_code` varchar(4) DEFAULT NULL,
  `description` text,
  `client_name` varchar(255) DEFAULT NULL,
  `client_address` text,
  `client_country_iso` char(2) DEFAULT NULL,
  `is_commercial` tinyint(1) DEFAULT NULL,
  `currency` enum('EUR','USD','GBP') DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT NULL,
  `project_budget` decimal(14,2) DEFAULT NULL,
  `project_invoiced` decimal(14,2) DEFAULT NULL,
  `project_paid` decimal(14,2) DEFAULT NULL,
  `project_hour_cost` decimal(14,2) DEFAULT NULL,
  `project_hours_est` int(11) DEFAULT NULL,
  `project_hours_reported` int(11) DEFAULT NULL,
  `project_expenses_est` decimal(14,2) DEFAULT NULL,
  `project_expenses` decimal(14,2) DEFAULT NULL,
  `project_mgmt_cost_pct` float DEFAULT NULL,
  `project_qa_cost_pct` float DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `finish_date` date DEFAULT NULL,
  `finish_time` time DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `stats` (`id`, `project_name`, `project_code`, `description`, `client_name`, `client_address`, `client_country_iso`, `is_commercial`, `currency`, `is_completed`, `project_budget`, `project_invoiced`, `project_paid`, `project_hour_cost`, `project_hours_est`, `project_hours_reported`, `project_expenses_est`, `project_expenses`, `project_mgmt_cost_pct`, `project_qa_cost_pct`, `start_date`, `finish_date`, `finish_time`, `created`, `updated`)
VALUES
    (1,'Agile DSQL','at01','DSQL is a composable SQL query builder. You can write multi-vendor queries in PHP profiting from better security, clean syntax and avoid human errors.','Agile Toolkit','Some Street,\nGarden City\nUK','GB',0,'GBP',1,7000.00,0.00,0.00,0.00,150,125,50.00,0.00,0.1,0.2,'2016-01-26','2016-06-23','12:50:00','2017-04-06 10:34:34','2017-04-06 10:35:04'),
    (2,'Agile Core','at02','Collection of PHP Traits for designing object-oriented frameworks.','Agile Toolkit','Some Street,\nGarden City\nUK','GB',0,'GBP',1,3000.00,0.00,0.00,0.00,70,56,50.00,0.00,0.1,0.2,'2016-04-27','2016-05-21','18:41:00','2017-04-06 10:21:50','2017-04-06 10:35:04'),
    (3,'Agile Data','at03','Agile Data implements an entirely new pattern for data abstraction, that is specifically designed for remote databases such as RDS, Cloud SQL, BigQuery and other distributed data storage architectures. It focuses on reducing number of requests your App have to send to the Database by using more sophisticated queries while also offering full Domain Model mapping and Database vendor abstraction.','Agile Toolkit','Some Street,\nGarden City\nUK','GB',0,'GBP',1,12000.00,0.00,0.00,0.00,300,394,600.00,430.00,0.2,0.3,'2016-04-17','2016-06-20','03:04:00','2017-04-06 10:30:15','2017-04-06 10:35:04'),
    (4,'Agile UI','at04','Web UI Component library.','Agile Toolkit','Some Street,\nGarden City\nUK','GB',0,'GBP',0,20000.00,0.00,0.00,0.00,600,368,1200.00,0.00,0.3,0.4,'2016-09-17',NULL,NULL,'2017-04-06 10:30:15','2017-04-06 10:35:04');

DROP TABLE IF EXISTS `product_category`;
CREATE TABLE `product_category` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(45) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `product_category` (`id`, `name`)
VALUES
    (1,'Condiments and Gravies'),(2,'Beverages'),(3,'Dairy');

DROP TABLE IF EXISTS `product_sub_category`;
CREATE TABLE `product_sub_category` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(45) DEFAULT NULL,
    `product_category_id` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `product_sub_category` (`id`, `name`, `product_category_id`)
VALUES
    (1,'Gravie',1),(2,'Spread',1),(3,'Salad Dressing',1),(4,'Alcoholic',2),(5,'Coffee and Tea',2),(6,'Lowfat Milk',3),(7,'Yogourt',3),(8,'HighFat',3),(9,'Sugar/Sweetened',2);

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(45) DEFAULT NULL,
    `brand` varchar(45) DEFAULT NULL,
    `product_category_id` int(11) NULL,
    `product_sub_category_id` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `product` (`id`, `name`, `brand`, `product_category_id`, `product_sub_category_id`)
VALUES
    (1,'Mustard','Condiment Corp.',1,2),(2,'Ketchup','Condiment Corp.',1,2),(3,'Cola','Beverage Corp.',2,9),(4,'Soda','Beverage Corp.',2,9),(5,'Milk 2%','Milk Corp.',3,8),(6,'Milk 1%','Milk Corp.',3,6),(7,'Ice Cream','Milk Corp.',3,8);
