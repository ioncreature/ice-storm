-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.1.51-community - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             6.0.0.4011
-- Date/time:                    2011-12-31 14:49:58
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for ice-storm
DROP DATABASE IF EXISTS `ice-storm`;
CREATE DATABASE IF NOT EXISTS `ice-storm` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ice-storm`;


-- Dumping structure for table ice-storm.auth_groups
DROP TABLE IF EXISTS `auth_groups`;
CREATE TABLE IF NOT EXISTS `auth_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_name` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.auth_groups: ~2 rows (approximately)
/*!40000 ALTER TABLE `auth_groups` DISABLE KEYS */;
INSERT INTO `auth_groups` (`id`, `code_name`, `name`) VALUES
	(1, 'admin', 'Administrators'),
	(2, 'guest', 'Guests');
/*!40000 ALTER TABLE `auth_groups` ENABLE KEYS */;


-- Dumping structure for table ice-storm.auth_group_permissions
DROP TABLE IF EXISTS `auth_group_permissions`;
CREATE TABLE IF NOT EXISTS `auth_group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow' COMMENT '''deny'' has a greater priority than an ''allow''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`,`permission_id`),
  KEY `auth_group_permissions_auth_permissions` (`permission_id`),
  CONSTRAINT `auth_group_permissions_auth_groups` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`),
  CONSTRAINT `auth_group_permissions_auth_permissions` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.auth_group_permissions: ~3 rows (approximately)
/*!40000 ALTER TABLE `auth_group_permissions` DISABLE KEYS */;
INSERT INTO `auth_group_permissions` (`id`, `group_id`, `permission_id`, `type`) VALUES
	(1, 1, 1, 'allow'),
	(2, 1, 2, 'allow'),
	(7, 2, 1, 'allow');
/*!40000 ALTER TABLE `auth_group_permissions` ENABLE KEYS */;


-- Dumping structure for table ice-storm.auth_permissions
DROP TABLE IF EXISTS `auth_permissions`;
CREATE TABLE IF NOT EXISTS `auth_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.auth_permissions: ~2 rows (approximately)
/*!40000 ALTER TABLE `auth_permissions` DISABLE KEYS */;
INSERT INTO `auth_permissions` (`id`, `name`, `description`) VALUES
	(1, 'siski', 'Siski'),
	(2, 'foo', 'Foo permission');
/*!40000 ALTER TABLE `auth_permissions` ENABLE KEYS */;


-- Dumping structure for table ice-storm.auth_users
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.auth_users: ~2 rows (approximately)
/*!40000 ALTER TABLE `auth_users` DISABLE KEYS */;
INSERT INTO `auth_users` (`id`, `human_id`, `login`, `password`, `email`, `active`) VALUES
	(1, 1, 'admin', 'a77414e9b56f7aecf75db4bd13f885dc', 'great_muchacho@mail.ru', 'yes'),
	(2, 0, 'test', 'a77414e9b56f7aecf75db4bd13f885dc', 'great_muchacho@rambler.ru', 'yes');
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;


-- Dumping structure for table ice-storm.auth_users_groups
DROP TABLE IF EXISTS `auth_users_groups`;
CREATE TABLE IF NOT EXISTS `auth_users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`),
  KEY `auth_users_groups_auth_groups` (`group_id`),
  CONSTRAINT `auth_users_groups_auth_groups` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`),
  CONSTRAINT `auth_users_groups_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.auth_users_groups: ~3 rows (approximately)
/*!40000 ALTER TABLE `auth_users_groups` DISABLE KEYS */;
INSERT INTO `auth_users_groups` (`id`, `user_id`, `group_id`) VALUES
	(1, 1, 1),
	(6, 1, 2),
	(5, 2, 2);
/*!40000 ALTER TABLE `auth_users_groups` ENABLE KEYS */;


-- Dumping structure for table ice-storm.auth_user_permissions
DROP TABLE IF EXISTS `auth_user_permissions`;
CREATE TABLE IF NOT EXISTS `auth_user_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `permission_id` int(10) NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_permission_id` (`user_id`,`permission_id`),
  KEY `auth_user_permissions_auth_permissions` (`permission_id`),
  CONSTRAINT `auth_user_permissions_auth_permissions` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`),
  CONSTRAINT `FK_auth_user_permissions_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.auth_user_permissions: ~2 rows (approximately)
/*!40000 ALTER TABLE `auth_user_permissions` DISABLE KEYS */;
INSERT INTO `auth_user_permissions` (`id`, `user_id`, `permission_id`, `type`) VALUES
	(2, 1, 2, 'allow'),
	(3, 2, 1, 'allow');
/*!40000 ALTER TABLE `auth_user_permissions` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_courses
DROP TABLE IF EXISTS `edu_courses`;
CREATE TABLE IF NOT EXISTS `edu_courses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `shortname` varchar(100) DEFAULT NULL,
  `hours` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Учебные курсы (истори, матемтика etc)';

