# --------------------------------------------------------
# Host:                         127.0.0.1
# Server version:               5.1.55-community
# Server OS:                    Win64
# HeidiSQL version:             6.0.0.3907
# Date/time:                    2011-07-13 19:24:11
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping database structure for ice-storm
CREATE DATABASE IF NOT EXISTS `ice-storm` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ice-storm`;


# Dumping structure for table ice-storm.auth_groups
CREATE TABLE IF NOT EXISTS `auth_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_groups` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_group_permissions
CREATE TABLE IF NOT EXISTS `auth_group_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL,
  `permission_id` int(10) NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow' COMMENT 'deny имеет больший приоритет чем allow',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='У групп приоритет ниже чем у пользователей';

# Dumping data for table ice-storm.auth_group_permissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_group_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_group_permissions` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_permissions
CREATE TABLE IF NOT EXISTS `auth_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code_name` varchar(50) NOT NULL,
  `full_name` varchar(75) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_name` (`code_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_permissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_permissions` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_users
CREATE TABLE IF NOT EXISTS `auth_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `human_id` int(10) NOT NULL DEFAULT '0',
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(75) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `human_id` (`human_id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_users_groups
CREATE TABLE IF NOT EXISTS `auth_users_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_users_groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_users_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_users_groups` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_user_permissions
CREATE TABLE IF NOT EXISTS `auth_user_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `permission_id` int(10) NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow' COMMENT 'deny имеет больший приоритет чем allow',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='У пользователей приоритет выше чем у групп';

# Dumping data for table ice-storm.auth_user_permissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_user_permissions` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
