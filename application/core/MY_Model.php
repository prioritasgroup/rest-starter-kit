<?php
defined("BASEPATH") OR exit();

class MY_Model extends CI_Model {

    public $table = '';

    function __construct() {
        parent::__construct();
        
    }

    function save_all($params=[]) {
        
        if(array_key_exists('id', $params)) {

            $params['tanggal_ubah'] = _now();
            $params['user_ubah']    = $this->api->id;
            $this->db->update($this->table, $params);
            
        } else {
            
            $params['tanggal_buat'] = _now();
            $params['user_buat']    = $this->api->id;
            $this->db->insert($this->table, $params);

        }

    }
}