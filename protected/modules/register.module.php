<?php
/*
	Register
	Alex Marenin
	2011
*/

$r = RequestParser::get_instance();
$error = '';
$message = '';


// регистрация
if ( isset($r->login, $r->password, $r->email) ){
	$u = new UserModel;
	$u->login = $r->login;
	$u->password = $r->password;
	$u->email = $r->email;
	$u->active = 'yes';
	$u->human_id = 0;
	$u->save();
	
	if ( $u->exists() )
		redirect( WEBURL .'register/success/'. $u->id );
	else
		$error .= "Произошла ошибка при регистрации, попробуйте позже";
}


// регистрация успешна
if ( $r->is_equal(1, 'success') and $r->is_int(2) ){
	$user = new UserModel( $r->to_int(2) );
	if ( $user->exists() )
		$message .= 'Регистрация прошла успешно, авторизуйтесь';
}


//
// ВЫВОД
//
top();
?>

<h2>Регистрация</h2>

<!-- СООБЩЕНИЕ ОБ ОШИБКЕ -->
<?php if ( !empty($error) ): ?>
	<div class="error"><?= $error ?></div>
<?php endif; ?>

<!-- СООБЩЕНИЕ ОБ УСПЕШНОЙ РЕГИСТРАЦИИ -->
<?php if ( !empty($message) ): ?>
	<div class="success"><?= $message ?></div>
<?php endif; ?>


<!-- ФОРМА РЕГИСТРАЦИИ -->
<form id="register" action="<?= WEBURL .'register' ?>" method="POST">
	<label>login<br />
		<input type="text" name="login" placeholder="логин" />
	</label>
	<label>password<br />
		<input type="text" name="password" placeholder="пароль" />
	</label>
	<label>retype password<br />
		<input type="text" name="repassword" placeholder="пароль" />
	</label>
	<label>email<br />
		<input type="text" name="email" placeholder="email" />
	</label>
	<input type="submit" value="Зарегистрироваться" />
	<div class="error" style="display:none"></div>
</form>
<script type="text/javascript">
	$( document ).ready( function(){
		$( 'form#register' ).submit( function(){
			var error = '';
			var login = $( 'input[name=login]', this ).val();
			var pass = $( 'input[name=password]', this ).val();
			var repass = $( 'input[name=repassword]', this ).val();
			var email = $( 'input[name=email]', this ).val();
			
			if ( pass !== repass )
				error += 'Пароли не совпадают<br/>';
				
			if ( ! (login && pass && email) )
				error += 'Заполните все поля<br/>';
			
			if ( error.length > 0 ){
				$('div.error').html( error ).show();
				return false;
			}
			
			return true;
		});
	});
</script>

<?= bottom() ?>