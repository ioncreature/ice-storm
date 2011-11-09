<?php
/**
 * User preferences
 * Marenin Alex
 * November 2011
 */

$r = RequestParser::get_instance();

if ( $r->equal('user/cabinet/change_pass') and isset($r->pass_old, $r->pass_new, $r->pass_confirm) ){
	$user = Auth::get_user();
	
	try {
		if ( ! $user->check_password($r->pass_old) )
			throw new Exception( 'Неверный пароль' );
		
		if ( $r->pass_new == '' )
			throw new Exception( 'Введите новый пароль' );
		
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


<!-- ФОРМА ИЗМЕНЕНИЯ ПАРОЛЯ -->
<form id="f_change_pass" action="<?= WEBURL .'user/cabinet/change_pass' ?>" method="POST">
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
	<input type="submit" value="Сменить пароль" />
	
	<!-- СОБЩЕНИЕ ОБ ОШИБКЕ -->
	<div class="error" style="display: none;">
		<?= isset($error) ? $error : '' ?>
	</div>
	
	<!-- СООБЩЕНИЕ ОБ УСПЕХЕ -->
	<?php if ( $r->equal('user/cabinet/success') ): ?>
		<div class="success">Пароль успешно изменён</div>
	<?php endif; ?>
</form>

<script type="text/javascript">
	$( 'form#f_change_pass' ).submit( function(){
		try{
			if ( ! $('input[name=pass_old]', this).val() )
				throw 'Введите старый пароль';

			if ( ! $('input[name=pass_new]', this).val() )
				throw 'Введите новый пароль';

			if ( $('input[name=pass_new]', this).val() !== $('input[name=pass_confirm]', this).val() )
				throw 'Новый пароль и подтверждение не совпадают';
		}
		catch ( e ){
			$( 'div.error', this ).html( e ).show();
			$( 'div.success', this ).hide();
			return false;
		}
		return true;
	});
</script>

<?= Template::bottom() ?>