<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

?>

<script type="text/javascript">
require([
	'app/init'
], function( parser ){
	console.error( 'asdasdasd' );
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
			<script type="text/javascript">
				console.error( $( 'input[name=human_source]' ) );
				$( 'input[name=human_source]' ).change( function( event ){
					console.log( 'asdasdasd' );
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

			<!-- EXISTING PERSONALIA -->
			<div id="exisiting_personalia">
				<select name="human_id" data-dojo-type="dijit.form.FilteringSelect" style="width: 350px;">
					<?= $data['form']->get_field( 'human_id' )->render_body() ?>
				</select>
			</div>

			<!-- NEW PERSONALIA -->
			<div id="new_personalia" style="display:none;">
				<?= Template::show( 'form/human', $data['human_form'] ) ?>
			</div>
		</fieldset>
	<?php endif; ?>

	<br/>
	<input type="submit" data-dojo-type="dijit.form.Button" label="<?= isset($data['edit']) && $data['edit'] ? 'Редактировать' : 'Добавить' ?>" />
</form>