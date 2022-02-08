/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- speedtest 데이터베이스 구조 내보내기
CREATE DATABASE IF NOT EXISTS `speedtest` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `speedtest`;

-- 테이블 speedtest.speedtest_refresh 구조 내보내기
CREATE TABLE IF NOT EXISTS `speedtest_refresh` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `command` varchar(50) DEFAULT NULL,
  `message` varchar(50) DEFAULT NULL,
  KEY `PRIMARY KEY` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 speedtest.test_info 구조 내보내기
CREATE TABLE IF NOT EXISTS `test_info` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `time` varchar(50) DEFAULT NULL,
  `server_name` varchar(50) DEFAULT NULL,
  `server_address` varchar(50) DEFAULT NULL,
  `isp_info` varchar(50) DEFAULT NULL,
  `ping` varchar(50) DEFAULT NULL,
  `download_speed` varchar(50) DEFAULT NULL,
  `upload_speed` varchar(50) DEFAULT NULL,
  KEY `PRIMARY KEY` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
