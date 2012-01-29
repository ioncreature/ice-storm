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
	protected $checked_value = 'yes';


	public function set_value( $value ){
		if ( $value and $value === $this->checked_value )
			$this->value = 'checked="checked"';
		else
			$this->value = false;
	}


	public function get_value(){
		return $this->checked ? parent::get_value() : false;
	}


	public function set_checked_value( $checked_value ){
		$this->checked_value = $checked_value;
	}

	public function set_checked(){
		$this>set_attribute( 'checked', 'checked' );
		$this->checked = true;
	}


	public function set_unchecked(){
		$this->remove_attribute( 'checked' );
		$this->checked = false;
	}
}
