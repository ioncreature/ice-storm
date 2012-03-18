<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace View;

use \View\AbstractView as View;
use \Template as Template;

class WebPage extends View {

	public $layout = DEFAULT_LAYOUT;
	public $template = 'page/not_found';


	/**
	 * @param string $layout_name
	 */
	public function set_layout( $layout_name ){
		$this->layout = $layout_name;
	}


	/**
	 * @param string $template_name
	 */
	public function set_template( $template_name ){
		$this->template = $template_name;
	}


	public function render(){
		Template::template_to_block( 'body', $this->template, $this->get_view_data() );
		return Template::tpl( $this->layout );
	}


	public function render_not_found(){
		Template::template_to_block( 'body', 'error/not_found', $this->get_view_data() );
		return Template::tpl( $this->layout );
	}


	public function render_access_denied(){
		Template::template_to_block( 'body', 'error/access_denied', $this->get_view_data() );
		return Template::tpl( $this->layout );
	}


	public function render_error(){
		Template::template_to_block( 'body', 'error/error', $this->get_view_data() );
		return Template::tpl( $this->layout );
	}


	public function render_bad_request(){
		Template::template_to_block( 'body', 'error/bad_request', $this->get_view_data() );
		return Template::tpl( $this->layout );
	}
}
