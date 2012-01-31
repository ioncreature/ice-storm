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
	protected $unchecked_value = 'no';

	public function set_value( $value ){
		if ( $this->checked_value === $value )
			$this->set_attribute( 'checked', 'checked' );
		else
			$this->remove_attribute( 'checked' );
		parent::set_value( $value );
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
	}

	public function set_unchecked(){
		$this->checked = false;
	}

	public function checked(){
		return $this->checked;
	}

	public function get_value(){
		return $this->checked_value;
	}
}
