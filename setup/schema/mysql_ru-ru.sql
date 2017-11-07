/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.25a-log : Database - test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `AccessOutside` */

DROP TABLE IF EXISTS `AccessOutside`;

CREATE TABLE `AccessOutside` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `Name` varchar(100) DEFAULT NULL COMMENT 'Комментарий записи',
  `AccessMethod` varchar(100) DEFAULT NULL COMMENT 'Имя метода в классе',
  `Url` varchar(100) DEFAULT NULL COMMENT 'Урл запроса к ресурсу',
  `Login` varchar(100) DEFAULT NULL COMMENT 'Логин',
  `Password` varchar(100) DEFAULT NULL COMMENT 'Пароль',
  `AuthUserToken` varchar(100) DEFAULT NULL COMMENT 'Авторизация по токену',
  `IsDebug` tinyint(1) DEFAULT NULL COMMENT 'Режим отладки',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Настройки для запросов к внешним ресурсам';

/*Data for the table `AccessOutside` */

/*Table structure for table `Action` */

DROP TABLE IF EXISTS `Action`;

CREATE TABLE `Action` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Section_ID` bigint(20) DEFAULT NULL,
  `Controllers_ID` bigint(20) DEFAULT NULL,
  `Groups_ID` bigint(20) DEFAULT NULL,
  `Action` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Section_ID`),
  KEY `Zero_Groups_ID` (`Groups_ID`),
  KEY `Controllers_ID` (`Controllers_ID`),
  CONSTRAINT `Action_ibfk_1` FOREIGN KEY (`Section_ID`) REFERENCES `Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Action_ibfk_2` FOREIGN KEY (`Groups_ID`) REFERENCES `Groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Action_ibfk_3` FOREIGN KEY (`Controllers_ID`) REFERENCES `Controllers` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `Target` enum('TopLeft') DEFAULT NULL,
  `Block` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Section_ID`),
  CONSTRAINT `Content_ibfk_1` FOREIGN KEY (`Section_ID`) REFERENCES `Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `Content` */

insert  into `Content`(`ID`,`Section_ID`,`Lang`,`Name`,`Content`,`Target`,`Block`) values (10,NULL,'ru-ru','Заголовок','<p>Заголовок</p>',NULL,'head');
insert  into `Content`(`ID`,`Section_ID`,`Lang`,`Name`,`Content`,`Target`,`Block`) values (11,NULL,'ru-ru','Подвал','<p>Подвал</p>',NULL,'footer');

/*Table structure for table `Controllers` */

DROP TABLE IF EXISTS `Controllers`;

