<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\edu;

class Teachers extends \Controller\AbstractController {

	public $routes = array(
		'GET' => array(
			'::int' => 'show_teacher'
		)
	);
}
