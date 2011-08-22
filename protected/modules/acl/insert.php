<?php
$r = RequestParser::get_instance();
$db = Fabric::get('db');
$col = $_POST['col'];
$row = $_POST['row'];
$stat = $_POST['stat'];
$db->query("
				INSERT INTO auth_group_permissions 
				SET group_id = '{$row}', permission_id = '{$col}' 
				ON DUPLICATE KEY UPDATE type = '{$stat}'
			");
?>