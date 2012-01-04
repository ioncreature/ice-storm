<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace View;

abstract class AbstractView {

	/**
	 * @var array
	 */
	protected $view_data;


	public function __construct( $view_data = null ){
		if ( $view_data )
			$this->add( $view_data );
	}


	/**
	 * Add params to view data
	 * @param string|array $key
	 * @param null $value optional
	 * @return AbstractView
	 */
	public function add( $key, $value = null ){
		if ( is_array($key) )
			$this->view_data = array_merge( $this->view_data, $key );
		else
			$this->view_data[$key] = $value;
		return $this;
	}


	/**
	 * @abstract
	 * @return string
	 */
	abstract public function show_404();


	/**
	 * @abstract
	 * @return string
	 */
	abstract public function render();
}
