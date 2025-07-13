-- --------------------------------------------------------
-- Host:                         185.237.144.186
-- Server version:               10.2.31-MariaDB - MariaDB Server
-- Server OS:                    Linux
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table u8322042_api.shp_ms_status_invoice
CREATE TABLE IF NOT EXISTS `shp_ms_status_invoice` (
  `id` int(11) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table u8322042_api.shp_ms_status_invoice: 4 rows
/*!40000 ALTER TABLE `shp_ms_status_invoice` DISABLE KEYS */;
INSERT INTO `shp_ms_status_invoice` (`id`, `remarks`, `description`) VALUES
	(0, 'WAITING TO APPROVE PACKING LIST', 'WAITING'),
	(1, 'PACKING LIST APPROVE-INVOICE WAITING', 'APPROVE'),
	(2, 'INVOICE POST', 'APPROVE'),
	(11, 'BLOCK PACKING LIST', 'BLOCK');
/*!40000 ALTER TABLE `shp_ms_status_invoice` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
