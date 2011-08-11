<?
set_time_limit(200);
//загрузка КОНФИГА
if( isset($_SERVER['ENV']) and ($_SERVER['ENV'] == "MUCHACHO" or $_SERVER['ENV'] == "DEV") )
	// devel
	include "/../config.inc.php";
else
	// production
	include "/var/www/protected/config.inc.php";

	
//автозагрузка классов по требованию
function __autoload( $name ){
	$url = DOCUMENT_ROOT .'protected/class/'. $name .'.class.php';
	// echo '<br/>' . $url;
	$inc = include $url;
	if ( !$inc ) die( "Class '$name' not found!");
}


// Засекаем время
$time_start = microtime (true);

// MySQL connect
$db = Fabric::get( 'db' );

// Mongo connect
$mongo = Fabric::get( 'mongo' );
$collection = $mongo->global_log;

$col = $collection->find();

print $fetched_docs = $col->count();

$hashs = array();
$deleteted = array();

foreach($col AS $val)
{
	if(isset($hashs[$val['hash']])) {
		$deleteted[] = $val['_id'];
	}
	
	print " ";
	ob_flush();
	
	$hashs[$val['hash']] = 1;
}

print "<br>".count($hashs);

print "<br>".count($deleteted);


foreach($deleteted AS $delete) {
	$collection->remove(array('_id' => new MongoId( $delete )));
}
