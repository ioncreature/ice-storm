<?php
/*
	test module
*/


//
// ВЫВОД
top();
echo '<pre>';
echo var_export( Auth::$acl, true);
echo '</pre>';

// Определяем текущую страницу
$r = RequestParser::get_instance();
$current_page = $r->is_int(1) ? $r->to_int(1) : 1;
paginator(array(
	'page_current' => $current_page,
	'items_total' => 250,
	'url_pattern' => WEBURL .'test/::page::',
));
?>
<p>Hello! This is a test page.</p>
<?= bottom() ?>