<?php
/*
	Модуль создания и редактироваия подраздедлений
	Marenin Alex
	August 2011
*/

$r = \Request\Parser::get_instance();
$db = Fabric::get('db');


// возвращает список вложеннх подразделений
if ( $r->equal('org/departments/get/') and isset($r->department_id) ){
	$did = (int) $r->department_id;
	$departments = $db->query("SELECT * FROM org_departments WHERE parent_id = '$did'");
	$out = array();
	foreach( $departments as $d ){
		$out[] = array(
			'data' => $d['name'],
			'rel' => 'folder',
			'state' => 'closed',
			'attr' => array( 'department_id' => (int) $d['id'] )
		);
	}
	die( json_encode( array( $out )));
}


// добавление подразделения
elseif ( $r->equal('org/departments/add') and isset($r->id, $r->title) ){
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
	$dep_id = (int) $r->id;
	$to_id = (int) $r->to;
	$db->start();
	$deps = $db->query("SELECT * FROM org_departments WHERE id IN ('$dep_id','$to_id')");
	if ( count($deps) === 2 or $to_id === 0 ){
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
	die( json_encode( array( 
		'status' => count($deps) === 2 or $to_id === 0 
	)));
}


// переименование нода
elseif ( $r->equal('org/departments/rename') and isset($r->id, $r->title) ){
	$id = (int) $r->id;
	$name = $db->safe( mb_substr($r->title, 0, 125) );
	$db->start();
	
	$dep = $db->query( "SELECT * FROM org_departments WHERE id = '$id'" );
	if ( $dep )
		$db->query("
			UPDATE org_departments
			SET
				name = '$name'
			WHERE
				id = '$id'
			LIMIT 1
		");
	
	$db->commit();
	die( json_encode( array(
		'status' => true
	)));
}


// удаление подразделения
elseif ( $r->equal('org/departments/remove') and isset($r->id) ){
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
$departments = $db->cached_query( "SELECT * FROM org_departments", 5 );
$dep = array();
foreach ( $departments as $k => $d ){
	$dep[] = array(
		'id'		=> (int) $d['id'],
		'parent_id' => (int) $d['parent_id'],
		'data'		=> $d['name'],
		'rel'		=> 'department',
		'state'		=> 'open',
		'attr'		=> array( 'department_id' => (int) $d['id'] )
	);
}
$tree = get_departments_tree( $dep );


//
// ВЫВОД
//
Template::add_to_title( 'Подразделения' );
Template::add_js( '/js/jstree/jquery.jstree.js' );
Template::top();
?>
<h2>Подразделения</h2>



<!-- СООБЩЕНИЕ ОБ ОШИБКЕ -->
<?php if ( !empty($error) ): ?>
	<div class="error"><?= $error ?></div>
<?php endif; ?>



<!-- JSTREE HANDLER -->
<div id="departments_tree"></div>
<script type="text/javascript">
$("#departments_tree")
	.bind("before.jstree", function( e, data ){
		// console.log('before.jstree fired', data);
	})
	.jstree({ 
		plugins: [ "json_data", "ui", "themes", "dnd", "contextmenu", "crrm" ],
		
		theme: {
			theme: 'apple',
			url: "/js/jstree/apple/style.css",
			dots: true,
			icons: true
		},
		
		json_data: {
			data: <?= json_encode(array($tree) ) ?>
			/*
			ajax : {
				url : "<?= WEBURL .'org/departments/get' ?>",
				type: 'POST',
				data : function( elem ){
					return { department_id: $(elem).attr('department_id') }
				}
			}
			*/
		},
		
		contextmenu: {
			items: {
				remove: {
					label: "Удалить"
				},
				create: {
					label: "Создать"
				},
				rename: {
					//_disabled: true,
					label: "Переименовать"
				},
				ccp: {
					label: "Редактировать",
					_disabled: true
				}
			}
		},
		dnd: {},
		crrm: {},
		core: {}
	})
	
	
	// Создание
	.bind( 'create.jstree', function( e, data ){
		console.log('create.jstree: ' + data.rslt.name);	
		$.ajax({
			url: '<?= WEBURL .'org/departments/add' ?>',
			type: 'POST',
			data: {
				id: data.rslt.parent.attr( 'department_id' ),
				title: data.rslt.name,
				type: data.rslt.obj.attr( 'rel' )
			},
			dataType: 'json',
			success: function(){
				if ( r.status )
					$(data.rslt.obj).attr( 'department_id', r.id);
				else {
					$.jstree.rollback(data.rlbk);
					alert( 'Подразделение с таким именем уже существует' );
				}
			}
		});
	})
	
	// Перенос
	.bind( 'move_node.jstree', function( e, data ){
		data.rslt.o.each( function( i ){
			$.ajax({
				url: '<?= WEBURL .'org/departments/move' ?>',
				async: false,
				type: 'POST',
				data : {
					id: $(data.rslt.o[i]).attr( 'department_id' ),
					to: data.rslt.cr === -1 ? 0 : data.rslt.np.attr( 'department_id' )
				}
			});
		});
	})
	
	// Удаление
	.bind( 'remove.jstree', function( e, data ){
		data.rslt.obj.each( function(){
			$.ajax({
				async: false,
				type: 'POST',
				url: '<?= WEBURL .'org/departments/remove' ?>',
				data: { 
					id: $(this).attr('department_id')
				}, 
				success: function( r ){
					if ( !r.status )
						data.inst.refresh();
				}
			});
		});
	})

	// Переименование
	.bind( 'rename.jstree', function ( e, data ){
		console.log( data );
		$.ajax({
			url: '<?= WEBURL .'org/departments/rename' ?>',
			type: 'POST',
			dataType: 'json', 
			data: {
				id: $(data.rslt.obj).attr( 'department_id' ),
				title: data.rslt.new_name
			},
			success: function ( r ){
				if ( !r.status )
					$.jstree.rollback(data.rlbk);
			}
		});
	});
	
</script>


<?php Template::bottom(); ?>