# --------------------------------------------------------
# Host:                         localhost
# Server version:               5.1.51-community - MySQL Community Server (GPL)
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3933
# Date/time:                    2011-09-11 02:03:34
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping database structure for ice-storm
DROP DATABASE IF EXISTS `ice-storm`;
CREATE DATABASE IF NOT EXISTS `ice-storm` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ice-storm`;


# Dumping structure for table ice-storm.auth_groups
DROP TABLE IF EXISTS `auth_groups`;
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
DROP TABLE IF EXISTS `auth_group_permissions`;
CREATE TABLE IF NOT EXISTS `auth_group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow' COMMENT '''deny'' has a greater priority than an ''allow''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`,`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_group_permissions: ~3 rows (approximately)
/*!40000 ALTER TABLE `auth_group_permissions` DISABLE KEYS */;
INSERT INTO `auth_group_permissions` (`id`, `group_id`, `permission_id`, `type`) VALUES
	(1, 1, 1, 'allow'),
	(2, 1, 2, 'allow'),
	(4, 2, 1, 'allow');
/*!40000 ALTER TABLE `auth_group_permissions` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_permissions
DROP TABLE IF EXISTS `auth_permissions`;
CREATE TABLE IF NOT EXISTS `auth_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_permissions: ~2 rows (approximately)
/*!40000 ALTER TABLE `auth_permissions` DISABLE KEYS */;
INSERT INTO `auth_permissions` (`id`, `name`, `description`) VALUES
	(1, 'siski', 'Siski'),
	(2, 'foo', 'Foo permission');
/*!40000 ALTER TABLE `auth_permissions` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_users
DROP TABLE IF EXISTS `auth_users`;
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
	(1, 0, 'admin', 'daf2ef34c542b65052514474e4b3e120', 'great_muchacho@mail.ru', 'yes');
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_users_groups
DROP TABLE IF EXISTS `auth_users_groups`;
CREATE TABLE IF NOT EXISTS `auth_users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.auth_users_groups: ~1 rows (approximately)
/*!40000 ALTER TABLE `auth_users_groups` DISABLE KEYS */;
INSERT INTO `auth_users_groups` (`id`, `user_id`, `group_id`) VALUES
	(1, 1, 1);
/*!40000 ALTER TABLE `auth_users_groups` ENABLE KEYS */;


# Dumping structure for table ice-storm.auth_user_permissions
DROP TABLE IF EXISTS `auth_user_permissions`;
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


# Dumping structure for table ice-storm.edu_courses
DROP TABLE IF EXISTS `edu_courses`;
CREATE TABLE IF NOT EXISTS `edu_courses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `shortname` varchar(100) DEFAULT NULL,
  `hours` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Учебные курсы (истори, матемтика etc)';

# Dumping data for table ice-storm.edu_courses: ~2 rows (approximately)
/*!40000 ALTER TABLE `edu_courses` DISABLE KEYS */;
INSERT INTO `edu_courses` (`id`, `name`, `shortname`, `hours`) VALUES
	(1, 'Правописание, 1 класс', 'Правописание', 400),
	(2, 'Музыка, 1 класс', 'Музыка', 60);
/*!40000 ALTER TABLE `edu_courses` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_course_stages
DROP TABLE IF EXISTS `edu_course_stages`;
CREATE TABLE IF NOT EXISTS `edu_course_stages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(10) NOT NULL,
  `stage_name` varchar(50) NOT NULL DEFAULT '',
  `order` int(11) NOT NULL DEFAULT '1',
  `hours` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Этапы учебного курса';

# Dumping data for table ice-storm.edu_course_stages: ~8 rows (approximately)
/*!40000 ALTER TABLE `edu_course_stages` DISABLE KEYS */;
INSERT INTO `edu_course_stages` (`id`, `course_id`, `stage_name`, `order`, `hours`) VALUES
	(1, 1, '', 1, 0),
	(2, 1, '', 2, 0),
	(3, 1, '', 3, 0),
	(4, 1, '', 4, 0),
	(5, 2, '', 1, 0),
	(6, 2, '', 2, 0),
	(7, 2, '', 3, 0),
	(8, 2, '', 4, 0);
/*!40000 ALTER TABLE `edu_course_stages` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_course_themes
DROP TABLE IF EXISTS `edu_course_themes`;
CREATE TABLE IF NOT EXISTS `edu_course_themes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `stage_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Этап учебного курса',
  `name` varchar(300) NOT NULL,
  `hours` int(11) NOT NULL DEFAULT '1' COMMENT 'Продолжительность темы',
  `order` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Темы из учебных курсов';

# Dumping data for table ice-storm.edu_course_themes: ~7 rows (approximately)
/*!40000 ALTER TABLE `edu_course_themes` DISABLE KEYS */;
INSERT INTO `edu_course_themes` (`id`, `course_id`, `stage_id`, `name`, `hours`, `order`) VALUES
	(1, 1, 1, 'Алфавит', 4, 1),
	(2, 1, 1, 'Слоги', 2, 2),
	(6, 1, 1, 'Гласные', 2, 3),
	(7, 1, 1, 'Согласные буквы', 4, 4),
	(8, 1, 1, 'Слова', 4, 5),
	(9, 1, 2, 'Предложения', 6, 1),
	(10, 1, 2, 'Сложные предложения', 10, 2);
