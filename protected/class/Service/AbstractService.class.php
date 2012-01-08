<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;
use \Controller\AbstractController;

abstract class AbstractService extends \Controller\AbstractController {
	protected $default_view = '\View\Json';
}
