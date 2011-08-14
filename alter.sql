ALTER TABLE `departments`
	RENAME TO `org_departments`;
ALTER TABLE `humans`
	RENAME TO `org_humans`;
ALTER TABLE `edu_curriculums`
	ADD COLUMN `next_curriculum` INT(10) NOT NULL DEFAULT '0' AFTER `terms_count`;
ALTER TABLE `edu_curriculums`
	ADD COLUMN `state` ENUM('active','refused','deprecated','inactive') NOT NULL DEFAULT 'active' AFTER `name`,
	ADD COLUMN `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `next_curriculum`;