/*!40000 ALTER TABLE `edu_course_themes` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_curriculums
DROP TABLE IF EXISTS `edu_curriculums`;
CREATE TABLE IF NOT EXISTS `edu_curriculums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `state` enum('active','refused','deprecated','inactive') NOT NULL DEFAULT 'active',
  `terms_count` int(10) NOT NULL DEFAULT '1',
  `next_curriculum` int(10) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Учебные планы - школьное/институтское образование и т.д.';

# Dumping data for table ice-storm.edu_curriculums: ~2 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculums` DISABLE KEYS */;
INSERT INTO `edu_curriculums` (`id`, `name`, `state`, `terms_count`, `next_curriculum`, `create_date`) VALUES
	(1, '1 класс. Общеобразовательный', 'active', 4, 0, '2011-08-15 00:43:16'),
	(2, '2 класс. Общеобразовательный', 'active', 4, 0, '2011-08-15 00:44:34');
/*!40000 ALTER TABLE `edu_curriculums` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_groups
DROP TABLE IF EXISTS `edu_groups`;
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
DROP TABLE IF EXISTS `edu_groups_terms_course_stages`;
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
DROP TABLE IF EXISTS `edu_lessons`;
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
DROP TABLE IF EXISTS `edu_marks`;
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
DROP TABLE IF EXISTS `edu_mark_absent_types`;
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
DROP TABLE IF EXISTS `edu_mark_note`;
CREATE TABLE IF NOT EXISTS `edu_mark_note` (
  `mark_id` int(11) NOT NULL,
  `note` varchar(300) NOT NULL,
  PRIMARY KEY (`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_mark_note: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_mark_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_mark_note` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_students
DROP TABLE IF EXISTS `edu_students`;
CREATE TABLE IF NOT EXISTS `edu_students` (
  `human_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_students: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_students` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_students` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_teachers
DROP TABLE IF EXISTS `edu_teachers`;
CREATE TABLE IF NOT EXISTS `edu_teachers` (
  `human_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_teachers: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_teachers` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_teachers` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_terms
DROP TABLE IF EXISTS `edu_terms`;
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


# Dumping structure for table ice-storm.org_departments
DROP TABLE IF EXISTS `org_departments`;
CREATE TABLE IF NOT EXISTS `org_departments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `state` enum('active','inactive') NOT NULL DEFAULT 'active',
  `parent_id` int(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edit_date` timestamp NULL DEFAULT NULL,
  `close_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.org_departments: ~4 rows (approximately)
/*!40000 ALTER TABLE `org_departments` DISABLE KEYS */;
INSERT INTO `org_departments` (`id`, `name`, `state`, `parent_id`, `create_date`, `edit_date`, `close_date`) VALUES
	(1, 'Институт ПМИ', 'active', 0, '2011-08-22 01:14:25', NULL, NULL),
	(2, 'Институт ГП', 'active', 0, '2011-08-22 01:15:14', NULL, NULL),
	(3, 'Факультет ПМИ', 'active', 1, '2011-08-22 21:32:06', NULL, NULL),
	(4, 'Факультет АСУ', 'active', 1, '2011-08-22 21:32:23', NULL, NULL);
/*!40000 ALTER TABLE `org_departments` ENABLE KEYS */;


# Dumping structure for table ice-storm.org_humans
DROP TABLE IF EXISTS `org_humans`;
CREATE TABLE IF NOT EXISTS `org_humans` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fio` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `photo` varchar(150) DEFAULT NULL,
  `phone_number` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(150) NOT NULL DEFAULT '',
  `skype` varchar(60) NOT NULL DEFAULT '',
  `icq` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.org_humans: ~0 rows (approximately)
/*!40000 ALTER TABLE `org_humans` DISABLE KEYS */;
/*!40000 ALTER TABLE `org_humans` ENABLE KEYS */;


# Dumping structure for table ice-storm.org_staff
DROP TABLE IF EXISTS `org_staff`;
CREATE TABLE IF NOT EXISTS `org_staff` (
  `human_id` int(10) NOT NULL,
  `department_id` int(10) NOT NULL DEFAULT '0',
  `state` enum('active','fired') NOT NULL DEFAULT 'active',
  `chief` enum('yes','no') NOT NULL DEFAULT 'no',
  `post` varchar(300) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `adoption_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `leave_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`human_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.org_staff: ~0 rows (approximately)
/*!40000 ALTER TABLE `org_staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `org_staff` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
