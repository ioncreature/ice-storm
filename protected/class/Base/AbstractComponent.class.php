<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

namespace Base;

abstract class AbstractComponent {

	protected $config;


	public function get_config(){
		return $this->config;
	}


	public function set_config( $config ){
		$this->config = $config;
	}


	public function create_component(){

	}

}
