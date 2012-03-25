<?php
/**
 * test module
 */

if ( $r->is_put() )
	die( var_export($r, true) );
elseif ( $r->is_delete() )
	die( var_export($r, true) );

// ВЫВОД
Template::add_js( WEBURL .'js/dojo/dojo.js', array('djConfig' => 'parseOnLoad: true, isDebug: true, async: false') );
Template::top();

$r = \Request\Parser::get_instance();
$db = \Db\Factory::get('db');
$acl = \Auth::$acl;

$u = new Model\User( 1 );
$hu = $u->Human;
$u->password = '1';
$u->save();


// phpinfo();
// Memcache(d)
echo 'Cache class';
$cache = new \Cache;
$cache->set( 'mydata', 'my super long string', 10 );
var_dump( $cache->get( 'mydata' ) );


echo '<pre>';
echo var_export( Auth::$acl, true );
echo '</pre>';


// merge test
var_dump(array_merge_recursive_distinct(
	array('a'=>10, 'b'=>array('c'=> 20,'e' => 60), 'd'=>100),
	array('b'=>array('c'=>30, 'd'=>40), 'e'=>50, 'd'=>200) ));


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
	<input type="submit" name="siski" value="4" class="common" />
</form>

<script type="text/javascript">

	var obj = {
		_test: 100,
		test: function(){
			console.log( 'test' );
			return this._test;
		},
		get val (){
			return this.test();
		},
		set val( value ){
			this._test = value;
			console.log( value );
		}
	};

	Object.defineProperty( obj, "some", {
		enumerable: false
	});
	console.log( obj );

	dojo
		.xhrPut({
			url: window.location.href,
			content: { content_test: 100500 }
		})
		.then( function( response ){
			console.warn( 'PUT', response.substr(0, 100) );
		});
	dojo
		.xhrDelete({
			url: window.location.href,
			content: { content_test: 100500 }
		})
		.then( function( response ){
			console.warn( 'DELETE', response.substr(0, 100) );
		});
</script>

<?= Template::bottom() ?>