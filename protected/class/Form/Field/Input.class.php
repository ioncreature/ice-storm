<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form\Field;
use \Form\AbstractField as Field;

class Input extends Field {
	protected $tag_name = 'input';
	protected $may_have_children = false;

	public function init(){
		$this->set_attribute( 'value', $this->get_value() );
	}


	public function set_value( $value ){
		parent::set_value( $value );
		$this->set_attribute( 'value', $value );
	}


	public function set_name( $name ){
		parent::set_name( $name );
		$this->set_attribute( 'name', $name );
	}
}
