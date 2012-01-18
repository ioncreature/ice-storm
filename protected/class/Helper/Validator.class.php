<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Helper;

/**
 * Класс для валидации.
 * По всеё видимости скоро станет абстрактным
 * и от него будут наследоваться другие валидаторы, а пока так
 * TODO: добавить добавление ошибок
 */
class Validator {

	public static function validate( $value, array $constraints = array() ){
		if ( empty($constraints) )
			return true;
		$valid = true;

		foreach ( $constraints as $k => $v ){

			if ( is_array($v) )
				$res = call_user_func_array( array(self, $k), array($v) );
			else {
				$method = $v;
				$res = self::$method( $value );
			}

			if ( !$res ){
				$valid = false;
				break;
			}
		}

		return $valid;
	}


	public static function is_not_empty( $value ){
		return !empty( $value );
	}
}
