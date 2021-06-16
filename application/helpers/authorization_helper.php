<?php

class AUTHORIZATION
{
    
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (time() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

    public static function check_api_key($api='')
    {
        // $api = (new Cache())->remember('api:'.$api, 60*60*24*7, function() use($api) {
            $CI =& get_instance();
            $CI->load->database();
            if($CI->db->select('key')->where('key', $api)->get('api_key')->num_rows() > 0)
            {
                return true;
            } else {
                return false;
            }
        // });

        // return $api;
    }

}