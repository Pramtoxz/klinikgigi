/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - dbklinik
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dbklinik` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `dbklinik`;

/*Table structure for table `booking` */

DROP TABLE IF EXISTS `booking`;

CREATE TABLE `booking` (
  `idbooking` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `id_pasien` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `idjadwal` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `idjenis` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `status` enum('diproses','diterima','ditolak') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'diproses',
  `bukti_bayar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idbooking`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `booking` */

/*Table structure for table `dokter` */

DROP TABLE IF EXISTS `dokter`;

CREATE TABLE `dokter` (
  `id_dokter` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text,
  `tgllahir` date DEFAULT NULL,
  `nohp` char(30) DEFAULT NULL,
  `jenkel` enum('L','P') DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_dokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `dokter` */

insert  into `dokter`(`id_dokter`,`nama`,`alamat`,`tgllahir`,`nohp`,`jenkel`,`iduser`,`created_at`,`updated_at`,`deleted_at`) values 
('DK0001','Pramtoxz','Jl. Padang','2025-07-03','08129323923','L',0,'2025-07-03 06:30:48','2025-07-16 14:47:48',NULL);

/*Table structure for table `jadwal` */

DROP TABLE IF EXISTS `jadwal`;

CREATE TABLE `jadwal` (
  `idjadwal` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hari` varchar(50) NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `iddokter` char(30) NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idjadwal`,`hari`,`waktu_mulai`,`iddokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `jadwal` */

insert  into `jadwal`(`idjadwal`,`hari`,`waktu_mulai`,`waktu_selesai`,`iddokter`,`is_active`,`created_at`,`updated_at`,`deleted_at`) values 
('JD0001','Selasa','08:00:00','12:00:00','DK0001',1,'2025-07-15 15:53:53','2025-07-16 03:02:24',NULL),
('JD0002','Senin','08:00:00','13:05:00','DK0001',1,'2025-07-15 16:27:55','2025-07-16 14:54:15',NULL),
('JD0003','Kamis','09:00:00','10:00:00','DK0001',1,'2025-07-16 14:51:31','2025-07-16 14:51:31',NULL);

/*Table structure for table `jenis_perawatan` */

DROP TABLE IF EXISTS `jenis_perawatan`;