CREATE TABLE `Controllers` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `Name` varchar(100) NOT NULL COMMENT 'Название контроллера',
  `Controller` varchar(100) NOT NULL COMMENT 'Контроллер',
  `Typ` enum('Web','Api','Console') NOT NULL DEFAULT 'Web' COMMENT 'Тип контроллера',
  `Url` varchar(100) DEFAULT NULL COMMENT 'Урл для API контроллеров',
  `Minute` varchar(100) DEFAULT NULL COMMENT 'Минуты',
  `Hour` varchar(100) DEFAULT NULL COMMENT 'Часы',
  `Day` varchar(100) DEFAULT NULL COMMENT 'Дни',
  `Month` varchar(100) DEFAULT NULL COMMENT 'Месяцы',
  `Week` varchar(100) DEFAULT NULL COMMENT 'День недели',
  `IsActive` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Контроллер активен',
  `IsAuthorized` enum('no','yes') NOT NULL DEFAULT 'no' COMMENT 'Авторизованный',
  `DateExecute` datetime DEFAULT NULL COMMENT 'Дата и время последнего выполнения',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Url` (`Url`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='Контроллеры';

/*Data for the table `Controllers` */

insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (1,'Zero_Section_Page','Zero_Section_Page','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (2,'Zero_Users_Login','Zero_Users_Login','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (5,'Zero_Users_Kcaptcha','Zero_Users_Kcaptcha','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (6,'Zero_Users_Grid','Zero_Users_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (7,'Zero_Users_Edit','Zero_Users_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (8,'Zero_Groups_Grid','Zero_Groups_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (9,'Zero_Groups_Edit','Zero_Groups_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (11,'Zero_Section_Grid','Zero_Section_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (12,'Zero_Section_Edit','Zero_Section_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (13,'Zero_Groups_Access','Zero_Groups_Access','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (14,'Zero_Content_EditSection','Zero_Content_EditSection','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (16,'Zero_System_GridService','Zero_System_GridService','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (17,'Zero_System_FileManager','Zero_System_FileManager','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (18,'Zero_System_FileEdit','Zero_System_FileEdit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (19,'Zero_Content_GridSection','Zero_Content_GridSection','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (21,'Zero_Content_Grid','Zero_Content_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (22,'Zero_Content_Edit','Zero_Content_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (23,'Zero_Users_Profile','Zero_Users_Profile','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (24,'Zero_System_Api_Upload','Zero_System_Api_Upload','Api','/api/v1/zero/system/upload',NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (27,'Helper_Mail_Api_Send','Helper_Mail_Api_Send','Api','/api/v1/mail/send',NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (29,'Helper_Mail_Api_Queue','Helper_Mail_Api_Queue','Api','/api/v1/mail/queue',NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (31,'Zero_System_Console_ApiGen','Zero_System_Console_ApiGen','Console',NULL,'*/10','*','*','*','*',0,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (32,'Zero_System_Console_RemTmpFileUpload','Zero_System_Console_RemTmpFileUpload','Console',NULL,'0','*','*','*','*',0,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (33,'Zero_Section_Console_SiteMap','Zero_Section_Console_SiteMap','Console',NULL,'0','0','*','*','*',0,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (34,'Zero_Users_Console_Offline','Zero_Users_Console_Offline','Console',NULL,'*/10','*','*','*','*',0,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (35,'Zero_System_Console_Engine','Zero_System_Console_Engine','Console',NULL,'*/10','*','*','*','*',0,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (36,'Helper_Mail_Sol_Send','Helper_Mail_Sol_Send','Console',NULL,'*/30','*','*','*','*',0,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (37,'Zero_Controllers_Grid','Zero_Controllers_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (38,'Zero_Controllers_Edit','Zero_Controllers_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (39,'Zero_Options_Grid','Zero_Options_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (40,'Zero_Options_Edit','Zero_Options_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (41,'Zero_AccessOutside_Grid','Zero_AccessOutside_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (42,'Zero_AccessOutside_Edit','Zero_AccessOutside_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (43,'Zero_Controllers_Access','Zero_Controllers_Access','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (44,'Zero_Section_Access','Zero_Section_Access','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no',NULL);
insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`,`DateExecute`) values (45,'Zero_Controllers_Check','Zero_Controllers_Check','Web',NULL,NULL,NULL,NULL,NULL,NULL,0,'yes',NULL);

/*Table structure for table `Groups` */

DROP TABLE IF EXISTS `Groups`;

CREATE TABLE `Groups` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `Status` enum('open','close') NOT NULL DEFAULT 'open',
  `Description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Groups` */

