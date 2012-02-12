<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

Template::add_css( WEBURL .'js/dijit/themes/claro/claro.css' );
Template::add_css( WEBURL .'js/dijit/themes/dijit.css' );
Template::add_js(  WEBURL .'js/dojo/dojo.js');
?>

<script type="text/javascript">
require([
	'dojo/parser',
	'dojo/domReady!',
	'app/init',
	'app/widget/Form',
	'dijit/form/DateTextBox',
	'dijit/form/TextBox',
	'dijit/form/Button',
	'dijit/form/CheckBox',
	'dijit/form/ValidationTextBox',
	'dijit/form/Select'
], function( parser ){
	parser.parse( document.body );
	console.log( dijit.byId( 'employee_form' ) );
	dijit.byId( 'employee_form' ).onSubmit = function(){
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
	id="employee_form"
	action="<?= $data['form']->get_action() ?>"
	method="POST"
	data-dojo-type="app.widget.Form"
>
	<?php if ( isset($data['employee']) and $data['employee']->id ): ?>
		<input type="hidden" name="employee_id" value="<?= $data['employee']->id ?>" />
	<?php endif; ?>
	<label>
		<span>Должность</span>
		<input
			value="<?= $data['form']->val( 'post' ); ?>"
			name="post"
			class="common_input"
			data-dojo-type="dijit.form.ValidationTextBox"
			data-dojo-props="required: true, regExp: '[a-zA-zа-яА-Я0-9,;\. -]{2,200}'"
		/>
	</label>

	<label>
		<span>Подразделение</span>
		<select
			name="department_id"
			data-dojo-type="dijit.form.Select"
			style="width: 300px;"
			class="common_input"
			requred="true"
		><?= $data['form']->get_field('department_id')->render_body() ?></select>
	</label>

	<label>
		<span>Рабочий телефон</span>
		<input
			data-dojo-type="dijit.form.ValidationTextBox"
			value="<?= $data['form']->val( 'phone' ); ?>"
			name="phone"
			class="common_input"
			data-dojo-props="required: false, regExp: '\\s*[\+]?[0-9 \(\)-]{2,20}\\s*'"
			>
	</label>

	<label>
		<span>Дата приема на работу</span>
		<div
			name="adoption_date"
			value="<?= $data['form']->val('adoption_date') ?>"
			class="common_input"
			data-dojo-type="dijit.form.DateTextBox"
			data-dojo-props="constraints: { max: '<?= date('Y-m-d') ?>', datePattern: 'yyyy.MM.dd' }, required: true"
		></div>
	</label>

	<label>
		<span>Ставка</span>
		<select
			name="work_rate"
			data-dojo-type="dijit.form.Select"
			style="width: 300px;"
			class="common_input"
			required="true"
		>
			<?= $data['form']->get_field( 'work_rate' )->render_body(); ?>
		</select>
	</label>

	<label>
		<span>Руководитель подразделения</span>
		<?= $data['form']->get_field('chief')->render() ?>
	</label>

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