-- Dumping data for table ice-storm.edu_courses: ~3 rows (approximately)
/*!40000 ALTER TABLE `edu_courses` DISABLE KEYS */;
INSERT INTO `edu_courses` (`id`, `name`, `shortname`, `hours`) VALUES
	(1, 'Правописание, 1 класс', 'Правописание', 300),
	(2, 'Музыка, 1 класс', 'Музыка', 40),
	(3, 'Труд, 1 класс', 'Труд', 60);
/*!40000 ALTER TABLE `edu_courses` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_course_terms
DROP TABLE IF EXISTS `edu_course_terms`;
CREATE TABLE IF NOT EXISTS `edu_course_terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(10) NOT NULL,
  `term_name` varchar(50) NOT NULL DEFAULT '',
  `order` int(11) NOT NULL DEFAULT '1',
  `hours` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `course_id_index` (`course_id`),
  CONSTRAINT `edu_course_stages_edu_courses` FOREIGN KEY (`course_id`) REFERENCES `edu_courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Этапы учебного курса';

-- Dumping data for table ice-storm.edu_course_terms: ~10 rows (approximately)
/*!40000 ALTER TABLE `edu_course_terms` DISABLE KEYS */;
INSERT INTO `edu_course_terms` (`id`, `course_id`, `term_name`, `order`, `hours`) VALUES
	(1, 1, '', 1, 0),
	(2, 1, '', 2, 0),
	(3, 1, '', 3, 0),
	(4, 1, '', 4, 0),
	(5, 2, '', 1, 0),
	(6, 2, '', 2, 0),
	(7, 2, '', 3, 0),
	(8, 2, '', 4, 0),
	(9, 3, '', 1, 0),
	(10, 3, '', 2, 0);
/*!40000 ALTER TABLE `edu_course_terms` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_course_themes
DROP TABLE IF EXISTS `edu_course_themes`;
CREATE TABLE IF NOT EXISTS `edu_course_themes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Этап учебного курса',
  `name` varchar(300) NOT NULL,
  `hours` int(11) NOT NULL DEFAULT '1' COMMENT 'Продолжительность темы',
  `order` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `course_id_index` (`course_id`),
  KEY `term_id_index` (`term_id`),
  CONSTRAINT `edu_course_themes_edu_courses` FOREIGN KEY (`course_id`) REFERENCES `edu_courses` (`id`),
  CONSTRAINT `edu_course_themes_edu_course_terms` FOREIGN KEY (`term_id`) REFERENCES `edu_course_terms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Темы из учебных курсов';

-- Dumping data for table ice-storm.edu_course_themes: ~12 rows (approximately)
/*!40000 ALTER TABLE `edu_course_themes` DISABLE KEYS */;
INSERT INTO `edu_course_themes` (`id`, `course_id`, `term_id`, `name`, `hours`, `order`) VALUES
	(1, 1, 1, 'Алфавит', 2, 1),
	(2, 1, 1, 'Гласные буквы', 1, 3),
	(3, 1, 1, 'Согласные', 2, 5),
	(4, 1, 1, 'Слоги', 2, 6),
	(5, 1, 1, 'Слова', 3, 4),
	(6, 1, 1, 'Части речи', 5, 2),
	(7, 2, 5, 'Введение в предмет', 1, 1),
	(8, 2, 5, 'Пюпитр и сюсюпитр', 2, 2),
	(9, 2, 5, 'Песня про Голубой Вагончик', 2, 3),
	(10, 1, 2, 'test', 3, 1),
	(11, 3, 9, 'Введение', 1, 1),
	(12, 2, 7, 'asdasd', 5, 1);
/*!40000 ALTER TABLE `edu_course_themes` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_curriculums
DROP TABLE IF EXISTS `edu_curriculums`;
CREATE TABLE IF NOT EXISTS `edu_curriculums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `state` enum('active','inactive') NOT NULL DEFAULT 'active',
  `terms_count` int(10) NOT NULL DEFAULT '1',
  `next_curriculum` int(10) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Учебные планы - школьное/институтское образование и т.д.';

-- Dumping data for table ice-storm.edu_curriculums: ~1 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculums` DISABLE KEYS */;
INSERT INTO `edu_curriculums` (`id`, `name`, `state`, `terms_count`, `next_curriculum`, `create_date`) VALUES
	(2, '1 класс. Общеобразовательный', 'active', 4, 0, '2011-09-18 21:31:43');
