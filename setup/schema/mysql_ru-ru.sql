/*
SQLyog Enterprise v9.50 
MySQL - 5.5.25a-log : Database - phpzero_kshamiev
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
) ENGINE=InnoDB AUTO_INCREMENT=2319 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Action` */

insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2288,2,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2295,3,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2296,3,4,'Profile');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2290,8,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2291,8,4,'CatalogMove');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2292,9,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2293,9,4,'Add');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2294,9,4,'Save');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2297,10,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2298,11,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2299,11,4,'Add');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2300,11,4,'Save');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2289,12,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2301,13,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2302,13,4,'CatalogMove');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2304,13,4,'Remove');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2303,13,4,'Update_Url');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2305,14,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2306,14,4,'Add');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2307,14,4,'Save');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2310,16,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2311,16,4,'Add');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2312,16,4,'Save');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2313,25,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2314,27,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2315,27,4,'Remove');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2316,28,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2317,28,4,'Add');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2318,28,4,'Save');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2308,29,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2309,29,4,'Remove');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (1932,1000,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (1958,1000,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2015,1000,4,'AccessAllow');
insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (2072,1000,4,'AccessAllow');

/*Table structure for table `Zero_Content` */

DROP TABLE IF EXISTS `Zero_Content`;

CREATE TABLE `Zero_Content` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Zero_Layout_ID` bigint(20) DEFAULT NULL,
  `Zero_Language_ID` bigint(20) NOT NULL DEFAULT '1',
  `Name` varchar(50) DEFAULT NULL,
  `Content` text,
  `Block` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Zero_Layout_ID` (`Zero_Layout_ID`),
  KEY `Zero_Language_ID` (`Zero_Language_ID`),
  KEY `Zero_Section_ID` (`Zero_Section_ID`),
  CONSTRAINT `Zero_Content_ibfk_3` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Zero_Content_ibfk_2` FOREIGN KEY (`Zero_Layout_ID`) REFERENCES `Zero_Layout` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `Zero_Content` */

insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (3,1000,NULL,1,'Www','<p>Www</p>','content');
insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (5,NULL,3,1,'Head','<p>Head</p>','head');
insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (6,NULL,3,1,'Footer','<p>Footer</p>','footer');
insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (9,1000,NULL,2,'Сайт','<p>Сайт</p>','content');
insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (10,NULL,3,2,'Заголовок','<p>Заголовок</p>','head');
insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (11,NULL,3,2,'Подвал','<p>Подвал</p>','footer');

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

insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (1,'Developer','open','bbbb');
insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (2,'Guest','open',NULL);
insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (3,'Users','open',NULL);
insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (4,'Administrator','open',NULL);

/*Table structure for table `Zero_Layout` */

DROP TABLE IF EXISTS `Zero_Layout`;

CREATE TABLE `Zero_Layout` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Layout` varchar(50) NOT NULL,
  `Description` text,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Layout` (`Layout`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Layout` */

insert  into `Zero_Layout`(`ID`,`Name`,`Layout`,`Description`) values (1,'Zero - Index','Zero_Index',NULL);
insert  into `Zero_Layout`(`ID`,`Name`,`Layout`,`Description`) values (2,'Zero - Content','Zero_Content',NULL);
insert  into `Zero_Layout`(`ID`,`Name`,`Layout`,`Description`) values (3,'Zero - Top','Zero_Top',NULL);

/*Table structure for table `Zero_Section` */

DROP TABLE IF EXISTS `Zero_Section`;

CREATE TABLE `Zero_Section` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Zero_Layout_ID` bigint(20) DEFAULT NULL,
  `Url` varchar(100) DEFAULT NULL,
  `UrlThis` varchar(50) NOT NULL,
  `UrlRedirect` varchar(150) DEFAULT NULL,
  `ModuleController` varchar(50) DEFAULT NULL,
  `ModuleUrl` varchar(100) DEFAULT NULL,
  `Controller` varchar(50) DEFAULT NULL,
  `IsAuthorized` enum('no','yes') NOT NULL DEFAULT 'no',
  `IsVisible` enum('no','yes') NOT NULL DEFAULT 'no',
  `Sort` int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Keywords` varchar(250) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`ID`),
  KEY `Zero_Layout_ID` (`Zero_Layout_ID`),
  KEY `Zero_Section_ID` (`Zero_Section_ID`),
  CONSTRAINT `Zero_Section_ibfk_2` FOREIGN KEY (`Zero_Layout_ID`) REFERENCES `Zero_Layout` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `Zero_Section_ibfk_3` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1019 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Section` */

insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (1,NULL,2,'zero','zero',NULL,NULL,NULL,'Zero_Users_Login','no','no',10,'CMF PhpZero','CMF PhpZero','CMF PhpZero','CMF PhpZero');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (2,19,1,'zero/system/modules','modules',NULL,NULL,NULL,'Zero_System_GridModules','yes','yes',30,'Модули','Модули','Модули','Модули');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (3,8,1,'zero/site/users/profile','profile',NULL,NULL,NULL,'Zero_Users_Profile','yes','no',120,'Профиль','Профиль','Профиль','Профиль');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (4,8,1,'zero/site/users/logout','logout',NULL,NULL,NULL,'Zero_Users_Logout','no','no',200,'Выход','Выход','Выход','Выход');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (5,8,2,'zero/site/users/registration','registration',NULL,NULL,NULL,'Zero_Users_Registration','no','no',110,'Регистрация','Регистрация','Регистрация','Регистрация');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (6,8,2,'zero/site/users/reminder','reminder',NULL,NULL,NULL,'Zero_Users_Reminder','no','no',130,'Восстановление пароля','Восстановление пароля','Восстановление пароля','Восстановление пароля');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (7,30,NULL,'zero/helper/captcha','captcha',NULL,NULL,NULL,'Zero_Users_Kcaptcha','no','no',140,'Капча','Капча','Капча','Капча');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (8,12,1,'zero/site/users','users',NULL,NULL,NULL,'Zero_Users_Grid','yes','yes',10,'Пользователи','Пользователи','Пользователи','Пользователи');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (9,8,1,'zero/site/users/edit','edit',NULL,NULL,NULL,'Zero_Users_Edit','yes','no',10,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (10,12,1,'zero/site/groups','groups',NULL,NULL,NULL,'Zero_Groups_Grid','yes','yes',20,'Группы','Группы','Группы','Группы');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (11,10,1,'zero/site/groups/edit','edit',NULL,NULL,NULL,'Zero_Groups_Edit','yes','no',10,'Группы изменение','Группы изменение','Группы изменение','Группы изменение');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (12,1,1,'zero/site','site',NULL,NULL,NULL,'Zero_Content_Page','yes','yes',30,'Сайт','Сайт','Сайт','Сайт');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (13,12,1,'zero/site/section','section',NULL,NULL,NULL,'Zero_Section_Grid','yes','yes',40,'Разделы','Разделы','Разделы','Разделы');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (14,13,1,'zero/site/section/edit','edit',NULL,NULL,NULL,'Zero_Section_Edit','yes','no',10,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (15,11,1,'zero/site/groups/edit/access','access',NULL,NULL,NULL,'Zero_Groups_Access','yes','yes',10,'Права доступа','Права доступа','Права доступа','Права доступа');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (16,29,1,'zero/site/section/edit/content/edit','edit',NULL,NULL,NULL,'Zero_Content_EditSection','yes','no',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (17,30,NULL,'zero/helper/output-file','output-file',NULL,NULL,NULL,'Zero_Crud_UploadFile','no','no',150,'Вывод бинарных данных из БД','Вывод бинарных данных из БД','Вывод бинарных данных из БД','Вывод бинарных данных из БД');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (18,2,1,'zero/system/modules/edit','edit',NULL,NULL,NULL,'Zero_System_EditModules','yes','no',10,'Настройки модуля','Настройки модуля','Настройки модуля','Настройки модуля');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (19,1,1,'zero/system','system',NULL,NULL,NULL,'Zero_Content_Page','yes','yes',20,'Система','Система','Система','Система');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (20,19,1,'zero/system/service','service',NULL,NULL,NULL,'Zero_System_GridService','yes','yes',20,'Обслуживание','Обслуживание','Обслуживание','Обслуживание');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (21,19,1,'zero/system/file','file',NULL,NULL,NULL,'Zero_System_FileManager','yes','yes',10,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (22,21,1,'zero/system/file/edit','edit',NULL,NULL,NULL,'Zero_System_FileEdit','yes','no',10,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (23,30,NULL,'zero/helper/filter','filter',NULL,NULL,NULL,'Zero_Crud_JsonFilter','no','no',160,'Фильтры через ajax','Фильтры через ajax','Фильтры через ajax','Фильтры через ajax');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (25,12,1,'zero/site/layout','layout',NULL,NULL,NULL,'Zero_Layout_Grid','yes','yes',50,'Макеты','Макеты','Макеты','Макеты');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (26,25,1,'zero/site/layout/edit','edit',NULL,NULL,NULL,'Zero_Layout_Edit','yes','no',500,'Макеты изменение','Макеты изменение','Макеты изменение','Макеты изменение');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (27,26,1,'zero/site/layout/edit/content','content',NULL,NULL,NULL,'Zero_Content_GridLayout','yes','yes',10,'Контент','Контент','Контент','Контент');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (28,27,1,'zero/site/layout/edit/content/edit','edit',NULL,NULL,NULL,'Zero_Content_EditLayout','yes','no',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (29,14,1,'zero/site/section/edit/content','content',NULL,NULL,NULL,'Zero_Content_GridSection','yes','yes',10,'Контент','Контент','Контент','Контент');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (30,1,1,'zero/helper','helper',NULL,NULL,NULL,'Zero_Content_Page','yes','no',10,'Helper','Helper','Helper','Helper');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (31,14,1,'zero/site/section/edit/translation','translation',NULL,NULL,NULL,'Zero_SectionLanguage_GridSection','yes','yes',20,'Переводы','Переводы','Переводы','Переводы');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (32,31,1,'zero/site/section/edit/translation/edit','edit',NULL,NULL,NULL,'Zero_SectionLanguage_EditSection','yes','no',10,'Переводы изменение','Переводы изменение','Переводы изменение','Переводы изменение');
insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Zero_Layout_ID`,`Url`,`UrlThis`,`UrlRedirect`,`ModuleController`,`ModuleUrl`,`Controller`,`IsAuthorized`,`IsVisible`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (1000,NULL,3,'www','www',NULL,'Www_Content_Page',NULL,'Www_Content_Page','no','yes',20,'Сайт','Сайт','Сайт','Сайт');

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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_SectionLanguage` */

insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (1,1,1,'CMF PhpZero','CMF PhpZero','CMF PhpZero','CMF PhpZero');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (2,2,1,'Modules','Modules','Modules','Modules');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (3,3,1,'Profile','Profile','Profile','Profile');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (4,4,1,'Exit','Exit','Exit','Exit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (5,5,1,'Registratin','Registratin','Registratin','Registratin');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (6,6,1,'Recovery access','Recovery access','Recovery access','Recovery access');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (7,7,1,'Kcaptcha','Kcaptcha','Kcaptcha','Kcaptcha');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (8,8,1,'Users','Users','Users','Users');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (9,9,1,'Users Edit','Users Edit','Users Edit','Users Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (10,10,1,'Groups','Groups','Groups','Groups');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (11,11,1,'Groups Edit','Groups Edit','Groups Edit','Groups Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (12,12,1,'Site','Site','Site','Site');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (13,13,1,'Section','Section','Section','Section');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (14,14,1,'Section Edit','Section Edit','Section Edit','Section Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (15,15,1,'Permissions','Permissions','Permissions','Permissions');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (16,16,1,'Content Edit','Content Edit','Content Edit','Content Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (17,17,1,'Output file from database','Output file from database','Output file from database','Output file from database');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (18,18,1,'Module settings','Module settings','Module settings','Module settings');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (19,19,1,'System','System','System','System');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (20,20,1,'Service','Service','Service','Service');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (21,21,1,'File Manager','File Manager','File Manager','File Manager');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (22,22,1,'File Edit','File Edit','File Edit','File Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (23,23,1,'Filter to ajax','Filter to ajax','Filter to ajax','Filter to ajax');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (24,25,1,'Layout','Layout','Layout','Layout');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (25,26,1,'Layout Edit','Layout Edit','Layout Edit','Layout Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (26,27,1,'Content','Content','Content','Content');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (27,28,1,'Content Edit','Content Edit','Content Edit','Content Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (28,29,1,'Content','Content','Content','Content');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (29,30,1,'Helper','Helper','Helper','Helper');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (30,31,1,'Tramslation','Tramslation','Tramslation','Tramslation');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (31,32,1,'Tramslation Edit','Tramslation Edit','Tramslation Edit','Tramslation Edit');
insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (32,1000,1,'Site','Site','Site','Site');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Users` */

insert  into `Zero_Users`(`ID`,`Zero_Groups_ID`,`Zero_Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`) values (1,1,NULL,'dev','dev','e77989ed21758e78331b20e477fc5582','open','dev@domain.com',NULL,NULL,'no',NULL,'yes','2013-06-13 00:05:03','2013-05-01 00:00:00');
insert  into `Zero_Users`(`ID`,`Zero_Groups_ID`,`Zero_Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`) values (2,4,NULL,'admin','admin','21232f297a57a5a743894a0e4a801fc3','open','admin@domain.com',NULL,NULL,'no','zero_users/100/100/100/100/100/100/100/100/100/100/2/services_imac.png','no','2013-05-29 17:41:43','2013-05-01 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
