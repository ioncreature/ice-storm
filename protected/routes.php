<?php
/**
 * @author Marenin Alex
 *         December 2011
 */


$routes = array(
	'' => 'module: index',
	'logout' => 'module: index',
	'register' => 'module: register',
	'test' => 'module: test',
	'some_test' => '\Controller\SomeTest',
	'org/departments' => 'module: org/departments',
	'org/staff' => 'module: org/staff',
	'org/employee' => 'module: org/employee',
	'stat/edu' => 'module: stat/edu',
	'edu/curriculum' => 'module: edu/curriculum',
	'edu/curriculums' => 'module: edu/curriculums',
	'edu/courses' => 'module: edu/courses',
	'edu/course' => 'module: edu/course',
	'edu/groups' => 'module: edu/groups',
	'edu/groups_list' => 'module: edu/groups_list',
	'edu/group' => 'module: edu/group',
	'edu/students' => 'module: edu/students',
	'edu/student' => 'module: edu/student',
	'user/cabinet' => 'module: user/cabinet',
	'service/department' => '\Service\Departments',
	'service/staff' => '\Service\Staff',
	'acl/groups' => 'module: acl/groups',
	'acl/users' => 'module: acl/users',
	'acl/usersingroups' => 'module: acl/usersingroups'
);