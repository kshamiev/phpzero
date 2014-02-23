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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Action` */

insert  into `Zero_Action`(`ID`,`Zero_Section_ID`,`Zero_Groups_ID`,`Action`) values (10,12,1001,'Default'),(17,13,1001,'CatalogMove'),(16,13,1001,'Default'),(18,13,1001,'Remove'),(20,14,1001,'Add'),(19,14,1001,'Default'),(21,14,1001,'Save'),(25,16,1001,'Add'),(24,16,1001,'Default'),(26,16,1001,'Save'),(22,29,1001,'Default'),(23,29,1001,'Remove'),(9,33,1001,'Default'),(11,40,1001,'Default'),(12,40,1001,'Remove'),(14,41,1001,'Add'),(13,41,1001,'Default'),(15,41,1001,'Save'),(58,101,1001,'Default'),(59,101,1001,'Profile'),(27,1017,1001,'Default'),(28,1020,1001,'Default'),(29,1020,1001,'Remove'),(31,1021,1001,'Add'),(30,1021,1001,'Default'),(32,1021,1001,'Save'),(33,1024,1001,'Default'),(34,1025,1001,'Default'),(35,1026,1001,'Default'),(36,1026,1001,'Remove'),(38,1027,1001,'Add'),(37,1027,1001,'Default'),(39,1027,1001,'Save'),(40,1030,1001,'Default'),(41,1030,1001,'Remove'),(42,1032,1001,'Default'),(43,1032,1001,'Remove'),(45,1033,1001,'Add'),(44,1033,1001,'Default'),(46,1033,1001,'Save'),(47,1045,1001,'Default'),(48,1046,1001,'Default'),(49,1046,1001,'Remove'),(51,1047,1001,'Add'),(50,1047,1001,'Default'),(52,1047,1001,'Save'),(53,1048,1001,'Default'),(54,1048,1001,'Remove'),(56,1049,1001,'Add'),(55,1049,1001,'Default'),(57,1049,1001,'Save');

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `Zero_Content` */

insert  into `Zero_Content`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Content`,`Block`) values (10,NULL,2,'Заголовок','<p>Заголовок</p>','head'),(11,NULL,2,'Подвал','<p>Подвал</p>','footer'),(17,1050,2,'Zero_Content_Page','<p>Zero_Content_Page</p>','content');

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

insert  into `Zero_Groups`(`ID`,`Name`,`Status`,`Description`) values (1,'Разработчики','open',NULL),(2,'Гости','open',NULL),(3,'Пользователи','open',NULL),(1001,'Администратор','open',NULL);

/*Table structure for table `Zero_Section` */

DROP TABLE IF EXISTS `Zero_Section`;

