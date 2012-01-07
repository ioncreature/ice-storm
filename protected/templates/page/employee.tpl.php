<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

Template::add_js(  WEBURL .'js/dojo/dojo.js');
Template::add_css( WEBURL .'js/dijit/themes/dijit.css' );
Template::add_css( WEBURL .'js/dijit/themes/claro/claro.css' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.Form");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.DateTextBox");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.TextBox");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.Button");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.CheckBox");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.ValidationTextBox");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.Select");' );
?>


<!-- FORM -->
<form
	class="common_form"
	id="employee_form"
	action="<?= WEBURL .'org/employee/add' ?>"
	method="POST"
	data-dojo-type="dijit.form.Form"
>
	<?php if ( isset($params['employee']) and $params['employee']->id ): ?>
		<input type="hidden" name="employee_id" value="<?= $params['employee']->id ?>" />
	<?php endif; ?>
	<label>
		<span>Должность</span>
		<input
			value=""
			name="post"
			class="common_input"
			data-dojo-type="dijit.form.ValidationTextBox"
			data-dojo-props="required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
		/>
	</label>

	<label>
		<span>Подразделение</span>
		<select name="department_id" data-dojo-type="dijit.form.Select" style="width: 240px;" class="common_input">
			<?php foreach( $params['department']->get_all() as $d ): ?>
				<option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
			<?php endforeach; ?>
		</select>
	</label>

	<label>
		<span>Рабочий телефон</span>
		<input data-dojo-type="dijit.form.TextBox" value="" name="phone" class="common_input"></input>
	</label>

	<label>
		<span>Дата приема на работу</span>
		<div
			name="adoption_date"
			value="<?= date('Y-m-d') ?>"
			class="common_input"
			data-dojo-type="dijit.form.DateTextBox"
			data-dojo-props="constraints: { max: '<?= date('Y-m-d') ?>', datePattern: 'yyyy.MM.dd' }"></div>
	</label>

	<label>
		<span>Ставка</span>
		<select name="work_rate" data-dojo-type="dijit.form.Select" style="width: 240px;" class="common_input">
			<option value="full">Полная</option>
			<option value="half">Одна вторая</option>
			<option value="quarter">Одна четвертая</option>
		</select>
	</label>

	<label>
		<span>Руководитель подразделения</span>
		<input
			type="checkbox"
			name="is_chief"
			data-dojo-type="dijit.form.CheckBox"
		/>
	</label>

	<!-- PERSONAL DATA -->
	<fieldset>
		<legend>Персональные данные</legend>

		<label style="display: inline-block;">
			<input type="radio" name="human_source" value="existing" checked />Выбрать из списка
		</label>
		<label style="display: inline-block;">
			<input type="radio" name="human_source" value="new" />Добавить
		</label>

		<!-- EXISTING PERSONALIA -->
		<div id="exisiting_personalia">
			<select name="human_id" data-dojo-type="dijit.form.Select" style="width: 350px;">
				<?php foreach( $params['human']->get_all() as $h ): ?>
					<option value="<?= $h['id'] ?>"><?= $h['name'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<!-- NEW PERSONALIA -->
		<div id="new_personalia" style="display:none;">
			<?= Template::show(
				'include/personal_data_subform',
				isset($params['personal_data']) ? $params['personal_data'] : array()
			) ?>
		</div>

		<script type="text/javascript">
			$( 'input[name=human_source]' ).change( function( event ){
				if ( $(this).val() === 'new' ){
					$( '#exisiting_personalia' ).hide();
					$( '#new_personalia' ).fadeIn(600);
				}
				else {
					$( '#exisiting_personalia' ).fadeIn(600);
					$( '#new_personalia' ).hide();
				}
			});
		</script>
	</fieldset>

	<input type="submit" data-dojo-type="dijit.form.Button" label="Добавить" />
</form>

<script type="text/javascript">
dojo.ready( function(){
	dijit.byId( 'employee_form' ).onSubmit = function(){
		if ( $( 'input[name=human_source]:checked' ).val() === 'existing' )
			return this.validate( 'post' );
		else
			return this.validate();
	};
});
</script>