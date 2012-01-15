<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
namespace Model;
use \Model\AbstractModel as Model;

abstract class InheritedModel extends Model {

	/**
	 * @var \Model\AbstractModel
	 */
	protected $inherit = null;


}
