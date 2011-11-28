<?php
/**
 * Staff module
 * Marenin Alex
 * November 2011
 */

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
// ВЫВОД
//

// PREPARE
Template::add_js( '/js/dojo/dojo.js', array( 'djConfig' => 'parseOnLoad: true, isDebug: true' ) );
Template::add_js( '/js/app/init.js' );
Template::add_css( '/js/dijit/themes/claro/claro.css' );
// JS
Template::add_to_block( 'js', "dojo.require( 'app.controller.Staff' );" );
Template::add_to_block( 'js', "dojo.require( 'dojo.data.ItemFileReadStore' );" );
Template::add_to_block( 'js', "dojo.require( 'dijit.Tree' );" );
Template::add_to_block( 'js', "dojo.require( 'dijit.TitlePane' );" );
Template::add_to_block( 'body_class', 'claro' );

Template::ob_to_block( 'body' );
?>

<script type="text/javascript">
</script>

<div
	dojoType="dijit.layout.BorderContainer"
	gutters="true"
	design="sidebar"
	style="width: 100%; height: 100%; min-height: 720px;">

	<!-- LEFT PANE -->
	<section id="left_pane" dojoType="dijit.layout.ContentPane" region="left" style="width: 275px;">
		<h1>Подразделения</h1>
		<div
			dojoType="dojo.data.ItemFileReadStore"
			jsId="continentStore"
			url="http://ice/c.json"></div>
		<div
			dojoType="dijit.tree.ForestStoreModel"
			jsId="continentModel"
			store="continentStore"
			query="{type:'continent'}"
			rootId="continentRoot"
			rootLabel="Continents"
			childrenAttrs="children"></div>
		<div dojoType="dijit.Tree" id="mytree" model="continentModel" openOnClick="true" style="overflow:hidden">
			<script type="dojo/method" event="onClick" args="item">
				alert("Execute of node " + continentStore.getLabel(item) + ", population=" + continentStore.getValue(item, "population"));
			</script>
		</div>
	</section>

	<!-- CENTER PANE -->
	<section id="center_pane" dojoType="dijit.layout.ContentPane" region="center">
		<h1>Сотрудники</h1>
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
	</section>
</div>

<?php Template::ob_end(); ?>