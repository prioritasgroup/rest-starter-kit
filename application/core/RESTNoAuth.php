<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class RESTNoAuth extends REST_NoAuth {

    public $cache;

    function __construct() {
        parent::__construct();
        $this->cache               = (new Cache());
    }
    
}