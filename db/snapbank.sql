/*
 Navicat Premium Data Transfer

 Source Server         : IKI_LOKAL
 Source Server Type    : MySQL
 Source Server Version : 100138 (10.1.38-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : snapbank

 Target Server Type    : MySQL
 Target Server Version : 100138 (10.1.38-MariaDB)
 File Encoding         : 65001

 Date: 22/11/2024 23:44:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for customer
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer`  (
  `id_customer` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'ddmmyyyy99',
  `id_nasabah` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nomor_identitas` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_kelamin` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_aktif` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_user` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_unik` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'enkrip_nomor_identitas',
  `tgl_daftar` date NULL DEFAULT NULL,
  `nomor_hp` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_unik_ktp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_unik_ktp_enkrip` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_customer`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of customer
-- ----------------------------
INSERT INTO `customer` VALUES ('0111202401', '01.0026700', 'ALAN PUJI PRASETYO', 'PERUM PURI PLESUNGAN RAYA RT 002 RW 002 PLESUNGAN GONDANGREJO KARANGANYAR', '3510170107930007', 'L', '1', '2024-11-11', '13:54:54', 'U001', NULL, '2024-11-01', '6287835276369', '0862642617', '6872514189819a5212c5e03e22bf9372f4941acf4419ff817c91408c28c92722');
INSERT INTO `customer` VALUES ('0411202401', '01.0026702', 'MUHAMMAD ARIF ANWAR', 'GODEGAN RT 001 RW 001 KRAGILAN GEMOLONG SRAGEN', '3312110311870004', 'L', '1', '2024-11-11', '09:55:54', 'U001', NULL, '2024-11-04', '082145554410', '', '6872514189819a5212c5e03e22bf9372f4941acf4419ff817c91408c28c92722');

-- ----------------------------
-- Table structure for log_cek_saldo
-- ----------------------------
DROP TABLE IF EXISTS `log_cek_saldo`;
CREATE TABLE `log_cek_saldo`  (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_customer` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_rekening` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_otp` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_log` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 65 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of log_cek_saldo
-- ----------------------------
INSERT INTO `log_cek_saldo` VALUES (40, '0111202401', '5.02.000.00043', '835122', '2024-11-11', '14:00:13', '0');
INSERT INTO `log_cek_saldo` VALUES (41, '0111202401', '5.02.000.00043', '260357', '2024-11-11', '14:01:56', '0');
INSERT INTO `log_cek_saldo` VALUES (42, '0111202401', '5.02.000.00043', '740400', '2024-11-11', '14:03:46', '0');
INSERT INTO `log_cek_saldo` VALUES (43, '0111202401', '5.02.000.00043', '801788', '2024-11-11', '14:05:07', '0');
INSERT INTO `log_cek_saldo` VALUES (44, '0111202401', '5.02.000.00043', '247732', '2024-11-11', '14:06:07', '0');
INSERT INTO `log_cek_saldo` VALUES (45, '0111202401', '5.03.000.00032', '644920', '2024-11-11', '14:06:26', '0');
INSERT INTO `log_cek_saldo` VALUES (46, '0111202401', '5.02.000.00043', '817296', '2024-11-11', '14:07:17', '0');
INSERT INTO `log_cek_saldo` VALUES (47, '0111202401', '5.02.000.00043', '641080', '2024-11-11', '14:08:41', '0');
INSERT INTO `log_cek_saldo` VALUES (48, '0111202401', '5.02.000.00043', '219797', '2024-11-11', '14:11:55', '0');
INSERT INTO `log_cek_saldo` VALUES (49, '0111202401', '5.02.000.00043', '434805', '2024-11-11', '15:50:16', '0');
INSERT INTO `log_cek_saldo` VALUES (50, '0111202401', '5.02.000.00043', '258307', '2024-11-11', '22:22:41', '0');
INSERT INTO `log_cek_saldo` VALUES (51, '0111202401', '5.03.000.00032', '754238', '2024-11-11', '23:24:59', '1');
INSERT INTO `log_cek_saldo` VALUES (52, '0111202401', '5.02.000.00043', '910693', '2024-11-11', '23:41:54', '1');
INSERT INTO `log_cek_saldo` VALUES (53, '0111202401', '', '240472', '2024-11-11', '23:42:53', '1');
INSERT INTO `log_cek_saldo` VALUES (54, '0111202401', '', '663108', '2024-11-11', '23:43:30', '1');
INSERT INTO `log_cek_saldo` VALUES (55, '0111202401', '5.02.000.00043', '488335', '2024-11-11', '23:44:44', '1');
INSERT INTO `log_cek_saldo` VALUES (56, '0111202401', '5.02.000.00043', '289828', '2024-11-11', '23:45:06', '1');
INSERT INTO `log_cek_saldo` VALUES (57, '0111202401', '5.02.000.00043', '451685', '2024-11-11', '23:46:03', '1');
INSERT INTO `log_cek_saldo` VALUES (58, '0111202401', '5.02.000.00043', '753311', '2024-11-12', '00:00:38', '0');
INSERT INTO `log_cek_saldo` VALUES (59, '0111202401', '5.02.000.00043', '323812', '2024-11-12', '15:33:01', '1');
INSERT INTO `log_cek_saldo` VALUES (60, '0111202401', '5.02.000.00043', '555692', '2024-11-12', '15:33:28', '0');
INSERT INTO `log_cek_saldo` VALUES (61, '0111202401', '5.02.000.00043', '486483', '2024-11-22', '23:18:22', '0');
INSERT INTO `log_cek_saldo` VALUES (62, '0111202401', '5.02.000.00043', '929182', '2024-11-22', '23:38:17', '1');
INSERT INTO `log_cek_saldo` VALUES (63, '0111202401', '5.02.000.00043', '925448', '2024-11-22', '23:39:29', '0');
INSERT INTO `log_cek_saldo` VALUES (64, '0111202401', '5.02.000.00043', '748088', '2024-11-22', '23:40:11', '0');

-- ----------------------------
-- Table structure for sys_jabatan
-- ----------------------------
DROP TABLE IF EXISTS `sys_jabatan`;
CREATE TABLE `sys_jabatan`  (
  `kode_jabatan` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_jabatan` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kode_jabatan`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sys_jabatan
-- ----------------------------
INSERT INTO `sys_jabatan` VALUES ('J01', 'Direktur Utama');
INSERT INTO `sys_jabatan` VALUES ('J02', 'Direktur YMFK');
INSERT INTO `sys_jabatan` VALUES ('J03', 'Kepala Bagian Operasional');
INSERT INTO `sys_jabatan` VALUES ('J04', 'Kepala Bagian Pemasaran');
INSERT INTO `sys_jabatan` VALUES ('J05', 'Kepala Bagian Kepatuhan, MR & APUPPPT');
INSERT INTO `sys_jabatan` VALUES ('J06', 'Ka Bag Analisa & Penyelesaian Kredit Bermasalah');
INSERT INTO `sys_jabatan` VALUES ('J07', 'Satuan Pengawas Internal');
INSERT INTO `sys_jabatan` VALUES ('J08', 'Staf Teknologi Informasi');
INSERT INTO `sys_jabatan` VALUES ('J09', 'Ka Sub Bag Analisa Kredit');
INSERT INTO `sys_jabatan` VALUES ('J10', 'Ka Sub Bag Kredit Pasar & Umum');
INSERT INTO `sys_jabatan` VALUES ('J11', 'Customer Service');
INSERT INTO `sys_jabatan` VALUES ('J12', 'Admin Kredit');
INSERT INTO `sys_jabatan` VALUES ('J13', 'Pelaksana Kredit Pasar');
INSERT INTO `sys_jabatan` VALUES ('J14', 'Pelaksana Kredit Pegawai');
INSERT INTO `sys_jabatan` VALUES ('J15', 'Pelaksana Kredit UKM');
INSERT INTO `sys_jabatan` VALUES ('J16', 'Kepala Kantor Kas');
INSERT INTO `sys_jabatan` VALUES ('J17', 'Kepala Sub Bagian Teknologi Informasi');
INSERT INTO `sys_jabatan` VALUES ('J18', 'Security');
INSERT INTO `sys_jabatan` VALUES ('J19', 'Teller');
INSERT INTO `sys_jabatan` VALUES ('J20', 'BO Dana');

-- ----------------------------
-- Table structure for sys_log_customer
-- ----------------------------
DROP TABLE IF EXISTS `sys_log_customer`;
CREATE TABLE `sys_log_customer`  (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_customer` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `action` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 34664 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sys_log_customer
-- ----------------------------
INSERT INTO `sys_log_customer` VALUES (34653, '0111202401', '2024-11-22', '23:35:00', 'cekSaldo');
INSERT INTO `sys_log_customer` VALUES (34654, '0111202401', '2024-11-22', '23:35:20', 'setorTunai');
INSERT INTO `sys_log_customer` VALUES (34655, '0111202401', '2024-11-22', '23:38:14', 'cekSaldo');
INSERT INTO `sys_log_customer` VALUES (34656, '0111202401', '2024-11-22', '23:38:17', 'OTP berhasil dikirim');
INSERT INTO `sys_log_customer` VALUES (34657, '0111202401', '2024-11-22', '23:39:27', 'cekSaldo');
INSERT INTO `sys_log_customer` VALUES (34658, '0111202401', '2024-11-22', '23:39:29', 'OTP berhasil dikirim');
INSERT INTO `sys_log_customer` VALUES (34659, '0111202401', '2024-11-22', '23:40:08', 'cekSaldo');
INSERT INTO `sys_log_customer` VALUES (34660, '0111202401', '2024-11-22', '23:40:11', 'OTP berhasil dikirim');
INSERT INTO `sys_log_customer` VALUES (34661, '0111202401', '2024-11-22', '23:40:23', 'Saldo Akhir : 10935127');
INSERT INTO `sys_log_customer` VALUES (34662, '0111202401', '2024-11-22', '23:42:21', 'setorTunai');
INSERT INTO `sys_log_customer` VALUES (34663, '0111202401', '2024-11-22', '23:42:28', 'Transaksi berhasil diproses ID : 71');

-- ----------------------------
-- Table structure for sys_log_reset_password
-- ----------------------------
DROP TABLE IF EXISTS `sys_log_reset_password`;
CREATE TABLE `sys_log_reset_password`  (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_captcha` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hasil` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sys_log_reset_password
-- ----------------------------

-- ----------------------------
-- Table structure for sys_log_unflag_user
-- ----------------------------
DROP TABLE IF EXISTS `sys_log_unflag_user`;
CREATE TABLE `sys_log_unflag_user`  (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_captcha` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hasil` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 167 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sys_log_unflag_user
-- ----------------------------
INSERT INTO `sys_log_unflag_user` VALUES (164, 'U009', '2024-10-09', '14:12:24', 'Berapa hasil dari 2 x 100 = 200', '1');
INSERT INTO `sys_log_unflag_user` VALUES (165, 'U009', '2024-10-09', '14:13:14', 'Berapa hasil dari 78 - 27 = 51', '1');
INSERT INTO `sys_log_unflag_user` VALUES (166, 'U009', '2024-10-10', '10:26:13', 'Berapa hasil dari 53 - 76 = -23', '1');

-- ----------------------------
-- Table structure for sys_log_user
-- ----------------------------
DROP TABLE IF EXISTS `sys_log_user`;
CREATE TABLE `sys_log_user`  (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `page_url` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 34652 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sys_log_user
-- ----------------------------
INSERT INTO `sys_log_user` VALUES (34584, 'U001', '2024-10-09', '09:07:51', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34585, 'U001', '2024-10-09', '09:10:37', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34586, 'U001', '2024-10-09', '09:10:53', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34587, 'U001', '2024-10-09', '09:47:29', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34588, 'U009', '2024-10-09', '10:19:58', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34589, 'U009', '2024-10-09', '14:12:24', 'http://localhost/ANTRIAN_V2/proses/unflag_user_save.php');
INSERT INTO `sys_log_user` VALUES (34590, 'U009', '2024-10-09', '14:12:37', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34591, 'U009', '2024-10-09', '14:13:14', 'http://localhost/ANTRIAN_V2/proses/unflag_user_save.php');
INSERT INTO `sys_log_user` VALUES (34592, 'U009', '2024-10-09', '14:13:27', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34593, 'U009', '2024-10-09', '15:30:08', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34594, 'U001', '2024-10-10', '08:20:28', 'http://localhost/antrian_v2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34595, 'U001', '2024-10-10', '08:20:46', 'http://localhost/antrian_v2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34596, 'U009', '2024-10-10', '09:03:22', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34597, 'U009', '2024-10-10', '10:26:13', 'http://localhost/ANTRIAN_V2/proses/unflag_user_save.php');
INSERT INTO `sys_log_user` VALUES (34598, 'U009', '2024-10-10', '10:26:33', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34599, 'U009', '2024-10-10', '14:17:46', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34600, 'U009', '2024-10-10', '14:22:27', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34601, 'U009', '2024-10-10', '14:23:09', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34602, 'U009', '2024-10-10', '14:24:23', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34603, 'U009', '2024-10-10', '15:28:02', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34604, 'U009', '2024-10-11', '09:15:40', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34605, 'U009', '2024-10-11', '13:20:31', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34606, 'U001', '2024-10-11', '13:27:37', 'http://localhost/ANTRIAN_V2/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34607, 'U001', '2024-10-11', '13:28:23', 'http://localhost/ANTRIAN_V2/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34608, 'U001', '2024-10-29', '08:44:44', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34609, 'U001', '2024-10-29', '09:18:35', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34610, 'U001', '2024-10-29', '09:45:11', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34611, 'U001', '2024-10-29', '14:55:21', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34612, 'U001', '2024-11-01', '16:13:11', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34613, 'U001', '2024-11-01', '16:13:39', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34614, 'U001', '2024-11-01', '19:20:49', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34615, 'U001', '2024-11-01', '21:30:55', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34616, 'U001', '2024-11-04', '09:43:11', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34617, 'U001', '2024-11-04', '10:20:38', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34618, 'U001', '2024-11-04', '13:28:20', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34619, 'U001', '2024-11-04', '16:01:36', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34620, 'U001', '2024-11-05', '09:39:30', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34621, 'U001', '2024-11-05', '11:44:27', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34622, 'U001', '2024-11-05', '22:56:54', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34623, 'U001', '2024-11-05', '23:29:23', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34624, 'U001', '2024-11-07', '07:57:17', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34625, 'U001', '2024-11-07', '08:20:46', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34626, 'U001', '2024-11-07', '08:21:09', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34627, 'U001', '2024-11-07', '10:26:04', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34628, 'U001', '2024-11-07', '12:09:35', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34629, 'U001', '2024-11-07', '16:03:13', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34630, 'U001', '2024-11-08', '07:49:33', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34631, 'U001', '2024-11-08', '08:47:29', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34632, 'U001', '2024-11-09', '15:26:40', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34633, 'U001', '2024-11-09', '16:07:29', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34634, 'U001', '2024-11-11', '08:18:05', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34635, 'U001', '2024-11-11', '10:05:16', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34636, 'U001', '2024-11-11', '13:54:44', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34637, 'U001', '2024-11-11', '13:54:59', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34638, 'U001', '2024-11-12', '09:07:13', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34639, 'U001', '2024-11-12', '09:12:37', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34640, 'U001', '2024-11-14', '11:25:55', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34641, 'U001', '2024-11-14', '12:47:56', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34642, 'U001', '2024-11-20', '12:06:45', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34643, 'U001', '2024-11-20', '13:12:37', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34644, 'U001', '2024-11-20', '23:23:34', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34645, 'U001', '2024-11-20', '23:44:25', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34646, 'U001', '2024-11-21', '16:30:32', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34647, 'U001', '2024-11-21', '16:42:32', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34648, 'U001', '2024-11-22', '09:51:36', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34649, 'U001', '2024-11-22', '10:16:21', 'http://localhost/snapbank/proses/logout.php');
INSERT INTO `sys_log_user` VALUES (34650, 'U001', '2024-11-22', '22:48:19', 'http://localhost/snapbank/proses/cek_login.php');
INSERT INTO `sys_log_user` VALUES (34651, 'U001', '2024-11-22', '23:14:46', 'http://localhost/snapbank/proses/logout.php');

-- ----------------------------
-- Table structure for sys_parameter
-- ----------------------------
DROP TABLE IF EXISTS `sys_parameter`;
CREATE TABLE `sys_parameter`  (
  `kode_parameter` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_parameter` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `value_parameter` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_user` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`kode_parameter`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sys_parameter
-- ----------------------------
INSERT INTO `sys_parameter` VALUES ('P001', 'KODE_TRANS_SETORAN_TABUNGAN', '195', '', '2024-11-20', '12:08:29', 'U001');
INSERT INTO `sys_parameter` VALUES ('P002', 'KODE_TRANS_PENARIKAN_TABUNGAN', '295', '', '2024-11-20', '12:08:42', 'U001');

-- ----------------------------
-- Table structure for sys_user
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user`  (
  `id_pengguna` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_lengkap` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password_user` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag` int NULL DEFAULT NULL,
  `tgl_dibuat` date NULL DEFAULT NULL,
  `tgl_expired` date NULL DEFAULT NULL,
  `latency` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `login_failure` int NULL DEFAULT 0,
  `status_aktif` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `last_login` date NULL DEFAULT NULL,
  `jabatan` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `first_use` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `chat_id` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_cbs` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_kas` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username_cbs` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sys_user
-- ----------------------------
INSERT INTO `sys_user` VALUES ('U001', 'ADMIN', 'ADMIN', '$2y$10$t2roaxXYYDgo1ue55KkbSeS4kl9/odO2MPA8W0E07YEekX52LkEQm', 0, '2020-09-15', '2025-07-09', '1', 0, '1', '2024-11-22', 'J17', '1', NULL, '1096', '10102', 'ALAN');

-- ----------------------------
-- Table structure for transaksi_tab
-- ----------------------------
DROP TABLE IF EXISTS `transaksi_tab`;
CREATE TABLE `transaksi_tab`  (
  `id_trans` int NOT NULL AUTO_INCREMENT,
  `id_customer` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_nasabah` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_rekening` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nominal` double(20, 2) NULL DEFAULT NULL,
  `date_modified` date NULL DEFAULT NULL,
  `time_modified` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_trans` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_trans` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_trans` date NULL DEFAULT NULL,
  `jam_trans` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_user` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_otp` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tabtrans_id` int NULL DEFAULT NULL,
  `status_penyetor` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '1=nasabah sendiri',
  `nama_penyetor` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sumber_dana` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `penggunaan_dana` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_penarik` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_penerima` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_trans`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of transaksi_tab
-- ----------------------------
INSERT INTO `transaksi_tab` VALUES (68, '0111202401', '01.0026700', '5.02.000.00043', 1000000.00, '2024-11-20', '13:08:21', '100', '0', '2024-11-21', '11:32:44', 'U001', '730740', 34542818, '1', 'ALAN PUJI PRASETYO', '', NULL, NULL, NULL);
INSERT INTO `transaksi_tab` VALUES (70, '0111202401', '01.0026700', '5.02.000.00043', 1000000.00, '2024-11-22', '22:54:59', '200', '3', '2024-11-22', '12:36:55', 'U001', '014518', 34542823, NULL, NULL, NULL, '', 'ALAN PUJI PRASETYO', 'ALAN PUJI PRASETYO');
INSERT INTO `transaksi_tab` VALUES (71, '0111202401', '01.0026700', '5.02.000.00043', 500000.00, '2024-11-22', '23:42:27', '100', '0', '2024-11-22', '23:42:27', NULL, NULL, NULL, '1', 'ALAN PUJI PRASETYO', '', NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
