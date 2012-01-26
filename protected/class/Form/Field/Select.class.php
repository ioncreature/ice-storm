<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form\Field;

class Select extends \Form\AbstractField {
	protected $tag_name = 'select';

	protected $options = array();

	public function __construct( $name, $value = null, array $constraints = array(), array $attributes = array() ){
		if ( $this->data_source ){
		}
		parent::__construct( $name, $value, $constraints, $attributes );
	}

	public function render_body(){

	}
}
