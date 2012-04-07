<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

/**
 * Хелпер для приложения
 * В дальнейшем возьмёт на себя функции автолоадера,
 * хранителя конфигов и registry компонентов (\Base\AbstractComponent)
 */
class Ice {

	/**
	 * @var \AbstractApplication
	 */
	protected static $app;


	/**
	 * Application config lives here
	 * @var array
	 */
	protected static $config = array();


	/**
	 * @static
	 * @param $name
	 * @return \StdClass
	 */
	public static function get_component( $name ){

	}


	public static function create_application( $appName ){
		static::$app = new $appName( static::$config );
	}


	/**
	 * @static
	 * @return AbstractApplication
	 */
	public static function app(){
		return static::$app;
	}


	/**
	 * @static
	 * @param string $name
	 * @return array
	 */
	public static function config( $name ){
		$res = self::$config;
		foreach ( explode('.', $name) as $n )
			$res = $res[$n];
		return $res;
	}


	/**
	 * @static
	 * @param string $name
	 */
	public static function save_config( $cfg ){
		static::$config = array_merge_recursive_distinct( static::$config, $cfg );
	}


	public static function load_config( $cfg_name ){
		$cfg = include_once PROTECTED_PATH .'config/'. $cfg_name .'conf.php';
		self::save_config( $cfg );
		return static::$config;
	}


	/**
	 * @var array
	 */
	protected static $components = array(
		'auth' => '',
		'acl' => '',
		'user' => '',
		'session' => '',
		'cache' => '',
		'request' => '',
		'response' => '',
		'router' => '',
	);


	public static function registry( $component_name ){

	}


	public static function set_component(){

	}

}
