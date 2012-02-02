<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form\Field;

class Checkbox extends \Form\AbstractField {
	protected $tag_name = 'input';
	protected $may_have_children = false;

	protected $checked = false;
	protected $checked_value = 'on';
	protected $unchecked_value = null;

	protected $attributes = array(
		'type' => 'checkbox'
	);


	public function init(){
		parent::set_value( $this->checked_value );
	}


	public function set_value( $value ){
		if ( $value === $this->checked_value )
			$this->set_checked();
		else
			$this->set_unchecked();
		$this->set_attribute( 'value', $this->checked_value );
	}


	public function set_name( $name ){
		parent::set_name( $name );
		$this->set_attribute( 'name', $name );
	}


	public function set_checked_value( $value ){
		$this->checked_value = $value;
		$this->set_value( $this->value );
	}


	public function set_unchecked_value( $value ){
		$this->unchecked_value = $value;
		$this->set_value( $this->value );
	}


	public function set_checked(){
		$this->checked = true;
		$this->set_attribute( 'checked', 'checked' );
	}


	public function set_unchecked(){
		$this->checked = false;
		$this->remove_attribute( 'checked' );
	}


	public function checked(){
		return $this->checked;
	}


	public function get_value(){
		if ( $this->checked() )
			return $this->checked_value;
		else
			return $this->unchecked_value;
	}
}
