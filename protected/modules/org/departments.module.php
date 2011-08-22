<?php
/*
	Модуль создания и редактироваия подраздедлений
	Marenin Alex
	August 2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');

if ( $r->equal('org/departments/get') ){
	$departments = $db->query("
		SELECT * FROM org_departments WHERE parent_id = '0'
	");
	$out = array();
	foreach( $departments as $d ){
		$out[] = array(
			'data' => $d['name'],
			'metadata' => array( 'id' => (int) $d['id'] ),
			'children' => array()
		);
	}
	die( json_encode( array( $out )));
}
$departments = $db->query("SELECT * FROM org_departments WHERE parent_id = '0'");
$out = array();
foreach( $departments as $d ){
	$out[] = array(
		'data' => $d['name'],
		'metadata' => array( 'id' => (int) $d['id'] ),
		'children' => array()
	);
}
$out = json_encode( array( $out ));


//
// ВЫВОД
//
top();
?>
<h2></h2>


<!-- СООБЩЕНИЕ ОБ ОШИБКЕ -->
<?php if ( !empty($error) ): ?>
	<div class="error"><?= $error ?></div>
<?php endif; ?>


<div id="departments_tree"></div>


<script type="text/javascript">
$("#departments_tree")
.bind("before.jstree", function ( e, data ){
	// console.log('before.jstree fired', data);
})
.jstree({ 
	// List of active plugins
	"plugins": [ "json_data", "ui", "themes" ],
	// json_data plugin
	"json_data": {
		"data": <?= $out ?>
		/*
		"ajax" : {
			"url" : "<?= WEBURL .'org/departments/get' ?>",
			// the result is fed to the AJAX request `data` option
			"data" : function (n){ 
				return { 
					"operation" : "get_children", 
					"id" : n.attr ? n.attr("id").replace("node_","") : 1 
				}; 
			}
		}
		*/
	},
	
	// the core plugin - not many options here
	"core" : { 
	}
})
</script>


<?= bottom() ?>