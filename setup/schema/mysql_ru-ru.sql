/*
SQLyog Ultimate v11.5 (64 bit)
MySQL - 5.5.44-MariaDB : Database - control
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `Content` */

insert  into `Content`(`ID`,`Section_ID`,`Lang`,`Name`,`Content`,`Target`,`Block`) values (10,NULL,'ru-ru','Заголовок','<p>Заголовок</p>',NULL,'head'),(11,NULL,'ru-ru','Подвал','<p>Подвал</p>',NULL,'footer');

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
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='Контроллеры';

/*Data for the table `Controllers` */

insert  into `Controllers`(`ID`,`Name`,`Controller`,`Typ`,`Url`,`Minute`,`Hour`,`Day`,`Month`,`Week`,`IsActive`,`IsAuthorized`) values (1,'Zero_Section_Page','Zero_Section_Page','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(2,'Zero_Users_Login','Zero_Users_Login','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(3,'Zero_Section_Page','Zero_Section_Page','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(4,'Zero_Users_Login','Zero_Users_Login','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(5,'Zero_Users_Kcaptcha','Zero_Users_Kcaptcha','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(6,'Zero_Users_Grid','Zero_Users_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(7,'Zero_Users_Edit','Zero_Users_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(8,'Zero_Groups_Grid','Zero_Groups_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(9,'Zero_Groups_Edit','Zero_Groups_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(10,'Zero_Section_Page','Zero_Section_Page','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(11,'Zero_Section_Grid','Zero_Section_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(12,'Zero_Section_Edit','Zero_Section_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(13,'Zero_Groups_Access','Zero_Groups_Access','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(14,'Zero_Content_EditSection','Zero_Content_EditSection','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(15,'Zero_Section_Page','Zero_Section_Page','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(16,'Zero_System_GridService','Zero_System_GridService','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(17,'Zero_System_FileManager','Zero_System_FileManager','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(18,'Zero_System_FileEdit','Zero_System_FileEdit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(19,'Zero_Content_GridSection','Zero_Content_GridSection','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(20,'Zero_Section_Page','Zero_Section_Page','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(21,'Zero_Content_Grid','Zero_Content_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(22,'Zero_Content_Edit','Zero_Content_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(23,'Zero_Users_Profile','Zero_Users_Profile','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(24,'Zero_Api_Base_Upload','Zero_Api_Base_Upload','Api','/api/v1/zero/base/upload',NULL,NULL,NULL,NULL,NULL,1,'no'),(25,'Zero_Users_Api_Login','Zero_Users_Api_Login','Api','/api/v1/zero/user/login',NULL,NULL,NULL,NULL,NULL,1,'no'),(26,'Zero_Users_Api_Logout','Zero_Users_Api_Logout','Api','/api/v1/zero/user/logout',NULL,NULL,NULL,NULL,NULL,1,'no'),(27,'Zero_Api_Mail_Send','Zero_Api_Mail_Send','Api','/api/v1/mail/send',NULL,NULL,NULL,NULL,NULL,1,'no'),(29,'Zero_Api_Mail_Queue','Zero_Api_Mail_Queue','Api','/api/v1/mail/queue',NULL,NULL,NULL,NULL,NULL,1,'no'),(31,'Zero_Console_Base_ApiGen','Zero_Console_Base_ApiGen','Console',NULL,'*/10','*','*','*','*',0,'no'),(32,'Zero_Console_Base_RemTmpFileUpload','Zero_Console_Base_RemTmpFileUpload','Console',NULL,'0','*','*','*','*',0,'no'),(33,'Zero_Console_Section_SiteMap','Zero_Console_Section_SiteMap','Console',NULL,'0','0','*','*','*',0,'no'),(34,'Zero_Console_Users_Offline','Zero_Console_Users_Offline','Console',NULL,'*/10','*','*','*','*',0,'no'),(35,'Zero_Console_Base_Engine','Zero_Console_Base_Engine','Console',NULL,'*/10','*','*','*','*',0,'no'),(36,'Zero_Console_Mail_Send','Zero_Console_Mail_Send','Console',NULL,'*/30','*','*','*','*',0,'no'),(37,'Zero_Controllers_Grid','Zero_Controllers_Grid','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no'),(38,'Zero_Controllers_Edit','Zero_Controllers_Edit','Web',NULL,NULL,NULL,NULL,NULL,NULL,1,'no');

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
  `Controllers_ID` bigint(20) DEFAULT NULL,
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
  `NameMenu` varchar(100) DEFAULT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Keywords` varchar(250) DEFAULT NULL,
  `Description` text,
  `Content` text,
  `Img` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Section_ID` (`Section_ID`),
  KEY `Controllers_ID` (`Controllers_ID`),
  CONSTRAINT `Section_ibfk_1` FOREIGN KEY (`Section_ID`) REFERENCES `Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Section_ibfk_2` FOREIGN KEY (`Controllers_ID`) REFERENCES `Controllers` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Section` */

insert  into `Section`(`ID`,`Controllers_ID`,`Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`IsIndex`,`Sort`,`Name`,`NameMenu`,`Title`,`Keywords`,`Description`,`Content`,`Img`) values (1,1,NULL,'/','www',NULL,'Zero_Content','Zero_Section_Page','no','yes','no','yes',10,'PhpZero','PhpZero','PhpZero','PhpZero','PhpZero','<p style=\"text-align:center\"><a href=\"/user\">Вход</a>&nbsp;&nbsp; <a href=\"/admin\">Admin</a></p>',NULL),(2,2,1,'/user','user',NULL,'Zero_Content','Zero_Users_Login','no','yes','no','no',10,'Пользователь вход','Пользователь вход','Пользователь вход','Пользователь вход','Пользователь вход',NULL,NULL),(3,3,33,'/admin/user','user',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',30,'Пользователи','Пользователи','Пользователи','Пользователи','Пользователи',NULL,NULL),(4,4,2,'/user/logout','logout',NULL,NULL,'Zero_Users_Login','no','yes','no','no',10,'Пользователь выход','Пользователь выход','Пользователь выход','Пользователь выход','Пользователь выход',NULL,NULL),(5,5,2,'/user/captcha','captcha',NULL,NULL,'Zero_Users_Kcaptcha','no','yes','no','yes',50,'Капча','Капча','Капча','Капча','Капча',NULL,NULL),(8,6,3,'/admin/user/users','users',NULL,'Zero_Main','Zero_Users_Grid','yes','yes','yes','yes',10,'Пользователи','Пользователи','Пользователи','Пользователи','Пользователи',NULL,NULL),(9,7,8,'/admin/user/users/edit','edit',NULL,'Zero_Main','Zero_Users_Edit','yes','yes','no','yes',10,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение',NULL,NULL),(10,8,3,'/admin/user/groups','groups',NULL,'Zero_Main','Zero_Groups_Grid','yes','yes','yes','yes',20,'Группы','Группы','Группы','Группы','Группы',NULL,NULL),(11,9,10,'/admin/user/groups/edit','edit',NULL,'Zero_Main','Zero_Groups_Edit','yes','yes','no','yes',10,'Группы изменение','Группы изменение','Группы изменение','Группы изменение','Группы изменение',NULL,NULL),(12,10,33,'/admin/site','site',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',40,'Сайт','Сайт','Сайт','Сайт','Сайт',NULL,NULL),(13,11,12,'/admin/site/section','section',NULL,'Zero_Main','Zero_Section_Grid','yes','yes','yes','yes',40,'Разделы','Разделы','Разделы','Разделы','Разделы',NULL,NULL),(14,12,13,'/admin/site/section/edit','edit',NULL,'Zero_Main','Zero_Section_Edit','yes','yes','no','yes',10,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение',NULL,NULL),(15,13,11,'/admin/user/groups/edit/access','access',NULL,'Zero_Main','Zero_Groups_Access','yes','yes','yes','yes',10,'Права доступа','Права доступа','Права доступа','Права доступа','Права доступа',NULL,NULL),(16,14,29,'/admin/site/section/edit/content/edit','edit',NULL,'Zero_Main','Zero_Content_EditSection','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL,NULL),(19,15,33,'/admin/system','system',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',20,'Система','Система','Система','Система','Система',NULL,NULL),(20,16,19,'/admin/system/service','service',NULL,'Zero_Main','Zero_System_GridService','yes','yes','yes','yes',20,'Обслуживание','Обслуживание','Обслуживание','Обслуживание','Обслуживание',NULL,NULL),(21,17,19,'/admin/system/file','file',NULL,'Zero_Main','Zero_System_FileManager','yes','yes','yes','yes',10,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер',NULL,NULL),(22,18,21,'/admin/system/file/edit','edit',NULL,'Zero_Main','Zero_System_FileEdit','yes','yes','no','yes',10,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла',NULL,NULL),(29,19,14,'/admin/site/section/edit/content','content',NULL,'Zero_Main','Zero_Content_GridSection','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент','Контент',NULL,NULL),(33,20,1,'/admin','admin',NULL,'Zero_Main','Zero_Section_Page','yes','yes','yes','yes',800,'CP','CP','Административная часть','Административная часть','Административная часть',NULL,NULL),(40,21,12,'/admin/site/content','content',NULL,'Zero_Main','Zero_Content_Grid','yes','yes','yes','yes',10,'Контент','Контент','Контент','Контент','Контент',NULL,NULL),(41,22,40,'/admin/site/content/edit','edit',NULL,'Zero_Main','Zero_Content_Edit','yes','yes','no','yes',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение','Контент изменение',NULL,NULL),(42,23,2,'/user/profile','profile',NULL,'Zero_Main','Zero_Users_Profile','yes','yes','no','no',50,'Профиль','Профиль','Профиль','Профиль','Профиль',NULL,NULL),(100,37,19,'/admin/system/controllers','controllers',NULL,'Zero_Main',NULL,'yes','yes','yes','no',30,'Контроллеры','Контроллеры','Контроллеры','Контроллеры','Контроллеры',NULL,NULL),(101,38,100,'/admin/system/controllers/edit','edit',NULL,'Zero_Main',NULL,'yes','yes','no','no',10,'Изменение','Изменение','Изменение','Изменение','Изменение',NULL,NULL);

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

insert  into `Users`(`ID`,`Groups_ID`,`Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`,`Address`,`Token`) values (1,1,NULL,'Разработчик','dev','e77989ed21758e78331b20e477fc5582','open','test@test.ru',NULL,NULL,'no',NULL,'yes','2016-06-08 01:03:10',NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
