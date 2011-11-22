<?php
/*
	test module
*/
$r = RequestParser::get_instance();
$db = Fabric::get('db');
$acl = Auth::$acl;

$u = new Model\User( 1 );
$u->password = '1';
$u->save();
//
// ВЫВОД
Template::add_js( '/js/jstree/jquery.jstree.js' );
Template::top();

echo '<pre>';
echo var_export( Auth::$acl, true);
echo '</pre>';

//$a = new \Model\Human();
//$a->get_by_id(1);
//var_dump($a);

// Определяем текущую страницу
$current_page = $r->is_int(1) ? $r->to_int(1) : 1;
paginator(array(
	'page_current' => $current_page,
	'items_total' => 250,
	'url_pattern' => WEBURL .'test/::page::',
));
?>
<p>Hello! This is a test page.</p>

<div id="demo1"></div>
<script type="text/javascript">	
$(function () {
	$("#demo1").jstree({ 
		"json_data" : {
			"data" : [
				{ 
					"data" : "A node", 
					"metadata" : { id : 23 },
					"children" : [
						{
							id: 'trsts',
							data: "leaf node",
							attr: { 
								id: "100",
								rel: "group"
							}
						},
						"Child 1", "A Child 2"
					],
					state: "open"
				},
				{ 
					"attr" : { "id" : "li.node.id1" }, 
					"data" : { 
						"title" : "Long format demo", 
						"attr" : { "href" : "#" } 
					} 
				}
			]
		},
		'types': {
			'types' : {
				'group' : {
					'icon' : {
						'image' : '<?= WEBURL ?>themes/default/groups.png'
					}
				}
			}

		},
		"plugins" : [ "themes", "json_data", "ui", "types" ]
	}).bind("select_node.jstree", function (e, data) { 
		// alert(data.rslt.obj.data("id")); 
	});
});
</script>

<form action="<?= WEBURL .'test/siski/10' ?>" method="POST">
<input type="submit" name="siski" value="4" />
</form>

<?= Template::bottom() ?>