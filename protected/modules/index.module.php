<?php
/*
	index page
	Alex Marenin
	2011
*/

$r = \Request\Parser::get_instance();
$error = '';

if ( isset($r->login, $r->password) ){
	$a = Auth::get_instance();
	if ( $a->login($r->login, $r->password) )
		redirect( WEBURL );
	else
		$error .= "Неверный логин или пароль";
}


//
// ВЫВОД
//
Template::top();
?>

<!-- СООБЩЕНИЕ ОБ ОШИБКЕ -->
<?php if ( !empty($error) ): ?>
	<div class="error"><?= $error?></div>
<?php endif; ?>


<h2>Главная страница</h2>
<p>hello!</p>
<?= Template::bottom() ?>