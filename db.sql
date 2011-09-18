# --------------------------------------------------------
# Host:                         localhost
# Server version:               5.1.51-community - MySQL Community Server (GPL)
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3943
# Date/time:                    2011-09-18 23:16:08
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
  UNIQUE KEY `group_id` (`group_id`,`permission_id`),
  KEY `auth_group_permissions_auth_permissions` (`permission_id`),
  CONSTRAINT `auth_group_permissions_auth_groups` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`),
  CONSTRAINT `auth_group_permissions_auth_permissions` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`)
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
  UNIQUE KEY `user_id` (`user_id`,`group_id`),
  KEY `auth_users_groups_auth_groups` (`group_id`),
  CONSTRAINT `auth_users_groups_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`),
  CONSTRAINT `auth_users_groups_auth_groups` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`)
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
  UNIQUE KEY `user_id_permission_id` (`user_id`,`permission_id`),
  KEY `auth_user_permissions_auth_permissions` (`permission_id`),
  CONSTRAINT `auth_user_permissions_auth_permissions` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`),
  CONSTRAINT `FK_auth_user_permissions_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учебные курсы (истори, матемтика etc)';

# Dumping data for table ice-storm.edu_courses: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_courses` DISABLE KEYS */;
INSERT INTO `edu_courses` (`id`, `name`, `shortname`, `hours`) VALUES
	(1, 'Правописание, 1 класс', 'Правописание', 300),
	(2, 'Музыка, 1 класс', 'Музыка', 40);
/*!40000 ALTER TABLE `edu_courses` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_course_terms
DROP TABLE IF EXISTS `edu_course_terms`;
CREATE TABLE IF NOT EXISTS `edu_course_terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(10) NOT NULL,
  `term_name` varchar(50) NOT NULL DEFAULT '',
  `order` int(11) NOT NULL DEFAULT '1',
  `hours` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `edu_course_stages_edu_courses` (`course_id`),
  CONSTRAINT `edu_course_stages_edu_courses` FOREIGN KEY (`course_id`) REFERENCES `edu_courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Этапы учебного курса';

# Dumping data for table ice-storm.edu_course_terms: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_course_terms` DISABLE KEYS */;
INSERT INTO `edu_course_terms` (`id`, `course_id`, `term_name`, `order`, `hours`) VALUES
	(1, 1, '', 1, 0),
	(2, 1, '', 2, 0),
	(3, 1, '', 3, 0),
	(4, 1, '', 4, 0),
	(5, 2, '', 1, 0),
	(6, 2, '', 2, 0),
	(7, 2, '', 3, 0),
	(8, 2, '', 4, 0);
/*!40000 ALTER TABLE `edu_course_terms` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_course_themes
DROP TABLE IF EXISTS `edu_course_themes`;
CREATE TABLE IF NOT EXISTS `edu_course_themes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Этап учебного курса',
  `name` varchar(300) NOT NULL,
  `hours` int(11) NOT NULL DEFAULT '1' COMMENT 'Продолжительность темы',
  `order` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `edu_course_themes_edu_courses` (`course_id`),
  KEY `edu_course_themes_edu_course_terms` (`term_id`),
  CONSTRAINT `edu_course_themes_edu_course_terms` FOREIGN KEY (`term_id`) REFERENCES `edu_course_terms` (`id`),
  CONSTRAINT `edu_course_themes_edu_courses` FOREIGN KEY (`course_id`) REFERENCES `edu_courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Темы из учебных курсов';

# Dumping data for table ice-storm.edu_course_themes: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_course_themes` DISABLE KEYS */;
INSERT INTO `edu_course_themes` (`id`, `course_id`, `term_id`, `name`, `hours`, `order`) VALUES
	(1, 1, 1, 'Алфавит', 2, 1),
	(2, 1, 1, 'Гласные буквы', 1, 2),
	(3, 1, 1, 'Согласные', 2, 3),
	(4, 1, 1, 'Слоги', 2, 4),
	(5, 1, 1, 'Слова', 2, 5),
	(6, 1, 1, 'Части речи', 5, 6),
	(7, 2, 5, 'Введение в предмет', 1, 1),
	(8, 2, 5, 'Пюпитр и сюсюпитр', 2, 2),
	(9, 2, 5, 'Песня про Голубой Вагончик', 2, 3);
