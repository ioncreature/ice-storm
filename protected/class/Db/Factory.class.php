<?php
/*
	Фабрика всякого хлама
	Marenin Alex
	2011
*/

namespace Db;

class Factory {
	
	protected static $obj = array();


	protected function exists( $key ){
		return isset(static::$obj[$key]);
	}


	protected static function saveObj( $key, $obj ){
		return static::$obj[$key] = $obj;
	}


	protected function getObj( $key ){
		if ( static::exists($key) )
			return static::$obj[$key];
		else
			throw new \Exception( "Class type \"$key\" not found" );
	}


	/**
	 * @static
	 * @param $key
	 * @return Cache|MySql|\MongoDB
	 * @throws \Exception
	 */
	public static function get( $key ){
		switch ( mb_strtolower($key) ){
			case 'db':
				return static::getSqlDb();
			case 'cache':
				return static::getCache();
			case 'mongo':
				return static::getMongo();
			default:
				throw new \Exception( "Class type \"$key\" not found" );
		}
	}


	/**
	 * @static
	 * @return \Db\MySql
	 */
	public static function getSqlDb(){
		if ( !static::exists('db') )
			static::saveObj( 'db', new \Db\MySql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) );
		return static::getObj( 'db' );
	}


	/**
	 * @static
	 * @return \Db\Cache
	 */
	public static function getCache(){
		if ( !static::exists('cache') )
			static::saveObj( 'cache', new Cache(MMC_HOST, MMC_PORT) );
		return static::getObj( 'cache' );
	}


	/**
	 * @static
	 * @return \MongoDB
	 */
	public function getMongo(){
		if ( !static::exists('mongo') ){
			$user = MONGO_USER;
			$auth = empty($user) ? false : array(
				"username" => MONGO_USER,
				"password" => MONGO_PASSWORD
			);

			$mongo_conn = new \Mongo( 'mongodb://'.MONGO_HOST.':'.MONGO_PORT, $auth);
			static::saveObj( 'mongo', $mongo_conn->selectDB(MONGO_NAME) );
		}
		return static::getObj( 'mongo' );
	}

}

?>