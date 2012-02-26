<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

?>
<label>
	<span class="label">Фамилия</span>
	<input
		type="text"
		name="human_last_name"
		value="<?= $data->val( 'human_last_name' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
	/>
</label>

<label>
	<span class="label">Имя</span>
	<input
		type="text"
		name="human_first_name"
		value="<?= $data->val( 'human_first_name' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
	/>
</label>

<label>
	<span class="label">Отчество</span>
	<input
		type="text"
		name="human_middle_name"
		value="<?= $data->val( 'human_middle_name' ) ?>"
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
	<span class="label">Дата рождения</span>
	<input
		name="human_birth_date"
		value="<?= $data->val( 'human_birth_date' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.DateTextBox"
		data-dojo-props="required: true, constraints: { max: '<?= date('Y-m-d', strtotime('-16 years')) ?>', datePattern: 'yyyy.MM.dd' }"
		/>
</label>

<label>
	<span class="label">Мобильный телефон</span>
	<input
		type="text"
		name="human_phone"
		value="<?= $data->val( 'human_phone' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '\\s*[\+]?[0-9 \(\)-]{2,20}\\s*'"
	/>
</label>

<label>
	<span class="label">Email</span>
	<input
		type="text"
		name="human_email"
		value="<?= $data->val( 'human_email' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExpGen: dojox.validate.regexp.emailAddress"
	/>
</label>

<label>
	<span class="label">Skype-аккаунт</span>
	<input
		type="text"
		name="human_skype"
		value="<?= $data->val( 'human_skype' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '[\\w0-9_-]{2,25}'"
	/>
</label>

<label>
	<span class="label">ICQ-аккаунт</span>
	<input
		type="text"
		name="human_icq"
		value="<?= $data->val( 'human_icq' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false, regExp: '[0-9]{4,9}'"
	/>
</label>

<label>
	<span class="label">Адрес страницы во vkontakte.ru</span>
	<input
		type="text"
		name="human_vkcom"
		value="<?= $data->val( 'human_vkcom' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false"
		promptMessage="Ссылка на страницу ВКонтакте, например <i>vkontakte.ru/id1</i> или <i>http://vkontakte.ru/durov</i>"
	/>
</label>

<label>
	<span class="label">Адрес страницы в facebook.com</span>
	<input
		type="text"
		name="human_facebook"
		value="<?= $data->val( 'human_facebook' ) ?>"
		class="common_input"
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="required: false"
		promptMessage="Ссылка на страницу в facebook.com, например <i>http://www.facebook.com/christianjossiel</i>"
	/>
</label>
