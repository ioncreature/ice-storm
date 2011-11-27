<?php
/**
 * test module
 */
//
// ВЫВОД
Template::top();

$r = RequestParser::get_instance();
$db = Fabric::get('db');
$acl = Auth::$acl;

$u = new Model\User( 1 );
$hu = $u->Human;
$u->password = '1';
$u->save();

echo '<pre>';
echo var_export( Auth::$acl, true );
echo '</pre>';

echo '<pre>';
echo var_export( $hu, true );
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
<h3>Hello! This is a test page.</h3>

<form action="<?= WEBURL .'test/siski/10' ?>" method="POST">
	<input type="submit" name="siski" value="4" />
</form>

<?= Template::bottom() ?>