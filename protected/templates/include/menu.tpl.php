<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
?>
<ul class="h_menu">
	<li><span class="a">Учебный процесс</span>
		<ul class="v_menu">
			<li><a href="<?= WEBURL .'edu/marks' ?>">Оценки</a></li>
			<li><a href="<?= WEBURL .'stat/edu' ?>">Статистика</a></li>
			<li><a href="<?= WEBURL .'edu/schedule' ?>">Расписание</a></li>
		</ul>
	</li>
	<li><span class="a">Организация учебы</span>
		<ul class="v_menu">
			<li><a href="<?= WEBURL .'edu/curriculums' ?>">Учебные планы</a></li>
			<li><a href="<?= WEBURL .'edu/courses' ?>">Учебные курсы</a></li>
			<li><a href="<?= WEBURL .'edu/groups' ?>">Группы</a></li>
			<li><a href="<?= WEBURL .'edu/students' ?>">Студенты</a></li>
			<li><a href="<?= WEBURL .'edu/teachers' ?>">Преподаватели</a></li>
		</ul>
	</li>
	<li><span class="a">Структура учреждения</span>
		<ul class="v_menu">
			<li><a href="<?= WEBURL .'org/staff' ?>">Сотрудники</a></li>
			<li><a href="<?= WEBURL .'org/departments' ?>">Подразделения</a></li>
		</ul>
	</li>
	<li><span class="a">Доступ</span>
		<ul class="v_menu">
			<li><a href="<?= WEBURL .'acl/groups' ?>">Группы</a></li>
			<li><a href="<?= WEBURL .'acl/users' ?>">Пользователи</a></li>
			<li><a href="<?= WEBURL .'acl/usersingroups' ?>">Группы/Пользователи</a></li>
		</ul>
	</li>
</ul>