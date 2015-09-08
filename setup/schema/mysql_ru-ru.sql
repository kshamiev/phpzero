/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.5.41-MariaDB : Database - test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `Action` */

DROP TABLE IF EXISTS `Action`;

CREATE TABLE `Action` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Section_ID` bigint(20) DEFAULT NULL,
  `Groups_ID` bigint(20) DEFAULT NULL,
  `Action` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Section_ID`),
  KEY `Zero_Groups_ID` (`Groups_ID`),
  KEY `Zero_Section_ID_2` (`Section_ID`,`Groups_ID`,`Action`),
  CONSTRAINT `Action_ibfk_1` FOREIGN KEY (`Section_ID`) REFERENCES `Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Action_ibfk_2` FOREIGN KEY (`Groups_ID`) REFERENCES `Groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Action` */

/*Table structure for table `Content` */

DROP TABLE IF EXISTS `Content`;

CREATE TABLE `Content` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Section_ID` bigint(20) DEFAULT NULL,
  `Lang` varchar(10) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Content` text,
  `Block` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Section_ID`),
  CONSTRAINT `Content_ibfk_1` FOREIGN KEY (`Section_ID`) REFERENCES `Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `Content` */

insert  into `Content`(`ID`,`Section_ID`,`Lang`,`Name`,`Content`,`Block`) values (10,NULL,'ru-ru','Заголовок','<p>Заголовок</p>','head'),(11,NULL,'ru-ru','Подвал','<p>Подвал</p>','footer');

/*Table structure for table `Groups` */

DROP TABLE IF EXISTS `Groups`;

CREATE TABLE `Groups` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `Status` enum('open','close') NOT NULL DEFAULT 'open',
  `Description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Groups` */

insert  into `Groups`(`ID`,`Name`,`Status`,`Description`) values (1,'Разработчики','open',NULL),(2,'Гости','open',NULL),(3,'Пользователи','open',NULL),(4,'Администратор','open',NULL);

/*Table structure for table `Section` */

DROP TABLE IF EXISTS `Section`;

