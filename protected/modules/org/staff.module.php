<?php
/**
 * Staff module
 * Marenin Alex
 * November 2011
 */

// JS + CSS
Template::add_js( '/js/dojo/dojo.js', array( 'djConfig' => 'parseOnLoad: true, isDebug: true' ) );
Template::add_js( '/js/app/init.js' );
Template::add_css( '/js/dijit/themes/claro/claro.css' );

$r = RequestParser::get_instance();
$db = Fabric::get( 'db' );

$staff = $db->query("
	SELECT
		org_staff.*,
		org_departments.name as department,
		CONCAT( org_humans.last_name, ' ', org_humans.first_name, ' ', org_humans.middle_name ) as name
	FROM
		org_staff
		LEFT JOIN org_departments ON org_departments.id = org_staff.department_id
		LEFT JOIN org_humans ON org_humans.id = org_staff.human_id
");

//
// Вывод
//
Template::ob_to_block( 'body' );
?>
<h1>Сотрудники</h1>
<script type="text/javascript">
	dojo.require( 'app.controller.Staff' );
</script>

<form action="<?= WEBURL .'org/staff' ?>">
	
</form>

<table class="common">
	<tr>
		<th>Имя</th>
		<th>Должность</th>
		<th>Подразделение</th>
	</tr>
<?php foreach ( $staff as $s ): ?>
	<tr>
		<td><?= htmlspecialchars($s['name']) ?></td>
		<td><?= htmlspecialchars($s['post']) ?></td>
		<td><?= htmlspecialchars($s['department']) ?></td>
	</tr>
<?php endforeach; ?>
</table>

<?php
Template::ob_end();
