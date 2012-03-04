<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace View;
use View\AbstractView as View;
use \Template as Template;

class Html extends View {

	protected $template;


	/**
	 * @param string $template_name
	 */
	public function set_template( $template_name ){
		$this->template = $template_name;
	}


	public function render(){
		if ( !$this->template )
			throw new \LogicException( __CLASS__ ."::render() : undefined template name" );

		return Template::template_to_block( '__temp_block', $this->template, $this->get_view_data() );
	}


	public function render_not_found(){
		return Template::template_to_block( '__temp_block', 'error/not_found', $this->get_view_data() );
	}


	public function render_access_denied(){
		return Template::template_to_block( '__temp_block', 'error/access_denied', $this->get_view_data() );
	}


	public function render_error(){
		return Template::template_to_block( 'body', 'error/error', $this->get_view_data() );
	}
}
