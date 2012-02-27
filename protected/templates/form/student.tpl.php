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

	<?php if ( isset($data['student']) and $data['student']->id ): ?>
		<input type="hidden" name="human_id" value="<?= $data['student']->id ?>" />
	<?php endif; ?>

	<label>
		<span class="label">Группа</span>
		<select
			name="group_id"
			data-dojo-type="dijit.form.FilteringSelect"
			style="width: 300px;"
			class="common_input"
			requred="true"
		><?= $data['form']->get_field('group_id')->render_body() ?></select>
		<span class="error_msg"><?= $data['form']->error('group_id') ?></span>
	</label>

	<label>
		<span class="label">Дата зачисления</span>
		<div
			name="enrollment_date"
			value="<?= $data['form']->val('enrollment_date') ?>"
			class="common_input"
			data-dojo-type="dijit.form.DateTextBox"
			data-dojo-props="constraints: { max: '<?= date('Y-m-d') ?>', datePattern: 'yyyy.MM.dd' }, required: true"
		></div>
		<span class="error_msg"><?= $data['form']->error('enrollment_date') ?></span>
	</label>

	<label>
		<span class="label">Приказ о зачислении</span>
		<input
			data-dojo-type="dijit.form.ValidationTextBox"
			value="<?= $data['form']->val('enrollment_order'); ?>"
			name="enrollment_order"
			class="common_input"
			data-dojo-props="required: false"
		/>
		<span class="error_msg"><?= $data['form']->error('enrollment_order') ?></span>
	</label>

	<label>
		<span class="label">Дата отчисления</span>
		<div
			name="dismissal_date"
			value="<?= $data['form']->val('dismissal_date') ?>"
			class="common_input"
			data-dojo-type="dijit.form.DateTextBox"
			data-dojo-props="constraints: { max: '<?= date('Y-m-d', time()+24*3600*15) ?>', datePattern: 'yyyy.MM.dd' }"
		></div>
		<span class="error_msg"><?= $data['form']->error('dismissal_date') ?></span>
	</label>

	<label>
		<span class="label">Приказ об отчислении</span>
		<input
			data-dojo-type="dijit.form.ValidationTextBox"
			name="dismissal_order"
			value="<?= $data['form']->val('dismissal_order'); ?>"
			class="common_input"
			data-dojo-props="required: false"
		/>
		<span class="error_msg"><?= $data['form']->error('dismissal_order') ?></span>
	</label>

	<label>
		<span class="label">Причина отчисления</span>
		<select
			name="dismissal_reason"
			data-dojo-type="dijit.form.Select"
			style="width: 300px;"
			class="common_input"
		><?= $data['form']->get_field('dismissal_reason')->render_body() ?></select>
		<span class="error_msg"><?= $data['form']->error('dismissal_reason') ?></span>
	</label>

	<?php if ( $data['action'] === 'add' ): ?>
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