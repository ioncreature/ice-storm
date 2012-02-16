<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

?>

<script type="text/javascript">
require([
	'app/widget/Form',
	'dijit/form/DateTextBox',
	'dijit/form/TextBox',
	'dijit/form/Button',
	'dijit/form/CheckBox',
	'dijit/form/ValidationTextBox',
	'dijit/form/Select',
	'dojo/domReady!',
	'app/init'
], function( parser ){
	dijit.byId( 'student_form' ).onSubmit = function(){
		if ( $( 'input[name=human_source]:checked' ).val() === 'existing' )
			return this.validate( 'post' );
		else
			return this.validate();
	};
});
</script>

<!-- FORM -->
<form
	class="common_form"
	id="student_form"
	action="<?= $data['form']->get_action() ?>"
	method="POST"
	data-dojo-type="app.widget.Form"
>

	<?php if ( !isset($data['edit']) or !$data['edit'] ): ?>
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
					<?php foreach( $data['human']->get_all() as $h ): ?>
						<option value="<?= $h['id'] ?>"><?= $h['name'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<!-- NEW PERSONALIA -->
			<div id="new_personalia" style="display:none;">
				<?= Template::show( 'include/personal_data_subform', $data['human_form'] ) ?>
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
	<?php endif; ?>

	<br/>
	<input type="submit" data-dojo-type="dijit.form.Button" label="<?= isset($data['edit']) && $data['edit'] ? 'Редактировать' : 'Добавить' ?>" />
</form>