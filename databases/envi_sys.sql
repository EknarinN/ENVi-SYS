-- --------------------------------------------------------
-- Host:                         192.168.111.16
-- Server version:               9.1.0 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for envi_sys
CREATE DATABASE IF NOT EXISTS `envi_sys` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `envi_sys`;

-- Dumping structure for table envi_sys.data_appropriate_value
CREATE TABLE IF NOT EXISTS `data_appropriate_value` (
  `id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสค่ากลางหรือค่าที่เหมาะสม ที่ออกโดยระบบ',
  `name_full_word` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อเรียกของค่าต่างๆ แบบเต็มประโยค',
  `name_abbreviation_word` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อเรียกของค่าต่างๆ แบบคำย่อ',
  `appropriate_value_min` double(7,2) NOT NULL COMMENT 'ค่าต่ำสุดของ ค่ากลาง หรือ ค่าที่เหมาะสม',
  `appropriate_value_max` double(7,2) NOT NULL COMMENT 'ค่าสูงสุดของ ค่ากลาง หรือ ค่าที่เหมาะสม',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_std_appropriate_value_std_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐานหรือรหัสกลางของค่าปกติ ค่ากลาง หรือค่าที่เหมาะสม ในการบันทึกการตรวจน้ำประปา น้ำเสีย น้ำทิ้ง';

-- Dumping data for table envi_sys.data_appropriate_value: ~6 rows (approximately)
INSERT INTO `data_appropriate_value` (`id`, `name_full_word`, `name_abbreviation_word`, `appropriate_value_min`, `appropriate_value_max`, `usage_id`) VALUES
	('AV01', 'Dissolved Oxygen', 'DO', 2.00, 2.00, '1'),
	('AV02', 'Sludge Volume 30', 'SV30', 200.00, 300.00, '1'),
	('AV03', 'Potential of Hydrogen', 'pH', 6.80, 8.20, '1'),
	('AV04', 'Free Chlorine (Day)', 'CL(D)', 0.20, 1.00, '1'),
	('AV05', 'Free Chlorine (Week)', 'CL(W)', 0.20, 0.50, '1'),
	('AV06', 'Residual Chlorine', 'Residual Cl', 0.20, 1.00, '1');

-- Dumping structure for table envi_sys.data_checkpoint
CREATE TABLE IF NOT EXISTS `data_checkpoint` (
  `id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสจุดตรวจหรือสถานที่ ที่ออกโดยระบบ',
  `name_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อจุดตรวจ หรือ สถานที่',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_checkpoint_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐานหรือรหัสกลาง ของจุดตรวจ หรือ สถานที่';

-- Dumping data for table envi_sys.data_checkpoint: ~6 rows (approximately)
INSERT INTO `data_checkpoint` (`id`, `name_location`, `usage_id`) VALUES
	('CP00', 'ไม่ระบุ', '1'),
	('CP01', 'หน้าแฟลตไม้เก่าในโรงพยาบาล', '1'),
	('CP02', 'โรงครัว', '1'),
	('CP03', 'อ่างล้างมือข้างตึก OPD', '1'),
	('CP04', 'อ่างล้างมือ TB', '1'),
	('CP05', 'งานซักฟอก', '1');

-- Dumping structure for table envi_sys.data_coliform_bacteria
CREATE TABLE IF NOT EXISTS `data_coliform_bacteria` (
  `id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสการตรวจพบ Coliform Bacteria ที่ออกโดยระบบ',
  `found` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'การตรวจพบ Coliform Bacteria',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_std_coliform_bacteria_std_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐานหรือรหัสกลาง ของการตรวจพบเชื้อแบคทีเรียโคลิฟอร์ม';

-- Dumping data for table envi_sys.data_coliform_bacteria: ~3 rows (approximately)
INSERT INTO `data_coliform_bacteria` (`id`, `found`, `usage_id`) VALUES
	('CB00', 'ไม่ระบุ', '1'),
	('CB01', 'ตรวจไม่พบ', '1'),
	('CB02', 'ตรวจพบ', '1');

-- Dumping structure for table envi_sys.data_color
CREATE TABLE IF NOT EXISTS `data_color` (
  `id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสของสี ออกโดยระบบ',
  `name_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อสี',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_stdcode_color_stdcode_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของสี';

-- Dumping data for table envi_sys.data_color: ~14 rows (approximately)
INSERT INTO `data_color` (`id`, `name_color`, `usage_id`) VALUES
	('CO00', 'ไม่ระบุ', '1'),
	('CO01', 'ฟ้า', '1'),
	('CO02', 'น้ำเงิน', '1'),
	('CO03', 'ม่วง', '1'),
	('CO04', 'ชมพู', '1'),
	('CO05', 'แดง', '1'),
	('CO06', 'น้ำตาล', '1'),
	('CO07', 'ส้ม', '1'),
	('CO08', 'เหลือง', '1'),
	('CO09', 'เขียว', '1'),
	('CO10', 'ทอง', '1'),
	('CO11', 'เงิน', '1'),
	('CO12', 'ขาว', '1'),
	('CO13', 'ดำ', '1');

-- Dumping structure for table envi_sys.data_defectiveness_of_parts
CREATE TABLE IF NOT EXISTS `data_defectiveness_of_parts` (
  `id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสความชำรุดของชิ้นส่วนอุปกรณ์',
  `name_defectiveness` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ความชำรุดของชิ้นส่วนอุปกรณ์',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_stdcode_defectiveness_of_parts_stdcode_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของความชำรุดของชิ้นส่วนอุปกรณ์';

-- Dumping data for table envi_sys.data_defectiveness_of_parts: ~5 rows (approximately)
INSERT INTO `data_defectiveness_of_parts` (`id`, `name_defectiveness`, `usage_id`) VALUES
	('DP00', 'ไม่ระบุ', '1'),
	('DP01', 'ปกติ', '1'),
	('DP02', 'บกพร่องเล็กน้อย', '1'),
	('DP03', 'แบตเตอรี่อ่อน', '1'),
	('DP04', 'ชำรุด', '1');

-- Dumping structure for table envi_sys.data_departments_group
CREATE TABLE IF NOT EXISTS `data_departments_group` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสกลุ่มงานหลัก ที่ออกโดยระบบ',
  `name_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อกลุ่มงานหลัก',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name_group`) USING BTREE,
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_departments_group_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของกลุ่มงานในโรงพยาบาล';

-- Dumping data for table envi_sys.data_departments_group: ~14 rows (approximately)
INSERT INTO `data_departments_group` (`id`, `name_group`, `usage_id`) VALUES
	(1, 'กลุ่มงานบริหารทั่วไป', '1'),
	(2, 'กลุ่มงานเทคนิคการแพทย์', '1'),
	(3, 'กลุ่มงานทันตกรรม', '1'),
	(4, 'กลุ่มงานเภสัชกรรมและคุ้มครองผู้บริโภค', '1'),
	(5, 'กลุ่มงานการแพทย์', '1'),
	(6, 'กลุ่มงานโภชนศาสตร์', '1'),
	(7, 'กลุ่มงานรังสีวิทยา', '1'),
	(8, 'กลุ่มงานเวชกรรมฟื้นฟู', '1'),
	(9, 'กลุ่มงานประกันสุขภาพ ยุทธศาสตร์', '1'),
	(10, 'กลุ่มงานบริการด้านปฐมภูมิและองค์รวม', '1'),
	(11, 'กลุ่มงานการพยาบาล', '1'),
	(12, 'กลุ่มงานการแพทย์แผนไทยและแพทย์ทางเลือก', '1'),
	(13, 'กลุ่มงานจิตเวชและยาเสพติด', '1'),
	(14, 'กลุ่มงานสุขภาพดิจิทัล', '1');

-- Dumping structure for table envi_sys.data_departments_sub
CREATE TABLE IF NOT EXISTS `data_departments_sub` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสหน่วยงานย่อย ที่ออกโดยระบบ',
  `name_departments` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อหน่วยงานย่อย',
  `departments_group_id` int unsigned NOT NULL COMMENT 'รหัสกลุ่มงานหลัก',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  KEY `department_id` (`departments_group_id`) USING BTREE,
  CONSTRAINT `data_departments_sub_ibfk_1` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`),
  CONSTRAINT `data_departments_sub_ibfk_2` FOREIGN KEY (`departments_group_id`) REFERENCES `data_departments_group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของหน่วยงานย่อยในกลุ่มงาน';

-- Dumping data for table envi_sys.data_departments_sub: ~61 rows (approximately)
INSERT INTO `data_departments_sub` (`id`, `name_departments`, `departments_group_id`, `usage_id`) VALUES
	(1, 'งานการเงินและบัญชี', 1, '1'),
	(2, 'งานพัสดุ', 1, '1'),
	(3, 'งานก่อสร้าง', 1, '1'),
	(4, 'งานซ่อมบำรุง', 1, '1'),
	(5, 'งานธุรการ', 1, '1'),
	(6, 'งานยานพาหนะ', 1, '1'),
	(7, 'งานรักษาความปลอดภัย', 1, '1'),
	(8, 'งานประชาสัมพันธ์', 1, '1'),
	(9, 'งานซักฟอก', 1, '1'),
	(10, 'งานอาคารสถานที่', 1, '1'),
	(11, 'งานการเจ้าหน้าที่', 1, '1'),
	(12, 'งานวิเคราะห์สิ่งตัวอย่างทางห้องปฏิบัติการเทคนิคการแพทย์', 2, '1'),
	(13, 'งานธนาคารเลือดและบริการส่วนประกอบของเลือด', 2, '1'),
	(14, 'งานตรวจ วินิจฉัย บำบัดรักษา ฟื้นฟูสภาพ ส่งเสริม และป้องกันทางทันตกรรม', 3, '1'),
	(15, 'งานบริการเภสัชกรรมผู้ป่วยนอก', 4, '1'),
	(16, 'งานบริการเภสัชกรรมผู้ป่วยใน', 4, '1'),
	(17, 'งานบริหารเวชภัณฑ์', 4, '1'),
	(18, 'งานคุ้มครองผู้บริโภค', 4, '1'),
	(19, 'งานให้คำปรึกษาด้านเภสัชกรรม', 4, '1'),
	(20, 'งานตรวจวินิจฉัย บำบัดรักษาผู้ป่วยนอก ผู้ป่วยอุบัติเหตุฉุกเฉิน ผู้ป่วยใน ผู้ป่วยผ่าตัด และผู้ป่วยคลอด ', 5, '1'),
	(21, 'งานบริการอาหารตามมาตรฐานโภชนาการ', 6, '1'),
	(22, 'งานโภชนบำบัด ให้คำปรึกษา คำแนะนำ ความรู้ด้านโภชนาการและโภชนบำบัด', 6, '1'),
	(23, 'งานตรวจ วินิจฉัย และรักษาโดยรังสีเอ็กซเรย์', 7, '1'),
	(24, 'งานตรวจประเมิน วินิจฉัย และบำบัดความบกพร่องของร่างกายด้วยวิธีทางกายภาพบำบัด', 8, '1'),
	(25, 'งานฟื้นฟูความเสื่อมสภาพความพิการ', 8, '1'),
	(26, 'งานบริหารจัดการข้อมูลผู้มีสิทธิและจำแนกผู้รับบริการ เพื่อให้ได้รับการดูแลตามสิทธิประกันสุขภาพอย่างเหมาะสม', 9, '1'),
	(27, 'งานเรียกเก็บ ตามจ่าย และงานพยาบาลผู้จัดการรายกรณ๊ (Case Management) ผู้รับบริการทุกกลุ่มวัยที่ได้รับบริการสุขภาพทั่วไปหรือเฉพาะกลุ่มโรค รวมทั้งผู้ที่ได้รับความเสียหายจากการบริการสาธารณสุข', 9, '1'),
	(28, 'งานคุ้มครองสิทธิ การรับเรื่องร้องเรียน', 9, '1'),
	(29, 'งานแผนงานและยุทธศาสตร์เครือข่ายสุขภาพ', 9, '1'),
	(30, 'งานสังคมสงเคราะห์ การให้บริการสังคมสงเคราะห์ทางการแพทย์ผู้ป่วยนอก ผู้ป่วยในครอบครัว ผู้ป่วยในชุมชน การบริการคลินิกศูนย์พึ่งได้', 9, '1'),
	(31, 'งานเวชปฏิบัติครอบครัวและชุมชน', 10, '1'),
	(32, 'งานการพยาบาลในชุมชน', 10, '1'),
	(33, 'งานส่งเสริมสุขภาพทุกกลุ่มวัย', 10, '1'),
	(34, 'งานป้องกันและควบคุมโรค และราบาดวิทยา', 10, '1'),
	(35, 'งานอาชีวอนามัย', 10, '1'),
	(36, 'งานสุขภิบาลสิ่งแวดล้อมและศูนย์ความปลอดภัย', 10, '1'),
	(37, 'งานพัฒนาระบบริการปฐมภูมิและสนับสนุนเครือข่าย', 10, '1'),
	(38, 'งานสุขภาพจิตและจิตเวช', 10, '1'),
	(39, 'งานอนามัยโรงเรียน', 10, '1'),
	(40, 'งานสุขภาพภาคประชาชน', 10, '1'),
	(41, 'งานบำบัดยาเสพติด สุรา บุหรี่', 10, '1'),
	(42, 'งานสุขศึกษาและพัฒนาพฤติกรรมสุขภาพ', 10, '1'),
	(43, 'งานการพยาบาล', 11, '1'),
	(44, 'งานการพยาบาลผู้ป่วยนอก', 11, '1'),
	(45, 'งานการพยาบาลผู้ป่วยอุบัติเหตุฉุกเฉินและนิติเวช', 11, '1'),
	(46, 'งานการพยาบาลผู้ป่วยใน', 11, '1'),
	(47, 'งานการพยาบาลผู้ป่วยหนัก', 11, '1'),
	(48, 'งานการพยาบาลผู้ป่วยผ่าตัดและวิสัญญีพยาบาล', 11, '1'),
	(49, 'งานการพยาบาลหน่วยควบคุมการติดเชื้อและงานจ่ายกลาง', 11, '1'),
	(50, 'งานการพยาบาลผู้คลอด', 11, '1'),
	(51, 'งานวิจัยและพัฒนา', 11, '1'),
	(52, 'งานการแพทย์แผนไทยและแพทย์ทางเลือก', 12, '1'),
	(53, 'งานดูแลบำบัดผู้ป่วยจิตเวชผู้ใหญ่และสูงอายุแบบผู้ป่วยนอก ผู้ป่วยใน และชุมชน', 13, '1'),
	(54, 'งานยาเสพติด บุหรี่ สุรา ระดับอำเภอ', 13, '1'),
	(55, 'งานดูแลผู้ป่วยจิตเวชเด็กและวัยรุ่นในเขตอำเภอที่รับผิดชอบ', 13, '1'),
	(56, 'งานส่งเสริมป้องกันปัญหาสุขภาพจิตในกลุ่มเสี่ยง กลุ่มป่วย และครอบครัว ในเขตอำเภอที่รับผิดชอบ', 13, '1'),
	(57, 'งานดูแลช่วยเหลือเด็กและสตรีที่ถูกกระทำรุนแรง และผู้ที่ตั้งครรภ์ไม่พึงประสงค์ (OSSCC : One Stop Service Crisis Center)', 13, '1'),
	(58, 'งานวิกฤติสุขภาพจิต (Mental Crisis Assessment Treatment Team)', 13, '1'),
	(59, 'งานบูรณาการดูแลกลุ่มวัย และโรคเรื้อรังต่างๆ ร่วมกับคณะกรรมการพัฒนาคุณภาพชีวิตระดับอำเภอ (พชอ.) ทั้งในและนอกกระทรวงสาธารณสุข', 13, '1'),
	(60, 'งานสารสนเทศทางการแพทย์', 14, '1'),
	(61, 'งานเวชระเบียน', 14, '1');

-- Dumping structure for table envi_sys.data_emer_lights_installation
CREATE TABLE IF NOT EXISTS `data_emer_lights_installation` (
  `id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสลักษณะการติดตั้งของไฟฉุกเฉิน ออกโดยระบบ',
  `name_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ลักษณะการติดตั้งของไฟฉุกเฉิน',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `data_emer_lights_installation_ibfk_1` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ลักษณะการติดตั้งของไฟฉุกเฉิน';

-- Dumping data for table envi_sys.data_emer_lights_installation: ~5 rows (approximately)
INSERT INTO `data_emer_lights_installation` (`id`, `name_type`, `usage_id`) VALUES
	('ELI00', 'ไม่ระบุ', '1'),
	('ELI01', 'แบบติดเพดาน (Ceiling Mounted)', '1'),
	('ELI02', 'แบบฝังฝ้า (Recessed Mounted)', '1'),
	('ELI03', 'แบบติดผนัง (Wall Mounted)', '1'),
	('ELI04', 'แบบแขวน (Suspended)', '1');

-- Dumping structure for table envi_sys.data_emer_lights_list
CREATE TABLE IF NOT EXISTS `data_emer_lights_list` (
  `id` int unsigned NOT NULL COMMENT 'รหัสของไฟฉุกเฉินแต่ละตัว ที่ออกโดยระบบ',
  `emer_lights_number` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเลขไฟฉุกเฉิน โดยผู้ใช้กำหนดเอง ตัวอย่าง ELN0001 , ELN0002',
  `brand` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ยี่ห้อของไฟฉุกเฉิน',
  `model` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รุ่นของไฟฉุกเฉิน',
  `data_color_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CO00' COMMENT 'รหัสสีของไฟฉุกเฉิน',
  `emer_light_type_id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ELT02' COMMENT 'รหัสประเภทของไฟฉุกเฉิน',
  `size_width` double(7,2) NOT NULL COMMENT 'ขนาดความกว้างของไฟฉุกเฉิน หน่วยเป็น cm. (เซนติเมตร) ',
  `size_height` double(7,2) NOT NULL COMMENT 'ขนาดความสูงของไฟฉุกเฉิน หน่วยเป็น cm. (เซนติเมตร) ',
  `size_thickness` double(7,2) NOT NULL COMMENT 'ขนาดความหนาของไฟฉุกเฉิน หน่วยเป็น cm. (เซนติเมตร) ',
  `weight` double(7,2) NOT NULL COMMENT 'น้ำหนักของไฟฉุกเฉิน หน่วยเป็น kg. (กิโลกรัม)',
  `input_voltage` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'แรงดันไฟเข้า เช่น 220–240V AC / 50Hz.',
  `output_voltage` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'แรงดันไฟออก เช่น 220–240V AC / 50Hz.',
  `power_watt` double(7,2) NOT NULL COMMENT 'กำลังไฟฟ้าต่อหลอด หน่วยเป็น watt. (วัตต์) เช่น 12 watt.',
  `temperature` double(7,2) NOT NULL COMMENT 'อุณหภูมิการใช้งาน หน่วยเป็น องศา C เช่น -10 ถึง 50 องศา C',
  `ingress_protection` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'มาตรฐาน IPXX หรือ ระดับการป้องกันของแข็ง/ของเหลว เช่น IP20',
  `brightness_daylight` double(7,2) NOT NULL COMMENT 'ความสว่างกลางวัน หน่วยเป็น lm (ลูเมน) เช่น 909.7 lm',
  `brightness_nightlight` double(7,2) NOT NULL COMMENT 'ความสว่างกลางคืน หน่วยเป็น lm (ลูเมน) เช่น 909.7 lm',
  `light_distribution_angle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'มุมกระจายแสง หน่วยเป็น องศา เช่น 45 องศา -5,+5',
  `external_material` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'วัสดุภายนอกที่ทำเป็นตัวกล่อง',
  `installation_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ลักษณะการติดตั้งของไฟฉุกเฉิน',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  KEY `installation_type` (`installation_type`),
  KEY `emer_light_type_id` (`emer_light_type_id`),
  CONSTRAINT `FK_data_emer_lights_list_data_emer_lights_installation` FOREIGN KEY (`installation_type`) REFERENCES `data_emer_lights_installation` (`id`),
  CONSTRAINT `FK_data_emer_lights_list_data_emer_lights_type` FOREIGN KEY (`emer_light_type_id`) REFERENCES `data_emer_lights_type` (`id`),
  CONSTRAINT `FK_data_emer_lights_list_data_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง เก็บรายละเอียดของไฟฉุกเฉินแต่ละตัว';

-- Dumping data for table envi_sys.data_emer_lights_list: ~0 rows (approximately)

-- Dumping structure for table envi_sys.data_emer_lights_type
CREATE TABLE IF NOT EXISTS `data_emer_lights_type` (
  `id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสประเภทของไฟฉุกเฉิน ออกโดยระบบ',
  `name_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประเภทของไฟฉุกเฉิน',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `data_emer_lights_type_ibfk_1` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ประเภทของไฟฉุกเฉิน';

-- Dumping data for table envi_sys.data_emer_lights_type: ~7 rows (approximately)
INSERT INTO `data_emer_lights_type` (`id`, `name_type`, `usage_id`) VALUES
	('ELT00', 'ไม่ระบุ', '1'),
	('ELT01', 'ไฟฉุกเฉินสำหรับหนีไฟ (Escape Route Lighting)', '1'),
	('ELT02', 'ไฟฉุกเฉินส่องสว่างพื้นที่ (Area Lighting)', '1'),
	('ELT03', 'ไฟฉุกเฉินสำหรับพื้นที่อันตราย (High Risk Task Area Lighting)', '1'),
	('ELT04', 'ไฟฉุกเฉินแบบคงแสง (Maintained Emergency Lighting)', '1'),
	('ELT05', 'ไฟฉุกเฉินแบบไม่คงแสง (Non-Maintained Emergency Lighting)', '1'),
	('ELT06', 'ไฟฉุกเฉินแบบต่อพ่วง (Central Battery System)', '1');

-- Dumping structure for table envi_sys.data_fire_extinguisher_list
CREATE TABLE IF NOT EXISTS `data_fire_extinguisher_list` (
  `id` int unsigned NOT NULL COMMENT 'รหัสของถังดับเพลิงแต่ละตัว ที่ออกโดยระบบ',
  `fire_extinguisher_number` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเลขของถังดับเพลิง โดยผู้ใช้กำหนดเอง ตัวอย่าง FEN0001 , FEN0002',
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ยี่ห้อของถังดับเพลิง',
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รุ่นของถังดับเพลิง',
  `data_fire_extinguisher_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FE00' COMMENT 'รหัสประเภทของถังดับเพลิง',
  `data_color_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CO00' COMMENT 'สีของถังดับเพลิง',
  `capacity` double(4,2) NOT NULL COMMENT 'น้ำหนักเคมี หน่วยเป็น Kg. (กิโลกรัม)',
  `weight_of_container` double(4,2) NOT NULL COMMENT 'น้ำหนักตัวถัง หน่วยเป็น Kg. (กิโลกรัม)',
  `gross_weight_approx` double(4,2) NOT NULL COMMENT 'น้ำหนักรวม หน่วยเป็น Kg. (กิโลกรัม)',
  `unit_height` double(4,2) NOT NULL COMMENT 'ความสูงของถัง หน่วยเป็น cm. (เซนติเมตร)',
  `diameter` double(4,2) NOT NULL COMMENT 'เส้นผ่านศูนย์กลาง หน่วยเป็น cm. (เซนติเมตร)',
  `propulsion_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PT00' COMMENT 'รหัสแรงขับดันของถังดับเพลิง',
  `working_pressure` double(4,2) NOT NULL COMMENT 'แรงดันใช้งานปกติ หน่วยเป็น Psi. หรือ Pound per Square Inch (ปอนด์ต่อตารางนิ้ว)',
  `test_pressure` double(4,2) NOT NULL COMMENT 'แรงดันขณะทดสอบ หน่วยเป็น Psi. หรือ Pound per Square Inch (ปอนด์ต่อตารางนิ้ว)',
  `discharging_time` int unsigned NOT NULL COMMENT 'ระยะเวลาฉีด หน่วยเป็น Sec. (วินาที)',
  `shooting_range_min` int unsigned NOT NULL COMMENT 'ระยะฉีดใกล้สุด หน่วยเป็น m. (เมตร)',
  `shooting_range_max` int unsigned NOT NULL COMMENT 'ระยะฉีดไกลสุด หน่วยเป็น m. (เมตร)',
  `fire_rating` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประสิทธิภาพและประเภทของไฟที่ถังดับเพลิงนั้นสามารถดับได้ เช่น 4A-5B , 6A-20B',
  `fire_type_a` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'การดับไฟประเภท Class A คือ ไฟที่เกิดจากเชื้อเพลิงของแข็ง เช่น ไม้, กระดาษ, ผ้า ฯลฯ',
  `fire_type_b` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'การดับไฟประเภท Class B คือ ไฟที่เกิดจากของเหลวไวไฟ เช่น น้ำมัน, แอลกอฮอล์, สี ฯลฯ',
  `fire_type_c` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'การดับไฟประเภท Class C คือ ไฟที่เกิดจากอุปกรณ์ไฟฟ้าที่มีกระแสไฟฟ้า ',
  `fire_type_d` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'การดับไฟประเภท Class D คือ ไฟที่เกิดจากโลหะไวไฟ',
  `fire_type_k` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'การดับไฟประเภท Class K คือ ไฟที่เกิดจากน้ำมันและไขมันสำหรับทำอาหาร',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fire_extinguisher_number` (`fire_extinguisher_number`),
  KEY `propulsion_type_id` (`propulsion_type_id`),
  KEY `usage_id` (`usage_id`),
  KEY `fire_extinguisher_color` (`data_color_id`) USING BTREE,
  KEY `fire_extinguisher_type` (`data_fire_extinguisher_type_id`) USING BTREE,
  KEY `fire_type_k` (`fire_type_k`),
  KEY `fire_type_d` (`fire_type_d`),
  KEY `fire_type_c` (`fire_type_c`),
  KEY `fire_type_b` (`fire_type_b`),
  KEY `fire_type_a` (`fire_type_a`),
  CONSTRAINT `FK_stdcode_fire_extinguisher_list_stdcode_color` FOREIGN KEY (`data_color_id`) REFERENCES `data_color` (`id`),
  CONSTRAINT `FK_stdcode_fire_extinguisher_list_stdcode_fire_extinguisher_type` FOREIGN KEY (`data_fire_extinguisher_type_id`) REFERENCES `data_fire_extinguisher_type` (`id`),
  CONSTRAINT `FK_stdcode_fire_extinguisher_list_stdcode_propulsion_type` FOREIGN KEY (`propulsion_type_id`) REFERENCES `data_propulsion_type` (`id`),
  CONSTRAINT `FK_stdcode_fire_extinguisher_list_stdcode_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง เก็บรายละเอียดของถังดับเพลิงแต่ละตัว';

-- Dumping data for table envi_sys.data_fire_extinguisher_list: ~0 rows (approximately)

-- Dumping structure for table envi_sys.data_fire_extinguisher_parts
CREATE TABLE IF NOT EXISTS `data_fire_extinguisher_parts` (
  `id` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสชิ้นส่วนถังดับเพลิง',
  `name_parts` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อเรียกชิ้นส่วนถังดับเพลิง',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_stdcode_fire_extinguisher_parts_stdcode_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของชิ้นส่วนถังดับเพลิง';

-- Dumping data for table envi_sys.data_fire_extinguisher_parts: ~5 rows (approximately)
INSERT INTO `data_fire_extinguisher_parts` (`id`, `name_parts`, `usage_id`) VALUES
	('FEP00', 'ไม่ระบุ', '1'),
	('FEP01', 'สายฉีด', '1'),
	('FEP02', 'คันบังคับ', '1'),
	('FEP03', 'ตัวถัง', '1'),
	('FEP04', 'เกจความดัน', '1');

-- Dumping structure for table envi_sys.data_fire_extinguisher_type
CREATE TABLE IF NOT EXISTS `data_fire_extinguisher_type` (
  `id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสประเภทของถังดับเพลิง ออกโดยระบบ',
  `name_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประเภทของถังดับเพลิง',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_stdcode_fire_extinguisher_type_stdcode_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ประเภทของถังดับเพลิง';

-- Dumping data for table envi_sys.data_fire_extinguisher_type: ~7 rows (approximately)
INSERT INTO `data_fire_extinguisher_type` (`id`, `name_type`, `usage_id`) VALUES
	('FET00', 'ไม่ระบุ', '1'),
	('FET01', 'ชนิดผงเคมีแห้ง (Dry Chemical)', '1'),
	('FET02', 'ชนิดน้ำยาเหลวระเหย (Clean Agent หรือ Halotron)', '1'),
	('FET03', 'ถังดับเพลิงชนิดโฟม (Foam)', '1'),
	('FET04', 'ชนิดก๊าซคาร์บอนไดออกไซด์ (CO2)', '1'),
	('FET05', 'ชนิดน้ำ (Water)', '1'),
	('FET06', 'ชนิดเปียก (Wet Chemical)', '1');

-- Dumping structure for table envi_sys.data_fire_exting_capability
CREATE TABLE IF NOT EXISTS `data_fire_exting_capability` (
  `id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสความสามารถในการดับเพลิงไหม้ ออกโดยระบบ',
  `name_capability` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ความสามารถในการดับเพลิงไหม้',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_is` (`usage_id`) USING BTREE,
  CONSTRAINT `FK_data_fire_extinguishing_capability_data_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ความสามารถในการดับเพลิงไหม้';

-- Dumping data for table envi_sys.data_fire_exting_capability: ~2 rows (approximately)
INSERT INTO `data_fire_exting_capability` (`id`, `name_capability`, `usage_id`) VALUES
	('0', 'ดับเพลิงประเภทนี้ไม่ได้', '1'),
	('1', 'ดับเพลิงประเภทนี้ได้ดี', '1');

-- Dumping structure for table envi_sys.data_fire_type
CREATE TABLE IF NOT EXISTS `data_fire_type` (
  `id` int unsigned NOT NULL COMMENT 'รหัสประเภทของไฟ ออกโดยระบบ',
  `name_fire_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประเภทของไฟ',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'คำอธิบาย ไฟแต่ละประเภทที่ถังดับเพลิงนั้นสามารถดับได้',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_data_fire_type_data_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ประเภทของไฟที่ถังดับเพลิงนั้นสามารถดับได้';

-- Dumping data for table envi_sys.data_fire_type: ~5 rows (approximately)
INSERT INTO `data_fire_type` (`id`, `name_fire_type`, `description`, `usage_id`) VALUES
	(1, 'Class A', 'เป็นเพลิงไหม้ที่เกิดจากของแข็งติดไฟ (Ordinary Combustibles) เช่น ไม้, ผ้า, กระดาษ, พลาสติก, ยาง ฯลฯ', '1'),
	(2, 'Class B', 'เป็นเพลิงไหม้ที่เกิดจากของเหลวติดไฟชนิดต่างๆ (Flammable Liquids) เช่น สารเคมี, น้ำมันเชื้อเพลิง, ก๊าสหุงต้ม, แก้ส, แอลกอฮอล์, สี ฯลฯ', '1'),
	(3, 'Class C', 'เป็นเพลิงไหม้ที่เกิดจากอุปกรณ์ไฟฟ้า หรือ เครื่องใช้ไฟฟ้าที่มีกระแสไฟ (Live Electrical Equipment) เช่น ไฟฟ้ารัดวงจร, สายไฟลัดวงจร ฯลฯ', '1'),
	(4, 'Class D', 'เป็นเพลิงไหม้ที่เกิดจากโลหะติดที่ไวต่อการทำปฏิกิริยากับน้ำและลุกติดไฟ (Combustible Metal) เช่น แมกนีเซียม, ไทเทเนียม, โครเมียม, โซเดียม, ลิเทียม, โปรแตสเซียม ฯลฯ', '1'),
	(5, 'Class K', 'เป็นเพลิงไหม้ที่เกิดจากน้ำมันที่เกิดจากการประกอบอาหาร (Combustible Cooking Media) เช่น นำมันที่ได้จากพืชและสัตว์, น้ำมันพืช, น้ำมันหมู ฯลฯ', '1');

-- Dumping structure for table envi_sys.data_hospital
CREATE TABLE IF NOT EXISTS `data_hospital` (
  `id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสหน่วยงาน ที่ออกโดยระบบ',
  `hosp_id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสหน่วยงาน เป็นรหัสมาตรฐานที่ใช้ทั้งประเทศ',
  `hosp_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อหน่วยงาน',
  `sub_district_id` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสตำบล เป็นรหัสมาตรฐานที่ใช้ทั้งประเทศ',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`,`hosp_id`) USING BTREE,
  UNIQUE KEY `hosp_id` (`hosp_id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_hospital_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของหน่วยบริการสุขภาพ';

-- Dumping data for table envi_sys.data_hospital: ~22 rows (approximately)
INSERT INTO `data_hospital` (`id`, `hosp_id`, `hosp_name`, `sub_district_id`, `usage_id`) VALUES
	('HP001', '10935', 'PCU กำแพง (PCC กำแพง)', '331001', '1'),
	('HP002', '03430', 'รพ.สต. อี่หล่ำ', '331002', '1'),
	('HP003', '03431', 'รพ.สต. ก้านเหลือง', '331003', '1'),
	('HP004', '03432', 'รพ.สต. อ้อมแก้ว', '331003', '1'),
	('HP005', '03433', 'รพ.สต. ทุ่งไชย', '331004', '1'),
	('HP006', '03434', 'รพ.สต. สำโรง (ยางเอือด)', '331005', '1'),
	('HP007', '03435', 'รพ.สต. แขม (โนนแตน)', '331006', '1'),
	('HP008', '03436', 'รพ.สต. หนองไฮ', '331007', '1'),
	('HP009', '03437', 'รพ.สต. ขะยูง', '331008', '1'),
	('HP010', '03438', 'รพ.สต. ตาเกษ', '331010', '1'),
	('HP011', '03439', 'รพ.สต. หัวช้าง', '331011', '1'),
	('HP012', '03440', 'รพ.สต. รังแร้ง (หนองนกเจ่า)', '331012', '1'),
	('HP013', '03441', 'รพ.สต. แต้', '331014', '1'),
	('HP014', '03442', 'รพ.สต. น้ำท่วม', '331015', '1'),
	('HP015', '03443', 'รพ.สต. แข้', '331015', '1'),
	('HP016', '03444', 'รพ.สต. โพธิ์ชัย', '331016', '1'),
	('HP017', '03445', 'รพ.สต. ปะอาว', '331017', '1'),
	('HP018', '03446', 'รพ.สต. พงพรต (หนองห้าง)', '331018', '1'),
	('HP019', '03447', 'รพ.สต. หนองแคน (หนองห้าง)', '331018', '1'),
	('HP020', '03448', 'รพ.สต. หนองหัวหมู (สระกำแพงใหญ่)', '331022', '1'),
	('HP021', '03449', 'รพ.สต. โคกหล่าม', '331024', '1'),
	('HP022', '03450', 'รพ.สต. โคกจาน', '331025', '1');

-- Dumping structure for table envi_sys.data_prefix_name
CREATE TABLE IF NOT EXISTS `data_prefix_name` (
  `id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสคำนำหน้าชื่ ที่ออกโดยระบบ',
  `full_word` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'คำนำหน้าชื่อแบบคำเต็ม',
  `abbreviation_word` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'คำนำหน้าชื่อแบบคำย่อ',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage` (`usage_id`) USING BTREE,
  CONSTRAINT `FK_prefix_name_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของคำนำหน้าชื่อ';

-- Dumping data for table envi_sys.data_prefix_name: ~5 rows (approximately)
INSERT INTO `data_prefix_name` (`id`, `full_word`, `abbreviation_word`, `usage_id`) VALUES
	('PN01', 'เด็กชาย', 'ด.ช.', '1'),
	('PN02', 'เด็กหญิง', 'ด.ญ.', '1'),
	('PN03', 'นาย', 'นาย', '1'),
	('PN04', 'นาง', 'นาง', '1'),
	('PN05', 'นางสาว', 'น.ส.', '1');

-- Dumping structure for table envi_sys.data_propulsion_type
CREATE TABLE IF NOT EXISTS `data_propulsion_type` (
  `id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสประเภทแรงขับดันของถังดับเพลิง ออกโดยระบบ',
  `full_word_en` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อเรียกประเภทแรงขับดันของถังดับเพลิงแบบเต็ม ภาษาอังกฤษ',
  `abbreviation_word_eng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อเรียกประเภทแรงขับดันของถังดับเพลิงแบบย่อ ภาษาอังกฤษ',
  `full_word_th` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อเรียกประเภทแรงขับดันของถังดับเพลิงแบบเต็ม ภาษาไทย',
  `abbreviation_word_th` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อเรียกประเภทแรงขับดันของถังดับเพลิงแบบย่อ  ภาษาไทย',
  `state_of_matter_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SM03' COMMENT 'รหัสสถานะของสสาร',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  KEY `state_of_matter_id` (`state_of_matter_id`),
  CONSTRAINT `FK_stdcode_propulsion_type_stdcode_state_of_matter` FOREIGN KEY (`state_of_matter_id`) REFERENCES `data_state_of_matter` (`id`),
  CONSTRAINT `FK_stdcode_propulsion_type_stdcode_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของประเภทแรงขับดันของถังดับเพลิง';

-- Dumping data for table envi_sys.data_propulsion_type: ~9 rows (approximately)
INSERT INTO `data_propulsion_type` (`id`, `full_word_en`, `abbreviation_word_eng`, `full_word_th`, `abbreviation_word_th`, `state_of_matter_id`, `usage_id`) VALUES
	('PT00', 'Not Specified', 'Not Specified', 'ไม่ระบุ', 'ไม่ระบุ', 'SM00', '1'),
	('PT01', 'Nitrogen', 'N2', 'ไนโตรเจน', 'น.', 'SM03', '1'),
	('PT02', 'Carbon Dioxide', 'CO2', 'คาร์บอนไดออกไซด์', 'CO2', 'SM03', '1'),
	('PT03', 'Helium', 'He', 'ฮีเลียม', 'ฮ.ส.', 'SM03', '1'),
	('PT04', 'Neon', 'Ne', 'นีออน', 'Ne', 'SM03', '1'),
	('PT05', 'Argon', 'Ar', 'อาร์กอน', 'Ar', 'SM03', '1'),
	('PT06', 'Krypton', 'Kr', 'คริปตอน', 'Kr', 'SM03', '1'),
	('PT07', 'Xenon', 'Xe', 'ซีนอน', 'Xe', 'SM03', '1'),
	('PT08', 'Radon', 'Rn', 'เรดอน', 'Rn', 'SM03', '1');

-- Dumping structure for table envi_sys.data_role
CREATE TABLE IF NOT EXISTS `data_role` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสตำแหน่งงาน ที่ออกโดยระบบ',
  `name_role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อตำแหน่งงาน',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name_role`) USING BTREE,
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_positions_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของตำแหน่งงาน';

-- Dumping data for table envi_sys.data_role: ~46 rows (approximately)
INSERT INTO `data_role` (`id`, `name_role`, `usage_id`) VALUES
	(1, 'นักจัดการงานทั้วไป', '1'),
	(2, 'เจ้าพนักงานการเงินและบัญชี', '1'),
	(3, 'นักวิชาการเงินและบัญชี', '1'),
	(4, 'เจ้าพนักงานธุรการ', '1'),
	(5, 'เจ้าพนักงานพัสดุ', '1'),
	(6, 'นักวิชาการพัสดุ', '1'),
	(7, 'นายช่างเทคนิค', '1'),
	(8, 'เจ้าพนักงานโสตทัศนศึกษา', '1'),
	(9, 'นักเทคนิดการแพทย์', '1'),
	(10, 'เจ้าพนักงานวิทยาศาตร์การแพทย์', '1'),
	(11, 'นักวิทยาศาสตร์การแพทย์', '1'),
	(12, 'ทันตแพทย์', '1'),
	(13, 'เจ้าพนักงานทันตสาธารณสุข', '1'),
	(14, 'นักวิชาการสาธารณสุข (ทันตสาธารณสุข)', '1'),
	(15, 'นักสาธารณสุข', '1'),
	(16, 'เภสัชกร', '1'),
	(17, 'เจ้าพนักงานเภสัชกรรม', '1'),
	(18, 'นายแพทย์', '1'),
	(19, 'นักโภชนาการ', '1'),
	(20, 'โภชนาการ', '1'),
	(21, 'นักรังสีการแพทย์', '1'),
	(22, 'เจ้าพนักงานรังสีการแพทย์', '1'),
	(23, 'นักกายภาพบำบัด', '1'),
	(24, 'นักกิจกรรมบำบัด', '1'),
	(25, 'ช่างกายอุปกรณ์', '1'),
	(26, 'เจ้าพนักงานเวชกรรมฟื้นฟู', '1'),
	(27, 'นักวิชาการสาธารณสุข', '1'),
	(28, 'นักสังคมสงเคราะห์', '1'),
	(29, 'พยาบาลวิชาชีพ', '1'),
	(30, 'พยาบาลเทคนิค', '1'),
	(31, 'เจ้าพนักงานสาธารณสุข', '1'),
	(32, 'นักจิตวิทยา', '1'),
	(33, 'นักจิตวิทยาคลินิก', '1'),
	(34, 'นักปฏิบัติการฉุกเฉินการแพทย์', '1'),
	(35, 'นักวิชาการสาธารณสุข (เวชกิจฉุกเฉิน)', '1'),
	(36, 'เจ้าพนักงานสาธารณสุข (เวชกิจฉุกเฉิน)', '1'),
	(37, 'เจ้าพนักงานฉุกเฉินการแพทย์', '1'),
	(38, 'แพทย์แผนไทย', '1'),
	(39, 'เจ้าพนักงานสาธารณสุข (อายุรเวท) (วุฒิปริญญาการแพทย์แผนไทย) ', '1'),
	(40, 'นักเทคโนโลยีสารสนเทศ', '1'),
	(41, 'นักวิชาการคอมพิวเตอร์', '1'),
	(42, 'เจ้าพนักงานเวชสถิติ', '1'),
	(43, 'นักวิชาการสาธารณสุข (เวชสถิติ)', '1'),
	(44, 'ผู้ช่วยเหลือผู้ป่วย', '1'),
	(45, 'เจ้าหน้าที่ทั่วไป', '1'),
	(46, 'วิศวกร', '1');

-- Dumping structure for table envi_sys.data_state_of_matter
CREATE TABLE IF NOT EXISTS `data_state_of_matter` (
  `id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสสถานะของสสาร ออกโดยระบบ',
  `matter_en` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'สถานะของสสาร ภาษาอังกฤษ',
  `matter_th` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'สถานะของสสาร ภาษาไทย',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_stdcode_state_of_matter_stdcode_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของสถานะของสสาร';

-- Dumping data for table envi_sys.data_state_of_matter: ~5 rows (approximately)
INSERT INTO `data_state_of_matter` (`id`, `matter_en`, `matter_th`, `usage_id`) VALUES
	('SM00', 'Not Specified', 'ไม่ระบุ', '1'),
	('SM01', 'Solid', 'ของแข็ง', '1'),
	('SM02', 'Liquid', 'ของเหลว', '1'),
	('SM03', 'Gas', 'แก๊ส', '1'),
	('SM04', 'Plasma', 'พลาสมา', '1');

-- Dumping structure for table envi_sys.data_unit_matrix
CREATE TABLE IF NOT EXISTS `data_unit_matrix` (
  `id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสหน่วยนับ ที่ออกโดยระบบ',
  `name_eng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อหรือคำเรียกหน่วยนับแบบเต็มคำ เป็นภาษาอังกฤษ',
  `name_abbreviation_eng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อหรือคำเรียกหน่วยนับแบบคำย่อ เป็นภาษาอังกฤษ',
  `name_th` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อหรือคำเรียกหน่วยนับแบบเต็มคำ เป็นภาษาไทย',
  `name_abbreviation_th` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อหรือคำเรียกหน่วยนับแบบคำย่อ เป็นภาษาไทย',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `id` (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_unit_matrix_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของหน่วยนับน้ำหนักในระบบ Matrix';

-- Dumping data for table envi_sys.data_unit_matrix: ~5 rows (approximately)
INSERT INTO `data_unit_matrix` (`id`, `name_eng`, `name_abbreviation_eng`, `name_th`, `name_abbreviation_th`, `usage_id`) VALUES
	('UN01', 'Milligram', 'mg', 'มิลลิกรัม', 'มก.', '1'),
	('UN02', 'Gram', 'g', 'กรัม', 'ก.', '1'),
	('UN03', 'Kilogram', 'kg', 'กิโลกรัม', 'กก.', '1'),
	('UN04', 'Ton', 't', 'ตัน', 'ตัน', '1'),
	('UN05', 'Milligram/Liter', 'mg/l', 'มิลลิกรัม/ลิตร', 'มก./ล.', '1');

-- Dumping structure for table envi_sys.data_usage
CREATE TABLE IF NOT EXISTS `data_usage` (
  `id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสสถานะการใช้งาน ที่ออกโดยระบบ',
  `name_usage` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'สถานะการใช้งาน',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของสถานะการใช้งานของรหัสต่างๆ หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ';

-- Dumping data for table envi_sys.data_usage: ~2 rows (approximately)
INSERT INTO `data_usage` (`id`, `name_usage`) VALUES
	('0', 'ยกเลิกใช้งาน'),
	('1', 'ใช้งานกปติ');

-- Dumping structure for table envi_sys.data_waste_group
CREATE TABLE IF NOT EXISTS `data_waste_group` (
  `id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสกลุ่มขยะ ที่ออกโดยระบบ',
  `name_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อกลุ่มขยะ',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_waste_group_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของกลุ่มขยะ';

-- Dumping data for table envi_sys.data_waste_group: ~7 rows (approximately)
INSERT INTO `data_waste_group` (`id`, `name_group`, `usage_id`) VALUES
	('WG00', 'ไม่ระบุ', '1'),
	('WG01', 'ขยะทั่วไป', '1'),
	('WG02', 'ขยะอินทรีย์', '1'),
	('WG03', 'ขยะติดเชื้อ', '1'),
	('WG04', 'ขยะรีไซเคิล', '1'),
	('WG05', 'ขยะอันตราย', '1'),
	('WG99', 'ขยะอื่นๆ', '1');

-- Dumping structure for table envi_sys.data_waste_type
CREATE TABLE IF NOT EXISTS `data_waste_type` (
  `id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสประเภทขยะ ที่ออกโดยระบบ',
  `name_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประเภทขยะ',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  KEY `usage_id` (`usage_id`),
  CONSTRAINT `FK_waste_type_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางรหัสมาตรฐาน หรือ รหัสกลาง ของประเภทของขยะ';

-- Dumping data for table envi_sys.data_waste_type: ~9 rows (approximately)
INSERT INTO `data_waste_type` (`id`, `name_type`, `usage_id`) VALUES
	('WT00', 'ไม่ระบุ', '1'),
	('WT01', 'กระดาษ', '1'),
	('WT02', 'พลาสติก', '1'),
	('WT03', 'แก้ว', '1'),
	('WT04', 'โลหะ', '1'),
	('WT05', 'สารเคมี', '1'),
	('WT06', 'แบตเตอรี่', '1'),
	('WT07', 'หลอดไฟ', '1'),
	('WT99', 'อื่นๆ', '1');

-- Dumping structure for table envi_sys.inspection_fire_extinguisher
CREATE TABLE IF NOT EXISTS `inspection_fire_extinguisher` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสการบันทึกการตรวจสอบถังดับเพลิง ที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูล',
  `time_record` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูล',
  `checkpoint_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CP00' COMMENT 'รหัสจุดตรวจหรือสถานที่',
  `data_fire_extinguisher_list_id` int unsigned NOT NULL COMMENT 'รหัสของถังดับเพลิงแต่ละตัว',
  `date_next_inspection` date NOT NULL COMMENT 'วันที่ที่จะตรวจสอบถังดับเพลิงครั้งถัดไป',
  `user_id` int unsigned NOT NULL COMMENT 'รหัสผู้ใช้งานระบบ (ผู้ที่บันทึกข้อมูล)',
  PRIMARY KEY (`id`),
  KEY `checkpoint_id` (`checkpoint_id`),
  KEY `user_id` (`user_id`),
  KEY `fire_extinguisher_number` (`data_fire_extinguisher_list_id`) USING BTREE,
  CONSTRAINT `FK_fire_extinguisher_std_checkpoint` FOREIGN KEY (`checkpoint_id`) REFERENCES `data_checkpoint` (`id`),
  CONSTRAINT `FK_fire_extinguisher_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_inspection_fire_extinguisher_data_fire_extinguisher_list` FOREIGN KEY (`data_fire_extinguisher_list_id`) REFERENCES `data_fire_extinguisher_list` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางการบันทึกการตรวจสอบอุปกรณ์ ถังดับเพลิง ประจำเดือน';

-- Dumping data for table envi_sys.inspection_fire_extinguisher: ~0 rows (approximately)

-- Dumping structure for table envi_sys.inspection_fire_extinguisher_detail
CREATE TABLE IF NOT EXISTS `inspection_fire_extinguisher_detail` (
  `id` int unsigned NOT NULL COMMENT 'รหัสการบันทึกรายละเอียดการตรวจสอบชิ้นส่วยของถังดับเพลิง',
  `inspection_fire_extinguisher_id` int unsigned NOT NULL COMMENT 'รหัสการบันทึกการตรวจสอบถังดับเพลิง',
  `data_fire_extinguisher_parts_id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสของชิ้นส่วนถังดับเพลิง',
  `data_defectiveness_of_parts_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DP01' COMMENT 'ความชำรุดของชิ้นส่วนอุปกรณ์',
  PRIMARY KEY (`id`),
  KEY `record_fire_extinguisher_id` (`inspection_fire_extinguisher_id`) USING BTREE,
  KEY `stdcode_fire_extinguisher_parts_id` (`data_fire_extinguisher_parts_id`) USING BTREE,
  KEY `stdcode_defectiveness_of_parts_id` (`data_defectiveness_of_parts_id`) USING BTREE,
  CONSTRAINT `FK_fire_extinguisher_detail_defectiveness_of_parts` FOREIGN KEY (`data_defectiveness_of_parts_id`) REFERENCES `data_defectiveness_of_parts` (`id`),
  CONSTRAINT `FK_fire_extinguisher_detail_fire_extinguisher` FOREIGN KEY (`inspection_fire_extinguisher_id`) REFERENCES `inspection_fire_extinguisher` (`id`),
  CONSTRAINT `FK_fire_extinguisher_detail_fire_extinguisher_parts` FOREIGN KEY (`data_fire_extinguisher_parts_id`) REFERENCES `data_fire_extinguisher_parts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อมูลรายละเอียดการตรวจสอบชิ้นส่วนของถังดับเพลิง';

-- Dumping data for table envi_sys.inspection_fire_extinguisher_detail: ~0 rows (approximately)

-- Dumping structure for table envi_sys.inspection_water_daily
CREATE TABLE IF NOT EXISTS `inspection_water_daily` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสการบันทึกข้อมูล ที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูล',
  `time_record` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูล',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกข้อมูลในวัน',
  `aeration_pond_do_front` double(7,2) NOT NULL COMMENT 'ปริมาณ DO หรือ Dissolved Oxygen หน้าเครื่องบ่อเติมอากาศ หน่วยเป็น mg/l',
  `aeration_pond_do_back` double(7,2) NOT NULL COMMENT 'ปริมาณ DO หรือ Dissolved Oxygen หลังเครื่องบ่อเติมอากาศ หน่วยเป็น mg/l',
  `aeration_pond_sv30` double(7,2) NOT NULL COMMENT 'ปริมาณ SV 30 หรือ Sludge Volume 30 ของบ่อเติมอากาศ หน่วยเป็น mg/l',
  `aeration_pond_ph` double(7,2) NOT NULL COMMENT 'ค่า pH หรือ Potential of Hydrogen ของบ่อเติมอากาศ',
  `chlorine_pond_do` double(7,2) NOT NULL COMMENT 'ปริมาณ DO หรือ Dissolved Oxygen ของบ่อสัมผัสคลอรีน หน่วยเป็น mg/l',
  `chlorine_pond_sv30` double(7,2) NOT NULL COMMENT 'ปริมาณ SV 30 หรือ Sludge Volume 30 ของบ่อสัมผัสคลอรีน หน่วยเป็น mg/l',
  `chlorine_pond_ph` double(7,2) NOT NULL COMMENT 'ค่า pH หรือ Potential of Hydrogen ของบ่อสัมผัสคลอรีน',
  `chlorine_pond_free_chlorine` double(7,2) NOT NULL COMMENT 'ปริมาณของคลอรีนอิสระ ของบ่สัมผัสคลอรีน หน่วยเป็น mg/l',
  `electricity_quantity` double(7,2) NOT NULL COMMENT 'ปริมาณการใช้ไฟฟ้าประจำวัน หน่วยเป็น kWh. ',
  `chlorine_amount` double(7,2) NOT NULL COMMENT 'ปริมาณคลอรีนที่ใช้ไปในวันนี้ หน่วยเป็น ลิตร (L)',
  `excess_sediment_pumping_min` int unsigned NOT NULL COMMENT 'เวลาในการสูบตะกอนส่วนเกิน หน่วยเป็น นาที',
  `excess_sediment_pumping_sec` int unsigned NOT NULL COMMENT 'เวลาในการสูบตะกอนส่วนเกิน หน่วยเป็น วินาที',
  `equipment_inspection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ลงข้อมูลอธิบายการตรวจสอบอุปกรณ์',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  PRIMARY KEY (`id`,`date_record`,`times`) USING BTREE,
  KEY `user_id` (`user_id`),
  CONSTRAINT `FK_wastewater_day_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลการตรวจน้ำประจำวัน';

-- Dumping data for table envi_sys.inspection_water_daily: ~0 rows (approximately)

-- Dumping structure for table envi_sys.inspection_water_weekly
CREATE TABLE IF NOT EXISTS `inspection_water_weekly` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสการบันทึกข้อมูล ที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูล',
  `time_record` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูล',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกข้อมูลในวัน',
  `coliform_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CB00' COMMENT 'รหัสการตรวจพบ Coliform Bacteria',
  `checkpoint_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CP00' COMMENT 'รหัสจุดตรวจหรือสถานที่',
  `free_chlorine` double(7,2) NOT NULL COMMENT 'ปริมาณของคลอรีนอิสระของการตรวจประจำสัปดาห์ หน่วยเป็น mg/l',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  PRIMARY KEY (`id`,`date_record`,`times`) USING BTREE,
  KEY `user_id` (`user_id`),
  KEY `coliform` (`coliform_id`) USING BTREE,
  KEY `checkpoint_id` (`checkpoint_id`),
  CONSTRAINT `FK_wastewater_week_std_coliform_bacteria` FOREIGN KEY (`coliform_id`) REFERENCES `data_coliform_bacteria` (`id`),
  CONSTRAINT `FK_water_inspection_weekly_std_checkpoint` FOREIGN KEY (`checkpoint_id`) REFERENCES `data_checkpoint` (`id`),
  CONSTRAINT `inspection_water_weekly_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลการตรวจน้ำประจำสัปดาห์';

-- Dumping data for table envi_sys.inspection_water_weekly: ~0 rows (approximately)

-- Dumping structure for table envi_sys.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `FK_password_resets_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table envi_sys.password_resets: ~3 rows (approximately)
INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`) VALUES
	(15, 8, 'a4a95343ff0bbd930c6104f244890f7160c4652306de1cb35f1313449c24782e', '2025-07-22 10:22:07'),
	(17, 8, '2d0525e20b44c1c0fd710a20333fa7da5b9ad3f6737ac4e91d3bac027bb188e6', '2025-07-22 10:48:44'),
	(18, 8, 'd1c2db902c596298f7db0085a62e46d1b22912670860e39198f1034b3230f318', '2025-07-22 10:58:54');

-- Dumping structure for table envi_sys.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัส User ที่ออกโดยระบบ',
  `prefix_name_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'คำหน้าชื่อ',
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อจริง',
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อสกุล',
  `dob` date NOT NULL COMMENT 'วัน เดือน ปี เกิด',
  `age` int unsigned NOT NULL COMMENT 'อายุ เป็น ปี โดยคำนวนจากคอลัมน์ dob',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อสำหรับ Login หรือ เข้าระบบ',
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสสำหรับ Login หรือ เข้าระบบ',
  `departments_group_id` int unsigned DEFAULT NULL COMMENT 'รหัสกลุ่มงานหลัก',
  `departments_sub_id` int unsigned DEFAULT NULL COMMENT 'รหัสหน่วยงานย่อย',
  `role_id` int unsigned DEFAULT NULL COMMENT 'รหัสตำแหน่งงาน',
  `email_verified_date_at` date DEFAULT NULL COMMENT 'วันที่ที่ยืนยันผ่าน E-mail',
  `email_verified_time_at` time DEFAULT NULL COMMENT 'เวลาที่ยืนยันผ่าน E-mail',
  `email_verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ที่ยืนยันผ่าน E-mail',
  `created_at` timestamp NULL DEFAULT (now()) COMMENT 'เวลาที่สร้าง User',
  `updated_at` timestamp NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP COMMENT 'เวลาที่แก้ไขข้อมูล User',
  `usage_id` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'รหัสสถานะการใช้งาน หากเป็น 0 คือ ยกเลิกใช้งาน หากเป็น 1 คือ ใช้งานปกติ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `id` (`id`),
  KEY `usage_id` (`usage_id`),
  KEY `prefix_name_id` (`prefix_name_id`),
  KEY `sub_department_id` (`departments_sub_id`) USING BTREE,
  KEY `department_id` (`departments_group_id`) USING BTREE,
  KEY `position_id` (`role_id`) USING BTREE,
  CONSTRAINT `FK_users_data_departments_sub` FOREIGN KEY (`departments_sub_id`) REFERENCES `data_departments_sub` (`id`),
  CONSTRAINT `FK_users_prefix_name` FOREIGN KEY (`prefix_name_id`) REFERENCES `data_prefix_name` (`id`),
  CONSTRAINT `FK_users_usage` FOREIGN KEY (`usage_id`) REFERENCES `data_usage` (`id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`departments_group_id`) REFERENCES `data_departments_group` (`id`),
  CONSTRAINT `users_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `data_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อมูลผู้ใช้งานระบบ';

-- Dumping data for table envi_sys.users: ~10 rows (approximately)
INSERT INTO `users` (`id`, `prefix_name_id`, `first_name`, `last_name`, `dob`, `age`, `email`, `username`, `password_hash`, `departments_group_id`, `departments_sub_id`, `role_id`, `email_verified_date_at`, `email_verified_time_at`, `email_verification_token`, `created_at`, `updated_at`, `usage_id`) VALUES
	(1, 'PN03', 'Eknarin', 'Natthaphon', '1982-09-24', 42, 'eknarinnatthaphon@gmail.com', 'EknarinN24', '$2y$12$GWbOSvhp9EFlYUMN.nmWteI8mtfkdCdxTLLF/U.GWSqmB5UnKrZbm', 14, 60, 41, NULL, NULL, NULL, '2025-07-20 23:25:12', '2025-07-22 10:34:20', '1'),
	(2, 'PN03', 'เอกนรินทร์', 'ณัฐภณ', '1982-09-24', 42, 'eknarinnatthaphon@hotmail.com', 'Eknarin', '$2y$12$rITUeN0ddbPSLUITuvve2uxp49oRFgfpRrLjtv2mDoHDczFPl8CEu', 14, 60, 41, NULL, NULL, NULL, '2025-07-21 00:15:49', '2025-07-21 02:49:36', '1'),
	(3, 'PN05', 'สิริคุณ', 'พวงทอง', '1997-09-04', 27, 'sirikun.pt@gmail.com', 'Sirikun', '$2y$12$wmk0thj7YqJT4Rc8IiiEMu6p0hnIKBkEpkmQjbD4v7k/XAd08MI5C', 10, 37, 27, NULL, NULL, NULL, '2025-07-21 02:23:23', '2025-07-23 20:25:35', '1'),
	(4, 'PN05', 'Benjamat', 'Wangnurat', '1987-12-09', 37, 'benjamat1987@gmail.com', 'APPLE', '$2y$12$qe0.0yKaMGh08akzYrga2uYlF2T3unYM/rngRViF/f84Om5TQtb2q', 10, 34, 27, NULL, NULL, NULL, '2025-07-21 03:07:00', '2025-07-21 06:41:00', '1'),
	(5, 'PN03', 'Wanit', 'Jaiya', '1985-09-26', 39, 'wanit.jaiya@gmail.com', 'Wanit', '$2y$12$FrxIZAn8Mx9AMd5orA4wX.2YBjenZSyKJZLNNGuJwM0nHnY3HMXeC', 14, 60, 41, NULL, NULL, NULL, '2025-07-21 09:28:35', '2025-07-21 09:29:41', '1'),
	(6, 'PN03', 'วรวิทย์', 'อมรวัฒนานุกูล', '2001-02-28', 24, 'mk123@gmail.com', 'Mydicksobig59', '$2y$12$tdCuJe87o2Y98pXHsBhcY.YV6UqcRFmHTxAjN3SqvBdvxQ09Bqtpe', 10, 31, 18, '2025-07-21', NULL, NULL, '2025-07-21 11:54:14', '2025-07-21 11:56:45', '1'),
	(7, 'PN01', 'ประวิทย์', 'อ้วนจัง', '1988-03-03', 37, 'zero3214654@gmail.com', 'Hellothailand', '$2y$12$H5P.KRYfBw4.41rJ2ZSHyOLKXQ5BOjkLbmFedbJluFe8SL7.4bZRO', 5, 20, 18, '2025-07-21', NULL, NULL, '2025-07-21 12:01:30', '2025-07-21 12:52:24', '1'),
	(8, 'PN03', 'หนู', 'ทดสอบ', '1989-03-16', 36, 'noonetwork@gmail.com', 'noonetwork', '$2y$12$luhw0t/ozVpQicc6YJHSwujrs2i/SQb7fbnHLj1qxeDR1ZeOfFeY2', 9, 29, 40, '2025-07-21', NULL, NULL, '2025-07-21 12:54:16', '2025-07-21 12:56:13', '1'),
	(13, 'PN05', 'ทิฟา', 'ล็อคฮาร์ท', '1982-09-24', 42, 'dn.premium.001@gmail.com', 'Tifa', '$2y$12$ilTZ7CW8Qqjf/UXdDxfOouGPo8ymmKLV0Q2vVihL0Yx.4CUChVHuy', 14, 60, 41, '2025-07-22', NULL, NULL, '2025-07-22 11:52:09', '2025-07-22 11:54:44', '1'),
	(16, 'PN01', 'ฉัตรมงคล', 'วันพระศรี', '2007-05-05', 18, 'james.wanprasri@gmail.com', 'james.wanprasri', '$2y$12$LX6eVL3KoEJT.sfwSvdiDOLKYUEsJhidhU2.JQF0AEmSmshi4pB9S', 2, 12, 11, '2025-07-24', NULL, NULL, '2025-07-24 15:45:15', '2025-07-24 15:45:48', '1');

-- Dumping structure for table envi_sys.waste_general
CREATE TABLE IF NOT EXISTS `waste_general` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ปัจจุบัน หรือ วันที่บันทึก',
  `time_record` time NOT NULL COMMENT 'เวลาปัจจุบัน หรือ เวลาที่บันทึก',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกในวันนั้นๆ เช่น ครั้งที่ 1 , ครั้งที่ 2',
  `waste_group_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WG01' COMMENT 'รหัสกลุ่มขยะ',
  `waste_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WT00' COMMENT 'รหัสประเภทขยะ',
  `waste_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเหตุ',
  `quantity` double(20,2) NOT NULL COMMENT 'ปริมาณน้ำหนัก เป็นเลขทศนิยม 2 ตำแหน่ง',
  `unit_matrix_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN03' COMMENT 'รหัสหน่วยนับ',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  `date_update` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  `time_update` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  PRIMARY KEY (`id`,`date_record`,`times`) USING BTREE,
  KEY `waste_type` (`waste_type_id`),
  KEY `waste_group_id` (`waste_group_id`),
  KEY `user_id` (`user_id`),
  KEY `unit_matrix_id` (`unit_matrix_id`),
  CONSTRAINT `FK_record_waste_general_stdcode_unit_matrix` FOREIGN KEY (`unit_matrix_id`) REFERENCES `data_unit_matrix` (`id`),
  CONSTRAINT `FK_waste_general_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_waste_general_waste_group` FOREIGN KEY (`waste_group_id`) REFERENCES `data_waste_group` (`id`),
  CONSTRAINT `FK_waste_general_waste_type` FOREIGN KEY (`waste_type_id`) REFERENCES `data_waste_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลขยะทั่วไป';

-- Dumping data for table envi_sys.waste_general: ~3 rows (approximately)
INSERT INTO `waste_general` (`id`, `date_record`, `time_record`, `times`, `waste_group_id`, `waste_type_id`, `waste_note`, `quantity`, `unit_matrix_id`, `user_id`, `date_update`, `time_update`) VALUES
	(1, '2025-07-27', '21:14:27', '1', 'WG01', 'WT01', 'ทดสอบ', 3.00, 'UN03', 1, '0000-00-00', '00:00:00'),
	(2, '2025-08-11', '20:28:06', '1', 'WG01', 'WT02', 'ทดสอบ', 5.00, 'UN03', 1, '0000-00-00', '00:00:00'),
	(3, '2025-08-11', '21:15:07', '2', 'WG01', 'WT02', 'Test', 2.00, 'UN03', 1, '0000-00-00', '00:00:00');

-- Dumping structure for table envi_sys.waste_hazardous
CREATE TABLE IF NOT EXISTS `waste_hazardous` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ปัจจุบัน หรือ วันที่บันทึก',
  `time_record` time NOT NULL COMMENT 'เวลาปัจจุบัน หรือ เวลาที่บันทึก',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกในวันนั้นๆ เช่น ครั้งที่ 1 , ครั้งที่ 2',
  `waste_group_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WG05' COMMENT 'รหัสกลุ่มขยะ',
  `waste_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WT00' COMMENT 'รหัสประเภทขยะ',
  `waste_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเหตุ',
  `quantity` double(20,2) NOT NULL COMMENT 'ปริมาณน้ำหนัก เป็นเลขทศนิยม 2 ตำแหน่ง',
  `unit_matrix_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN03' COMMENT 'รหัสหน่วยนับ',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  `date_update` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  `time_update` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  PRIMARY KEY (`id`,`date_record`,`times`) USING BTREE,
  KEY `waste_type` (`waste_type_id`),
  KEY `waste_group_id` (`waste_group_id`),
  KEY `user_id` (`user_id`),
  KEY `unit_matrix_id` (`unit_matrix_id`),
  CONSTRAINT `FK_record_waste_hazardous_stdcode_unit_matrix` FOREIGN KEY (`unit_matrix_id`) REFERENCES `data_unit_matrix` (`id`),
  CONSTRAINT `FK_waste_hazardous_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_waste_hazardous_waste_group` FOREIGN KEY (`waste_group_id`) REFERENCES `data_waste_group` (`id`),
  CONSTRAINT `waste_hazardous_ibfk_1` FOREIGN KEY (`waste_type_id`) REFERENCES `data_waste_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลขยะอันตราย';

-- Dumping data for table envi_sys.waste_hazardous: ~0 rows (approximately)

-- Dumping structure for table envi_sys.waste_infectious
CREATE TABLE IF NOT EXISTS `waste_infectious` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ปัจจุบัน หรือ วันที่บันทึก',
  `time_record` time NOT NULL COMMENT 'เวลาปัจจุบัน หรือ เวลาที่บันทึก',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกในวันนั้นๆ เช่น ครั้งที่ 1 , ครั้งที่ 2',
  `waste_group_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WG03' COMMENT 'รหัสกลุ่มขยะ',
  `waste_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WT00' COMMENT 'รหัสประเภทขยะ',
  `waste_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเหตุ',
  `quantity` double(20,2) NOT NULL COMMENT 'ปริมาณน้ำหนัก เป็นเลขทศนิยม 2 ตำแหน่ง',
  `unit_matrix_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN03' COMMENT 'รหัสหน่วยนับ',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  `date_update` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  `time_update` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  PRIMARY KEY (`id`,`date_record`,`times`) USING BTREE,
  KEY `waste_type` (`waste_type_id`),
  KEY `waste_group_id` (`waste_group_id`),
  KEY `user_id` (`user_id`),
  KEY `unit_matrix_id` (`unit_matrix_id`),
  CONSTRAINT `FK_record_waste_infectious_stdcode_unit_matrix` FOREIGN KEY (`unit_matrix_id`) REFERENCES `data_unit_matrix` (`id`),
  CONSTRAINT `FK_waste_infectious_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_waste_infectious_waste_group` FOREIGN KEY (`waste_group_id`) REFERENCES `data_waste_group` (`id`),
  CONSTRAINT `waste_infectious_ibfk_1` FOREIGN KEY (`waste_type_id`) REFERENCES `data_waste_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลขยะติดเชื้อ';

-- Dumping data for table envi_sys.waste_infectious: ~0 rows (approximately)

-- Dumping structure for table envi_sys.waste_infectious_shph
CREATE TABLE IF NOT EXISTS `waste_infectious_shph` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ปัจจุบัน หรือ วันที่บันทึก',
  `time_record` time NOT NULL COMMENT 'เวลาปัจจุบัน หรือ เวลาที่บันทึก',
  `hosp_id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสหน่วยงาน เป็นรหัสมาตรฐานที่ใช้ทั้งประเทศ',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกในวันนั้นๆ เช่น ครั้งที่ 1 , ครั้งที่ 2',
  `waste_group_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WG03' COMMENT 'รหัสกลุ่มขยะ',
  `waste_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WT00' COMMENT 'รหัสประเภทขยะ',
  `waste_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเหตุ',
  `quantity` double(20,2) NOT NULL COMMENT 'ปริมาณน้ำหนัก เป็นเลขทศนิยม 2 ตำแหน่ง',
  `unit_matrix_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN03' COMMENT 'รหัสหน่วยนับ',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  `date_update` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  `time_update` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  PRIMARY KEY (`id`,`date_record`,`times`,`hosp_id`) USING BTREE,
  KEY `waste_type` (`waste_type_id`),
  KEY `waste_group_id` (`waste_group_id`),
  KEY `hosp_id` (`hosp_id`),
  KEY `user_id` (`user_id`),
  KEY `unit_matrix_id` (`unit_matrix_id`),
  CONSTRAINT `FK_record_waste_infectious_shph_stdcode_unit_matrix` FOREIGN KEY (`unit_matrix_id`) REFERENCES `data_unit_matrix` (`id`),
  CONSTRAINT `FK_waste_infectious_shph_hospital` FOREIGN KEY (`hosp_id`) REFERENCES `data_hospital` (`hosp_id`),
  CONSTRAINT `FK_waste_infectious_shph_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `waste_infectious_shph_ibfk_1` FOREIGN KEY (`waste_group_id`) REFERENCES `data_waste_group` (`id`),
  CONSTRAINT `waste_infectious_shph_ibfk_2` FOREIGN KEY (`waste_type_id`) REFERENCES `data_waste_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลขยะติดเชื้อจาก รพ.สต.';

-- Dumping data for table envi_sys.waste_infectious_shph: ~0 rows (approximately)

-- Dumping structure for table envi_sys.waste_organic
CREATE TABLE IF NOT EXISTS `waste_organic` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ปัจจุบัน หรือ วันที่บันทึก',
  `time_record` time NOT NULL COMMENT 'เวลาปัจจุบัน หรือ เวลาที่บันทึก',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกในวันนั้นๆ เช่น ครั้งที่ 1 , ครั้งที่ 2',
  `waste_group_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WG02' COMMENT 'รหัสกลุ่มขยะ',
  `waste_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WT00' COMMENT 'รหัสประเภทขยะ',
  `waste_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเหตุ',
  `quantity` double(20,2) NOT NULL COMMENT 'ปริมาณน้ำหนัก เป็นเลขทศนิยม 2 ตำแหน่ง',
  `unit_matrix_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN03' COMMENT 'รหัสหน่วยนับ',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  `date_update` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  `time_update` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  PRIMARY KEY (`id`,`date_record`,`times`) USING BTREE,
  KEY `waste_type` (`waste_type_id`),
  KEY `waste_group_id` (`waste_group_id`),
  KEY `user_id` (`user_id`),
  KEY `unit_matrix_id` (`unit_matrix_id`),
  CONSTRAINT `FK_record_waste_organic_stdcode_unit_matrix` FOREIGN KEY (`unit_matrix_id`) REFERENCES `data_unit_matrix` (`id`),
  CONSTRAINT `FK_waste_organic_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_waste_organic_waste_group` FOREIGN KEY (`waste_group_id`) REFERENCES `data_waste_group` (`id`),
  CONSTRAINT `waste_organic_ibfk_1` FOREIGN KEY (`waste_type_id`) REFERENCES `data_waste_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลขยะอินทรีย์';

-- Dumping data for table envi_sys.waste_organic: ~0 rows (approximately)

-- Dumping structure for table envi_sys.waste_recyclable
CREATE TABLE IF NOT EXISTS `waste_recyclable` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'รหัสที่ออกโดยระบบ',
  `date_record` date NOT NULL COMMENT 'วันที่ปัจจุบัน หรือ วันที่บันทึก',
  `time_record` time NOT NULL COMMENT 'เวลาปัจจุบัน หรือ เวลาที่บันทึก',
  `times` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ครั้งที่บันทึกในวันนั้นๆ เช่น ครั้งที่ 1 , ครั้งที่ 2',
  `waste_group_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WG04' COMMENT 'รหัสกลุ่มขยะ',
  `waste_type_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WT00' COMMENT 'รหัสประเภทขยะ',
  `waste_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'หมายเหตุ',
  `quantity` double(20,2) NOT NULL COMMENT 'ปริมาณน้ำหนัก เป็นเลขทศนิยม 2 ตำแหน่ง',
  `unit_matrix_id` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN03' COMMENT 'รหัสหน่วยนับ',
  `user_id` int unsigned NOT NULL COMMENT 'ID ของ User ที่บันทึกข้อมูล เป็นรหัส User ที่ออกโดยระบบ',
  `date_update` date NOT NULL COMMENT 'วันที่ที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  `time_update` time NOT NULL COMMENT 'เวลาที่บันทึกข้อมูลหรือแก้ไขข้อมูล ',
  PRIMARY KEY (`id`,`date_record`,`times`) USING BTREE,
  KEY `waste_type` (`waste_type_id`),
  KEY `waste_group_id` (`waste_group_id`),
  KEY `user_id` (`user_id`),
  KEY `unit_matrix_id` (`unit_matrix_id`),
  CONSTRAINT `FK_record_waste_recyclable_stdcode_unit_matrix` FOREIGN KEY (`unit_matrix_id`) REFERENCES `data_unit_matrix` (`id`),
  CONSTRAINT `FK_waste_recyclable_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_waste_recyclable_waste_group` FOREIGN KEY (`waste_group_id`) REFERENCES `data_waste_group` (`id`),
  CONSTRAINT `waste_recyclable_ibfk_1` FOREIGN KEY (`waste_type_id`) REFERENCES `data_waste_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกข้อมูลขยะรีไซเคิล';

-- Dumping data for table envi_sys.waste_recyclable: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
