<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

if ( Auth::is_logged() ): ?>

<form id="auth" class="logged" method="POST" action="<?= WEBURL .'logout' ?>">
	<a href="<?= WEBURL . 'user/cabinet'?>" title="Мой Кабинет"><?= Auth::get_user()->login ?></a>
	<input type="submit" name="logout" value="Выход" class="common" />
</form>

<?php else: ?>
<form id="auth" class="unlogged" method="POST" action="<?= WEBURL ?>">
	<input type="text" name="login" value="" placeholder="Ваш логин" class="common" />
	<input type="password" name="password" value="" placeholder="Пароль" class="common" /><br />
	<input type="submit" class="submit" value="Войти" class="common" />
	<a href="<?= WEBURL .'register' ?>" style="float:right;">регистрация</a>
</form>

<?php endif; ?>