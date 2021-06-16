<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $exp = explode('.', $_SERVER['HTTP_HOST']);


// if($exp[0] == 'beta-api') {
// 	$active_group = 'default';
// } else if($exp[0] == 'dev-api') {
// 	$active_group = 'demo';
// } else {
	// }
$active_group = 'default';

$query_builder = TRUE;

$db['default'] = array(
	'dsn'		   => '',
	'hostname'     => '168.63.242.2',
	'username'     => 'user',
	'password'     => '2019predator030317',
	'database'     => 'predator_4',
	'dbdriver'     => 'sqlsrv',
	'dbprefix'     => '',
	'pconnect'     => FALSE,
	'db_debug'     => (ENVIRONMENT !== 'production'),
	'cache_on'     => FALSE,
	'cachedir'     => APPPATH.'cache',
	'char_set'     => 'utf8',
	'dbcollat'     => 'utf8_general_ci',
	'swap_pre'     => '',
	'encrypt' 	   => FALSE,
	'compress'     => FALSE,
	'stricton'     => FALSE,
	'failover'     => array(),
	'save_queries' => TRUE
);

$db['beta'] = array(
	'dsn'		   => '',
	'hostname'     => '168.63.242.2',
	'username'     => 'user',
	'password'     => '2019predator030317',
	'database'     => 'predator_4_beta',
	'dbdriver'     => 'sqlsrv',
	'dbprefix'     => '',
	'pconnect'     => FALSE,
	'db_debug'     => (ENVIRONMENT !== 'production'),
	'cache_on'     => FALSE,
	'cachedir'     => APPPATH.'cache',
	'char_set'     => 'utf8',
	'dbcollat'     => 'utf8_general_ci',
	'swap_pre'     => '',
	'encrypt' 	   => FALSE,
	'compress'     => FALSE,
	'stricton'     => FALSE,
	'failover'     => array(),
	'save_queries' => TRUE
);