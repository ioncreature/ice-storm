<?php
/**
 * @author Marenin Alex
 *         February 2012
 */
?>
<ul class="h_menu">
	<li><a href="<?= WEBURL .'admin/main' ?>">Админка</a></li>
	<li><span class="a">Разграничение доступа</span>
		<ul class="v_menu">
			<li><a href="<?= WEBURL .'admin/acl/groups' ?>">Группы</a></li>
			<li><a href="<?= WEBURL .'admin/acl/users' ?>">Пользователи</a></li>
			<li><a href="<?= WEBURL .'admin/acl/usersingroups' ?>">Группы/Пользователи</a></li>
		</ul>
	</li>
	<li><span class="a"><a href="<?= WEBURL .'admin/users' ?>">Пользователи</a></span>
		<ul class="v_menu">
			<li><a href="<?= WEBURL .'admin/user/updates' ?>">Обновления</a></li>
			<li><a href="<?= WEBURL .'admin/user/services' ?>">Службы</a></li>
		</ul>
	</li>
</ul>