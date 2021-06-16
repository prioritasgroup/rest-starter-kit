<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'v5';
$query_builder = TRUE;

$db['v5'] = array(
	'dsn'	=> '',
	'hostname' => '104.43.14.90,6969',
	'username' => 'sa',
	'password' => '2021Pr3d4t0r_5',
	'database' => 'predator_5',
	'dbdriver' => 'sqlsrv',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
