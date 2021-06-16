<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Firebase {
    protected $api_key = 'AIzaSyBMdtbEyvbNcnarMaFEE5XL78WZL4URbC0';
    function __construct() {

    }

    function send_notification($tokens=[], $title="Order baru !", $message="hai", $activity="com.kumpul.employeepg.activity.analist.Analist") {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $msg = array(
            'body' => $message,
            'title' => $title,
            'sound' => 'default',
            'click_action' => $activity
        );
        
        $fields = array(
            'registration_ids' => $tokens,
            'notification' => $msg
        );

        $headers = array(
            'Authorization:key = '.$this->api_key,
            'Content-Type: application/json'
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            return 'Curl failed: ' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($result);
    }

    function send_notification2($tokens, $title="Order baru !", $message="hai", $activity="com.kumpul.employeepg.activity.analist.Analist") {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $msg = array(
            'body' => $message,
            'title' => $title,
            'sound' => 'default',
            'click_action' => $activity
        );
        
        $fields = array(
            'to'           => $tokens,
            'data'         => $msg
        );

        $headers = array(
            'Authorization:key = '.$this->api_key,
            'Content-Type: application/json'
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
// dd(json_encode($fields));
        if ($result === FALSE) {
            return 'Curl failed: ' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($result);
    }


    function user_token($_where='analis', $data=[]) {
        $db = getCI()->db;
        
        if($_where=='analis') {
            $unitbisnis = unitbisnisRef($data['cabang_id']);

            $params = 'analis_id';
            $db->trans_start();
                $db->select($params);
                $db->where_in('cabang_id', $unitbisnis);
                $db->where('kelurahan_id LIKE \'%"'.$data['kelurahan_id'].'"%\'');
                $db->from('mapping_survey');
                $result = $db->get()->result();

            $db->trans_complete();

        } elseif($_where=='pemesanan_sb_hd') {

            $id         = $data['pemesanan_sb_hd_id'];
            $params     = $data['params'];

            $db->trans_start();
                $db->where('id', $id);
                $db->select($params);
                $result = $db->get('pemesanan_sb_hd')->result();
            $db->trans_complete();

        } elseif($_where=='koordinator') {
            $id         = $data['pemesanan_sb_hd_id'];
            $db->trans_start();
            $db->where('id', $id);
            $db->select('sub_group_marketing_id');
            $get_sb = $db->get('pemesanan_sb_hd')->row();
            $db->trans_complete();
            
            $Asc = unitbisnisAsc($get_sb->sub_group_marketing_id, 8, 6);
            
            $params     = 'id';
            $db->trans_start();
                $db->where('unitbisnis_mutasi_id', $Asc);
                $db->where_in('jabatan_id', [185,180]);
                $db->select($params)->from('karyawan_mutasi_organisasi');
                $result = $db->get()->result();
            $db->trans_complete();
        }

        $kar_id     = [];
        $tokenFB    = [];

        foreach ($result as $value) {
            $kar_id[] = $value->$params;
        }

        if(count($kar_id) > 0) {
            $db->trans_start();
            $db->select('id_firebase_token');
            $db->where_in('id_karyawan', $kar_id);
            $db->from('users');
            $result = $db->get()->result();
            $db->trans_complete();
            
            foreach ($result as $value) {
                $tokenFB[] = $value->id_firebase_token;
            }
        }

        return $tokenFB;
    }



}