/*!40000 ALTER TABLE `edu_curriculums` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_curriculum_courses
DROP TABLE IF EXISTS `edu_curriculum_courses`;
CREATE TABLE IF NOT EXISTS `edu_curriculum_courses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `curriculum_id` int(10) NOT NULL,
  `course_id` int(10) NOT NULL,
  `curriculum_term_id` int(10) DEFAULT NULL,
  `course_term_id` int(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `curriculum_id_index` (`curriculum_id`),
  KEY `course_id_index` (`course_id`),
  CONSTRAINT `edu_curriculum_courses_edu_courses` FOREIGN KEY (`course_id`) REFERENCES `edu_courses` (`id`),
  CONSTRAINT `edu_curriculum_courses_edu_curriculums` FOREIGN KEY (`curriculum_id`) REFERENCES `edu_curriculums` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_curriculum_courses: ~10 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculum_courses` DISABLE KEYS */;
INSERT INTO `edu_curriculum_courses` (`id`, `curriculum_id`, `course_id`, `curriculum_term_id`, `course_term_id`, `name`) VALUES
	(1, 2, 1, 1, 1, ''),
	(2, 2, 1, 2, 2, ''),
	(3, 2, 1, 3, 3, ''),
	(4, 2, 1, 4, 4, ''),
	(5, 2, 3, 2, 9, ''),
	(6, 2, 3, 3, 10, ''),
	(7, 2, 2, 1, 5, ''),
	(8, 2, 2, 2, 6, ''),
	(9, 2, 2, 3, 7, ''),
	(10, 2, 2, 4, 8, '');
/*!40000 ALTER TABLE `edu_curriculum_courses` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_curriculum_terms
DROP TABLE IF EXISTS `edu_curriculum_terms`;
CREATE TABLE IF NOT EXISTS `edu_curriculum_terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `curriculum_id` int(10) NOT NULL,
  `order` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `curriculum_id_index` (`curriculum_id`),
  CONSTRAINT `edu_curriculum_stages_edu_curriculums` FOREIGN KEY (`curriculum_id`) REFERENCES `edu_curriculums` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Семестры в учебном плане';

-- Dumping data for table ice-storm.edu_curriculum_terms: ~4 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculum_terms` DISABLE KEYS */;
INSERT INTO `edu_curriculum_terms` (`id`, `curriculum_id`, `order`) VALUES
	(1, 2, 1),
	(2, 2, 2),
	(3, 2, 3),
	(4, 2, 4);
/*!40000 ALTER TABLE `edu_curriculum_terms` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_groups
DROP TABLE IF EXISTS `edu_groups`;
CREATE TABLE IF NOT EXISTS `edu_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL DEFAULT '',
  `department_id` int(11) DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `state` enum('on','off') NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`),
  KEY `edu_groups_org_departments` (`department_id`),
  CONSTRAINT `edu_groups_org_departments` FOREIGN KEY (`department_id`) REFERENCES `org_departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_groups: ~2 rows (approximately)
/*!40000 ALTER TABLE `edu_groups` DISABLE KEYS */;
INSERT INTO `edu_groups` (`id`, `name`, `department_id`, `start_date`, `end_date`, `state`) VALUES
	(1, '1211', 3, '2011-10-24 23:21:17', NULL, 'on'),
	(2, 'test', 2, '2011-11-06 00:43:37', NULL, 'on');
/*!40000 ALTER TABLE `edu_groups` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_group_terms
DROP TABLE IF EXISTS `edu_group_terms`;
CREATE TABLE IF NOT EXISTS `edu_group_terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL,
  `curriculum_id` int(10) NOT NULL,
  `curriculum_term_id` int(10) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `closed` enum('yes','no') NOT NULL DEFAULT 'no',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `group_id_index` (`group_id`),
  KEY `edu_group_terms_edu_curriculums` (`curriculum_id`),
  CONSTRAINT `edu_group_terms_edu_curriculums` FOREIGN KEY (`curriculum_id`) REFERENCES `edu_curriculums` (`id`),
  CONSTRAINT `edu_group_terms_edu_groups` FOREIGN KEY (`group_id`) REFERENCES `edu_groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_group_terms: ~1 rows (approximately)
/*!40000 ALTER TABLE `edu_group_terms` DISABLE KEYS */;
INSERT INTO `edu_group_terms` (`id`, `group_id`, `curriculum_id`, `curriculum_term_id`, `date_start`, `date_end`, `closed`, `name`) VALUES
	(1, 1, 2, 1, '2011-09-01', '2011-01-15', 'no', '');
