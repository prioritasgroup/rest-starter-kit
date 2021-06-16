<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class RESTAuth extends REST_Auth {

    private $data = [];
    // public $redis;
    public $cache;

    function __construct() {
        parent::__construct();

        $this->api = $this->api_data;

        $this->id_user             = $this->api->id;
        $this->id_karyawan         = $this->api->id_karyawan;
        $this->id_personal         = $this->api->id_personal;
        $this->id_relasi_cabang    = $this->api->id_relasi_cabang;
        $this->unitbisnis_level    = $this->api->unitbisnis_level_id;
        $this->jabatan_nama        = $this->api->jabatan_nama;
        $this->jabatan_id          = $this->api->jabatan_id;
        $this->cabang_id           = $this->api->cabang_id;
        $this->outlet_id           = $this->api->outlet_id;
        $this->group_marketing_id  = $this->api->group_marketing_id;

        $this->survey_level_6      = [180, 185];
        $this->cache               = (new Cache());

    }


    function _response($data=null, $messageSuccess="Success!", $messageError="Error!") {
        if($data) {

            $output['status']       = true;
            $output['message']      = $messageSuccess;

            $output['data']         = $data;

        } else {

            $output['status']       = false;
            $output['message']      = $messageError;

        }

        return $this->response($output);
    }

    // function set_json($key, $value) {
    //     $this->data[$key] = $value; 
    // }

    // function json_response($data) {
    //     $this->data['data'] = $data;
    //     $res = $this->data; 
    //     $this->response($res);
    // }

    
}