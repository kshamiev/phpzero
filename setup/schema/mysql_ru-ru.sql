/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.5.25a-log : Database - phpzero
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Action` */

LOCK TABLES `Zero_Action` WRITE;

UNLOCK TABLES;

/*Table structure for table `Zero_Content` */

DROP TABLE IF EXISTS `Zero_Content`;

CREATE TABLE `Zero_Content` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Lang` varchar(10) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Title` varchar(50) DEFAULT NULL,
  `Keywords` varchar(100) DEFAULT NULL,
  `Description` varchar(300) DEFAULT NULL,
  `Content` text,
  `Block` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Zero_Section_ID`),
  CONSTRAINT `Zero_Content_ibfk_3` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `Zero_Content` */

LOCK TABLES `Zero_Content` WRITE;

insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Lang`,`Name`,`Title`,`Keywords`,`Description`,`Content`,`Block`) values (10,NULL,'ru-ru','Заголовок',NULL,NULL,NULL,'<p>Заголовок</p>','head');
insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Lang`,`Name`,`Title`,`Keywords`,`Description`,`Content`,`Block`) values (11,NULL,'ru-ru','Подвал',NULL,NULL,NULL,'<p>Подвал</p>','footer');

UNLOCK TABLES;

/*Table structure for table `Zero_Groups` */

DROP TABLE IF EXISTS `Zero_Groups`;

CREATE TABLE `Zero_Groups` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `Status` enum('open','close') NOT NULL DEFAULT 'open',
  `Description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Groups` */

LOCK TABLES `Zero_Groups` WRITE;

insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (1,'Разработчики','open',NULL);
insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (2,'Гости','open',NULL);
insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (3,'Пользователи','open',NULL);
insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (4,'Администратор','open',NULL);

UNLOCK TABLES;

/*Table structure for table `Zero_Section` */

DROP TABLE IF EXISTS `Zero_Section`;

CREATE TABLE `Zero_Section` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
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
  KEY `Zero_Section_ID` (`Zero_Section_ID`),
  CONSTRAINT `Zero_Section_ibfk_3` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1144 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Section` */

LOCK TABLES `Zero_Section` WRITE;

insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (1,NULL,'www/','www',NULL,'Zero_Content','Zero_Section_Page','no','yes','no','yes',10,'PhpZero','PhpZero','PhpZero','PhpZero','<p style=\"text-align:center\"><a href=\"/user\">Вход</a>&nbsp;&nbsp; <a href=\"/admin\">Admin</a></p>');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (2,1,'www/user','user',NULL,'Zero_Content','Zero_Users_Login','no','yes','no','no',10,'Пользователь вход','Пользователь вход','Пользователь вход','Пользователь вход',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (3,33,'www/admin/user','user',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',30,'Пользователи','Пользователи','Пользователи','Пользователи',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (4,2,'www/user/logout','logout',NULL,NULL,'Zero_Users_Login','no','yes','no','no',10,'Пользователь выход','Пользователь выход','Пользователь выход','Пользователь выход',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (5,3,'www/admin/user/captcha','captcha',NULL,NULL,'Zero_Users_Kcaptcha','no','yes','no','yes',50,'Капча','Капча','Капча','Капча',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (8,3,'www/admin/user/users','users',NULL,'Zero_Main','Zero_Users_Grid','yes','yes','yes','yes',10,'Пользователи','Пользователи','Пользователи','Пользователи',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (9,8,'www/admin/user/users/edit','edit',NULL,'Zero_Main','Zero_Users_Edit','yes','yes','no','yes',10,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (10,3,'www/admin/user/groups','groups',NULL,'Zero_Main','Zero_Groups_Grid','yes','yes','yes','yes',20,'Группы','Группы','Группы','Группы',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (11,10,'www/admin/user/groups/edit','edit',NULL,'Zero_Main','Zero_Groups_Edit','yes','yes','no','yes',10,'Группы изменение','Группы изменение','Группы изменение','Группы изменение',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (12,33,'www/admin/site','site',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',40,'Сайт','Сайт','Сайт','Сайт',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (13,12,'www/admin/site/section','section',NULL,'Zero_Main','Zero_Section_Grid','yes','yes','yes','yes',40,'Разделы','Разделы','Разделы','Разделы',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (14,13,'www/admin/site/section/edit','edit',NULL,'Zero_Main','Zero_Section_Edit','yes','yes','no','yes',10,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (15,11,'www/admin/user/groups/edit/access','access',NULL,'Zero_Main','Zero_Groups_Access','yes','yes','yes','yes',10,'Права доступа','Права доступа','Права доступа','Права доступа',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (16,29,'www/admin/site/section/edit/content/edit','edit',NULL,'Zero_Main','Zero_Content_EditSection','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (19,33,'www/admin/system','system',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',20,'Система','Система','Система','Система',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (20,19,'www/admin/system/service','service',NULL,'Zero_Main','Zero_System_GridService','yes','yes','yes','yes',20,'Обслуживание','Обслуживание','Обслуживание','Обслуживание',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (21,19,'www/admin/system/file','file',NULL,'Zero_Main','Zero_System_FileManager','yes','yes','yes','yes',10,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (22,21,'www/admin/system/file/edit','edit',NULL,'Zero_Main','Zero_System_FileEdit','yes','yes','no','yes',10,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (29,14,'www/admin/site/section/edit/content','content',NULL,'Zero_Main','Zero_Content_GridSection','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (33,1,'www/admin','admin',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',800,'CP','Административная часть','Административная часть','Административная часть',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (40,12,'www/admin/site/content','content',NULL,'Zero_Main','Zero_Content_Grid','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент',NULL);
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`Title`,`Keywords`,`Description`,`Content`) values (41,40,'www/admin/site/content/edit','edit',NULL,'Zero_Main','Zero_Content_Edit','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL);

UNLOCK TABLES;

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
  `Address` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Groups_ID` (`Zero_Groups_ID`),
  KEY `Zero_Users_ID` (`Zero_Users_ID`),
  CONSTRAINT `Zero_Users_ibfk_1` FOREIGN KEY (`Zero_Groups_ID`) REFERENCES `Zero_Groups` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `Zero_Users_ibfk_2` FOREIGN KEY (`Zero_Users_ID`) REFERENCES `Zero_Users` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Users` */

LOCK TABLES `Zero_Users` WRITE;

insert  into `Zero_Users`(`ID`,`Zero_Groups_ID`,`Zero_Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`,`Address`) values (1,1,NULL,'Разработчик','dev','e77989ed21758e78331b20e477fc5582','open','test@test.ru',NULL,NULL,'no',NULL,'yes','2014-10-22 00:33:37',NULL,NULL);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
