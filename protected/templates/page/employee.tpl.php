<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

Template::add_js( WEBURL .'js/dojo/dojo.js');
Template::add_css( WEBURL .'js/dijit/themes/dijit.css' );
Template::add_css( WEBURL .'js/dijit/themes/claro/claro.css' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.DateTextBox");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.TextBox");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.ValidationTextBox");' );
Template::add_to_block( 'js', 'dojo.require("dijit.form.Select");' );
?>


<!-- FORM -->
<form class="common_form" id="add_employee_form" action="<?= WEBURL .'org/employee/add' ?>" method="POST">
	<label>
		<span>Должность</span>
		<input data-dojo-type="dijit.form.TextBox" value="" name="post" class="common_input"></input>
	</label>

	<label>
		<span>Подразделение</span>
		<select name="department_id" data-dojo-type="dijit.form.Select" class="common_input" style="width: 240px;">
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
		<span>Дата приема</span>
		<div
			name="adoption_date"
			value="<?= date('Y-m-d') ?>"
			class="common_input"
			data-dojo-type="dijit.form.DateTextBox"
			data-dojo-props="constraints: { max: '<?= date('Y-m-d') ?>', datePattern: 'yyyy.MM.dd' }"></div>
	</label>

	<!-- PERSONAL DATA -->
	<fieldset>
		<legend>Персональные данные</legend>

		<label style="display: inline-block;">
			<input type="radio" name="human_source" value="existing" checked />Выбрать из списка
		</label>
		<label style="display: inline-block;">
			<input type="radio" name="human_source" value="new" />Добавить новые
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

	<input type="submit" value="Добавить" class="common" />
</form>
