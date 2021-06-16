<?php


class RESTModel extends MY_Model {

    public $_table    = '';
    public $_DATA     = [];
    public $api;

    function __construct() {
        parent::__construct();
        $this->rest                = new RESTAuth;
        $this->api                 = $this->rest->api;

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

        $this->survey_level_6             = [180, 185, 194]; //korlet | koord. analis | koord. wilayah
        
        $this->survey_level_5             = [180];

        $this->survey_analis_6            = [187];

        $this->collect_level_6            = [];
 
        // [180, 185, 182, 183, 194, 191]

        $this->load->helper('serial_number/serial_number');
        $this->sn = new SerialNumber();
    }

    function save_master($data, $id=false) {
        
        if($id) {
            unset($data['id']);
            $this->db->set($data);
            if(is_array($id)) {
                $this->db->where($id);
            } else {
                $this->db->where('id', $id);
            }
            $this->db->update($this->_table);
            $output = $id;
        } else {
            unset($data['id']);
            $data['tanggal_buat'] = _now('Y-m-d H:i:s');
            $this->db->set($data);
            $this->db->insert($this->_table);
            $output = $this->db->insert_id();
        }

        return $output;
    }

    function select_array($data, $protect=TRUE) {

        foreach ($data as $value) {
            $this->db->select($value, $protect);
        }

    }

    function _delete_where($where=[]) {

        $this->db->where($where);

        $this->db->delete($this->_table);
        return $this->db->affected_rows();
    }

    function _delete($id) {
        
        if(is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where('id', $id);
        }

        $this->db->delete($this->_table);
        return $this->db->affected_rows();
    }

    function _update($_table, $set, $where) {
        $this->db->set($set);
        $this->db->where($where);

        return $this->db->update($_table);
    }

    function rollback($table, $id) {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete($table);
        $this->db->trans_complete();
    }

    function timestamps() {
        $this->db->set  ('tanggal_update', _now('Y-m-d H:i:s.000'));
        $this->db->set  ('status', 1);
        $this->db->set  ('is_trash', 0);
    }

    function list_detail($table_dt, $column_qty='qty') {
        $db = $this->db;
        $db->select('pn.id as persediaan_nama_id');
        $db->select('pn.tgl_berlaku');
        $db->select('pn.unitbisnis_id');
        $db->select('pn.leaflet');
        $db->select('pn.status_jual');
        $db->select('pn.status_sku_supplier');
        $db->select('pn.status_serial_number');
        $db->select('pn.status_persediaan');
        $db->select('pn.sku_supplier');
        $db->select('pn.sku_internal');
        $db->select('pn.nama_barang_paket');
        $db->select('pn.barang_nama_id');
        $db->select('pn.lokasi_id');
        $db->select('pn.kondisi_id');
        $db->select('pn.barang_satuan_id');
        $db->select('(SELECT nama_barang FROM barang_nama WHERE id =  pn.barang_nama_id) as nama_barang');
        $db->select('(SELECT nama_kondisi FROM barang_kondisi WHERE id =  pn.kondisi_id) as kondisi');
        $db->select('(SELECT nama_unitbisnis FROM unitbisnis_nama WHERE id =  pn.lokasi_id) as lokasi');
        $db->select('(SELECT nama_satuan FROM barang_satuan WHERE id =  pn.barang_satuan_id) as satuan');

        $q = '(SELECT id FROM barang_nama WHERE id = pn.barang_nama_id)';
        $db->select('(SELECT gambar_1 FROM barang_gambar WHERE barang_nama_id = '.$q.') as image');

        $db->select  ('pn.harga_hpp');
        $db->select  ('pn.harga_hjt');
        $db->select  ('pn.harga_DP');
        $db->select  ('pn.view_DP');
        
        $db->select  ('(SELECT COUNT(id) FROM '.$table_dt.' t_dt WHERE t_dt.persediaan_nama_id = pn.id ) as count_related');
        $db->select  ('(SELECT SUM('.$column_qty.') FROM '.$table_dt.' t_dt WHERE t_dt.persediaan_nama_id = pn.id ) as ttl_qty ');
        $db->from    ('persediaan_nama pn');
        
        if($this->unitbisnis_level == 5) {
            $db->where   ('pn.unitbisnis_id', $this->api->id_relasi_cabang);
        } else {
            $db->where   ('pn.unitbisnis_id', $this->api->cabang_id);
        }

        $db->where   ('(SELECT COUNT(id) FROM '.$table_dt.' t_dt WHERE t_dt.persediaan_nama_id = pn.id ) > 0');
        // $db->order_by('(SELECT COUNT(id) FROM '.$table_dt.' t_dt WHERE t_dt.persediaan_nama_id = pn.id )', 'DESC');
    }
}