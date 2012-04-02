<?php
/**
 * @author Marenin Alex
 *         March 2012
 */
require "protected/bootstrap.php";

// Инициализация сессии
$sess = new Session();
$sess->setSid( $sid );
//$sess->getSid();
$sess->start();
$sess->isStarted();
$sess->end(); // $sess->_destruct();
$sess->__get( $key );
$sess->__set( $key, $val );




// проверка авторизации


$auth = new \Auth\Base();
