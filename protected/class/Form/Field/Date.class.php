<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form\Field;

class Date extends \Form\AbstractField {

	protected $constraints = array( 'date_or_empty' );

}