CREATE TABLE `Zero_Section` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) DEFAULT NULL,
  `Url` varchar(150) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=1084 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Section` */

insert  into `Zero_Section`(`ID`,`Zero_Section_ID`,`Url`,`UrlThis`,`UrlRedirect`,`Layout`,`Controller`,`IsAuthorized`,`IsEnable`,`IsVisible`,`ContentType`,`Sort`,`Name`,`Title`,`Keywords`,`Description`) values (1,NULL,'www/','www',NULL,'Www_Main','Zero_Content_Page','no','yes','no','html',10,'Стильная бижутерия','Стильная бижутерия','Стильная бижутерия','Стильная бижутерия'),(3,33,'admin/user','user',NULL,'Admin_Main','Zero_Content_Page','yes','yes','yes','html',30,'Профиль','Профиль','Профиль','Профиль'),(7,1,'www/captcha','captcha',NULL,'Admin_Content','Zero_Users_Kcaptcha','no','yes','no','img',140,'Капча','Капча','Капча','Капча'),(8,3,'admin/user/users','users',NULL,'Admin_Main','Zero_Users_Grid','yes','yes','yes','html',10,'Пользователи','Пользователи','Пользователи','Пользователи'),(9,8,'admin/user/users/edit','edit',NULL,'Admin_Main','Zero_Users_Edit','yes','yes','no','html',10,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение'),(10,3,'admin/user/groups','groups',NULL,'Admin_Main','Zero_Groups_Grid','yes','yes','yes','html',20,'Группы','Группы','Группы','Группы'),(11,10,'admin/user/groups/edit','edit',NULL,'Admin_Main','Zero_Groups_Edit','yes','yes','no','html',10,'Группы изменение','Группы изменение','Группы изменение','Группы изменение'),(12,33,'admin/site','site',NULL,'Admin_Main','Zero_Content_Page','yes','yes','yes','html',40,'Сайт','Сайт','Сайт','Сайт'),(13,12,'admin/site/section','section',NULL,'Admin_Main','Zero_Section_Grid','yes','yes','yes','html',40,'Разделы','Разделы','Разделы','Разделы'),(14,13,'admin/site/section/edit','edit',NULL,'Admin_Main','Zero_Section_Edit','yes','yes','no','html',10,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение'),(15,11,'admin/user/groups/edit/access','access',NULL,'Admin_Main','Zero_Groups_Access','yes','yes','yes','html',10,'Права доступа','Права доступа','Права доступа','Права доступа'),(16,29,'admin/site/section/edit/content/edit','edit',NULL,'Admin_Main','Zero_Content_EditSection','yes','yes','no','html',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение'),(19,33,'admin/system','system',NULL,'Admin_Main','Zero_Content_Page','yes','yes','yes','html',20,'Система','Система','Система','Система'),(20,19,'admin/system/service','service',NULL,'Admin_Main','Zero_System_GridService','yes','yes','yes','html',20,'Обслуживание','Обслуживание','Обслуживание','Обслуживание'),(21,19,'admin/system/file','file',NULL,'Admin_Main','Zero_System_FileManager','yes','yes','yes','html',10,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер'),(22,21,'admin/system/file/edit','edit',NULL,'Admin_Main','Zero_System_FileEdit','yes','yes','no','html',10,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла'),(29,14,'admin/site/section/edit/content','content',NULL,'Admin_Main','Zero_Content_GridSection','yes','yes','yes','html',10,'Контент','Контент','Контент','Контент'),(31,14,'admin/site/section/edit/translation','translation',NULL,'Admin_Main','Zero_SectionLanguage_GridSection','yes','yes','yes','html',20,'Переводы','Переводы','Переводы','Переводы'),(32,31,'admin/site/section/edit/translation/edit','edit',NULL,'Admin_Main','Zero_SectionLanguage_EditSection','yes','yes','no','html',10,'Переводы изменение','Переводы изменение','Переводы изменение','Переводы изменение'),(33,NULL,'admin/','admin',NULL,'Admin_Content','Zero_Users_Login','yes','yes','yes','html',NULL,'CMS','CMS','CMS','CMS'),(40,12,'admin/site/content','content',NULL,'Admin_Main','Zero_Content_Grid','yes','yes','yes','html',10,'Контент','Контент','Контент','Контент'),(41,40,'admin/site/content/edit','edit',NULL,'Admin_Main','Zero_Content_Edit','yes','yes','no','html',10,'Контент изменение','Контент изменение','Контент изменение','Контент изменение'),(100,1,'www/user','user',NULL,'Www_Main','Www_Users_Login','no','yes','yes','html',10,'Вход','Пользователь авторизация','Пользователь авторизация','Пользователь авторизация'),(101,100,'www/user/profile','profile',NULL,'Www_Main','Www_Users_Profile','yes','yes','yes','html',NULL,'Профиль','Профиль пользователя','Профиль пользователя','Профиль пользователя'),(102,100,'www/user/registration','registration',NULL,'Www_Main','Www_Users_Registration','no','yes','no','html',110,'Регистрация','Регистрация','Регистрация','Регистрация'),(1017,33,'admin/shop','shop',NULL,'Admin_Main','Zero_Content_Page','yes','yes','yes','html',110,'Магазин','Магазин','Магазин','Магазин'),(1018,1017,'admin/shop/basket','basket',NULL,'Admin_Main','Shop_Basket_Grid','yes','yes','no','html',500,'Покупательская корзина','Покупательская корзина','Покупательская корзина','Покупательская корзина'),(1019,1018,'admin/shop/basket/edit','edit',NULL,'Admin_Main','Shop_Basket_Edit','yes','yes','no','html',500,'Покупательская корзина изменение','Покупательская корзина изменение','Покупательская корзина изменение','Покупательская корзина изменение'),(1020,1017,'admin/shop/goods','goods',NULL,'Admin_Main','Shop_Goods_Grid','yes','yes','no','html',500,'Продукция','Продукция','Продукция','Продукция'),(1021,1020,'admin/shop/goods/edit','edit',NULL,'Admin_Main','Shop_Goods_Edit','yes','yes','no','html',500,'Продукция изменение','Продукция изменение','Продукция изменение','Продукция изменение'),(1022,1017,'admin/warehouse/goodsphoto','goodsphoto',NULL,'Admin_Main','Shop_WaresPhoto_Grid','yes','yes','no','html',30,'Фото товара','Фото товара','Фото товара','Фото товара'),(1023,1022,'admin/warehouse/goodsphoto/edit','edit',NULL,'Admin_Main','Shop_WaresPhoto_Edit','yes','yes','no','html',500,'Фото товара изменение','Фото товара изменение','Фото товара изменение','Фото товара изменение'),(1024,1017,'admin/shop/orders','orders',NULL,'Admin_Main','Shop_Orders_Grid','yes','yes','yes','html',500,'Заказы','Заказы','Заказы','Заказы'),(1025,1024,'admin/shop/orders/edit','edit',NULL,'Admin_Main','Shop_Orders_Edit','yes','yes','no','html',500,'Изменение','Изменение','Изменение','Изменение'),(1026,1017,'admin/shop/ordersgoods','ordersgoods',NULL,'Admin_Main','Shop_OrdersGoods_Grid','yes','yes','no','html',500,'Продукция заказа','Продукция заказа','Продукция заказа','Продукция заказа'),(1027,1026,'admin/shop/ordersgoods/edit','edit',NULL,'Admin_Main','Shop_OrdersGoods_Edit','yes','yes','no','html',500,'Продукция заказа изменение','Продукция заказа изменение','Продукция заказа изменение','Продукция заказа изменение'),(1028,1017,'admin/shop/reserve','reserve',NULL,'Admin_Main','Shop_Reserve_Grid','yes','yes','no','html',500,'Резерв','Резерв','Резерв','Резерв'),(1029,1028,'admin/shop/reserve/edit','edit',NULL,'Admin_Main','Shop_Reserve_Edit','yes','yes','no','html',500,'Резерв изменение','Резерв изменение','Резерв изменение','Резерв изменение'),(1030,1045,'admin/directory/warehouse','warehouse',NULL,'Admin_Main','Shop_Warehouse_Grid','yes','yes','yes','html',500,'Склады','Склады','Склады','Склады'),(1031,1030,'admin/warehouse/warehouse/edit','edit',NULL,'Admin_Main','Shop_Warehouse_Edit','yes','yes','no','html',500,'Склад изменение','Склад изменение','Склад изменение','Склад изменение'),(1032,1017,'admin/warehouse/wares','wares',NULL,'Admin_Main','Shop_Wares_Grid','yes','yes','yes','html',20,'Товар','Товар','Товар','Товар'),(1033,1032,'admin/warehouse/wares/edit','edit',NULL,'Admin_Main','Shop_Wares_Edit','yes','yes','no','html',500,'Изменение','Изменение','Изменение','Изменение'),(1045,33,'admin/directory','directory',NULL,'Admin_Main','Zero_Content_Page','yes','yes','yes','html',500,'Справочники','Справочники','Справочники','Справочники'),(1046,1045,'admin/directory/color','color',NULL,'Admin_Main','Directory_Color_Grid','yes','yes','yes','html',500,'Справочник цветов','Справочник цветов','Справочник цветов','Справочник цветов'),(1047,1046,'admin/directory/color/edit','edit',NULL,'Admin_Main','Directory_Color_Edit','yes','yes','no','html',500,'Справочник цветов изменение','Справочник цветов изменение','Справочник цветов изменение','Справочник цветов изменение'),(1048,1045,'admin/directory/packing','packing',NULL,'Admin_Main','Directory_Packing_Grid','yes','yes','yes','html',500,'Справочник фасовок','Справочник фасовок','Справочник фасовок','Справочник фасовок'),(1049,1048,'admin/directory/packing/edit','edit',NULL,'Admin_Main','Directory_Packing_Edit','yes','yes','no','html',500,'Справочник фасовок изменение','Справочник фасовок изменение','Справочник фасовок изменение','Справочник фасовок изменение'),(1050,1,'www/catalog','catalog',NULL,'Www_Main','Zero_Content_Page','no','yes','yes','html',20,'Продукция','Продукция','Продукция','Продукция'),(1051,1017,'admin/warehouse/catalog','catalog',NULL,'Admin_Main','Shop_Catalog_Grid','yes','yes','yes','html',10,'Каталог продукции','Каталог продукции','Каталог продукции','Каталог продукции'),(1052,1051,'admin/warehouse/catalog/edit','edit',NULL,'Admin_Main','Shop_Catalog_Edit','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение'),(1061,1052,'admin/warehouse/catalog/edit/wares','wares',NULL,'Admin_Main','Shop_Wares_GridCatalog','no','yes','yes','html',10,'Товар','Товар','Товар','Товар'),(1062,1061,'admin/warehouse/catalog/edit/wares/edit','edit',NULL,'Admin_Main','Shop_Wares_EditCatalog','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение'),(1064,33,'admin/profile','profile',NULL,'Admin_Main','Zero_Users_Profile','yes','yes','no','html',10,'Профиль','Профиль',NULL,'Профиль'),(1065,1033,'admin/warehouse/wares/edit/photo','photo',NULL,'Admin_Main','Shop_WaresPhoto_GridWares','yes','yes','yes','html',10,'Фото товара','Фото товара','Фото товара','Фото товара'),(1066,1065,'admin/warehouse/wares/edit/photo/edit','edit',NULL,'Admin_Main','Shop_WaresPhoto_EditWares','yes','yes','no','html',10,'Фото товара изменение','Фото товара изменение','Фото товара изменение','Фото товара изменение'),(1067,1062,'admin/warehouse/catalog/edit/wares/edit/photo','photo',NULL,'Admin_Main','Shop_WaresPhoto_GridWares','yes','yes','yes','html',10,'Фото товара','Фото товара','Фото товара','Фото товара'),(1068,1067,'admin/warehouse/catalog/edit/wares/edit/photo/edit','edit',NULL,'Admin_Main','Shop_WaresPhoto_EditWares','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение'),(1070,1025,'admin/shop/orders/edit/goods','goods',NULL,'Admin_Main','Shop_OrdersGoods_GridOrders','yes','yes','yes','html',10,'Продукция заказа','Продукция заказа','Продукция заказа','Продукция заказа'),(1071,1070,'admin/shop/orders/edit/goods/edit','edit',NULL,'Admin_Main','Shop_OrdersGoods_EditOrders','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение'),(1075,1033,'admin/warehouse/wares/edit/goods','goods',NULL,'Admin_Main','Shop_Goods_GridWares','yes','yes','yes','html',50,'Продукция','Продукция','Продукция','Продукция'),(1076,1075,'admin/warehouse/wares/edit/goods/edit','edit',NULL,'Admin_Main','Shop_Goods_EditWares','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение'),(1077,1076,'admin/warehouse/wares/edit/goods/edit/reserve','reserve',NULL,'Admin_Main','Shop_Reserve_GridGoods','yes','yes','yes','html',10,'Резерв','Резерв','Резерв','Резерв'),(1078,1077,'admin/warehouse/wares/edit/goods/edit/reserve/edit','edit',NULL,'Admin_Main','Shop_Reserve_EditGoods','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение'),(1079,1050,'www/catalog/mbnmbnm','mbnmbnm',NULL,'Zero_Main','Shop_Wares_Page','no','yes','yes','html',1,'ssss','ss','ss','ss'),(1080,1062,'admin/warehouse/catalog/edit/wares/edit/goods','goods',NULL,'Admin_Main','Shop_Goods_GridWares','yes','yes','yes','html',10,'Продукция','Продукция','Продукция','Продукция'),(1081,1080,'admin/warehouse/catalog/edit/wares/edit/goods/edit','edit',NULL,'Admin_Main','Shop_Goods_EditWares','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение'),(1082,1081,'admin/warehouse/catalog/edit/wares/edit/goods/edit/reserve','reserve',NULL,'Admin_Main','Shop_Reserve_GridGoods','yes','yes','yes','html',10,'Резерв','Резерв','Резерв','Резерв'),(1083,1082,'admin/warehouse/catalog/edit/wares/edit/goods/edit/reserve/edit','edit',NULL,'Admin_Main','Shop_Reserve_EditGoods','yes','yes','no','html',10,'Изменение','Изменение','Изменение','Изменение');

/*Table structure for table `Zero_SectionLanguage` */

DROP TABLE IF EXISTS `Zero_SectionLanguage`;

CREATE TABLE `Zero_SectionLanguage` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Zero_Section_ID` bigint(20) NOT NULL,
  `Zero_Language_ID` bigint(20) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Keywords` varchar(250) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Zero_Section_ID` (`Zero_Section_ID`,`Zero_Language_ID`),
  KEY `Zero_Section_ID_2` (`Zero_Section_ID`),
  CONSTRAINT `Zero_SectionLanguage_ibfk_1` FOREIGN KEY (`Zero_Section_ID`) REFERENCES `Zero_Section` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;

