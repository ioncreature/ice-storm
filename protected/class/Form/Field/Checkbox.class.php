<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form\Field;

class Checkbox extends \Form\AbstractField {
	protected $tag_name = 'input';
	protected $may_have_children = false;


	public function set_value( $value ){
		if ( $value )
			$this->set_attribute( 'checked', 'checked' );
		else
			$this->remove_attribute( 'checked' );
		parent::set_value( $value ? $value : false );
	}
}