/*!40000 ALTER TABLE `edu_course_themes` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_curriculums
DROP TABLE IF EXISTS `edu_curriculums`;
CREATE TABLE IF NOT EXISTS `edu_curriculums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `state` enum('active','inactive') NOT NULL DEFAULT 'active',
  `terms_count` int(10) NOT NULL DEFAULT '1',
  `next_curriculum` int(10) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Учебные планы - школьное/институтское образование и т.д.';

# Dumping data for table ice-storm.edu_curriculums: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculums` DISABLE KEYS */;
INSERT INTO `edu_curriculums` (`id`, `name`, `state`, `terms_count`, `next_curriculum`, `create_date`) VALUES
	(2, '1 класс. Общеобразовательный', 'active', 4, 0, '2011-09-18 21:31:43');
/*!40000 ALTER TABLE `edu_curriculums` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_curriculum_courses
DROP TABLE IF EXISTS `edu_curriculum_courses`;
CREATE TABLE IF NOT EXISTS `edu_curriculum_courses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `curriculum_id` int(10) NOT NULL,
  `course_id` int(10) NOT NULL,
  `curriculum_term_id` int(10) DEFAULT NULL,
  `course_term_id` int(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `edu_curriculum_courses_edu_curriculums` (`curriculum_id`),
  KEY `edu_curriculum_courses_edu_courses` (`course_id`),
  CONSTRAINT `edu_curriculum_courses_edu_courses` FOREIGN KEY (`course_id`) REFERENCES `edu_courses` (`id`),
  CONSTRAINT `edu_curriculum_courses_edu_curriculums` FOREIGN KEY (`curriculum_id`) REFERENCES `edu_curriculums` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_curriculum_courses: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculum_courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_curriculum_courses` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_curriculum_terms
DROP TABLE IF EXISTS `edu_curriculum_terms`;
CREATE TABLE IF NOT EXISTS `edu_curriculum_terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `curriculum_id` int(10) NOT NULL,
  `order` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `edu_curriculum_stages_edu_curriculums` (`curriculum_id`),
  CONSTRAINT `edu_curriculum_stages_edu_curriculums` FOREIGN KEY (`curriculum_id`) REFERENCES `edu_curriculums` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Семестры в учебном плане';

# Dumping data for table ice-storm.edu_curriculum_terms: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_curriculum_terms` DISABLE KEYS */;
INSERT INTO `edu_curriculum_terms` (`id`, `curriculum_id`, `order`) VALUES
	(1, 2, 1),
	(2, 2, 2),
	(3, 2, 3),
	(4, 2, 4);
/*!40000 ALTER TABLE `edu_curriculum_terms` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_groups
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_groups` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_group_terms
DROP TABLE IF EXISTS `edu_group_terms`;
CREATE TABLE IF NOT EXISTS `edu_group_terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL,
  `curriculum_id` int(10) NOT NULL,
  `curriculum_term_id` int(10) NOT NULL,
  `date_start` timestamp NULL DEFAULT NULL,
  `date_end` timestamp NULL DEFAULT NULL,
  `closed` enum('yes','no') NOT NULL DEFAULT 'no',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `edu_group_terms_edu_groups` (`group_id`),
  CONSTRAINT `edu_group_terms_edu_groups` FOREIGN KEY (`group_id`) REFERENCES `edu_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ice-storm.edu_group_terms: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_group_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_group_terms` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_homework
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

# Dumping data for table ice-storm.edu_homework: ~0 rows (approximately)
/*!40000 ALTER TABLE `edu_homework` DISABLE KEYS */;
/*!40000 ALTER TABLE `edu_homework` ENABLE KEYS */;


# Dumping structure for table ice-storm.edu_lessons
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
  `importance` enum('high','medium','low') NOT NULL DEFAULT 'medium' COMMENT 'важность оценки - высокая, средняя, низкая',
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
  `group_id` int(10) DEFAULT NULL,
  KEY `group_id` (`group_id`),
  KEY `human_id` (`human_id`),
  CONSTRAINT `edu_students_edu_groups` FOREIGN KEY (`group_id`) REFERENCES `edu_groups` (`id`),
  CONSTRAINT `edu_students_org_humans` FOREIGN KEY (`human_id`) REFERENCES `org_humans` (`id`)
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
