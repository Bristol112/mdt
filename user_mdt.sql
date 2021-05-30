USE `qbus`;

CREATE TABLE `user_mdt` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`char_id` varchar(48) DEFAULT NULL,
	`notes` varchar(255) DEFAULT NULL,
	`mugshot_url` varchar(255) DEFAULT NULL,
	`fingerprint` varchar(255) DEFAULT NULL,

	PRIMARY KEY (`id`)
);

CREATE TABLE `user_convictions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`char_id` varchar(48) DEFAULT NULL,
	`offense` varchar(255) DEFAULT NULL,
	`count` int(11) DEFAULT NULL,
	
	PRIMARY KEY (`id`)
);

CREATE TABLE `mdt_reports` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`char_id` varchar(48) DEFAULT NULL,
	`title` varchar(255) DEFAULT NULL,
	`incident` longtext DEFAULT NULL,
    `charges` longtext DEFAULT NULL,
    `author` varchar(255) DEFAULT NULL,
	`name` varchar(255) DEFAULT NULL,
    `date` varchar(255) DEFAULT NULL,
    `jailtime` int(11) DEFAULT NULL,

	PRIMARY KEY (`id`)
);

CREATE TABLE `mdt_warrants` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	`char_id` varchar(48) DEFAULT NULL,
	`report_id` int(11) DEFAULT NULL,
	`report_title` varchar(255) DEFAULT NULL,
	`charges` longtext DEFAULT NULL,
	`date` varchar(255) DEFAULT NULL,
	`expire` varchar(255) DEFAULT NULL,
	`notes` varchar(255) DEFAULT NULL,
	`author` varchar(255) DEFAULT NULL,

	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `fine_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `jailtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


INSERT INTO `fine_types` (`id`, `label`, `amount`, `category`, `jailtime`) VALUES
	(53, 'Misuse of a horn', 30, 0, 1),
	(54, 'Illegally Crossing a continuous Line', 40, 0, 1),
	(55, 'Driving on the wrong side of the road', 250, 0, 100),
	(56, 'Illegal U-Turn', 250, 0, 100),
	(57, 'Illegally Driving Off-road', 170, 0, 100),
	(58, 'Refusing a Lawful Command', 30, 0, 1),
	(59, 'Illegally Stoped of a Vehicle', 150, 0, 100),
	(60, 'Illegal Parking', 70, 0, 1),
	(61, 'Failing to Yield to the right', 70, 0, 1),
	(62, 'Failure to comply with Vehicle Information', 90, 0, 1),
	(63, 'Failing to stop at a Stop Sign ', 105, 0, 100),
	(64, 'Failing to stop at a Red Light', 130, 0, 100),
	(65, 'Illegal Passing', 100, 0, 100),
	(66, 'Driving an illegal Vehicle', 100, 0, 100),
	(67, 'Driving without a License', 1500, 0, 100),
	(68, 'Hit and Run', 800, 0, 100),
	(69, 'Exceeding Speeds Over < 5 mph', 90, 0, 1),
	(70, 'Exceeding Speeds Over 5-15 mph', 120, 0, 100),
	(71, 'Exceeding Speeds Over 15-30 mph', 180, 0, 100),
	(72, 'Exceeding Speeds Over > 30 mph', 300, 0, 100),
	(73, 'Impeding traffic flow', 110, 1, 100),
	(74, 'Public Intoxication', 90, 1, 100),
	(75, 'Disorderly conduct', 90, 1, 100),
	(76, 'Obstruction of Justice', 130, 1, 100),
	(77, 'Insults towards Civilans', 75, 1, 1),
	(78, 'Disrespecting of an LEO', 110, 1, 100),
	(79, 'Verbal Threat towards a Civilan', 90, 1, 100),
	(80, 'Verbal Threat towards an LEO', 150, 1, 100),
	(81, 'Providing False Information', 250, 1, 100),
	(82, 'Attempt of Corruption', 1500, 1, 100),
	(83, 'Brandishing a weapon in city Limits', 120, 2, 100),
	(84, 'Brandishing a Lethal Weapon in city Limits', 300, 2, 100),
	(85, 'No Firearms License', 600, 2, 100),
	(86, 'Possession of an Illegal Weapon', 700, 2, 100),
	(87, 'Possession of Burglary Tools', 300, 2, 100),
	(88, 'Grand Theft Auto', 1800, 2, 100),
	(89, 'Intent to Sell/Distrube of an illegal Substance', 1500, 2, 100),
	(90, 'Frabrication of an Illegal Substance', 1500, 2, 100),
	(91, 'Possession of an Illegal Substance ', 650, 2, 100),
	(92, 'Kidnapping of a Civilan', 1500, 2, 100),
	(93, 'Kidnapping of an LEO', 2000, 2, 100),
	(94, 'Robbery', 650, 2, 100),
	(95, 'Armed Robbery of a Store', 650, 2, 100),
	(96, 'Armed Robbery of a Bank', 1500, 2, 100),
	(97, 'Assault on a Civilian', 2000, 3, 100),
	(98, 'Assault of an LEO', 2500, 3, 100),
	(99, 'Attempt of Murder of a Civilian', 3000, 3, 100),
	(100, 'Attempt of Murder of an LEO', 5000, 3, 100),
	(101, 'Murder of a Civilian', 10000, 3, 100),
	(102, 'Murder of an LEO', 30000, 3, 100),
	(103, 'Involuntary manslaughter', 1800, 3, 100),
	(104, 'Fraud', 2000, 2, 100);
