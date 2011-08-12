# --------------------------------------------------------
# Host:                         localhost
# Server version:               5.1.51-community
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3916
# Date/time:                    2011-08-13 03:48:46
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_name` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_groups: ~2 rows (approximately)
/*!40000 ALTER TABLE `auth_groups` DISABLE KEYS */;
INSERT INTO `auth_groups` (`id`, `code_name`, `name`) VALUES
	(1, 'admin', 'Administrators'),
	(2, 'guest', 'Guests');
/*!40000 ALTER TABLE `auth_groups` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_group_permissions
CREATE TABLE IF NOT EXISTS `auth_group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow' COMMENT '''deny'' has a greater priority than an ''allow''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_group_permissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_group_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_group_permissions` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_permissions
CREATE TABLE IF NOT EXISTS `auth_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `resource_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_permissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_permissions` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_users
CREATE TABLE IF NOT EXISTS `auth_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `human_id` int(10) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(75) NOT NULL DEFAULT '',
  `active` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `human_id` (`human_id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_users: ~1 rows (approximately)
/*!40000 ALTER TABLE `auth_users` DISABLE KEYS */;
INSERT INTO `auth_users` (`id`, `human_id`, `login`, `password`, `email`, `active`) VALUES
	(1, 0, 'admin', '5f5abc0e7c47e396b5a3bc7a8d17104d', 'great_muchacho@mail.ru', 'yes');
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_users_groups
CREATE TABLE IF NOT EXISTS `auth_users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_users_groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_users_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_users_groups` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_user_permissions
CREATE TABLE IF NOT EXISTS `auth_user_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `permission_id` int(10) NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_permission_id` (`user_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_user_permissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_user_permissions` ENABLE KEYS */;


# Dumping structure for table ice-storm.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` int(10) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.departments: ~0 rows (approximately)
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_courses
CREATE TABLE IF NOT EXISTS `edu_courses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `shortname` varchar(100) DEFAULT NULL,
  `hours` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учебные курсы\r\n';

# Dumping data for table ice-storm.edu_courses: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_courses` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_course_stages
CREATE TABLE IF NOT EXISTS `edu_course_stages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(10) NOT NULL,
  `stage_name` varchar(50) NOT NULL DEFAULT '',
  `order` int(11) NOT NULL DEFAULT '0',
  `hours` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Этапы учебного курса';

# Dumping data for table ice-storm.edu_course_stages: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_course_stages` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_course_stages` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_course_themes
CREATE TABLE IF NOT EXISTS `edu_course_themes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `stage_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Этап учебного курса',
  `name` varchar(200) NOT NULL,
  `hours` int(11) NOT NULL DEFAULT '1' COMMENT 'Продолжительность темы',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Темы из учебных курсов';

# Dumping data for table ice-storm.edu_course_themes: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_course_themes` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_course_themes` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_curriculums
CREATE TABLE IF NOT EXISTS `edu_curriculums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `terms_count` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учебные планы - школьное/институтское образование и т.д.';

# Dumping data for table ice-storm.edu_curriculums: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculums` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_curriculums` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_groups
CREATE TABLE IF NOT EXISTS `edu_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `curriculum_id` int(10) NOT NULL DEFAULT '0',
  `current_term_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` enum('on','off') NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_groups` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_groups_terms_course_stages
CREATE TABLE IF NOT EXISTS `edu_groups_terms_course_stages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL,
  `term_id` int(10) NOT NULL,
  `course_stage_id` int(10) NOT NULL,
  `teacher_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id_term_id_course_stage_id` (`group_id`,`term_id`,`course_stage_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_groups_terms_course_stages: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_groups_terms_course_stages` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_groups_terms_course_stages` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_lessons
CREATE TABLE IF NOT EXISTS `edu_lessons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(10) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `hours` int(11) NOT NULL DEFAULT '1',
  `homework` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_lessons: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_lessons` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_lessons` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_marks
CREATE TABLE IF NOT EXISTS `edu_marks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date_add` int(10) NOT NULL,
  `estimated_type` enum('student','department') NOT NULL DEFAULT 'student' COMMENT 'Тип оцениваемого объекта',
  `estimated_id` int(11) NOT NULL COMMENT 'Идентификатор оцениваемого объекта',
  `work_type` enum('lesson','term','course','homework') NOT NULL DEFAULT 'lesson' COMMENT 'lesson - оценка за урок, term - итоговая оценка за семестр, course - итоговая оценка за курс, homework - оценка за домашнюю работу',
  `work_id` int(11) NOT NULL,
  `mark_type` enum('mark','absent') NOT NULL DEFAULT 'mark',
  `mark_id` int(11) NOT NULL,
  `correct_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id исправляемой оценки',
  PRIMARY KEY (`id`),
  KEY `date_add` (`date_add`),
  KEY `estimated_type_estimated_id` (`estimated_type`,`estimated_id`),
  KEY `work_type_work_id` (`work_type`,`work_id`),
  KEY `mark_type_mark_id` (`mark_type`,`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Оценки учеников';

# Dumping data for table ice-storm.edu_marks: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_marks` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_mark_absent_types
CREATE TABLE IF NOT EXISTS `edu_mark_absent_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `shortname` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Типы отсутствия на занятии';

# Dumping data for table ice-storm.edu_mark_absent_types: ~5 rows (approximately)
/*!40000 ALTER TABLE `edu_mark_absent_types` DISABLE KEYS */;
INSERT INTO `edu_mark_absent_types` (`id`, `name`, `shortname`) VALUES
	(1, 'Пропуск по неуважительной причине', 'н'),
	(2, 'Пропуск по болезни', 'болен'),
	(3, 'Пропуск по уважительной причине', 'ув'),
	(4, 'Спортивные соревнования', 'спорт'),
	(5, 'Другое', 'др');
/*!40000 ALTER TABLE `edu_mark_absent_types` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_mark_note
CREATE TABLE IF NOT EXISTS `edu_mark_note` (
  `mark_id` int(11) NOT NULL,
  `note` varchar(300) NOT NULL,
  PRIMARY KEY (`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_mark_note: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_mark_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_mark_note` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_students
CREATE TABLE IF NOT EXISTS `edu_students` (
  `human_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_students: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_students` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_students` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_teachers
CREATE TABLE IF NOT EXISTS `edu_teachers` (
  `human_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_teachers: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_teachers` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_teachers` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_terms
CREATE TABLE IF NOT EXISTS `edu_terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `curriculum_id` int(10) NOT NULL,
  `order` int(10) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_terms: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_terms` ENABLE KEYS */;


# Dumping structure for table ice-storm.humans
CREATE TABLE IF NOT EXISTS `humans` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fio` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `phone_number` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.humans: ~0 rows (approximately)
/*!40000 ALTER TABLE `humans` DISABLE KEYS */;
/*!40000 ALTER TABLE `humans` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
