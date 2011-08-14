<?php
function top(){
?>
<!DOCTYPE html>
<html>
<head>
	<title>icestorm</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="icon" type="image/png" href="/theme/viboom/favicon.png" />
	<link rel="stylesheet" href="/themes/default/style.css?<?=rand(0,999)?>" />
	
	<!-- Libraries -->
    <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="js/json2.js"></script> 
    <script type="text/javascript" src="js/underscore.js"></script>
    <script type="text/javascript" src="js/backbone.js"></script>
    <script type="text/javascript" src="js/mustache.js"></script>

	<!-- Application -->
    <!-- <script type="text/javascript" src="js/app.js"></script> -->
</head>
<body>
<?= menu() ?>
<div id="body">
<?php
}



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
	<div id="header">
		<h1><a href="<?= WEBURL ?>" title="IceStorm">IceStorm</a></h1>
		<ul class="top_menu">
			<li><a href="<?= WEBURL ?>">Главная</a></li>
			<li><a href="<?= WEBURL .'test' ?>">Test</a></li>
			<li><a href="<?= WEBURL .'acl/groups' ?>">ACL groups</a></li>
		</ul>
		<?= auth_form() ?>
	</div>
	<?
}


function auth_form(){?>
	<?php if ( Auth::is_logged() ): ?>
	<form id="auth" method="POST" action="<?= WEBURL .'logout' ?>">
		<input type="submit" name="logout" value="Выход" />
	</form>
	<?php else: ?>
	<form id="auth" method="POST" action="<?= WEBURL ?>">
		<input type="text" name="login" value="" placeholder="Ваш логин" />
		<input type="password" name="password" value="" placeholder="Пароль" />
		<input type="submit" class="submit" value="Войти" />
		<a href="<?= WEBURL .'register' ?>">регистрация</a>
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