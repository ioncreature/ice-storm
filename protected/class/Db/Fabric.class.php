<?php
/*
	Фабрика всякого хлама
	Marenin Alex
	2011
*/

namespace Db;

class Fabric {
	
	protected static $obj = array();
		
	public static function get( $key, $new = false ){
		if ( isset( static::$obj[$key] ) and !$new )
			return static::$obj[$key];
			
		else switch ( mb_strtolower($key) ){
			case 'db':
				$obj = new \Db\MySql( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
				break;
			
			case 'mongo':
				// Mongo connect
				
				$user = MONGO_USER;
				$auth = empty($user) ? false : array( 
					"username" => MONGO_USER,
					"password" => MONGO_PASSWORD
				);
				
				$mongo_conn = new \Mongo( 'mongodb://'.MONGO_HOST.':'.MONGO_PORT, $auth);
				$obj = $mongo_conn->selectDB( MONGO_NAME );
				break;
			
			default:
				throw new Exception( "Class type \"$key\" not found" );
		}
		
		if ( !$new )
			static::$obj[$key] = $obj;
		
		return $obj;
	}

}

?>