/*
SQLyog Enterprise v9.50 
MySQL - 5.5.25a-log : Database - jewerlystyle
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `Zero_Action` */

DROP TABLE IF EXISTS `Zero_Action`;

CREATE TABLE `Zero_Action` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Zero_Groups_ID` bigint(20) DEFAULT NULL,
  `Action` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Zero_Section_ID`),
  KEY `Zero_Groups_ID` (`Zero_Groups_ID`),
  KEY `Zero_Section_ID_2` (`Zero_Section_ID`,`Zero_Groups_ID`,`Action`),
  CONSTRAINT `Zero_Action_ibfk_1` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Zero_Action_ibfk_2` FOREIGN KEY (`Zero_Groups_ID`) REFERENCES `Zero_Groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Action` */

/*Table structure for table `Zero_Content` */

DROP TABLE IF EXISTS `Zero_Content`;

CREATE TABLE `Zero_Content` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Zero_Language_ID` bigint(20) NOT NULL DEFAULT '1',
  `Name` varchar(50) DEFAULT NULL,
  `Content` text,
  `Block` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Language_ID` (`Zero_Language_ID`),
  KEY `Zero_Section_ID` (`Zero_Section_ID`),
  CONSTRAINT `Zero_Content_ibfk_3` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `Zero_Content` */

insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (5,NULL,1,'Head','<p>Head</p>','head'),(6,NULL,1,'Footer','<p>Footer</p>','footer'),(10,NULL,2,'Заголовок','<p>Заголовок</p>','head'),(11,NULL,2,'Подвал','<p>Подвал</p>','footer'),(12,NULL,1,NULL,NULL,'ccxv'),(13,NULL,1,NULL,NULL,'bcv'),(14,102,1,'bvcbvcb',NULL,'bvbvcb'),(15,102,1,'bcvbvc',NULL,'bcvbvc');

/*Table structure for table `Zero_Groups` */

DROP TABLE IF EXISTS `Zero_Groups`;

CREATE TABLE `Zero_Groups` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `Status` enum('open','close') NOT NULL DEFAULT 'open',
  `Description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Groups` */

insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (1,'Разработчики','open','bbbb'),(2,'Гости','open',NULL);

/*Table structure for table `Zero_Section` */

DROP TABLE IF EXISTS `Zero_Section`;

CREATE TABLE `Zero_Section` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Url` varchar(100) DEFAULT NULL,
  `UrlThis` varchar(50) NOT NULL,
  `UrlRedirect` varchar(150) DEFAULT NULL,
  `Layout` varchar(100) NOT NULL DEFAULT 'Zero_Content',
  `Controller` varchar(50) DEFAULT NULL,
  `IsAuthorized` enum('no','yes') NOT NULL DEFAULT 'no',
  `IsEnable` enum('yes','no') NOT NULL DEFAULT 'yes',
  `IsVisible` enum('no','yes') NOT NULL DEFAULT 'no',
  `ContentType` enum('html','xml','json','file','img') NOT NULL DEFAULT 'html',
  `Sort` int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Keywords` varchar(250) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Zero_Section_ID`),
  CONSTRAINT `Zero_Section_ibfk_3` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Section` */

insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`ContentType`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (1,NULL,'www/','www',NULL,'Zero_Main','Zero_Content_Page','no','yes','no','html',10,'CMF PhpZero','CMF PhpZero','CMF PhpZero','CMF PhpZero'),(3,33,'www/admin/user','user',NULL,'Zero_Admin','Zero_Content_Page','yes','yes','yes','html',30,'Профиль','Профиль','Профиль','Профиль'),(7,1,'www/captcha','captcha',NULL,'Zero_Content','Zero_Users_Kcaptcha','no','yes','no','img',140,'Капча','Капча','Капча','Капча'),(8,3,'www/admin/user/users','users',NULL,'Zero_Admin','Zero_Users_Grid','yes','yes','yes','html',10,'Пользователи','Пользователи','Пользователи','Пользователи'),(9,8,'www/admin/user/users/edit','edit',NULL,'Zero_Admin','Zero_Users_Edit','yes','yes','no','html',10,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение'),(10,3,'www/admin/user/groups','groups',NULL,'Zero_Admin','Zero_Groups_Grid','yes','yes','yes','html',20,'Группы','Группы','Группы','Группы'),(11,10,'www/admin/user/groups/edit','edit',NULL,'Zero_Admin','Zero_Groups_Edit','yes','yes','no','html',10,'Группы изменение','Группы изменение','Группы изменение','Группы изменение'),(12,33,'www/admin/site','site',NULL,'Zero_Admin','Zero_Content_Page','yes','yes','yes','html',40,'Сайт','Сайт','Сайт','Сайт'),(13,12,'www/admin/site/section','section',NULL,'Zero_Admin','Zero_Section_Grid','yes','yes','yes','html',40,'Разделы','Разделы','Разделы','Разделы'),(14,13,'www/admin/site/section/edit','edit',NULL,'Zero_Admin','Zero_Section_Edit','yes','yes','no','html',10,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение'),(15,11,'www/admin/user/groups/edit/access','access',NULL,'Zero_Admin','Zero_Groups_Access','yes','yes','yes','html',10,'Права доступа','Права доступа','Права доступа','Права доступа'),(16,29,'www/admin/site/section/edit/content/edit','edit',NULL,'Zero_Admin','Zero_Content_EditSection','yes','yes','no','html',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение'),(19,33,'www/admin/system','system',NULL,'Zero_Admin','Zero_Content_Page','yes','yes','yes','html',20,'Система','Система','Система','Система'),(20,19,'www/admin/system/service','service',NULL,'Zero_Admin','Zero_System_GridService','yes','yes','yes','html',20,'Обслуживание','Обслуживание','Обслуживание','Обслуживание'),(21,19,'www/admin/system/file','file',NULL,'Zero_Admin','Zero_System_FileManager','yes','yes','yes','html',10,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер'),(22,21,'www/admin/system/file/edit','edit',NULL,'Zero_Admin','Zero_System_FileEdit','yes','yes','no','html',10,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла'),(29,14,'www/admin/site/section/edit/content','content',NULL,'Zero_Admin','Zero_Content_GridSection','yes','yes','yes','html',10,'Контент','Контент','Контент','Контент'),(31,14,'www/admin/site/section/edit/translation','translation',NULL,'Zero_Admin','Zero_SectionLanguage_GridSection','yes','yes','yes','html',20,'Переводы','Переводы','Переводы','Переводы'),(32,31,'www/admin/site/section/edit/translation/edit','edit',NULL,'Zero_Admin','Zero_SectionLanguage_EditSection','yes','yes','no','html',10,'Переводы изменение','Переводы изменение','Переводы изменение','Переводы изменение'),(33,1,'www/admin','admin',NULL,'Zero_Admin','Zero_Content_Page','yes','yes','yes','html',NULL,'Административный раздел','Административный раздел','Административный раздел','Административный раздел'),(40,12,'www/admin/site/content','content',NULL,'Zero_Admin','Zero_Content_Grid','no','yes','yes','html',10,'Контент','Контент','Контент','Контент'),(41,40,'www/admin/site/content/edit','edit',NULL,'Zero_Admin','Zero_Content_Edit','no','yes','no','html',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение'),(100,1,'www/user','user',NULL,'Zero_Main','Zero_Users_Login','no','yes','yes','html',NULL,'Вход','Пользователь авторизация','Пользователь авторизация','Пользователь авторизация'),(101,100,'www/user/profile','profile',NULL,'Zero_Main','Zero_Users_Profile','yes','yes','yes','html',NULL,'Профиль','Профиль пользователя','Профиль пользователя','Профиль пользователя'),(102,100,'www/user/registration','registration',NULL,'Zero_Main','Zero_Users_Registration','no','yes','no','html',110,'Регистрация','Регистрация','Регистрация','Регистрация');

/*Table structure for table `Zero_SectionLanguage` */

DROP TABLE IF EXISTS `Zero_SectionLanguage`;

CREATE TABLE `Zero_SectionLanguage` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Zero_Language_ID` bigint(20) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Keywords` varchar(250) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Zero_Section_ID` (`Zero_Section_ID`,`Zero_Language_ID`),
  CONSTRAINT `Zero_SectionLanguage_ibfk_1` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_SectionLanguage` */

insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (1,1,2,'CMF PhpZero','CMF PhpZero','CMF PhpZero','CMF PhpZero'),(3,3,2,'Профиль','Профиль','Профиль','Профиль'),(5,102,2,'Регистрация','Регистрация','Регистрация','Регистрация'),(7,7,2,'Капча','Капча','Капча','Капча'),(8,8,2,'Пользователи','Пользователи','Пользователи','Пользователи'),(9,9,2,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение'),(10,10,2,'Группы','Группы','Группы','Группы'),(11,11,2,'Группы изменение','Группы изменение','Группы изменение','Группы изменение'),(12,12,2,'Сайт','Сайт','Сайт','Сайт'),(13,13,2,'Разделы','Разделы','Разделы','Разделы'),(14,14,2,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение'),(15,15,2,'Права доступа','Права доступа','Права доступа','Права доступа'),(16,16,2,'Контент изменение','Контент изменение','Контент изменение','Контент изменение'),(18,19,2,'Система','Система','Система','Система'),(19,20,2,'Обслуживание','Обслуживание','Обслуживание','Обслуживание'),(20,21,2,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер'),(21,22,2,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла'),(27,29,2,'Контент','Контент','Контент','Контент'),(29,31,2,'Переводы','Переводы','Переводы','Переводы'),(30,32,2,'Переводы изменение','Переводы изменение','Переводы изменение','Переводы изменение'),(33,101,2,'Профиль','Профиль пользователя','Профиль пользователя','Профиль пользователя'),(34,33,2,'Административный раздел','Административный раздел','Административный раздел','Административный раздел'),(35,100,2,'Вход','Пользователь авторизация','Пользователь авторизация','Пользователь авторизация'),(36,40,2,'Контент','Контент','Контент','Контент'),(37,41,2,'Контент изменение','Контент изменение','Контент изменение','Контент изменение');

/*Table structure for table `Zero_Users` */

DROP TABLE IF EXISTS `Zero_Users`;

CREATE TABLE `Zero_Users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Groups_ID` bigint(20) DEFAULT NULL,
  `Zero_Users_ID` bigint(20) DEFAULT NULL,
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
  PRIMARY KEY (`ID`),
  KEY `Zero_Groups_ID` (`Zero_Groups_ID`),
  KEY `Zero_Users_ID` (`Zero_Users_ID`),
  CONSTRAINT `Zero_Users_ibfk_1` FOREIGN KEY (`Zero_Groups_ID`) REFERENCES `Zero_Groups` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `Zero_Users_ibfk_2` FOREIGN KEY (`Zero_Users_ID`) REFERENCES `Zero_Users` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Users` */

insert  into `Zero_Users`(`ID`,`Zero_Groups_ID`,`Zero_Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`) values (1,1,NULL,'Разработчик','dev','e77989ed21758e78331b20e477fc5582','open','dev@domain.com','456',NULL,'no',NULL,'yes','2014-02-14 03:30:02','2013-05-01 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
