<?php
/**
 * @author Marenin Alex
 *         March 2012
 */

return array(
	'weburl' => 'http://ice/',
	'document_root' => $_SERVER['DOCUMENT_ROOT'] .'/',
	'protected_path' => $_SERVER['DOCUMENT_ROOT'] .'/protected/',
	'is_debug' => true,

	'app_title' => 'Ice Storm',
	'admin_email' => 'admin@ice',

	'sql' => array(
		'provider' => 'mysql',
		'mysql' => array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => 'root',
			'name' => 'ice-storm'
		)
	),

	'cache' => array(
		'provider' => 'memcache',
		'memcache' => array(
			'host' => 'localhost',
			'port' => '11211'
		),
		'memcached' => array(
			'host' => 'localhost',
			'port' => '11211'
		),
	),

	'view' => array(
		'default' => array(
			'type' => '\View\WebPage',
			'layout' => 'layout/base',
		),
		'admin' => array(
			'type' => '\View\WebPage',
			'layout' => 'layout/admin',
		),
		'service' => array(
			'type' => '\View\Json',
		),
	),


	// TODO: додумать и реализовать
	'component' => array(
		'auth' => '',
		'session' => '',
		'cache' => '',
		'request' => '',
		'response' => '',
	),
);
