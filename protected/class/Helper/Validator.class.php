<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Helper;

/**
 * Класс для валидации.
 * По всей видимости скоро станет абстрактным
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


	/**
	 * @static
	 * @param $c1
	 * @param $c2
	 * @return array
	 */
	public static function merge_constraints( $c1, $c2 ){
		$res = $c1;
		foreach ( $c2 as $v )
			if ( !in_array($v, $c1, true) )
				$res[] = $v;
		return $res;
	}


	public static function is_not_empty( $value ){
		return !empty( $value );
	}


	public static function is_numeric( $value ){
		return is_numeric( $value );
	}


	public static function not_zero( $value ){
		return (int) $value !== 0;
	}

}
