<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace View;

class WebPage extends \View\AbstractView {

	public $layout = 'layout/base';
	public $template = 'page/not_found';


	public function set_layout( $layout_name ){
		$this->layout = $layout_name;
	}


	public function set_template( $template_name ){
		$this->template = $template_name;
	}


	public function render( $output ){
		\Template::template_to_block( 'body', $this->template, $this->view_data );
		if ( $output )
			return \Template::show( $this->layout );
		else {
			\Template::template_to_block( 'trololo', $this->layout );
			return \Template::block( 'trololo' );
		}
	}


	public function render_not_found(){
		header( "HTTP/1.1 404 Not Found" );
		\Template::template_to_block( 'page/not_found', 'body' );
		return \Template::block( 'body' );
	}


	public function render_access_denied(){
		header( "HTTP/1.1 403 Forbidden" );
		\Template::template_to_block( 'page/access_denied', 'body' );
		return \Template::block( 'body' );
	}
}
