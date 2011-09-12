<?php
/*
	test module
*/
$r = RequestParser::get_instance();
$db = Fabric::get('db');
$acl = Auth::$acl;

//
// ВЫВОД
Template::top();

echo '<pre>';
echo var_export( Auth::$acl, true);
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

<form action="<?= WEBURL .'test/siski/10' ?>" method="POST">
<input type="submit" name="siski" value="4" />
</form>

<?= Template::bottom() ?>