CREATE TABLE `Section` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Section_ID` bigint(20) DEFAULT NULL,
  `Url` varchar(150) DEFAULT NULL,
  `UrlThis` varchar(50) NOT NULL,
  `UrlRedirect` varchar(150) DEFAULT NULL,
  `Layout` varchar(100) DEFAULT NULL,
  `Controller` varchar(50) DEFAULT NULL,
  `IsAuthorized` enum('no','yes') NOT NULL DEFAULT 'no',
  `IsEnable` enum('yes','no') NOT NULL DEFAULT 'yes',
  `IsVisible` enum('no','yes') NOT NULL DEFAULT 'no',
  `IsIndex` enum('yes','no') DEFAULT 'yes',
  `Sort` int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Keywords` varchar(250) DEFAULT NULL,
  `Description` text,
  `Content` text,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Section_ID`),
  CONSTRAINT `Section_ibfk_1` FOREIGN KEY (`Section_ID`) REFERENCES `Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Section` */

insert  into `Section`(`ID`,`Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (1,NULL,'/','www',NULL,'Zero_Content','Zero_Section_Page','no','yes','no','yes',10,'PhpZero','PhpZero','PhpZero','PhpZero','<p style=\"text-align:center\"><a href=\"/user\">Вход</a>&nbsp;&nbsp; <a href=\"/admin\">Admin</a></p>'),(2,1,'/user','user',NULL,'Zero_Content','Zero_Users_Login','no','yes','no','no',10,'Пользователь вход','Пользователь вход','Пользователь вход','Пользователь вход',NULL),(3,33,'/admin/user','user',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',30,'Пользователи','Пользователи','Пользователи','Пользователи',NULL),(4,2,'/user/logout','logout',NULL,NULL,'Zero_Users_Login','no','yes','no','no',10,'Пользователь выход','Пользователь выход','Пользователь выход','Пользователь выход',NULL),(5,2,'/user/captcha','captcha',NULL,NULL,'Zero_Users_Kcaptcha','no','yes','no','yes',50,'Капча','Капча','Капча','Капча',NULL),(8,3,'/admin/user/users','users',NULL,'Zero_Main','Zero_Users_Grid','yes','yes','yes','yes',10,'Пользователи','Пользователи','Пользователи','Пользователи',NULL),(9,8,'/admin/user/users/edit','edit',NULL,'Zero_Main','Zero_Users_Edit','yes','yes','no','yes',10,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение',NULL),(10,3,'/admin/user/groups','groups',NULL,'Zero_Main','Zero_Groups_Grid','yes','yes','yes','yes',20,'Группы','Группы','Группы','Группы',NULL),(11,10,'/admin/user/groups/edit','edit',NULL,'Zero_Main','Zero_Groups_Edit','yes','yes','no','yes',10,'Группы изменение','Группы изменение','Группы изменение','Группы изменение',NULL),(12,33,'/admin/site','site',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',40,'Сайт','Сайт','Сайт','Сайт',NULL),(13,12,'/admin/site/section','section',NULL,'Zero_Main','Zero_Section_Grid','yes','yes','yes','yes',40,'Разделы','Разделы','Разделы','Разделы',NULL),(14,13,'/admin/site/section/edit','edit',NULL,'Zero_Main','Zero_Section_Edit','yes','yes','no','yes',10,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение',NULL),(15,11,'/admin/user/groups/edit/access','access',NULL,'Zero_Main','Zero_Groups_Access','yes','yes','yes','yes',10,'Права доступа','Права доступа','Права доступа','Права доступа',NULL),(16,29,'/admin/site/section/edit/content/edit','edit',NULL,'Zero_Main','Zero_Content_EditSection','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL),(19,33,'/admin/system','system',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',20,'Система','Система','Система','Система',NULL),(20,19,'/admin/system/service','service',NULL,'Zero_Main','Zero_System_GridService','yes','yes','yes','yes',20,'Обслуживание','Обслуживание','Обслуживание','Обслуживание',NULL),(21,19,'/admin/system/file','file',NULL,'Zero_Main','Zero_System_FileManager','yes','yes','yes','yes',10,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер',NULL),(22,21,'/admin/system/file/edit','edit',NULL,'Zero_Main','Zero_System_FileEdit','yes','yes','no','yes',10,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла',NULL),(29,14,'/admin/site/section/edit/content','content',NULL,'Zero_Main','Zero_Content_GridSection','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент',NULL),(33,1,'/admin','admin',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',800,'CP','Административная часть','Административная часть','Административная часть',NULL),(40,12,'/admin/site/content','content',NULL,'Zero_Main','Zero_Content_Grid','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент',NULL),(41,40,'/admin/site/content/edit','edit',NULL,'Zero_Main','Zero_Content_Edit','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL),(42,2,'/user/profile','profile',NULL,'Zero_Main','Zero_Users_Profile','yes','yes','no','no',50,'Профиль','Профиль','Профиль','Профиль',NULL);

/*Table structure for table `Users` */

DROP TABLE IF EXISTS `Users`;

CREATE TABLE `Users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Groups_ID` bigint(20) DEFAULT NULL,
  `Users_ID` bigint(20) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Login` varchar(50) NOT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `IsAccess` enum('open','close') NOT NULL DEFAULT 'open',
  `Email` varchar(50) NOT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `Skype` varchar(50) DEFAULT NULL,
  `IsCondition` enum('yes','no') NOT NULL DEFAULT 'yes',
  `ImgAvatar` varchar(150) DEFAULT NULL,
  `IsOnline` enum('no','yes') NOT NULL DEFAULT 'no',
  `DateOnline` datetime DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `Token` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Groups_ID` (`Groups_ID`),
  KEY `Zero_Users_ID` (`Users_ID`),
  CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`Groups_ID`) REFERENCES `Groups` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `Users_ibfk_2` FOREIGN KEY (`Users_ID`) REFERENCES `Users` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Users` */

insert  into `Users`(`ID`,`Groups_ID`,`Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`,`Address`,`Token`) values (1,1,NULL,'Разработчик','dev','e77989ed21758e78331b20e477fc5582','open','test@test.ru',NULL,NULL,'no',NULL,'yes','2015-05-11 21:41:09',NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
