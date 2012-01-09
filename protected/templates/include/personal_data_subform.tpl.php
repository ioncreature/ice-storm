<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

Template::add_to_block( 'js', 'dojo.require( "dojox.validate.regexp" );' );
Template::add_to_block( 'js', 'dojo.require( "dijit.form.Button" );' );
Template::add_css( WEBURL .'js/dojox/form/resources/FileInput.css' );
?>
<label>
	<span>Фамилия</span>
	<input
		type="text"
		name="human_last_name"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
	/>
</label>

<label>
	<span>Имя</span>
	<input
		type="text"
		name="human_first_name"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
	/>
</label>

<label>
	<span>Отчество</span>
	<input
		type="text"
		name="human_middle_name"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
	/>
</label>

<!--
<label>
	<span>Фото</span>
	<input
		type="file"
		name="human_photo"
		class="common_input"
		data-dojo-type="dijit.form.Button"
		multiple="false"
		label="Выберите фото"
		id="human_photo_uploaded2"
	/>
</label>
-->

<label>
	<span>Дата рождения</span>
	<div
		name="human_birth_date"
		value="<?= date('Y-m-d', strtotime('-18 years')) ?>"
		required="true"
		class="common_input"
		data-dojo-type="dijit.form.DateTextBox"
		data-dojo-props="constraints: { max: '<?= date('Y-m-d', strtotime('-16 years')) ?>', datePattern: 'yyyy.MM.dd' }"></div>
</label>

<label>
	<span>Мобильный телефон</span>
	<input
		type="text"
		name="human_phone"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '\\s*[\+]?[0-9 \(\)-]{2,20}\\s*'"
	/>
</label>

<label>
	<span>Email</span>
	<input
		type="text"
		name="human_email"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExpGen: dojox.validate.regexp.emailAddress"
	/>
</label>

<label>
	<span>Skype-аккаунт</span>
	<input
		type="text"
		name="human_skype"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '[\\w0-9_-]{2,25}'"
	/>
</label>

<label>
	<span>ICQ-аккаунт</span>
	<input
		type="text"
		name="human_icq"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '[0-9]{4,9}'"
	/>
</label>

<label>
	<span>Адрес страницы во vkontakte.ru</span>
	<input
		type="text"
		name="human_vkontakte"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '[0-9]{4,9}'"
		promptMessage="Ссылка на страницу ВКонтакте, например <i>vkontakte.ru/id1</i> или <i>http://vkontakte.ru/durov</i>"
	/>
</label>

<label>
	<span>Адрес страницы в facebook.com</span>
	<input
		type="text"
		name="human_facebook"
		value=""
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '[0-9]{4,9}'"
		promptMessage="Ссылка на страницу в facebook.com, например <i>http://www.facebook.com/christianjossiel</i>"
	/>
</label>