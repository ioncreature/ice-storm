<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace View;
use View\AbstractView;

class Html extends AbstractView {

	public function render(){
		if ( !$this->template_name )
			throw new \LogicException( __CLASS__ ."::render() : Template name must be " );

		return;
	}

	public function render_not_found(){
			header( "HTTP/1.1 404 Not Found" );
			return json_encode( array(
				'status' => false,
				'code' => 404,
				'msg' => 'Not found'
			));
		}


		public function render_access_denied(){
			header( "HTTP/1.1 403 Forbidden" );
			return json_encode( array(
				'status' => false,
				'code' => 403,
				'msg' => 'Forbidden'
			));
		}
}
