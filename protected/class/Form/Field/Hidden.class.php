<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form\Field;

class Hidden extends Text {

	public function init(){
		$this->set_attribute( 'value', $this->get_value() );
		$this->set_attribute( 'type', 'hiddens' );
	}

}