/*Data for the table `Zero_SectionLanguage` */

insert  into `Zero_SectionLanguage`(`ID`,`Zero_Section_ID`,`Zero_Language_ID`,`Name`,`Title`,`Keywords`,`Description`) values (1,1,2,'Стильная бижутерия','Стильная бижутерия','Стильная бижутерия','Стильная бижутерия'),(3,3,2,'Профиль','Профиль','Профиль','Профиль'),(5,102,2,'Регистрация','Регистрация','Регистрация','Регистрация'),(7,7,2,'Капча','Капча','Капча','Капча'),(8,8,2,'Пользователи','Пользователи','Пользователи','Пользователи'),(9,9,2,'Пользователи изменение','Пользователи изменение','Пользователи изменение','Пользователи изменение'),(10,10,2,'Группы','Группы','Группы','Группы'),(11,11,2,'Группы изменение','Группы изменение','Группы изменение','Группы изменение'),(12,12,2,'Сайт','Сайт','Сайт','Сайт'),(13,13,2,'Разделы','Разделы','Разделы','Разделы'),(14,14,2,'Разделы изменение','Разделы изменение','Разделы изменение','Разделы изменение'),(15,15,2,'Права доступа','Права доступа','Права доступа','Права доступа'),(16,16,2,'Контент изменение','Контент изменение','Контент изменение','Контент изменение'),(18,19,2,'Система','Система','Система','Система'),(19,20,2,'Обслуживание','Обслуживание','Обслуживание','Обслуживание'),(20,21,2,'Файловый менеджер','Файловый менеджер','Файловый менеджер','Файловый менеджер'),(21,22,2,'Редактирование файла','Редактирование файла','Редактирование файла','Редактирование файла'),(27,29,2,'Контент','Контент','Контент','Контент'),(29,31,2,'Переводы','Переводы','Переводы','Переводы'),(30,32,2,'Переводы изменение','Переводы изменение','Переводы изменение','Переводы изменение'),(33,101,2,'Профиль','Профиль пользователя','Профиль пользователя','Профиль пользователя'),(34,33,2,'CMS','CMS','CMS','CMS'),(35,100,2,'Вход','Пользователь авторизация','Пользователь авторизация','Пользователь авторизация'),(36,40,2,'Контент','Контент','Контент','Контент'),(37,41,2,'Контент изменение','Контент изменение','Контент изменение','Контент изменение'),(38,1017,2,'Магазин','Магазин','Магазин','Магазин'),(39,1018,2,'Покупательская корзина','Покупательская корзина','Покупательская корзина','Покупательская корзина'),(40,1019,2,'Покупательская корзина изменение','Покупательская корзина изменение','Покупательская корзина изменение','Покупательская корзина изменение'),(41,1020,2,'Продукция','Продукция','Продукция','Продукция'),(42,1021,2,'Продукция изменение','Продукция изменение','Продукция изменение','Продукция изменение'),(43,1022,2,'Фото товара','Фото товара','Фото товара','Фото товара'),(44,1023,2,'Фото товара изменение','Фото товара изменение','Фото товара изменение','Фото товара изменение'),(45,1024,2,'Заказы','Заказы','Заказы','Заказы'),(46,1025,2,'Изменение','Изменение','Изменение','Изменение'),(47,1026,2,'Продукция заказа','Продукция заказа','Продукция заказа','Продукция заказа'),(48,1027,2,'Продукция заказа изменение','Продукция заказа изменение','Продукция заказа изменение','Продукция заказа изменение'),(49,1028,2,'Резерв','Резерв','Резерв','Резерв'),(50,1029,2,'Резерв изменение','Резерв изменение','Резерв изменение','Резерв изменение'),(51,1030,2,'Склады','Склады','Склады','Склады'),(52,1031,2,'Склад изменение','Склад изменение','Склад изменение','Склад изменение'),(53,1032,2,'Товар','Товар','Товар','Товар'),(54,1033,2,'Изменение','Изменение','Изменение','Изменение'),(74,1045,2,'Справочники','Справочники','Справочники','Справочники'),(75,1046,2,'Справочник цветов','Справочник цветов','Справочник цветов','Справочник цветов'),(76,1047,2,'Справочник цветов изменение','Справочник цветов изменение','Справочник цветов изменение','Справочник цветов изменение'),(77,1048,2,'Справочник фасовок','Справочник фасовок','Справочник фасовок','Справочник фасовок'),(78,1049,2,'Справочник фасовок изменение','Справочник фасовок изменение','Справочник фасовок изменение','Справочник фасовок изменение'),(79,1065,2,'Фото товара','Фото товара','Фото товара','Фото товара'),(80,1066,2,'Фото товара изменение','Фото товара изменение','Фото товара изменение','Фото товара изменение'),(81,1067,2,'Фото товара','Фото товара','Фото товара','Фото товара'),(82,1068,2,'Изменение','Изменение','Изменение','Изменение'),(83,1052,2,'Изменение','Изменение','Изменение','Изменение'),(84,1062,2,'Изменение','Изменение','Изменение','Изменение'),(86,1070,2,'Продукция заказа','Продукция заказа','Продукция заказа','Продукция заказа'),(87,1071,2,'Изменение','Изменение','Изменение','Изменение'),(88,1050,2,'Продукция','Продукция','Продукция','Продукция'),(89,1051,2,'Каталог продукции','Каталог продукции','Каталог продукции','Каталог продукции'),(90,1061,2,'Товар','Товар','Товар','Товар'),(91,1064,2,'Профиль','Профиль',NULL,'Профиль'),(92,1075,2,'Продукция','Продукция','Продукция','Продукция'),(93,1076,2,'Изменение','Изменение','Изменение','Изменение'),(94,1077,2,'Резерв','Резерв','Резерв','Резерв'),(95,1078,2,'Изменение','Изменение','Изменение','Изменение'),(96,1080,2,'Продукция','Продукция','Продукция','Продукция'),(97,1081,2,'Изменение','Изменение','Изменение','Изменение'),(98,1082,2,'Резерв','Резерв','Резерв','Резерв'),(99,1083,2,'Изменение','Изменение','Изменение','Изменение');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

/*Data for the table `Zero_Users` */

insert  into `Zero_Users`(`ID`,`Zero_Groups_ID`,`Zero_Users_ID`,`Name`,`Login`,`Password`,`IsAccess`,`Email`,`Phone`,`Skype`,`IsCondition`,`ImgAvatar`,`IsOnline`,`DateOnline`,`Date`) values (1,1,NULL,'Разработчик','dev','e77989ed21758e78331b20e477fc5582','open','dev@domain.com','456',NULL,'no','zero_users/100/100/100/100/100/100/100/100/100/100/1/Jellyfish.jpg','yes','2014-02-23 14:38:35','2013-05-01 00:00:00'),(2,1001,NULL,'Петрова Наталия','nata','093d8a0793df4654fee95cc1215555b3','open','yatakaya78@mail.ru',NULL,NULL,'no',NULL,'yes','2014-02-18 23:04:43',NULL),(4,3,NULL,'Васечкин','ilosa','bbb4f34ffc9a2d5e06cda80e924e6208','open','konstantin@shamiev.ru',NULL,NULL,'yes',NULL,'yes','2014-02-23 14:36:23',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
