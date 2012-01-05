<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;
use \Controller\AbstractController;

abstract class AbstractService extends \Controller\AbstractController {

	public $view;

	public function __construct( \Request\Parser $request, $root_path = null ){
		parent::__construct( $request, $root_path );
		$this->view = new \View\Json();
	}

}
