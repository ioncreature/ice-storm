<?php
/*
	test module
*/
$r = RequestParser::get_instance();
$db = Fabric::get('db');
$a = Auth::get_instance();
$acl = Auth::$acl;
$u = $a->get_user();
// $u = new UserModel(1);
// $u->password  = 'siski';
// $u->save();
//
// ВЫВОД
top();
echo '<pre>';
echo var_export( Auth::$acl, true);
// echo var_export( $u, true);
echo '</pre>';

// Определяем текущую страницу
$current_page = $r->is_int(1) ? $r->to_int(1) : 1;
paginator(array(
	'page_current' => $current_page,
	'items_total' => 250,
	'url_pattern' => WEBURL .'test/::page::',
));
?>
<p>Hello! This is a test page.</p>
<?= bottom() ?>