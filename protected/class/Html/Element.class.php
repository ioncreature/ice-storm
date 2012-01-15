<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Html;

class Element {

	protected $tag_name;

	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * Array of \Html\Element objects
	 */
	protected $children = array();


	/**
	 * Constructor
	 * @param $tag_name
	 * @param array $attributes
	 * @param array $children
	 */
	public function __construct( $tag_name, $attributes = array(), $children = array() ){
		$this->set_tag( $tag_name );
		$this->set_attributes( $attributes );
		foreach ( $children as $id => $child )
			$this->add_child( $child, $id );
	}


	public function set_tag( $tag_name ){
		// TODO: normalize tag name
		$this->tag_name = $tag_name;
	}

	public function get_tag(){
		return $this->tag_name;
	}

	public function set_attributes( array $attr ){
		foreach ( $attr as $name => $value )
			$this->attributes[$name] = $value;
	}

	public function set_attribute( $name, $value ){
		$this->attributes[$name] = $value;
	}

	public function remove_attribute( $name ){
		unset( $this->attributes[$name] );
	}

	public function get_attribute( $name ){
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}

	public function get_children(){
		return $this->children;
	}

	public function add_child( self $child, $id = null ){
		if ( is_null($id) )
			$this->children[] = $child;
		else
			$this->children[$id] = $child;
	}



	public function render_start(){
		return '<'. $this->tag_name .' '. $this->render_attributes() .'>';
	}


	public function render_body(){
		$out = '';
		foreach ( $this->children as $id => $child )
			$out .= $child->render();
		return $out;
	}


	public function render_end(){
		return '</'. $this->tag_name .'>';
	}


	/**
	 * Renders html element into string
	 * @return string
	 */
	public function render(){
		return $this->render_start() . $this->render_body() . $this->render_end();
	}


	protected function render_attributes(){
		$a = '';
		foreach ( $this->attributes as $name => $value )
			$a .= $name .'="'. \Helper\Html::encode_attr( $value ) .'" ';
		return $a;
	}
}