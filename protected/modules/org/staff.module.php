<?php
/**
 * Staff module
 * Marenin Alex
 * November 2011
 */

$r = RequestParser::get_instance();
$db = Fabric::get( 'db' );

$department_id = $r->to_int( 2 );


// берем список пользователей
if ( $department_id ){

}



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
// ВЫВОД
//

// PREPARE
Template::add_js( '/js/dojo/dojo.js', array('djConfig' => 'parseOnLoad: true, isDebug: true') );
Template::add_js( '/js/app/init.js' );
Template::add_js( '/js/app/page/Staff.js' );
Template::add_css( '/js/dijit/themes/claro/claro.css' );
Template::add_css( '/js/dojox/grid/resources/Grid.css' );
Template::add_css( '/js/dojox/grid/resources/claroGrid.css' );
Template::add_to_block( 'body_class', 'claro' );

// JS
Template::ob_to_block( 'head' ); ?>
<script type="text/javascript">

</script>
<?php
Template::ob_end();

// BODY
Template::ob_to_block( 'body' );
?>

<script type="text/javascript">
</script>

<div
	dojoType="dijit.layout.BorderContainer"
	gutters="true"
	design="sidebar"
	style="min-height: 760px;">

	<!-- LEFT PANE -->
	<section id="left_pane" dojoType="dijit.layout.ContentPane" region="left" style="width: 35%; min-width: 350px; padding: 0px;">
		<div dojoType="dijit.layout.BorderContainer" gutters="false">
			<div dojoType="dijit.layout.ContentPane" region="top">
				<h1>Подразделения</h1>
			</div>

			<!-- DEPARTMENTS TREE -->
			<div dojoType="dijit.layout.ContentPane" region="center">
				<div dojoType="dijit.Tree" id="staff_departments_tree" model="app.store.Departments" style="height:100%"></div>
			</div>
		</div>

	</section>

	<!-- CENTER PANE -->
	<section id="center_pane" dojoType="dijit.layout.ContentPane" region="center">
		<div id="staff_grid"></div>
		<!--
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
		-->
	</section>
</div>

<?php Template::ob_end(); ?>