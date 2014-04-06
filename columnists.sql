# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.33)
# Database: lebanese_blogs
# Generation Time: 2013-12-04 20:26:13 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table columnists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `columnists`;

CREATE TABLE `columnists` (
  `col_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `col_name` varchar(250) DEFAULT NULL,
  `col_media_source` varchar(250) DEFAULT NULL,
  `col_home_page` varchar(250) DEFAULT NULL,
  `col_shorthand` varchar(20) DEFAULT NULL,
  `col_media_source_shorthand` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `col_shorthand` (`col_shorthand`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `columnists` WRITE;
/*!40000 ALTER TABLE `columnists` DISABLE KEYS */;

INSERT INTO `columnists` (`col_id`, `col_name`, `col_media_source`, `col_home_page`, `col_shorthand`, `col_media_source_shorthand`)
VALUES
	(1,'Michael Young','The Daily Star','http://dailystar.com.lb/Michael-Young.ashx#axzz2lGySus7D','m_young_ds','daily_star'),
	(2,'Rami G Khouri','The Daily Star','http://www.dailystar.com.lb/Rami-G-Khouri.ashx#axzz2mEhnis1W','r_khouri_ds','daily_star'),
	(3,'Hanin Ghaddar','Now Lebanon','https://now.mmedia.me/lb/en/Author/Hanin.Ghadar','h_ghaddar_nl','now_lebanon'),
	(4,'Joumnana Haddad','Now Lebanon','https://now.mmedia.me/lb/en/Author/Joumana.Haddad','j_haddad_nl','now_lebanon'),
	(5,'Ibrahim al-Amin','Al-Akhbar English','http://english.al-akhbar.com/node/125','i_amin_akh','al_akhbar'),
	(6,'Michael Karam','The National','http://www.thenational.ae/apps/pbcs.dll/search?q=*&NavigatorFilter=[Byline:Michael%20Karam]&BuildNavigators=1','m_karam_tn','the_national'),
	(7,'Michael Young','The National','http://www.thenational.ae/apps/pbcs.dll/search?q=*&NavigatorFilter=[Byline:Michael%20Young]&BuildNavigators=1','m_young_tn','the_national');

/*!40000 ALTER TABLE `columnists` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
