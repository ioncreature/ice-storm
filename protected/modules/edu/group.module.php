<?php
/*
	������ ������ ������ �� ������� ������
	Marenin Alex
	October
	2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');

$gid = $r->to_int(2);
if ( !$gid )
	redirect( WEBURL );

$group = $db->fetch_query("
	SELECT * 
	FROM edu_groups
	WHERE id = '$gid'
	LIMIT 1
");
if ( !$group )
	redirect( WEBURL );


//
// �����
//
Template::top();
?>
<h2>�����a <?= htmlspecialchars($group['name']) ?></h2>




<?= Template::bottom() ?>