CREATE TABLE `jenis_perawatan` (
  `idjenis` char(30) NOT NULL,
  `namajenis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `estimasi` int NOT NULL,
  `harga` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idjenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `jenis_perawatan` */

insert  into `jenis_perawatan`(`idjenis`,`namajenis`,`estimasi`,`harga`,`created_at`,`updated_at`,`deleted_at`) values 
('JP0001','Cabut gigi',30,250000,'2025-07-16 04:03:40','2025-07-16 04:03:40',NULL),
('JP0002','ssc',45,50000,'2025-07-16 14:35:19','2025-07-16 14:35:30',NULL);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(1,'2023-08-01-000001','App\\Database\\Migrations\\CreateUsersTable','default','App',1749937856,1),
(2,'2023-10-10-000001','App\\Database\\Migrations\\CreateOtpCodesTable','default','App',1749937893,2);

/*Table structure for table `obat` */

DROP TABLE IF EXISTS `obat`;

CREATE TABLE `obat` (
  `idobat` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `stok` int DEFAULT NULL,
  `jenis` enum('minum','bahan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idobat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `obat` */

insert  into `obat`(`idobat`,`nama`,`stok`,`jenis`,`keterangan`,`created_at`,`updated_at`,`deleted_at`) values 
('OB0001','DELUXE',0,'bahan','sadasd','2025-07-16 03:53:05','2025-07-16 03:53:05',NULL);

/*Table structure for table `otp_codes` */

DROP TABLE IF EXISTS `otp_codes`;

CREATE TABLE `otp_codes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `otp_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('register','forgot_password') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `otp_code` (`otp_code`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `otp_codes` */

insert  into `otp_codes`(`id`,`email`,`otp_code`,`type`,`is_used`,`expires_at`,`created_at`,`updated_at`) values 
(5,'pramuditometra@gmail.com','411073','register',1,'2025-06-14 22:24:12','2025-06-14 22:14:12','2025-06-14 22:14:50'),
(7,'bossrentalpadang@gmail.com','377888','register',1,'2025-06-14 22:30:04','2025-06-14 22:20:04','2025-06-14 22:20:22'),
(9,'srimulyarni2@gmail.com','866665','register',1,'2025-06-14 22:54:28','2025-06-14 22:44:28','2025-06-14 22:45:35'),
(10,'rindianir573@gmail.com','216643','register',1,'2025-06-28 10:39:30','2025-06-28 10:29:30','2025-06-28 10:30:11'),
(11,'03xa8cfygp@cross.edu.pl','678301','register',1,'2025-07-03 07:31:50','2025-07-03 07:21:50','2025-07-03 07:22:22'),
(12,'putrialifianoerbalqis@gmail.com','531028','register',1,'2025-07-03 14:35:06','2025-07-03 14:25:06','2025-07-03 14:26:15'),
(13,'gamingda273@gmail.com','119943','register',1,'2025-07-16 04:45:40','2025-07-16 04:35:40','2025-07-16 04:36:06');

/*Table structure for table `pasien` */

DROP TABLE IF EXISTS `pasien`;

CREATE TABLE `pasien` (
  `id_pasien` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text,
  `tgllahir` date DEFAULT NULL,
  `nohp` char(30) DEFAULT NULL,
  `jenkel` enum('L','P') DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pasien` */

insert  into `pasien`(`id_pasien`,`nama`,`alamat`,`tgllahir`,`nohp`,`jenkel`,`iduser`,`created_at`,`updated_at`,`deleted_at`) values 
('PS0001','Pramudito Metra','Jl. Padang','2025-07-03','08129323923','L',3,'2025-07-03 06:30:48','2025-07-03 06:30:48',NULL),
('PS0002','Cimul','ss','2025-07-09','081234256734','P',5,'2025-07-09 02:20:38','2025-07-15 14:32:09',NULL),
('PS0003','Tari','asdasd','2025-07-15','08743557687','L',NULL,'2025-07-15 16:16:54','2025-07-15 16:16:54',NULL),
('PS0004','Agus Saputra','asdasd','2025-07-15','06754343212','L',NULL,'2025-07-15 16:25:30','2025-07-15 16:25:30',NULL),
('PS0005','Taris','sdsds','2025-07-16','08123123123123','P',16,'2025-07-16 14:10:56','2025-07-16 14:46:45',NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'admin, user, dll',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`role`,`status`,`last_login`,`remember_token`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'balqis','admin@example.com','$2y$10$hI1mC1S1wh2sz1NqPDgDl.I.ZM9sjbmqm4aiFI6lzzB7XgOvZgnhe','admin','active','2025-07-16 13:45:38',NULL,'2025-06-14 21:50:56','2025-06-14 21:50:56',NULL),
(3,'pramudito','pramuditometra2@gmail.com','$2y$10$/sKJ3nocDTaEBZwfqWvNj.H08jfcrWSolaA7F6buM7Tq2hYdwg.cK','user','active','2025-06-14 22:14:59',NULL,'2025-06-14 22:14:50','2025-06-14 22:14:50',NULL),
(4,'boss','bossrentalpadang@gmail.com','$2y$10$x1Sb65DdkNNlpU02EiOHcuP.YW1BbF29e4HB8LD14jMqbnV8k4vpG','user','active',NULL,NULL,'2025-06-14 22:20:22','2025-06-14 22:20:22',NULL),
(5,'cimul','srimulyarni2@gmail.com','$2y$10$qLdPOp12x6mohcK9q3FG1.5l/pymdxPRhOVTSuf7PWKDHjuiEZ6Fm','user','active','2025-06-14 22:46:26',NULL,'2025-06-14 22:45:35','2025-06-14 22:45:35',NULL),
(7,'pramtoxz','pramtoxz@gmail.com','$2y$10$/mOhlx0mFM/sLkcdDI7ijOdu48p9dg.j3FZLqtnqZtJawqB24w1le','dokter','active',NULL,NULL,'2025-06-23 19:32:30','2025-06-23 19:32:30',NULL),
(8,'prarram','prararm@gmail.com','$2y$10$Oa66DQC76UttSvugCcqxFeYM0yRC/1cp9dyQwKPQAHeA/KLepSE8.','user','active',NULL,NULL,'2025-06-23 19:36:13','2025-06-23 19:36:13',NULL),
(9,'Rindiani','rindianir573@gmail.com','$2y$10$iF4y9bw3chbQ//818ZYDkuX4JsjHLCqdgP39YZPFRR.oFueUc7vNq','user','active','2025-06-28 10:42:11',NULL,'2025-06-28 10:30:11','2025-06-28 10:30:11',NULL),
(10,'akademis','03xa8cfygp@cross.edu.pl','$2y$10$XDoOZvMEUQ424rV4VXBkhOlbc52IVwTwTJpqzSp5ItkOmk/hmE9ZC','user','active','2025-07-03 07:22:37',NULL,'2025-07-03 07:22:22','2025-07-03 07:22:22',NULL),
(11,'balqisa','putrialifianoerbalqis@gmail.com','$2y$10$LDX08rQsEptfP1g/fp5kGuHBL70c99FOjCJeD0d6RvRm3sxQwR9hW','user','active','2025-07-03 14:27:34',NULL,'2025-07-03 14:26:15','2025-07-03 14:26:15',NULL),
(13,'gaming','gamingda273@gmail.com','$2y$10$lFUjQkkArXn3..WQrXadD.APWNfBnNVN1cWpI/42B1.LktGT55ra.','user','active','2025-07-16 04:36:18',NULL,'2025-07-16 04:36:06','2025-07-16 04:36:06',NULL),
(16,'akademis7','password@gmail.com','$2y$10$Tl4glHJOhYfkWhx6TcA5b.7W/kP2awhhA3CG2VEXlWNnMJ7C6Rq9m','user','active',NULL,NULL,'2025-07-16 14:46:45','2025-07-16 14:46:45',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
