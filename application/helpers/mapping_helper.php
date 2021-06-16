

<?php


class Mapping {

    static function outlet($id_cabang) {

        $db =  getCI()->db;

        $db->trans_start();
            $db->select ('mapping_outlet.regional, nama.nama_unitbisnis')->from('mapping_outlet');
            $db->join   ('unitbisnis_nama nama', 'nama.id = mapping_outlet.outlet_id', 'left');
            $db->where  ('mapping_outlet.cabang_id', $id_cabang);
            $res = $db->get()->result();
        $db->trans_complete();

        $json = [];
        foreach($res as $value) {
            $reg                = json_decode($value->regional);
            $data['unitbisnis'] = [];
            
            if(isset($value->nama_unitbisnis)) {
                $data['unitbisnis'] = $value->nama_unitbisnis;
            }

            $id                 = [];

            foreach($reg as $GetKel) {
                $id             = array_merge( $GetKel->kelurahan, $id );
            }
            $data['mapping']         = $id;

            $json[]             = $data;
        } 

        return $json;
    }

    static function survey() {

    }
}