/*!40000 ALTER TABLE `edu_group_terms` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_homework
DROP TABLE IF EXISTS `edu_homework`;
CREATE TABLE IF NOT EXISTS `edu_homework` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(10) NOT NULL,
  `for` enum('group','student') NOT NULL DEFAULT 'group',
  `for_id` int(11) NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT '',
  `lesson_id` int(10) DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `due_lesson` int(11) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_homework: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_homework` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_homework` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_lessons
DROP TABLE IF EXISTS `edu_lessons`;
CREATE TABLE IF NOT EXISTS `edu_lessons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) DEFAULT NULL,
  `course_id` int(10) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hours` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_lessons: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_lessons` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_lessons` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_marks
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
  `importance` enum('high','medium','low') NOT NULL DEFAULT 'medium' COMMENT 'важность оценки - высокая, средняя, низкая',
  PRIMARY KEY (`id`),
  KEY `date_add_index` (`date_add`),
  KEY `estimated_type_estimated_id_index` (`estimated_type`,`estimated_id`),
  KEY `work_type_work_id_index` (`work_type`,`work_id`),
  KEY `mark_type_mark_id_index` (`mark_type`,`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Оценки учеников';

-- Dumping data for table ice-storm.edu_marks: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_marks` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_mark_absent_types
DROP TABLE IF EXISTS `edu_mark_absent_types`;
CREATE TABLE IF NOT EXISTS `edu_mark_absent_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `shortname` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Типы отсутствия на занятии';

-- Dumping data for table ice-storm.edu_mark_absent_types: ~5 rows (approximately)
/*!40000 ALTER TABLE `edu_mark_absent_types` DISABLE KEYS */;
INSERT INTO `edu_mark_absent_types` (`id`, `name`, `shortname`) VALUES
	(1, 'Пропуск по неуважительной причине', 'н'),
	(2, 'Пропуск по болезни', 'болен'),
	(3, 'Пропуск по уважительной причине', 'ув'),
	(4, 'Спортивные соревнования', 'спорт'),
	(5, 'Другое', 'др');
/*!40000 ALTER TABLE `edu_mark_absent_types` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_mark_note
DROP TABLE IF EXISTS `edu_mark_note`;
CREATE TABLE IF NOT EXISTS `edu_mark_note` (
  `mark_id` int(11) NOT NULL,
  `note` varchar(300) NOT NULL,
  PRIMARY KEY (`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_mark_note: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_mark_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_mark_note` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_students
DROP TABLE IF EXISTS `edu_students`;
CREATE TABLE IF NOT EXISTS `edu_students` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `human_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `enrollment_date` date DEFAULT NULL COMMENT 'Дата зачисления',
  `enrollment_order` varchar(50) NOT NULL DEFAULT '' COMMENT 'Приказ о зачислении',
  `dismissal_date` date DEFAULT NULL COMMENT 'Дата отчисления',
  `dismissal_order` varchar(50) NOT NULL DEFAULT '' COMMENT 'Приказ об отчислении',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `human_id` (`human_id`),
  CONSTRAINT `edu_students_edu_groups` FOREIGN KEY (`group_id`) REFERENCES `edu_groups` (`id`),
  CONSTRAINT `edu_students_org_humans` FOREIGN KEY (`human_id`) REFERENCES `org_humans` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_students: ~2 rows (approximately)
/*!40000 ALTER TABLE `edu_students` DISABLE KEYS */;
INSERT INTO `edu_students` (`id`, `human_id`, `group_id`, `enrollment_date`, `enrollment_order`, `dismissal_date`, `dismissal_order`) VALUES
	(1, 1, 1, '2005-09-01', '1', NULL, ''),
	(2, 2, 1, '2011-10-07', '1', NULL, '');
/*!40000 ALTER TABLE `edu_students` ENABLE KEYS */;


