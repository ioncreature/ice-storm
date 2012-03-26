<?php
/**
 * @author Marenin Alex
 *         March 2012
 */

return array(
	'weburl' => 'http://spvi.vv/ice-storm/',
	'document_root' => $_SERVER['DOCUMENT_ROOT'] .'/ice-storm/',
	'protected_path' => $_SERVER['DOCUMENT_ROOT'] .'/protected/',
	'is_debug' => true,

	'app_title' => 'Ice Storm',
	'admin_email' => 'admin@spvi.vv',

	'sql' => array(
		'provider' => 'mysql',
		'mysql' => array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => 'dfhbfnbd',
			'name' => 'ice-storm'
		)
	),

	'cache' => array(
		'provider' => 'memcached',
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
);
