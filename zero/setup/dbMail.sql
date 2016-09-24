/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.5.25a-log : Database - coral
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `MailQueue` */

DROP TABLE IF EXISTS `MailQueue`;

CREATE TABLE `MailQueue` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `Name` varchar(50) DEFAULT NULL COMMENT 'Название сообщения',
  `Description` varchar(250) DEFAULT NULL COMMENT 'Описние сообщения',
  `Content` text COMMENT 'Данные сообщения для отправки',
  `Date` datetime DEFAULT NULL COMMENT 'Дата создания сообщения',
  `DateSend` datetime DEFAULT NULL COMMENT 'Дата отправки сообщения',
  `RetryCnt` tinyint(4) DEFAULT NULL COMMENT 'Количество попыток отправки',
  `Method` varchar(50) DEFAULT NULL COMMENT 'Метод отправки',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COMMENT='Сервис отправки почтовых сообщений';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