insert  into `Groups`(`ID`,`Name`,`Status`,`Description`) values (1,'Разработчики','open',NULL);
insert  into `Groups`(`ID`,`Name`,`Status`,`Description`) values (2,'Гости','open',NULL);
insert  into `Groups`(`ID`,`Name`,`Status`,`Description`) values (3,'Пользователи','open',NULL);
insert  into `Groups`(`ID`,`Name`,`Status`,`Description`) values (4,'Администратор','open',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=1265 DEFAULT CHARSET=utf8 COMMENT='Сервис отправки почтовых сообщений';

/*Data for the table `MailQueue` */

/*Table structure for table `Options` */

DROP TABLE IF EXISTS `Options`;

CREATE TABLE `Options` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `Name` varchar(50) NOT NULL COMMENT 'Имя параметра',
  `Value` varchar(200) NOT NULL COMMENT 'Значение параметра',
  `Typ` enum('string','int','float','array') NOT NULL DEFAULT 'string' COMMENT 'Тип параметра',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `Options` */

/*Table structure for table `Section` */

DROP TABLE IF EXISTS `Section`;

CREATE TABLE `Section` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Controllers_ID` bigint(20) DEFAULT NULL,
  `Section_ID` bigint(20) DEFAULT NULL,
  `Url` varchar(150) DEFAULT NULL,
  `UrlAlias` varchar(150) DEFAULT NULL,
  `UrlThis` varchar(50) NOT NULL,
  `UrlRedirect` varchar(150) DEFAULT NULL,
  `Layout` varchar(100) DEFAULT NULL,
  `IsAuthorized` enum('no','yes') NOT NULL DEFAULT 'no',
  `IsEnable` enum('yes','no') NOT NULL DEFAULT 'yes',
  `IsVisible` enum('no','yes') NOT NULL DEFAULT 'no',
  `IsIndex` enum('yes','no') DEFAULT 'yes',
  `Sort` int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `NameMenu` varchar(100) DEFAULT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Keywords` varchar(250) DEFAULT NULL,
  `Description` text,
  `Content` text,
  `Img` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Url` (`Url`),
  KEY `Zero_Section_ID` (`Section_ID`),
  KEY `Controllers_ID` (`Controllers_ID`),
  CONSTRAINT `Section_ibfk_1` FOREIGN KEY (`Section_ID`) REFERENCES `Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Section_ibfk_2` FOREIGN KEY (`Controllers_ID`) REFERENCES `Controllers` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Section` */

insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (1,1,NULL,'/',NULL,'/',NULL,'Zero_Content','no','yes','no','yes',10,'PhpZero','PhpZero','PhpZero','PhpZero','PhpZero','<p style=\"text-align:center\"><a href=\"/user\">Вход</a>&nbsp;&nbsp; <a href=\"/zero\">Admin</a></p>',NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (2,2,1,'/user',NULL,'user',NULL,'Zero_Content','no','yes','no','no',10,'Пользователь вход','Пользователь вход','Пользователь вход','Пользователь вход','Пользователь вход',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (3,1,33,'/zero/user',NULL,'user',NULL,'Zero_Main','yes','yes','yes','yes',30,'Пользователи','Пользователи','Пользователи','Пользователи','Пользователи',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (4,2,2,'/user/logout',NULL,'logout',NULL,NULL,'no','yes','no','no',10,'Пользователь выход','Пользователь выход','Пользователь выход','Пользователь выход','Пользователь выход',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (5,5,2,'/user/captcha',NULL,'captcha',NULL,NULL,'no','yes','no','yes',50,'Капча','Капча','Капча','Капча','Капча',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (8,6,3,'/zero/user/users',NULL,'users',NULL,'Zero_Main','yes','yes','yes','yes',10,'Пользователи','Пользователи','Пользователи','Пользователи','Пользователи',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (9,7,8,'/zero/user/users/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','yes',10,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (10,8,3,'/zero/user/groups',NULL,'groups',NULL,'Zero_Main','yes','yes','yes','yes',20,'Группы','Группы','Группы','Группы','Группы',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (11,9,10,'/zero/user/groups/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','yes',10,'Группы изменение','Группы изменение','Группы изменение','Группы изменение','Группы изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (12,1,33,'/zero/site',NULL,'site',NULL,'Zero_Main','yes','yes','yes','yes',40,'Сайт','Сайт','Сайт','Сайт','Сайт',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (13,11,12,'/zero/site/section',NULL,'section',NULL,'Zero_Main','yes','yes','yes','yes',40,'Разделы','Разделы','Разделы','Разделы','Разделы',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (14,12,13,'/zero/site/section/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','yes',10,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (15,13,11,'/zero/user/groups/edit/access',NULL,'access',NULL,'Zero_Main','yes','yes','yes','yes',10,'Права доступа','Права доступа','Права доступа','Права доступа','Права доступа',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (16,14,29,'/zero/site/section/edit/content/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (19,1,33,'/zero/system',NULL,'system',NULL,'Zero_Main','yes','yes','yes','yes',20,'Система','Система','Система','Система','Система',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (20,16,19,'/zero/system/service',NULL,'service',NULL,'Zero_Main','yes','yes','yes','yes',20,'Обслуживание','Обслуживание','Обслуживание','Обслуживание','Обслуживание',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (21,17,19,'/zero/system/file',NULL,'file',NULL,'Zero_Main','yes','yes','yes','yes',10,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (22,18,21,'/zero/system/file/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','yes',10,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (29,19,14,'/zero/site/section/edit/content',NULL,'content',NULL,'Zero_Main','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент','Контент',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (33,1,1,'/zero',NULL,'admin',NULL,'Zero_Main','yes','yes','yes','yes',800,'CP','CP','Административная часть','Административная часть','Административная часть',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (40,21,12,'/zero/site/content',NULL,'content',NULL,'Zero_Main','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент','Контент',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (41,22,40,'/zero/site/content/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (42,23,2,'/user/profile',NULL,'profile',NULL,'Zero_Main','yes','yes','no','no',50,'Профиль','Профиль','Профиль','Профиль','Профиль',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (100,37,19,'/zero/system/controllers',NULL,'controllers',NULL,'Zero_Main','yes','yes','yes','no',30,'Контроллеры','Контроллеры','Контроллеры','Контроллеры','Контроллеры',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (101,38,100,'/zero/system/controllers/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','no',10,'Изменение','Изменение','Изменение','Изменение','Изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (102,39,19,'/zero/system/options',NULL,'options',NULL,'Zero_Main','yes','yes','yes','no',40,'Опции','Опции','Опции','Опции','Опции',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (103,40,102,'/zero/system/options/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','no',10,'Изменение','Изменение','Изменение','Изменение','Изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (104,41,19,'/zero/system/accessoutside',NULL,'accessoutside',NULL,'Zero_Main','yes','yes','yes','no',50,'Реквизиты квнешних API','Реквизиты квнешних API','Реквизиты квнешних API','Реквизиты квнешних API','Реквизиты квнешних API',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (105,42,104,'/zero/system/accessoutside/edit',NULL,'edit',NULL,'Zero_Main','yes','yes','no','no',10,'Изменение','Изменение','Изменение','Изменение','Изменение',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (106,43,101,'/zero/system/controllers/edit/access',NULL,'access',NULL,'Zero_Main','yes','yes','no','no',30,'Права','Права','Права','Права','Права',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (107,44,14,'/zero/site/section/edit/access',NULL,'access',NULL,'Zero_Main','yes','yes','yes','no',30,'Права','Права','Права','Права','Права',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (108,45,100,'/zero/system/controllers/check',NULL,'check',NULL,'Zero_Main','yes','yes','yes','no',30,'проверки','проверки','проверки','проверки','проверки',NULL,NULL);
insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlAlias`,`UrlThis`,`UrlRedirect`,`Layout`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (109,43,14,'/zero/site/section/edit/access-controller',NULL,'access-controller',NULL,'Zero_Main','yes','yes','no','no',30,'право на к. раздела','право на к. раздела','право на к. раздела','право на к. раздела','право на к. раздела',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Users` */

insert  into `Users`(`ID`,`Groups_ID`,`Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`,`Address`,`Token`) values (1,1,NULL,'Разработчик','dev','e77989ed21758e78331b20e477fc5582','open','dev@dev.ru',NULL,NULL,'no',NULL,'yes','2017-11-01 16:43:15','2005-09-01 12:00:00',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
