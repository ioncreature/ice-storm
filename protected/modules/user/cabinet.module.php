<?php
/**
 * User preferences
 * Marenin Alex
 * November 2011
 */

$r = RequestParser::get_instance();

if ( $r->equal('user/cabinet/pass') and isset($r->pass_old, $r->pass_new, $r->pass_confirm) ){
	$user = Auth::get_user();
	
	try {
		if ( ! $user->check_password($r->pass_old) )
			throw new Exception( 'Неверный пароль' );
		
		if ( $r->pass_new !== $r->pass_confirm )
			throw new Exception( 'Пароли не совпадают' );
		
		$user->password = $r->pass_new;
		$user->save();
		
		redirect( WEBURL .'user/cabinet/success' );
	}
	catch ( Exception $e ){
		$error = $e->getMessage();
	}
}

//
// ВЫВОД
//
Template::top();
?>
<h2>Личный кабинет</h2>

<?php if ( isset($error) ): ?>
	<div class="error"><?= $error ?></div>
<?php endif; ?>

<?php if ( $r->equal('user/cabinet/success') ): ?>
	<div class="success">Пароль успешно изменён</div>
<?php endif; ?>


<!-- ФОРМА ИЗМЕНЕНИЯ ПАРОЛЯ -->
<form id="f_change_pass" action="<?= WEBURL .'user/cabinet/pass' ?>" method="POST">
	<label>
		<span style="display: inline-block; width: 250px;">Введите старый пароль</span>
		<input type="password" name="pass_old" value="" />
	</label>
	<label>
		<span style="display: inline-block; width: 250px;">Введите новый пароль</span>
		<input type="password" name="pass_new" value="" />
	</label>
	<label>
		<span style="display: inline-block; width: 250px;">Подтвердите новый пароль</span>
		<input type="password" name="pass_confirm" value="" />
	</label>
	<input type="submit" value="Сохранить" />
</form>

<?= Template::bottom() ?>