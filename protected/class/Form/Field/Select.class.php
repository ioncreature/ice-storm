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
	 * @param string $value_key
	 * @param string $title_key
	 */
	public function set_options( array $options, $value_key = 'value', $title_key = 'title' ){
		$this->options = array();
		foreach ( $options as $o )
			$this->options[] = array(
				'title' => $o[$title_key],
				'value' => $o[$value_key]
			);
	}

	public function set_name( $name ){
		parent::set_name( $name );
		$this->set_attribute( 'name', $name );
	}


	public function render_body(){
		$out = '';
		foreach ( $this->options as $o )
			$out .=
				"<option value='{$o['value']}' ".
					($this->get_value() === $o['value'] ? 'selected="selected"' : '') .
				 ">{$o['title']}</option>";
		return $out;
	}
}
