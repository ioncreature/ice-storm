<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form\Field;

class Select extends \Form\AbstractField {
	protected $tag_name = 'select';

	protected $options = array();


	/**
	 * @param array $options
	 */
	public function set_options( array $options ){
		$this->options = $options;
	}


	public function render_body(){
		$out = '';
		foreach ( $this->options as $o )
			$out .= "<option value='{$o['value']}'>{$o['name']}</option>";
	}
}
