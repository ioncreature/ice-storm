<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

// TODO: придумать, блеать, всю эту куету с классом приложения
// TODO: по всеё видимости нет смысла фигачить этот класс пока в системе есть понятие "модуль"
abstract class AbstractApplication extends \Base\AbstractComponent {

	/**
	 * Application components
	 * @var array
	 */
	protected $components = array();

	/**
	 * Application config lives here
	 * @var array
	 */
	protected $config = array();

	/**
	 * Возващает компонент или null
	 * @param string $name
	 * @return StdClass|null
	 */
	public function __get( $name ){
		if ( isset($this->components[$name])
			return $this->components[$name];
		elseif ( isset($this->config['components'][$name]) ){
			$this->create_component( $this->config['components'][$name] );
		}
		else
			return null;

		if ( $this->config['components']  ){
			$components = $this->config['components'];

			foreach ( $components as $c ){
				if (  )
			}
		}
	}


	public function create_component( $config ){
		$class = $config['class'];

	}




	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception\Base
	 */
	public function cfg( $name ){
		if ( isset($this->config[$name]) )
			return $this->config[$name];
		else
			throw new \Exception\Base( "App don't have config entry '$name'" );
	}


	/**
	 * Here app lifecycle
	 * @param array $config
	 */
	public function __construct( array $config = array() ){
		$this->set_config( $config );
		$this->init();
	}


	/**
	 * Run application
	 */
	public function run(){
		$this->handle_request();
		$this->send_response();
	}



}
