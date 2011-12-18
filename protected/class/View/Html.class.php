<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace View;
use View\AbstractView;

class Html extends AbstractView {

	protected $template_name;

	public function __construct( $view_data = null ){

	}

	public function render(){
		if ( !$this->template_name )
			throw new \LogicException( __CLASS__ ."::render() : Template name must be " );


		return;
	}
}
