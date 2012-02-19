<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

/**
 * Хелпер для приложения
 * В дальнейшем возьмёт на себя функции автолоадера,
 * хранителя конфигов и фабрики для компонентов (\Base\AbstractComponent)
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
	protected $config = array();


	/**
	 * @static
	 * @param $name
	 * @return \StdClass
	 */
	public static function get_component( $name ){

	}


	public static function create_application( $appName, $config ){
		static::$app = new $appName();
		return static::cfg( 'weburl' );
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
	 */
	public static function cfg( $name ){
		return self::$config[$name];
	}


	/**
	 * @var array
	 */
	protected static $components = array(
		'auth' => '',
		'session' => '',
		'cache' => '',
		'request' => '',
		'response' => '',
	);
}
