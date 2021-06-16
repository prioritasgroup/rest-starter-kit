<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Illuminate\Database\Capsule\Manager as DB;

class DBBuilder extends MX_Controller {
    

    function __construct()
    {
        parent::__construct();
        include(APPPATH.'/config/database.php');

        $active = $db[$active_group];
        $capsule = new DB();
        $capsule->addConnection([
            'driver'    => 'sqlsrv',
            'host'      => $active['hostname'],
            'database'  => $active['database'],
            'username'  => $active['username'],
            'password'  => $active['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsule->setAsGlobal();
        $this->load->helper('db');
        
        $this->xdb = XDB();

    }
}