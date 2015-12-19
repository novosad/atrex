-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: diplom
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `catalog`
--

DROP TABLE IF EXISTS `catalog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog` (
  `id_catalog` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_name` varchar(50) NOT NULL,
  `catalog_photo` varchar(30) NOT NULL,
  `child` int(11) NOT NULL,
  PRIMARY KEY (`id_catalog`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog`
--

LOCK TABLES `catalog` WRITE;
/*!40000 ALTER TABLE `catalog` DISABLE KEYS */;
INSERT INTO `catalog` VALUES (1,'Ноутбуки','notebook.jpeg',0),(2,'Печатная техника','print_tech.png',0),(3,'Материнские платы','mather.jpg',0),(4,'Процессоры','pros.jpeg',0),(5,'Жесткие диски','hd.jpg',0),(6,'Аудио-видео техника','audio_tech.jpg',0),(7,'Клавиатуры, мыши','keyboard.jpg',0),(8,'Мониторы','monitor.jpg',0),(9,'Моноблоки','monoblock.jpg',0),(10,'Корпуса, блоки питания','case.jpg',0),(11,'Модули памяти','memory.jpg',0),(12,'Видеокарты','video.jpg',0),(13,'Оптические накопители','optic.jpeg',0),(14,'Сетевое оборудование ','network.jpg',0);
/*!40000 ALTER TABLE `catalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1448049998),('m130524_201442_init',1448050000);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id_news` int(11) NOT NULL AUTO_INCREMENT,
  `title_news` varchar(200) NOT NULL,
  `date_news` date NOT NULL,
  `description_news` longtext NOT NULL,
  `photo_news` varchar(30) NOT NULL,
  PRIMARY KEY (`id_news`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Низкоскоростные тарифы byfly подорожают, а «Рекорд» станет быстрее','2015-12-03','Национальный провайдер объявил об оптимизации с 1 декабря тарифных планов byfly, в рамках которой увеличится стоимость низкоскоростных тарифов, а некоторые высокоскоростные при старой цене станут быстрее. В частности, «Рекорд 15» переименуют в «Рекорд 25» и ускорят до 25 Мбит/с без изменения цены.\r\n<br/><br/>\r\n«Рекорд 20» превратится в «Рекорд 50 new» со скоростью передачи данных до 50 Мбит/с. Тариф «Рекорд 100» остается без изменений. «Рекорд 10» подешевеет до 135 тысяч рублей, а скорость приема/передачи данных останется на уровне 10/5 Мбит/с. «Рекорд 5», «Рекорд 50», «Социальный анлим», «Социальный анлим 2», «Социальный» переводятся в архив.','beltelecom.jpg'),(2,'Фотофакт: Windows исполнилось 30 лет','2015-11-20','20 ноября 1985 года Microsoft запустила первую версию операционной системы Windows. Это стало огромной вехой, которая на десятки лет вперед определила путь развития операционных систем, рассказывает The Verge. Ресурс предлагает вспомнить, как выглядели и менялись разные версии ОС на протяжении тридцати лет.\r\n<br/><br/>\r\nПервая версия Windows с графическим интерфейсом и поддержкой мыши вышла в этот день, но только 30 лет назад. За ней последовали номерные версии (2.0 и 3.0), версии по годам (95 и 98), XP и Vista, а затем снова номерные версии (7, 8 и 10). Самой популярной Windows в мире до 2012 года оставалась XP. Затем лидерство перешло к ее наследнице — Windows 7. Актуальная Windows 10 планирует за 2—3 года покорить миллиард устройств.','windows.png'),(3,'Игры PlayStation 2 позволят запускать на PlayStation 4','2015-11-19','В этом году ходило много слухов о том, что Sony работает над переизданием некоторых классических игр для консоли последнего поколения. На днях компания подтвердила, что пользователи PlayStation 4 смогут поиграть в тайтлы с PS2. Но не благодаря переизданию, а благодаря эмулятору, который будет запускаться на новой консоли.\r\n<br/><br/>\r\n«Мы работаем над технологией эмуляции PS2, чтобы перенести игры с нее на современное поколение консолей», — сообщили представители компании в коротком комментарии Wired. На данный момент из таких игр доступна коллекция старых тайтлов по вселенной Star Wars. И на новом поколении эмулированные игры выглядят немного лучше в плане графики, чем запущенные на «родной» PlayStation 2.','playstation.jpg'),(4,'New Balance представила напечатанные на 3D-принтере кроссовки','2015-11-18','Американский производитель спортивной одежды в Бостоне представил свои первые беговые кроссовки, напечатанные на 3D-принтере. Как сообщает Engadget, в этом деле New Balance идет по пятам более известного бренда — Adidas, который в прошлом месяце показал прототип своей печатной обуви.\r\n<br/><br/>\r\nНа самом деле на 3D-принтере печатается только подошва кроссовок. Используя специально разработанный эластомерный порошок, New Balance удалось найти «оптимальный» баланс гибкости, долговечности, прочности и веса. По крайней мере, так говорят представители бренда.','sneakers.png'),(5,'МТС сообщил о подорожании услуг связи','2015-11-17','30 ноября абонентская плата на большинстве тарифных планов МТС будет увеличена. «На всех тарифах, кроме линейки „МТС СМАРТ“, стоимость разговоров и переадресованных вызовов внутри сети повысится, — сообщается на сайте компании. — Цена на интернет-трафик сверх пакетов, включенных в абонентскую плату, останется на всех тарифных планах прежней. Стоимость SMS и SMS-пакетов будет повышена на всех предложениях, кроме тарифов „Отличный“ и „Легко сказать“ — на них SMS-сообщения станут, наоборот, дешевле».\r\n<br/><br/>\r\nВ МТС также отметили, что с 30 ноября будет изменена стоимость некоторых дополнительных и сервисных услуг МТС. «В частности, цена на интернет-опции будет скорректирована в сторону повышения. Кроме этого, будут повышены тарифы на подключение таких услуг, как „Обещанный платеж 30“, „Срочный кредит 100“, „Срочный безлимит“, а также на пакеты дополнительных минут в рамках корпоративных тарифов. Также изменятся цены на большинство информационно-развлекательных сервисов», — информирует оператор.','mts.jpg'),(6,'Pepsi сделала собственный смартфон','2015-11-16','С месяц назад в сети появились слухи о скором выходе на рынок смартфона под брендом Pepsi. На днях компания подтвердила эту информацию и официально показала свой Pepsi Phone P1 — первый смартфон с брендом газировки. От большинства других гаджетов его отличает не только значок на задней панели, но и адекватный баланс характеристик и цены.\r\n<br/><br/>\r\nPepsi Phone P1 в алюминиевом корпусе оборудован дисплеем с диагональю 5,5 дюйма и разрешением 1920×1080 пикселей. Внутри установлен 8-ядерный процессор MediaTek MT6792 с тактовой частотой 1,7 ГГц, 2 ГБ оперативной и 16 ГБ встроенной памяти. На передней панели находится камера на 5 Мп, на задней — 13 Мп. Аккумулятор обладает емкостью 3000 мАч. Двухсимочный смартфон может работать в LTE-сети, а слот под вторую SIM-карту можно использовать в качестве слота для карты microSD.','pepsi.jpeg'),(7,'Доля Windows Phone стремительно сокращается — ОС занимает всего 1,7% рынка','2015-11-16','Аналитическая компания Gartner опубликовала результаты исследования мирового рынка смартфонов в третьем квартале 2015 года. В первую очередь эксперты отметили стремительное падение доли устройств под управлением Windows Phone. В настоящее время такие девайсы занимают лишь 1,7% рынка, тогда как годом ранее соответствующий показатель составлял 3%.\r\n<br/><br/>\r\nНесмотря на совсем скорый выход новых аппаратов под управлением Windows 10 Mobile, в Gartner не прогнозируют взрывного роста популярности системы от Microsoft. Аналитики считают, что Windows 10 Mobile будет занимать небольшую часть рынка смартфонов, ориентированную прежде всего на корпоративных пользователей.','phone.jpg'),(8,'В Минске презентовали центр искусственного интеллекта','2015-11-14','В рамках XIV Международной конференции «Развитие информатизации и государственной системы научно-технической информации» (РИНТИ) состоялась презентация Межведомственного исследовательского центра искусственного интеллекта. Он должен объединить усилия специалистов в области медицинских, биологических, технических и физико-математических наук для создания передовых и конкурентоспособных технологий искусственного интеллекта.\r\n<br/><br/>\r\n«В программе социально-экономического развития страны есть очень интересный пункт — интеллектуализация наших производств, нашей продукции. Сегодня конкурентоспособность любой техники, обладающей качественным программным продуктом, серьезно возрастает», — отметил первый заместитель председателя Президиума Национальной академии наук (НАН) Беларуси Сергей Чижик.','minsk.jpg'),(9,'Элитные кибервойска Британии вступят в борьбу с «Исламским государством»','2015-10-19','Министр финансов Великобритании Джордж Осборн опасается, что террористы в настоящее время могут готовить кибератаки на инфраструктуру городов, больницы, электрические сети и системы контроля воздушного трафика. А потому стране необходимо усилить защиту киберпространства и создать «элитные наступательные кибервойска».\r\n<br/><br/>\r\nПоэтому расходы на эти нужды будут удвоены и составят 2,9 млрд долларов до 2020 года. Финансирование распределят между Центром правительственной связи и британскими военными. Кибервойска займутся отдельными хакерами, группами преступников и боевиков, враждебными странами, сообщает Gizmodo.','army.jpg'),(10,'Британец на случай Апокалипсиса построил бункер','2015-10-07','Сумасшедший изобретатель Колин Фарз наконец-то завершил дело всей своей жизни и построил на заднем дворе подземный бункер на случай Апокалипсиса. В нем есть все: кухня, унитаз, койка для сна и оружие для самообороны. Когти Росомахи из «Людей Икс», клинок скрытого убийцы из Assasin\'s Creed и старый добрый «Калашников».\r\n<br/><br/>\r\nАК-74 установлен у входа в бункер и приводится в действие по нажатию кнопки на пульте дистанционного управления. Это обезопасит поселенца от незваных гостей. Бункер оборудован системой вентиляции и автономным генератором, в импровизированной гостиной стоит телевизор, на котором можно сыграть в приставку, а рядом расположилась барабанная установка. Постройку бункера спонсировал британский телеканал Sky 1, но Колин утверждает, что мечтал о нем всю свою жизнь.','bunker.png'),(11,'В США начали продавать девайс для метания «файерболов» из руки','2015-09-11','Магазин магии Ellusionist продемонстрировал носимый девайс, который способен превратить скучные руки в огнеметы. Pyro Mini является усовершенствованной версией гаджета, который вышел в прошлом году и позволял выпускать языки пламени из запястий. Pyro Mini стал компактнее, незаметнее и позиционируется как эффектный гаджет для волшебников и фокусников.\r\n<br/><br/>\r\nPyro Mini рассчитан на 600 выстрелов с полного заряда встроенного аккумулятора. Перезаряжается он с помощью обычного кабеля microUSB от компьютера или сети. Дальность выстрела сгустком огня может достигать 12 метров. Специальный беспроводной пульт позволяет удаленно стрелять огнем на расстоянии до 9 метров от Pyro Mini.','usa.jpg'),(12,'Sharp попросила сотрудников купить свою продукцию','2015-09-09','Японский производитель электроники компания Sharp находится не в самой лучшей форме. Ценовая, технологическая конкуренция со стороны корейских и китайских производителей вызвала затяжной кризис в компании. И руководство идет на крайние меры, обращаясь к своим сотрудникам наполовину с просьбой, а наполовину с требованием покупать продукцию Sharp.\r\n<br/><br/>\r\nВысший менеджмент должен потратить на телевизоры, холодильники, кондиционеры или другую бытовую технику не менее 1660 долларов, управленцы среднего звена — 800 долларов, остальные сотрудники — 400 долларов. Продажи среди своих может простимулировать возврат 2% от суммы. При этом уточняется, что акция не носит обязательного характера. Однако, учитывая корпоративную культуру Японии и национальный менталитет, большинство сотрудников примут участие в акции.','sharp.jpg'),(13,'iPad Pro «намертво» зависает после длительной подзарядки','2015-08-13','Владельцы планшета iPad Pro жалуются на серьезный недостаток новинки: после длительного подключения к сети (на несколько часов и больше) устройство зависает и перестает реагировать на какие-либо действия. Чтобы вывести его из состояния «ступора», необходимо зажать кнопки «домой» и включения питания на 10 секунд.\r\n<br/><br/>\r\nВ этом случае iPad Pro перезагружается, уровень заряда батареи указан как 100%. Однако при последующем длительном подключении к сети ситуация может повториться, передает NeoWin. Один из вариантов избежать зависания — перед зарядкой установить авиарежим.','ipad.jpg'),(14,'Словом 2015 года по версии Оксфордского словаря стал смайлик','2015-08-10','Оксфордский словарь ежегодно определяет слово года, которое отражает или называет наиболее актуальные социальные явления. В 2013 году это было, например, «селфи», а в 2014 — vape, которое относится к процессу потребления электронных сигарет. Словом 2015 года стала не одна из основных структурных единиц языка, а эмотикон, или иначе смайлик.\r\n<br/><br/>\r\nОт авторитетного издания не ожидаешь подобного шага, однако в Oxford University Press все же решили наделить званием смайлик, изображающий «лицо со слезами радости на лице». Причина кроется в высокой популярности этого эмотикона, который, по данным исследований одной британской компании, занял 20-процентную долю от всех использованных в сообщениях пиктограмм в Великобритании, 17% — в США. В 2014 году эти показатели составляли 4% и 9% соответственно.','smile.jpg');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id_product` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` varchar(225) NOT NULL,
  `price` varchar(75) NOT NULL,
  `photo` varchar(50) NOT NULL,
  PRIMARY KEY (`id_product`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`id_section`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,1,'ASUS X553MA-XX490D','15.6\" 1366 x 768 глянцевый, Intel Celeron N2840 2160 МГц, 4 ГБ, 500 Гб (HDD), Intel HD Graphics, DOS','5 400 000','asus1'),(2,1,'ASUS K501LX-DM060H ','15.6\" 1920 x 1080, Intel Core i5 5200U 2200 МГц, 6 ГБ, 1000 ГБ + не установлен (HDD), NVIDIA GeForce GTX 950M, Windows 8.1, цвет крышки синий, цвет корпуса черный/серебристый','13 085 000','asus2'),(3,1,'ASUS N551JM-CN123H ','15.6\" 1920 x 1080 матовый, Intel Core i7 4710HQ 2500 МГц, 12 ГБ, 1000 ГБ (HDD), NVIDIA GeForce GTX 860M, Windows 8.1, цвет крышки стальной, цвет корпуса черный/серебристый','17 043 000','asus3'),(4,1,'ASUS X453MA-WX315B ','14.0\" 1366 x 768 глянцевый, Intel Celeron N2840 2160 МГц, 2 ГБ, 500 Гб (HDD), Intel HD Graphics, Windows 8.1, цвет крышки черный, цвет корпуса черный','4 417 000','asus4'),(5,2,'Lenovo Y50-70 (59443090) ','15.6\" 1920 x 1080 матовый, Intel Core i7 4720HQ 2600 МГц, 8 ГБ, 1000 ГБ + 8 Гб (HDD + SSD), NVIDIA GeForce GTX 960M, DOS, цвет крышки темно-серый, цвет корпуса черный/темно-серый','16 000 000','lenovo1'),(6,2,'Lenovo Z50-70 (59430330) ','15.6\" 1920 x 1080 глянцевый, Intel Core i5 4210U 1700 МГц, 8 ГБ, 1000 ГБ + 8 Гб (HDD + SSD), NVIDIA GeForce 840M, Windows 8.1, цвет крышки черный, цвет корпуса черный/серебристый','10 390 000','lenovo2'),(7,2,'Lenovo G50-80 (80L000H5NX) ','15.6\" 1366 x 768, Intel Core i3 4030U 1900 МГц, 4 ГБ, 500 Гб (HDD), Intel HD Graphics 4400, без ОС, цвет крышки темно-серый, цвет корпуса темно-серый/черный','6 500 000','lenovo3'),(8,3,'MSI GE70 2PC-070XPL Apache ','17.3\" 1920 x 1080 матовый, Intel Core i5 4200H 2800 МГц, 8 ГБ, 1000 ГБ (HDD), NVIDIA GeForce GTX 860M, без ОС, цвет крышки черный, цвет корпуса черный','17 414 000','msi1'),(9,3,'MSI PE70 2QE-201RU ','17.3\" 1920 x 1080 матовый, Intel Core i7 5700HQ 2700 МГц, 8 ГБ, 1000 ГБ + 128 Гб (HDD + SSD), NVIDIA GeForce GTX 960M, Windows 8.1, цвет крышки серебристый, цвет корпуса черный/серебристый','21 060 000','msi2'),(10,4,'Canon i-SENSYS MF3010 ','МФУ, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 18 стр/мин, разрешение 600x400 dpi','2 093 000','4canon1'),(11,4,'Canon PIXMA MG2440 ','МФУ, струйный, цветной, формат A4 (210x297 мм), скорость ч/б печати 8 стр/мин, скорость цветной печати 4 стр/мин, разрешение 4800x600 dpi','342 000','4canon2'),(13,4,'HP LaserJet Pro M125ra (CZ177A) ','МФУ, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 20 стр/мин, разрешение 600 dpi','2 027 300','4hp1'),(14,4,'HP LaserJet Pro M1132 MFP (CE847A)','МФУ, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 18 стр/мин, разрешение 600 dpi','2 620 000','4hp2'),(15,4,'Samsung SL-M2070 ','МФУ, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 20 стр/мин, разрешение 1200 dpi','1 874 500','4samsung1'),(16,4,'Samsung SCX-3400 ','МФУ, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 20 стр/мин, разрешение 1200 dpi','1 949 800','4samsung2'),(18,8,'Canon i-SENSYS LBP6030B ','принтер, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 18 стр/мин, разрешение 2400x600 dpi','1 207 700','7canon1'),(19,8,'Canon i-SENSYS LBP7100Cn','принтер, лазерный, цветной, формат A4 (210x297 мм), скорость ч/б печати 14 стр/мин, скорость цветной печати 14 стр/мин, разрешение 1200 dpi, LAN','2 141 400','7canon2'),(20,8,'HP LaserJet Pro P1102 (CE651A) ','принтер, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 18 стр/мин, разрешение 600 dpi','1 260 000','7hp1'),(21,8,'HP LaserJet Pro P1606dn (CE749A) ','принтер, лазерный, черно-белый, формат A4 (210x297 мм), скорость ч/б печати 25 стр/мин, разрешение 600 dpi, LAN, AirPrint','1 360 000','7hp2'),(22,11,'Gigabyte GA-F2A78M-D3H (rev. 3.0)','mATX, сокет AMD FM2/FM2+, чипсет AMD A78, память 4xDDR3, слоты: 2xPCIe x16, 1xPCIe x1, 1xPCI','968 500','39gig'),(23,11,'ASUS A58M-K ','mATX, сокет AMD FM2+, чипсет AMD A58, память 2xDDR3, слоты: 1xPCIe x16, 1xPCIe x1, 1xPCI','803 100','310asus1'),(39,11,'ASRock FM2A58M-VG3+ R2.0','mATX, сокет AMD FM2/FM2+, чипсет AMD A58, память 2xDDR3, слоты: 1xPCIe x16, 1xPCIe x1','703 000','311asrock'),(40,11,'Gigabyte GA-970A-DS3P (rev. 1.0)','ATX, сокет AMD AM3/AM3+, чипсет AMD 970 + SB950, память 4xDDR3, слоты: 2xPCIe x16, 3xPCIe x1, 2xPCI','1 223 500','312gig'),(41,11,'ASUS M5A78L-M LX3 ','mATX, сокет AMD AM3+, чипсет AMD 760G + SB710, память 2xDDR3, слоты: 1xPCIe x16, 1xPCIe x1, 1xPCI','772 900','313asus'),(42,11,'ASRock 960GC-GS FX','mATX, сокет AMD AM2/AM2+/AM3/AM3+, чипсет AMD 760G + SB710, память 4xDDR2/DDR3, слоты: 1xPCIe x16, 1xPCIe x1, 2xPCI','1 116 000','314asrock1'),(44,20,'Gigabyte GA-H81M-S2PV (rev. 1.0) ','mATX, сокет Intel LGA1150, чипсет Intel H81, память 2xDDR3, слоты: 1xPCIe x16, 1xPCIe x1, 2xPCI','882 000','15gig'),(46,20,'ASUS B85-PLUS ','ATX, сокет Intel LGA1150, чипсет Intel B85, память 4xDDR3, слоты: 2xPCIe x16, 2xPCIe x1, 3xPCI','1 542 400','16asus2'),(47,20,'ASRock Z97 Anniversary ','ATX, сокет Intel LGA1150, чипсет Intel Z97, память 4xDDR3, слоты: 1xPCIe x16, 3xPCIe x1, 2xPCI','1 418 400','17asrock'),(52,29,'Intel Core i7-4790K ','Devil\'s Canyon, 4 ядра, частота 4 ГГц, кэш 1 МБ + 8 МБ, техпроцесс 22 нм, сокет LGA1150, TDP 88W','6 230 000','21intel1'),(53,29,'Intel Core i7-6700K ','Skylake, 4 ядра, частота 4 ГГц + 8 МБ, техпроцесс 14 нм, сокет LGA1151, TDP 91W','9 647 900','21intel2'),(54,29,'Intel Core i5-4460 ','Haswell, 4 ядра, частота 3.2 ГГц, кэш 1 МБ + 6 МБ, техпроцесс 22 нм, сокет LGA1150, TDP 84W','3 272 400','21intel2'),(55,30,'AMD FX-8300 (FD8300WMW8KHK)','Vishera, 8 ядер, частота 3.3 ГГц, кэш 8 МБ + 8 МБ, техпроцесс 32 нм, сокет AM3+, TDP 95W','2 257 600','22amd1'),(56,30,'AMD FX-6300 (FD6300WMW6KHK) ','Vishera, 6 ядер, частота 3.5 ГГц, кэш 6 МБ + 8 МБ, техпроцесс 32 нм, сокет AM3+, TDP 95W','1 982 300','22amd2'),(57,31,'Samsung 850 Pro 256GB (MZ-7KE256BW) ','2.5\", SATA 6Gbps, контроллер Samsung S4LN045X01-8030, микросхемы 3D V-NAND, последовательный доступ: 550/520 MBps, случайный доступ: 100000/90000 IOps','2 509 900','ssd1'),(58,31,'Samsung 850 Evo 250GB (MZ-75E250) ','2.5\", SATA 6Gbps, контроллер Samsung MGX, микросхемы 3D V-NAND, последовательный доступ: 540/520 MBps','1 777 000','ssd2'),(59,32,'WD Blue 500GB (WD5000LPVX) ','2.5\", SATA 3.0 (6Gbps), 5400 об/мин, буфер 8 МБ','810 300','2hd1'),(60,32,'Hitachi Travelstar 5K1000 1TB ','2.5\", SATA 3.0 (6Gbps), 5400 об/мин, буфер 8 МБ, время доступа 12 мс','949 700','2hd2'),(61,32,'Seagate Barracuda 7200.14 1TB ','3.5\", SATA 3.0 (6Gbps), 7200 об/мин, буфер 64 МБ, линейная скорость 156/156 МБ/с, время доступа 8.5 мс','895 500','3hd1'),(62,32,'WD Caviar Blue 1TB (WD10EZEX) ','3.5\", SATA 3.0 (6Gbps), 7200 об/мин, буфер 64 МБ, линейная скорость 150/150 МБ/с','949 200','3hd2'),(63,35,'Silicon-Power Armor A60 1TB','2.5\", корпус: пластик/резина, USB 2.0, USB 3.0','1 259 600','ex1'),(64,35,'WD Elements Portable 2TB ','2.5\", корпус: пластик, USB 2.0, USB 3.0','1 702 000','ex2'),(65,35,'Transcend StoreJet 25H3P 1TB','2.5\", корпус: резина, USB 3.0','1 151 800','ex3'),(66,36,'SVEN HT-200 ','5.1, усилитель 80 Вт, пульт, радио, карты памяти','1 298 500','column1'),(67,36,'Microlab Solo-7C ','2.0, усилитель 110 Вт, пульт','2 543 700','column2'),(68,36,'Microlab Solo 8C ','2.0, усилитель 120 Вт, пульт, S/PDIF','2 413 000','column3'),(69,37,'A4Tech Bloody G501','гарнитура для игр, мониторные, оформление закрытое, излучатель 40 мм, 20-20000 Гц, 32 Ом, кабель 2.2 м','750 300','headphones1'),(70,37,'SteelSeries Siberia Raw','гарнитура для игр, излучатель 40 мм, 20-20000 Гц, 32 Ом, кабель 1.2 м, для Sony PlayStation 4','662 600','headphones2'),(71,37,'Philips SHM6500','гарнитура для игр/для общения, мониторные, оформление открытое, излучатель 40 мм, 16-22000 Гц, 16 Ом, кабель 2 м','336 900','headphones3'),(72,38,'A4Tech Bloody B120','игровая, проводная (USB), для ПК, подсветка, влагозащита','524 200','1keybord1'),(73,38,'A4Tech Bloody Q100 ','игровая, проводная (USB, PS/2), для ПК, 104 кл., влагозащита','256 300','1keybord2'),(74,38,'Logitech K120','офисная, проводная (USB), для ПК, 104 кл.','135 500','1keybord3'),(75,38,'Logitech Wireless Combo MK240','мышь + клавиатура, для ПК, мышь радио (сенсор оптический), клавиатура радио','420 000','2keybord1'),(76,38,'Logitech Wireless Combo MK330 ','мышь + клавиатура, мышь радио (сенсор оптический), клавиатура радио','615 000','2keybord2'),(77,38,'Logitech Wireless Combo MK220 ','мышь + клавиатура, мышь радио (сенсор оптический), клавиатура радио','324 000','2keybord3'),(78,41,'Elecom Лазерная проводная мышь (13062) ','мышь, проводная (USB), сенсор лазерный, 3 кнопки','200 000','1mouse1'),(79,41,'Elecom Лазерная проводная мышь (13063) ','\r\n\r\nмышь, проводная (USB), сенсор лазерный, 3 кнопки','250 000','1mouse2'),(80,41,'Canyon CNR-MSOW01NP ','мышь + коврик, для ПК, мышь радио (сенсор оптический)','177 600','2mouse1'),(82,44,'ASUS ROG SWIFT PG278Q ','27\", 16:9, 2560x1440, TN+Film, 144 Hz, 3D, интерфейсы DisplayPort','18 000 000','33asus1  '),(83,44,'ASUS VX239H ','23\", 16:9, 1920x1080, AH-IPS, динамики, интерфейсы D-Sub (VGA)+HDMI+MHL','3 741 400','33asus2'),(84,44,'ASUS MG279Q','27\", 16:9, 2560x1440, IPS, 144 Hz, Flicker-free, динамики, интерфейсы HDMI+DisplayPort+Mini DisplayPort','13 014 000','33asus3'),(85,45,'Samsung S24D590PL','23.6\", 16:9, 1920x1080, PLS, 60 Hz, интерфейсы D-Sub (VGA)+HDMI','3 635 800','34samsung1'),(86,45,'Samsung S24D300H ','24\", 16:9, 1920x1080, TN+Film, 60 Hz, интерфейсы D-Sub (VGA)+HDMI','2 924 500','34samsung2'),(87,45,'Samsung S24E390HL','23.6\", 16:9, 1920x1080, PLS, 60 Hz, интерфейсы D-Sub (VGA)+HDMI','3 233 100','34samsung3'),(88,46,'LG 25UM57 ','25\", 21:9, 2560x1080, IPS, интерфейсы HDMI','3 716 800','35lg1'),(89,46,'LG 24MP47HQ-P ','23.8\'\', 16:9, 1920x1080, IPS, 60 Hz, Flicker-free, интерфейсы HDMI+D-Sub (VGA)','3 119 700','35lg2'),(90,46,'LG 29UM67','29\", 21:9, 2560x1080, IPS, динамики, интерфейсы DVI+HDMI+DisplayPort','6 156 400','35lg3'),(91,47,'Lenovo ThinkCentre S310','18.5\" 1366 x 768, AMD E1 1200 1400 МГц, 4 ГБ, HDD 500 Гб, AMD Radeon HD 7310, DOS, цвет черный','5 789 000','36lenovo1'),(92,47,'Lenovo ThinkCentre E63z (10E0000XRU) ','19.5\" 1600 x 900, глянцевый, Intel Core i3 4005U 1700 МГц, 4 ГБ, HDD 500 Гб, Intel HD Graphics 4400, DOS, цвет черный','14 076 900','36lenovo2'),(93,47,'Lenovo B40-30 (F0AW003CUA) ','21.5\" 1920 x 1080, глянцевый, сенсорный, Intel Core i5 4460T 1900 МГц, 6 ГБ, HDD + SSD 1000 ГБ, NVIDIA GeForce 820A, Windows 8.1, цвет черный/серый','20 236 000','36lenovo3'),(94,48,'HP ENVY Beats Special Edition 23-n200u','23\" 1920 x 1080, сенсорный, Intel Core i5 4460T 1900 МГц, 8 ГБ, HDD 1000 ГБ, Intel HD Graphics 4600, Windows 8.1, цвет черный/красный','17 937 100','37hp1'),(95,48,'HP ENVY Recline 23-k300nr (K2B38EA) ','23\" 1920 x 1080, глянцевый, сенсорный, Intel Core i5 4590T 2000 МГц, 8 ГБ, HDD + SSD 1000 ГБ, NVIDIA GeForce 830A, Windows 8.1, цвет черный/серебристый','23 638 600','37hp2'),(96,48,'HP ProOne 400 G1 (G9E77EA)','23\" 1920 x 1080, матовый, Intel Core i5 4590T 2000 МГц, 4 ГБ, HDD 500 Гб, Intel HD Graphics 4600, DOS, цвет черный/серебристый','15 052 000','37hp3'),(97,49,'Zalman ZM-Z1 Black ','без блока питания Tower, для плат ATX/micro-ATX, USB 3.0','644 400','case1'),(98,49,'Zalman Z9 U3 Black ','без блока питания Tower, для плат ATX/micro-ATX/mini-ITX, USB 3.0','1 141 000','case2'),(99,49,'Zalman R1 ','без блока питания Tower, для плат ATX/micro-ATX/ITX, USB 3.0','856 800','case3'),(100,50,'AeroCool Kcas 600W ','600 Вт, активная PFC, КПД 85%, бронзовый сертификат 80 PLUS','893 300','power1'),(101,50,'FSP Qdion QD500 500W ','500 Вт, активная PFC','594 000','power2'),(102,50,'Zalman ZM500-LE 500W ','500 Вт, КПД 81%','675 900','power3'),(103,51,'NCP DDR PC-3200 512 Мб','частота 400 МГц, напряжение 2.5 В','168 500','ddr11'),(104,51,'Samsung DDR PC-3200 1 Гб','частота 400 МГц, CL 3T, тайминги 3-3-3, напряжение 2.5 В','415 900','ddr12'),(105,52,'Hynix DDR2 PC2-6400 2 Гб ','частота 800 МГц, CL 6T, тайминги 6-6-6, напряжение 1.8 В','289 900','ddr21'),(106,52,'NCP DDR2 PC2-6400 2 Гб','частота 800 МГц, напряжение 1.8 В','280 400','ddr22'),(107,53,'Crucial 4GB DDR3 PC3-12800 ','частота 1600 МГц, CL 11T, напряжение 1.5 В','389 400','ddr31'),(108,53,'Hynix DDR3 PC3-10600 4GB','частота 1333 МГц, CL 9T, тайминги 9-9-9, напряжение 1.5 В','521 200','ddr32'),(109,54,'Crucial 4GB DDR4 PC4-17000','частота 2133 МГц, CL 15T, напряжение 1.2 В','962 400','ddr41'),(110,54,'Crucial 8GB DDR4 PC4-17000','частота 2133 МГц, CL 15T, напряжение 1.2 В','922 700','ddr42'),(111,55,'Palit GeForce GTX 750 Ti StormX Dual 2GB','GPU 1202 МГц (640sp), RAM 1502 МГц 128 бит, DirectX 11.2, VGA, DVI, miniHDMI','2 417 400','palit1'),(112,55,'Palit GeForce GTX 750 Ti StormX OC 2GB','GPU 1085 МГц (640sp), RAM 1375 МГц 128 бит, DirectX 11.2, VGA, DVI, miniHDMI','2 257 400','palit2'),(113,55,'Palit GeForce GTX 950 StormX 2GB GDDR5','GPU 1026 МГц (768sp), RAM 1652 МГц 128 бит, DirectX 12, DVI, HDMI, DisplayPort','2 964 400','palit3'),(114,56,'MSI GeForce GTX 970 Gaming 4GB','GPU 1140 МГц (1664sp), RAM 1753 МГц 256 бит, DirectX 12, DVI, HDMI, DisplayPort','6 960 000','45msi1'),(115,56,'MSI GeForce GTX 960 2GB GDDR5','GPU 1241 МГц, RAM 1752 МГц 128 бит, DirectX 12, DVI, HDMI, DisplayPort','4 116 800','45msi2'),(116,56,'MSI GeForce GTX 750 Ti Gaming 2GB','GPU 1020 МГц (640sp), RAM 1350 МГц 128 бит, DirectX 11.2, VGA, DVI, HDMI','3 267 500','45msi3'),(117,57,'Gigabyte GeForce GTX 660 2GB GDDR5','GPU 1033 МГц (960sp), RAM 1502 МГц 192 бит, DirectX 11.1, DVI, HDMI, DisplayPort','4 094 900','46gig1'),(118,57,'Gigabyte GeForce GTX 960 4GB','GPU 1190 МГц (1024sp), RAM 1752 МГц 128 бит, DirectX 12, DVI, HDMI, DisplayPort','3 988 100','46gig2'),(119,57,'Gigabyte GeForce GTX 970 OC 4GB','GPU 1076 МГц (1664sp), RAM 1750 МГц 256 бит, DirectX 12, DVI, HDMI, DisplayPort','5 947 700','46gig3'),(120,58,'Lite-On iHAS124-04 ','DVD Multi, внутренний для ПК, SATA, 2 Мб буфер','281 300','lite1'),(121,58,'Lite-On iHAS122-14','DVD-RW, внутренний для ПК, SATA, 2 МБ буфер','252 600','lite2'),(122,58,'Lite-On eTAU108 ','DVD-RW DL, внешний, USB, 2 Мб буфер, 362.8 гр','711 700','lite3'),(123,59,'LG GH24NSC0','DVD Multi, внутренний для ПК, SATA, 500 КБ буфер','262 900','48lg1'),(124,59,'LG GP60NB60','DVD Multi, USB, 750 КБ буфер','499 200','48lg2'),(125,59,'LG GP60NS60','DVD Multi, USB, 750 КБ буфер','508 200','48lg3'),(126,60,'Samsung SH-224DB/BEBE ','DVD Multi, USB, 750 КБ буфер','508 200','49samsung1'),(127,60,'Samsung SN-208FB/BEBE','DVD-RW, внутренний для ноутбука, SATA, 1 МБ буфер','309 400','49samsung2'),(128,60,'Samsung SE-506AB/TSBD','BD-RE, внешний Slim, USB, 4 Мб буфер','700 000','49samsung3'),(129,61,'Acorp Sprinter@ADSL LAN410 ver2 ','модем, ADSL2+ (G.992.5)/G.992.1 Annex B/G.992.3 Annex M, со сплиттером, 4 LAN FE','250 000','adsl1'),(130,61,'Acorp Sprinter@ADSL LAN110 (Ver 2.0)','модем, ADSL2+ (G.992.5)/G.992.1 Annex B/G.992.3 Annex M, со сплиттером, 1 LAN FE','270 000','adsl2'),(131,61,'Acorp Sprinter@ADSL USB + ','модем, ADSL (G.992.1), со сплиттером','210 000','adsl3'),(132,62,'TP-Link TL-WR841N','802.11n, до 300 Mbps, 4 LAN FE, 1 WAN','315 000','wlan1'),(133,62,'Zyxel Keenetic Lite III','802.11n, до 300 Mbps, доп. SSID (0), 4 LAN FE, 1 WAN','435 000','wlan2'),(134,62,'Zyxel Keenetic Giga II ','802.11n, до 300 Mbps, доп. SSID (0), 4 LAN GbE, 1 WAN, 2 USB','1 247 500','wlan2');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `id_review` int(11) NOT NULL AUTO_INCREMENT,
  `review_date` date NOT NULL,
  `review_name` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `review` varchar(200) NOT NULL,
  `review_moderation` enum('no','yes') NOT NULL,
  PRIMARY KEY (`id_review`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id_product`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (1,'2015-12-07','nik_neman',86,'Хороший монитор. Рекомендую','no'),(4,'2015-12-07','dream',86,'Согласен. Моник хорош','no');
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_table`
--

DROP TABLE IF EXISTS `search_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `search_table` (
  `id_search` int(11) NOT NULL AUTO_INCREMENT,
  `name_search` text NOT NULL,
  `type_search` varchar(30) NOT NULL,
  `link_search` int(11) NOT NULL,
  PRIMARY KEY (`id_search`),
  FULLTEXT KEY `name_search` (`name_search`),
  FULLTEXT KEY `name_search_2` (`name_search`),
  FULLTEXT KEY `name_search_3` (`name_search`)
) ENGINE=MyISAM AUTO_INCREMENT=319 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_table`
--

LOCK TABLES `search_table` WRITE;
/*!40000 ALTER TABLE `search_table` DISABLE KEYS */;
INSERT INTO `search_table` VALUES (160,'Ноутбуки','1',1),(161,'Печатная техника','1',2),(162,'Материнские платы','1',3),(163,'Процессоры','1',4),(164,'Жесткие диски','1',5),(165,'Аудио-видео техника','1',6),(166,'Клавиатуры, мыши','1',7),(167,'Мониторы','1',8),(168,'Моноблоки','1',9),(169,'Корпуса, блоки питания','1',10),(170,'Модули памяти','1',11),(171,'Видеокарты','1',12),(172,'Оптические накопители','1',13),(173,'Сетевое оборудование ','1',14),(174,'Asus ','2',1),(175,'Lenovo','2',2),(176,'MSI','2',3),(177,'МФУ','2',4),(178,'Лазерный принтер','2',8),(179,'AMD','2',11),(180,'Intel','2',20),(181,'Intel','2',29),(182,'AMD','2',30),(183,'SSD','2',31),(184,'HDD диски','2',32),(185,'внешние диски','2',35),(186,'Колонки','2',36),(187,'Наушники','2',37),(188,'Клавиатуры','2',38),(189,'мыши','2',41),(190,'Asus','2',44),(191,'Samsung','2',45),(192,'LG','2',46),(193,'Lenovo','2',47),(194,'HP	','2',48),(195,'Корпуса','2',49),(196,'Блоки питания','2',50),(197,'DDR1','2',51),(198,'DDR2','2',52),(199,'DDR3','2',53),(200,'DDR4','2',54),(201,'Palit','2',55),(202,'MSI','2',56),(203,'Gigabyte','2',57),(204,'Lite-on','2',58),(205,'LG','2',59),(206,'Samsung','2',60),(207,'модемы ADSL','2',61),(208,'WLAN Маршрузаторы','2',62),(209,'ASUS X553MA-XX490D','3',1),(210,'ASUS K501LX-DM060H ','3',2),(211,'ASUS N551JM-CN123H ','3',3),(212,'ASUS X453MA-WX315B ','3',4),(213,'Lenovo Y50-70 (59443090) ','3',5),(214,'Lenovo Z50-70 (59430330) ','3',6),(215,'Lenovo G50-80 (80L000H5NX) ','3',7),(216,'MSI GE70 2PC-070XPL Apache ','3',8),(217,'MSI PE70 2QE-201RU ','3',9),(218,'Canon i-SENSYS MF3010 ','3',10),(219,'Canon PIXMA MG2440 ','3',11),(220,'HP LaserJet Pro M125ra (CZ177A','3',13),(221,'HP LaserJet Pro M1132 MFP (CE8','3',14),(222,'Samsung SL-M2070 ','3',15),(223,'Samsung SCX-3400 ','3',16),(224,'Canon i-SENSYS LBP6030B ','3',18),(225,'Canon i-SENSYS LBP7100Cn','3',19),(226,'HP LaserJet Pro P1102 (CE651A)','3',20),(227,'HP LaserJet Pro P1606dn (CE749','3',21),(228,'Gigabyte GA-F2A78M-D3H (rev. 3','3',22),(229,'ASUS A58M-K ','3',23),(230,'ASRock FM2A58M-VG3+ R2.0','3',39),(231,'Gigabyte GA-970A-DS3P (rev. 1.','3',40),(232,'ASUS M5A78L-M LX3 ','3',41),(233,'ASRock 960GC-GS FX','3',42),(234,'Gigabyte GA-H81M-S2PV (rev. 1.','3',44),(235,'ASUS B85-PLUS ','3',46),(236,'ASRock Z97 Anniversary ','3',47),(237,'Intel Core i7-4790K ','3',52),(238,'Intel Core i7-6700K ','3',53),(239,'Intel Core i5-4460 ','3',54),(240,'AMD FX-8300 (FD8300WMW8KHK)','3',55),(241,'AMD FX-6300 (FD6300WMW6KHK) ','3',56),(242,'Samsung 850 Pro 256GB (MZ-7KE2','3',57),(243,'Samsung 850 Evo 250GB (MZ-75E2','3',58),(244,'WD Blue 500GB (WD5000LPVX) ','3',59),(245,'Hitachi Travelstar 5K1000 1TB ','3',60),(246,'Seagate Barracuda 7200.14 1TB ','3',61),(247,'WD Caviar Blue 1TB (WD10EZEX) ','3',62),(248,'Silicon-Power Armor A60 1TB','3',63),(249,'WD Elements Portable 2TB ','3',64),(250,'Transcend StoreJet 25H3P 1TB','3',65),(251,'SVEN HT-200 ','3',66),(252,'Microlab Solo-7C ','3',67),(253,'Microlab Solo 8C ','3',68),(254,'A4Tech Bloody G501','3',69),(255,'SteelSeries Siberia Raw','3',70),(256,'Philips SHM6500','3',71),(257,'A4Tech Bloody B120','3',72),(258,'A4Tech Bloody Q100 ','3',73),(259,'Logitech K120','3',74),(260,'Logitech Wireless Combo MK240','3',75),(261,'Logitech Wireless Combo MK330 ','3',76),(262,'Logitech Wireless Combo MK220 ','3',77),(263,'Elecom Лазерная проводная мышь','3',78),(264,'Elecom Лазерная проводная мышь','3',79),(265,'Canyon CNR-MSOW01NP ','3',80),(266,'ASUS ROG SWIFT PG278Q ','3',82),(267,'ASUS VX239H ','3',83),(268,'ASUS MG279Q','3',84),(269,'Samsung S24D590PL','3',85),(270,'Samsung S24D300H ','3',86),(271,'Samsung S24E390HL','3',87),(272,'LG 25UM57 ','3',88),(273,'LG 24MP47HQ-P ','3',89),(274,'LG 29UM67','3',90),(275,'Lenovo ThinkCentre S310','3',91),(276,'Lenovo ThinkCentre E63z (10E00','3',92),(277,'Lenovo B40-30 (F0AW003CUA) ','3',93),(278,'HP ENVY Beats Special Edition ','3',94),(279,'HP ENVY Recline 23-k300nr (K2B','3',95),(280,'HP ProOne 400 G1 (G9E77EA)','3',96),(281,'Zalman ZM-Z1 Black ','3',97),(282,'Zalman Z9 U3 Black ','3',98),(283,'Zalman R1 ','3',99),(284,'AeroCool Kcas 600W ','3',100),(285,'FSP Qdion QD500 500W ','3',101),(286,'Zalman ZM500-LE 500W ','3',102),(287,'NCP DDR PC-3200 512 Мб','3',103),(288,'Samsung DDR PC-3200 1 Гб','3',104),(289,'Hynix DDR2 PC2-6400 2 Гб ','3',105),(290,'NCP DDR2 PC2-6400 2 Гб','3',106),(291,'Crucial 4GB DDR3 PC3-12800 ','3',107),(292,'Hynix DDR3 PC3-10600 4GB','3',108),(293,'Crucial 4GB DDR4 PC4-17000','3',109),(294,'Crucial 8GB DDR4 PC4-17000','3',110),(295,'Palit GeForce GTX 750 Ti Storm','3',111),(296,'Palit GeForce GTX 750 Ti Storm','3',112),(297,'Palit GeForce GTX 950 StormX 2','3',113),(298,'MSI GeForce GTX 970 Gaming 4GB','3',114),(299,'MSI GeForce GTX 960 2GB GDDR5','3',115),(300,'MSI GeForce GTX 750 Ti Gaming ','3',116),(301,'Gigabyte GeForce GTX 660 2GB G','3',117),(302,'Gigabyte GeForce GTX 960 4GB','3',118),(303,'Gigabyte GeForce GTX 970 OC 4G','3',119),(304,'Lite-On iHAS124-04 ','3',120),(305,'Lite-On iHAS122-14','3',121),(306,'Lite-On eTAU108 ','3',122),(307,'LG GH24NSC0','3',123),(308,'LG GP60NB60','3',124),(309,'LG GP60NS60','3',125),(310,'Samsung SH-224DB/BEBE ','3',126),(311,'Samsung SN-208FB/BEBE','3',127),(312,'Samsung SE-506AB/TSBD','3',128),(313,'Acorp Sprinter@ADSL LAN410 ver','3',129),(314,'Acorp Sprinter@ADSL LAN110 (Ve','3',130),(315,'Acorp Sprinter@ADSL USB + ','3',131),(316,'TP-Link TL-WR841N','3',132),(317,'Zyxel Keenetic Lite III','3',133),(318,'Zyxel Keenetic Giga II ','3',134);
/*!40000 ALTER TABLE `search_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `id_section` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `section_photo` varchar(30) NOT NULL,
  PRIMARY KEY (`id_section`),
  KEY `catalog_id` (`catalog_id`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id_catalog`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES (1,1,'Asus ','asus.jpg'),(2,1,'Lenovo','lenovo.jpg'),(3,1,'MSI','msi.png'),(4,2,'МФУ','mfu.jpg'),(8,2,'Лазерный принтер','printer.jpg'),(11,3,'AMD','amd.jpeg'),(20,3,'Intel','intel.png'),(29,4,'Intel','intel.png'),(30,4,'AMD','amd.jpeg'),(31,5,'SSD','ssd.jpg'),(32,5,'HDD диски','hdd.jpg'),(35,5,'внешние диски','external.jpg'),(36,6,'Колонки','column.jpg'),(37,6,'Наушники','head.jpg'),(38,7,'Клавиатуры','keybord.jpg'),(41,7,'мыши','mouse.jpeg'),(44,8,'Asus','asus.jpg'),(45,8,'Samsung','samsung.jpeg'),(46,8,'LG','lg.png'),(47,9,'Lenovo','lenovo.jpg'),(48,9,'HP	','hp.jpg'),(49,10,'Корпуса','corpus.jpg'),(50,10,'Блоки питания','block.jpg'),(51,11,'DDR1','ddr1.jpg'),(52,11,'DDR2','ddr2.jpeg'),(53,11,'DDR3','ddr3.jpg'),(54,11,'DDR4','ddr4.jpg'),(55,12,'Palit','palit.jpeg'),(56,12,'MSI','msi.png'),(57,12,'Gigabyte','gigabyte.jpg'),(58,13,'Lite-on',''),(59,13,'LG',''),(60,13,'Samsung',''),(61,14,'модемы ADSL',''),(62,14,'WLAN Маршрузаторы','');
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'diplom','gHTwRy-cf4Kvk90BgrMh0yAxKxVRARKQ','$2y$13$bLn3OJFXSUUjqPisSHtGeuSrBIQU8.CebxiPKLdxIPTNkzDGC7nyG',NULL,'diplom@mail.ru',10,1448050057,1448050057);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-19 15:35:12
