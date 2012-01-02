<?php
/**
 * test module
 */

if ( $r->is_put() )
	die( var_export($r, true) );
elseif ( $r->is_delete() )
	die( var_export($r, true) );

// ВЫВОД
Template::add_js( WEBURL .'js/dojo/dojo.js', array('djConfig' => 'parseOnLoad: true, isDebug: true') );
Template::top();

$r = \Request\Parser::get_instance();
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

<script type="text/javascript">
	dojo
		.xhrPut({
			url: window.location.href,
			content: { content_test: 100500 }
		})
		.then( function( response ){
			console.error( 'PUT' );
			console.log( response );
		});
	dojo
		.xhrDelete({
			url: window.location.href,
			content: { content_test: 100500 }
		})
		.then( function( response ){
			console.error( 'DELETE' );
			console.log( response );
		});

</script>

<?= Template::bottom() ?>