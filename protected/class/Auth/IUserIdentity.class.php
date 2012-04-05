<?php
/**
 * @author Marenin Alex
 *         April 2012
 */

namespace Auth;

interface IUserIdentity {

	public function get_id();

	public function authenticate();

	public function is_authenticated();
}
