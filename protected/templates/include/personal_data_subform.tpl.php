<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
?>
<label>
	<span>Фамилия</span>
	<input type="text" name="last_name" value="" data-dojo-type="dijit.form.ValidationTextBox" class="common_input" required="true"/>
</label>

<label>
	<span>Имя</span>
	<input type="text" name="first_name" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>

<label>
	<span>Отчество</span>
	<input type="text" name="middle_name" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>

<label>
	<span>Дата рождения</span>
	<div
		name="adoption_date"
		value="<?= date('Y-m-d', strtotime('-16 years')) ?>"
		required="true"
		class="common_input"
		data-dojo-type="dijit.form.DateTextBox"
		data-dojo-props="constraints: { max: '<?= date('Y-m-d', strtotime('-16 years')) ?>', datePattern: 'yyyy.MM.dd' }"></div>
</label>

<label>
	<span>Мобильный телефон</span>
	<input type="text" name="phone" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>

<label>
	<span>Email</span>
	<input type="text" name="email" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>

<label>
	<span>Skype-аккаунт</span>
	<input type="text" name="skype" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>

<label>
	<span>ICQ-аккаунт</span>
	<input type="text" name="icq" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>

<label>
	<span>Адрес страницы во vkontakte.ru</span>
	<input type="text" name="vkontakte" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>

<label>
	<span>Адрес страницы в facebook.com</span>
	<input type="text" name="facebook" value="" data-dojo-type="dijit.form.TextBox" class="common_input" />
</label>