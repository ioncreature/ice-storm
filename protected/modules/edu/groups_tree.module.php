<?php
/*
	Модуль редактирования учебных групп
	Marenin Alex
	October
	2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');

$groups = $db->query("
	SELECT 
		edu_groups.*,
		edu_curriculums.name as cname
	FROM 
		edu_groups
		LEFT JOIN edu_group_terms ON
			edu_groups.id = edu_group_terms.group_id
		LEFT JOIN edu_curriculums ON
			edu_group_terms.curriculum_id = edu_curriculums.id
	WHERE edu_groups.state = 'on'
");


// возвращает список вложеннх подразделений
if ( $r->equal('org/departments/get/') and isset($r->department_id) ){
	$did = (int) $r->department_id;
	$departments = $db->query( "SELECT * FROM org_departments WHERE parent_id = '$did'" );
	$out = array();
	foreach( $departments as $d ){
		$out[] = array(
			'id'	=> (int) $d['id'], 
			'data'	=> $d['name'],
			'rel'	=> 'folder',
			'state' => 'closed',
			'attr'	=> array( 'department_id' => (int) $d['id'] )
		);
	}
	die( json_encode( array( $out )));
}


// добавление подразделения
elseif ( $r->equal('edu/groups/add') and isset($r->id, $r->title) ){
	die( json_encode( array( 'status' => true )));
	$pid = (int) $r->id;
	$name = $db->safe( $r->title );
	$db->start();
	$dep = $db->fetch_query("
		SELECT * 
		FROM org_departments
		WHERE 
			parent_id = '$pid' AND
			name = '$name'
	");
	if ( !$dep )
		$db->insert( 'org_departments', array(
			'name' => $r->title,
			'parent_id' => $pid
		));
	$db->commit();
	die( json_encode( array( 'status' => !$dep )));
}


// перемещение нода в дереве
elseif ( $r->equal('org/departments/move') and isset($r->id, $r->to) ){
	die( json_encode( array( 'status' => true )));
	$dep_id = (int) $r->id;
	$to_id = (int) $r->to;
	$db->start();
	$deps = $db->query("SELECT * FROM org_departments WHERE id IN ('$dep_id','$to_id')");
	if ( count($deps) === 2 ){
		$db->query("
			UPDATE org_departments
			SET
				parent_id = '$to_id'
			WHERE
				id = '$dep_id'
			LIMIT 1
		");
	}
	$db->commit();
	die( json_encode( array( 'status' => count($deps) === 2 )));
}


// удаление подразделения
elseif ( $r->equal('org/departments/remove') and isset($r->id) ){
	die( json_encode( array( 'status' => true )));
	$id = (int) $r->id;
	$db->start();
	$dep = $db->fetch_query("SELECT * FROM org_departments WHERE id = '$id'");
	if ( $dep ){
		$parent_id = (int) $dep['parent_id'];
		$db->query("
			UPDATE org_departments 
			SET parent_id = '$parent_id' 
			WHERE parent_id = '$id'
		");
		$db->query("
			DELETE FROM org_departments 
			WHERE id = '$id'
			LIMIT 1
		");
	}
	$db->commit();
	die( json_encode( array( 'status' => true )));
}


// первоначальное состояние дерева
$departments = $db->cached_query("
	SELECT 
		id, name, parent_id, 
		'department' as rel
	FROM org_departments
	UNION
	SELECT
		id, name, department_id as parent_id,
		'default' as rel
	FROM
		edu_groups
", 5 );
$dep = array();
foreach ( $departments as $k => $d ){
	$a = array(
		'id'		=> (int) $d['id'],
		'parent_id' => (int) $d['parent_id'],
		'data'		=> $d['name'],
		'attr'		=> array( 
			'department_id' => (int) $d['id'],
			'rel' => $d['rel']
		)
	);
	if ( $d['rel'] === 'department' )
		$a['state'] = 'open';
		
// 	$a['state'] = $d['rel'] === 'department' ? 'open' : 'closed';
	$dep[] = $a;
}
$tree = get_departments_tree( $dep );


//
// ВЫВОД
//
Template::add_js( '/js/jstree/jquery.jstree.js' );
Template::top();
?>
<h2>Группы <a href="<?= WEBURL . 'edu/groups_list' ?>">(перейти к списку)</a></h2>



<!-- СООБЩЕНИЕ ОБ ОШИБКЕ -->
<?php if ( !empty($error) ): ?>
	<div class="error"><?= $error ?></div>
<?php endif; ?>

<!-- JSTREE HANDLER -->
<div id="departments_tree"></div>
<script type="text/javascript">
console.log(<?= json_encode( array($tree) ) ?>);
$("#departments_tree")
	.bind("before.jstree", function( e, data ){
		// console.log('before.jstree fired', data);
	})
	.jstree({ 
		plugins: [ "json_data", "ui", "themes", "dnd", "contextmenu", "crrm", "types" ],
		
		theme: {
			theme: 'apple',
			url: "/js/jstree/apple/style.css",
			dots: true,
			icons: true
		},
		
		json_data: {
			data: <?= json_encode( array($tree) ) ?>
		},

		types: {
			max_depth: -2,
			max_children: -2,
			valid_children: [ "department" ],
			types: {
				default: {
					icon: { 'image': "<?= WEBURL .'themes/default/groups.png' ?>" },
					valid_children: "none",
					max_depth: 0,
					max_children: 0
				},
				department: {
					valid_children: [ "department", "group" ]
				}
			}
		},
		
		contextmenu: {
			items: {
				remove: {
					label: "Удалить группу"
				},
				create: {
					label: "Добавить группу",
					action: function( obj ){
						console.log( obj );
						var data = {
							attr: {
								rel: 'default'
							} 
						}
						$("#demo").jstree( "create", null, "last", data );
					}
				},
				rename: {
					label: "Переименовать группу"
				},
				ccp: {
					label: "Редактировать",
					_disabled: true,
					submenu: false
				}
			}
		},
		dnd: {},
		crrm: {},
		core: {}
	})
	
	// create
	.bind( 'create.jstree', function( e, data ){
		/*
		var type = data.rslt.obj.attr( "rel" );
		if ( type !== 'group' ){
			$.jstree.rollback(data.rlbk);
			return false;
		}
		*/
		$.ajax({
			url: '<?= WEBURL .'edu/groups/add' ?>',
			type: 'POST',
			data: {
				parent_id: data.rslt.parent.attr( 'department_id' ),
				title: data.rslt.name,
				type: data.rslt.obj.attr( 'rel' )
			},
			dataType: 'json',
			success: function(){
				if ( r.status )
					$(data.rslt.obj).attr( 'department_id', r.id );
				else {
					$.jstree.rollback(data.rlbk);
					alert( 'Подразделение с таким именем уже существует' );
				}
			}
		});
	})
	
	// move
	.bind( 'move_node.jstree', function( e, data ){
		var type = data.rslt.obj.attr( "rel" );
		if ( type !== 'group' ){
			$.jstree.rollback(data.rlbk);
			return false;
		}
		
		data.rslt.o.each( function( i ){
			$.ajax({
				url: '<?= WEBURL .'edu/groups/move' ?>',
				async: false,
				type: 'POST',
				data : {
					id: $(data.rslt.o[i]).attr( 'department_id' ),
					to: data.rslt.cr === -1 ? 1 : data.rslt.np.attr( 'department_id' )
				}
			});
		});
	})
	
	// remove
	.bind("remove.jstree", function( e, data ){
		var type = data.rslt.obj.attr( "rel" );
		console.log( type );
		if ( type !== 'group' ){
			$.jstree.rollback(data.rlbk);
			return false;
		}
		
		data.rslt.obj.each( function(){
			$.ajax({
				async : false,
				type: 'POST',
				url: '<?= WEBURL .'edu/groups/remove' ?>',
				data : { 
					"id" : $(this).attr('department_id')
				}, 
				success : function( r ){
					if ( !r.status )
						data.inst.refresh();
				}
			});
		});
	});
</script>

<?= Template::bottom() ?>