<?php
function bottom(){?>
</div>
<!-- FOOTER -->
<?php $db = Fabric::get( 'db' ); ?>
<!-- MySQL time: <?=$db->get_time()?> / <?=$db->get_query_count()?> -->
</body></html>
<?php
}


function menu(){?>
	<!-- TOP MENU -->
	<ul class="h_menu">
		<!--
		<li><a href="<?= WEBURL ?>">Главная</a></li>
		-->
		<li><span class="a">Учебный процесс</span>
			<ul class="v_menu">
				<li><a href="<?= WEBURL .'edu/marks' ?>">Оценки</a></li>
				<li><a href="<?= WEBURL .'edu/statistics' ?>">Статистика</a></li>
				<li><a href="<?= WEBURL .'edu/schedule' ?>">Расписание</a></li>
			</ul>
		</li>
		<li><span class="a">Организация учебы</span>
			<ul class="v_menu">
				<li><a href="<?= WEBURL .'edu/curriculums' ?>">Учебные планы</a></li>
				<li><a href="<?= WEBURL .'edu/courses' ?>">Учебные курсы</a></li>
				<li><a href="<?= WEBURL .'edu/groups' ?>">Группы</a></li>
				<li><a href="<?= WEBURL .'edu/students' ?>">Студенты</a></li>
			</ul>
		</li>
		<li><span class="a">Структура учреждения</span>
			<ul class="v_menu">
				<li><a href="<?= WEBURL .'org/staff' ?>">Сотрудники</a></li>
				<li><a href="<?= WEBURL .'org/departments' ?>">Подразделения</a></li>
			</ul>
		</li>
		<li><span class="a">Разграничение доступа</span>
			<ul class="v_menu">
				<li><a href="<?= WEBURL .'acl/groups' ?>">Группы</a></li>
				<li><a href="<?= WEBURL .'acl/users' ?>">Пользователи</a></li>
				<li><a href="<?= WEBURL .'acl/usersingroups' ?>">Группы/Пользователи</a></li>
			</ul>
		</li>
		<!--
		<li><a href="<?= WEBURL .'test' ?>">Тестовый модуль</a></li>
		-->
	</ul>
	<?
}


function auth_form(){?>
	<?php if ( Auth::is_logged() ): ?>
	<form id="auth" method="POST" action="<?= WEBURL .'logout' ?>">
		<a href="<?= WEBURL . 'logout'?>" title="exit"><?= Auth::get_user()->login ?></a>
		<input type="submit" name="logout" value="Выход" />
	</form>
	<?php else: ?>
	<form id="auth" method="POST" action="<?= WEBURL ?>">
		<input type="text" name="login" value="" placeholder="Ваш логин" />
		<input type="password" name="password" value="" placeholder="Пароль" /><br />
		<input type="submit" class="submit" value="Войти" />
		<a href="<?= WEBURL .'register' ?>" style="float:right;">регистрация</a>
	</form>
	<?php endif;
}


// Функция для вывода постраничной навигации
function paginator( array $params ){
	$defaults = array(
		'page_current' => 1,
		'items_per_page' => 10,
		'items_total' => 1,
		'url_pattern' => '::page::',
		'page_show' => 5 // сколько показывать страниц после и перед текущей
	);
	$p = array_merge( $defaults, $params );
	
	$first_page_link = false;
	$last_page_link = false;
	$pages = ceil($p['items_total'] / $p['items_per_page']);
	$page_start = 1;
	$page_end = $pages;
	
	if ( $p['page_current'] - $p['page_show'] > 1 ){
		$first_page_link = true;
		$page_start = $p['page_current'] - $p['page_show'];
	}
	if ( $pages - $p['page_current'] > $p['page_show'] ){
		$last_page_link = true;
		$page_end = $p['page_current'] + $p['page_show'];
	}	
	?>
	
	<?php if ( $pages > 1 ): ?>
		<div class="paginator">
		
		<?php if ( $first_page_link ): ?>
			<a href="<?= str_replace( '::page::', '1', $p['url_pattern'] ) ?>">&lt;&lt;</a>
		<?php endif; ?>
		
		<?php for ( $i = $page_start; $i <= $page_end; $i++ ): ?>
			<?php if ( $i === $p['page_current'] ): ?>
				<span class="current"><?= $i ?></span>
			<?php else: ?>
				<a href="<?= str_replace( '::page::', (string) $i, $p['url_pattern'] ) ?>"><?=$i?></a>
			<?php endif; ?>
		<?php endfor; ?>
			
		<?php if ( $last_page_link ): ?>
			<a href="<?= str_replace( '::page::', (string) $pages, $p['url_pattern'] ) ?>">&gt;&gt;</a>
		<?php endif; ?>
		
		</div>
	<?php endif; ?>
	
	<?
}