-- Dumping structure for table ice-storm.edu_teachers
DROP TABLE IF EXISTS `edu_teachers`;
CREATE TABLE IF NOT EXISTS `edu_teachers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `human_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `edu_teachers_org_humans` (`human_id`),
  CONSTRAINT `edu_teachers_org_humans` FOREIGN KEY (`human_id`) REFERENCES `org_humans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.edu_teachers: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_teachers` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_teachers` ENABLE KEYS */;


-- Dumping structure for table ice-storm.org_departments
DROP TABLE IF EXISTS `org_departments`;
CREATE TABLE IF NOT EXISTS `org_departments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `state` enum('active','inactive') NOT NULL DEFAULT 'active',
  `parent_id` int(10) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edit_date` timestamp NULL DEFAULT NULL,
  `close_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.org_departments: ~5 rows (approximately)
/*!40000 ALTER TABLE `org_departments` DISABLE KEYS */;
INSERT INTO `org_departments` (`id`, `name`, `state`, `parent_id`, `create_date`, `edit_date`, `close_date`) VALUES
	(2, 'Институт ГП', 'active', 6, '2011-08-22 01:15:14', NULL, NULL),
	(3, 'Факультет ПМИ', 'active', 2, '2011-08-22 21:32:06', NULL, NULL),
	(4, 'Факультет АСУ', 'active', 2, '2011-08-22 21:32:23', NULL, NULL),
	(5, 'Кафедра Алгебры и Геометрии', 'active', 3, '2011-10-24 21:39:58', NULL, NULL),
	(6, 'СПВИ ВВ МВД России', 'active', 0, '2011-12-03 23:47:00', NULL, NULL);
/*!40000 ALTER TABLE `org_departments` ENABLE KEYS */;


-- Dumping structure for table ice-storm.org_humans
DROP TABLE IF EXISTS `org_humans`;
CREATE TABLE IF NOT EXISTS `org_humans` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `full_name` varchar(250) NOT NULL DEFAULT '',
  `birth_date` date DEFAULT NULL,
  `photo` varchar(150) DEFAULT NULL,
  `phone` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(150) NOT NULL DEFAULT '',
  `skype` varchar(60) NOT NULL DEFAULT '',
  `icq` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `full_name` (`full_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.org_humans: ~3 rows (approximately)
/*!40000 ALTER TABLE `org_humans` DISABLE KEYS */;
INSERT INTO `org_humans` (`id`, `first_name`, `middle_name`, `last_name`, `full_name`, `birth_date`, `photo`, `phone`, `email`, `skype`, `icq`) VALUES
	(1, 'Александр', 'Юрьевич', 'Маренин', 'Маренин Александр Юрьевич', '1988-02-01', NULL, '89817140250', 'great_muchacho@mail.ru', 'ioncreature', '3001879'),
	(2, 'Егор', 'Александрович', 'Маренин', 'Маренин Егор Александрович', '2011-10-07', NULL, '', 'comrade2.0@mail.ru', 'Comrade2.0', ''),
	(3, 'Пантелеймон', 'Соломонович', 'Шникерсон', 'Пантелеймон Соломонович Шникерсон', '1979-12-22', NULL, '', 'solo@gmail.com', 'solo', '');
/*!40000 ALTER TABLE `org_humans` ENABLE KEYS */;


-- Dumping structure for table ice-storm.org_staff
DROP TABLE IF EXISTS `org_staff`;
CREATE TABLE IF NOT EXISTS `org_staff` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `human_id` int(10) NOT NULL,
  `department_id` int(10) NOT NULL DEFAULT '0',
  `state` enum('active','fired') NOT NULL DEFAULT 'active',
  `chief` enum('yes','no') NOT NULL DEFAULT 'no',
  `work_rate` enum('full','half','quarter') NOT NULL DEFAULT 'full',
  `post` varchar(300) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `adoption_date` date DEFAULT NULL,
  `leave_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `human_id` (`human_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `org_staff_org_departments` FOREIGN KEY (`department_id`) REFERENCES `org_departments` (`id`),
  CONSTRAINT `org_staff_org_humans` FOREIGN KEY (`human_id`) REFERENCES `org_humans` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table ice-storm.org_staff: ~2 rows (approximately)
/*!40000 ALTER TABLE `org_staff` DISABLE KEYS */;
INSERT INTO `org_staff` (`id`, `human_id`, `department_id`, `state`, `chief`, `work_rate`, `post`, `phone`, `adoption_date`, `leave_date`) VALUES
	(1, 1, 5, 'active', 'no', 'full', 'Линейный математик, проекционый геометр', '', '2011-11-14', NULL),
	(2, 2, 6, 'active', 'yes', 'full', 'Президент всея Руси', '', '2011-12-19', NULL);
/*!40000 ALTER TABLE `org_staff` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
