# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.19)
# Database: edensnake
# Generation Time: 2018-08-31 05:10:39 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table false_answers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `false_answers`;

CREATE TABLE `false_answers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `riddle_answer_key` int(4) DEFAULT NULL,
  `riddle_answer` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `false_answers` WRITE;
/*!40000 ALTER TABLE `false_answers` DISABLE KEYS */;

INSERT INTO `false_answers` (`id`, `riddle_answer_key`, `riddle_answer`)
VALUES
	(1,111,'fire extinguisher'),
	(2,222,'water fountain'),
	(3,333,'painting');

/*!40000 ALTER TABLE `false_answers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table riddles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `riddles`;

CREATE TABLE `riddles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `riddle` text CHARACTER SET latin1,
  `day` int(11) DEFAULT NULL,
  `riddle_question_key` int(4) DEFAULT NULL,
  `riddle_answer_key` int(4) DEFAULT NULL,
  `riddle_answer` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  `quote_english` text CHARACTER SET latin1,
  `quote_hebrew` varchar(128) DEFAULT '',
  `qr_style` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `riddles` WRITE;
/*!40000 ALTER TABLE `riddles` DISABLE KEYS */;

INSERT INTO `riddles` (`id`, `riddle`, `day`, `riddle_question_key`, `riddle_answer_key`, `riddle_answer`, `quote_english`, `quote_hebrew`, `qr_style`)
VALUES
	(1,'Launching pad of mighty spark,<br/>\nWith power to destroy the dark.\n',1,6753,481,'lightswitch','and God separated between the light and between the darkness','וַיַּבְדֵּ֣ל אֱלֹהִ֔ים בֵּ֥ין הָא֖וֹר וּבֵ֥ין הַחֽשֶׁךְ','main'),
	(2,'A streaming service on-demand<br/>Used to purify your hands.',2,1341,891,'sink','and let it be a separation between water and water','וִיהִ֣י מַבְדִּ֔יל בֵּ֥ין מַ֖יִם לָמָֽיִם','main'),
	(3,'Cut me and I will not bleed;<br/>Freeze me and I will not leave.',3,8678,135,'tree','Let the earth sprout vegetation, seed yielding herbs and fruit trees','תַּדְשֵׁ֤א הָאָ֨רֶץ֙ דֶּ֗שֶׁא עֵ֚שֶׂב מַזְרִ֣יעַ זֶ֔רַע עֵ֣ץ פְּרִ֞י','main'),
	(4,'Bright but not clever,<br/>Visit me never.',4,9632,201,'stars','the great luminary to rule the day and the lesser luminary to rule the night, and the stars','אֶת־הַמָּא֤וֹר הַגָּדֹל֙ לְמֶמְשֶׁ֣לֶת הַיּ֔וֹם וְאֶת־הַמָּא֤וֹר הַקָּטֹן֙ לְמֶמְשֶׁ֣לֶת הַלַּ֔יְלָה וְאֵ֖ת הַכּֽוֹכָבִֽים','main'),
	(5,'Never thirsty, ever drinking,<br/>All in mail, never clinking.',5,2645,874,'fish','Let the waters swarm a swarming of living creatures, and let fowl fly over the earth','יִשְׁרְצ֣וּ הַמַּ֔יִם שֶׁ֖רֶץ נֶ֣פֶשׁ חַיָּ֑ה וְעוֹף֙ יְעוֹפֵ֣ף עַל־הָאָ֔רֶץ','main'),
	(6,'Someone to talk to when you are alone,<br/>But you\'ll never get through<br/>&nbsp;&nbsp;&nbsp;when you call on your phone.',6,4562,512,'me','Let us make man in our image, after our likeness','נַֽעֲשֶׂ֥ה אָדָ֛ם בְּצַלְמֵ֖נוּ כִּדְמוּתֵ֑נוּ','selfie'),
	(7,'Not dry with wine,<br/>Held high with shine.',7,5581,315,'kiddush cup','And God blessed the seventh day and He hallowed it','וַיְבָ֤רֶךְ אֱלֹהִים֙ אֶת־י֣וֹם הַשְּׁבִיעִ֔י וַיְקַדֵּ֖שׁ אֹת֑וֹ','main');

/*!40000 ALTER TABLE `riddles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_riddles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_riddles`;

CREATE TABLE `user_riddles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `day_1_proposed_riddle_id` int(11) DEFAULT NULL,
  `day_2_proposed_riddle_id` int(11) DEFAULT NULL,
  `day_3_proposed_riddle_id` int(11) DEFAULT NULL,
  `day_4_proposed_riddle_id` int(11) DEFAULT NULL,
  `day_5_proposed_riddle_id` int(11) DEFAULT NULL,
  `day_6_proposed_riddle_id` int(11) DEFAULT NULL,
  `day_7_proposed_riddle_id` int(11) DEFAULT NULL,
  `next_day` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user_riddles` WRITE;
/*!40000 ALTER TABLE `user_riddles` DISABLE KEYS */;

INSERT INTO `user_riddles` (`id`, `user_id`, `day_1_proposed_riddle_id`, `day_2_proposed_riddle_id`, `day_3_proposed_riddle_id`, `day_4_proposed_riddle_id`, `day_5_proposed_riddle_id`, `day_6_proposed_riddle_id`, `day_7_proposed_riddle_id`, `next_day`)
VALUES
	(1,1,NULL,2,3,NULL,NULL,NULL,NULL,4);

/*!40000 ALTER TABLE `user_riddles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_title` varchar(11) NOT NULL,
  `first_name` varchar(11) NOT NULL DEFAULT '',
  `last_name` varchar(11) NOT NULL,
  `username` varchar(11) NOT NULL DEFAULT '',
  `password` varchar(11) NOT NULL DEFAULT '',
  `capture_style` varchar(11) DEFAULT NULL,
  `hint_level` varchar(11) DEFAULT NULL,
  `group_7thgrade` tinyint(4) DEFAULT NULL,
  `group_family` tinyint(4) DEFAULT NULL,
  `group_yha_parent_pals` tinyint(4) DEFAULT NULL,
  `creation_start_time` timestamp NULL DEFAULT NULL,
  `creation_finish_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name_title`, `first_name`, `last_name`, `username`, `password`, `capture_style`, `hint_level`, `group_7thgrade`, `group_family`, `group_yha_parent_pals`, `creation_start_time`, `creation_finish_time`)
VALUES
	(1,'','Alan','Karben','alank14','nebrak','text','none',NULL,NULL,NULL,NULL,NULL),
	(3,'','David','Gershov','dgershov','david','text','none',NULL,NULL,NULL,NULL,NULL),
	(89,'','Coby','Karben','coby13','nebrak','text','none',NULL,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
