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

	/**
	 * @static
	 * @param mixed $value
	 * @param array $constraints array( 'name', array('constraint' => 'name', 'params' => array(1, '2 param', 3) ) )
	 * @return bool
	 */
	public static function validate( $value, array $constraints ){
		if ( empty($constraints) )
			return true;
		$valid = true;

		foreach ( $constraints as $c ){
			if ( is_array($c) ){
				$method = array_shift( $c );
				$res = call_user_func_array( array(self, $method), $c );
			}
			else
				$res = self::$c( $value );

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


	//
	// Validator methods
	//


	public static function not_empty( $value ){
		return !empty( $value );
	}


	public static function numeric( $value ){
		return is_numeric( $value );
	}


	public static function not_zero( $value ){
		return intval($value) !== 0;
	}


	public static function not_null( $value ){
		return $value !== null;
	}


	public static function regexp( $value, $re ){
		return !!mb_ereg( $re, $value );
	}


	public static function date( $value ){
		return !!strtotime( $value );
	}

}
