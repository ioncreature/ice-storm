<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace View;

use \View\AbstractView as View;
use \Template as Template;

class WebPage extends View {

	public $layout = 'layout/base';
	public $template = 'page/not_found';


	public function set_layout( $layout_name ){
		$this->layout = $layout_name;
	}


	public function set_template( $template_name ){
		$this->template = $template_name;
	}


	public function render(){
		Template::template_to_block( 'body', $this->template, $this->get_view_data() );
		return Template::template_to_block( '__temp_block', $this->layout );
	}


	public function render_not_found(){
		header( "HTTP/1.1 404 Not Found" );
		return Template::template_to_block( 'body', 'page/not_found', $this->get_view_data() );
	}


	public function render_access_denied(){
		header( "HTTP/1.1 403 Forbidden" );
		return Template::template_to_block( 'body', 'page/access_denied', $this->get_view_data() );
	}


	public function render_error(){
		header( "HTTP/1.1 500 Internal Server Error" );
		return Template::template_to_block( 'body', 'page/error', $this->get_view_data() );
	}
}
