<?php 
if (!defined("BASEPATH")) exit("No direct script access allowed");

class Sec {

    static $encryption_key = '121212121212121' ; //16 digits
    static $iv = '2456378494765431' ; //16 digits
    static $encryption_mechanism = 'aes-256-cbc';
    
    static function encrypt($string) {
        $output = false;
        /*
        * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
        */        
        $secret_key     = self::$encryption_key;
        $secret_iv      = self::$iv;
        $encrypt_method = self::$encryption_mechanism;
        // hash
        $key    = hash("sha256", $secret_key);
        // iv � encrypt method AES-256-CBC expects 16 bytes � else you will get a warning
        $iv     = substr(hash("sha256", $secret_iv), 0, 16);
        //do the encryption given text/string/number
        $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);
        return $output;
    }
    
    static function decrypt($string) {
        $output = false;
        /*
        * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
        */
        $secret_key     = self::$encryption_key;
        $secret_iv      = self::$iv;
        $encrypt_method = self::$encryption_mechanism;
        // hash
        $key    = hash("sha256", $secret_key);
        // iv � encrypt method AES-256-CBC expects 16 bytes � else you will get a warning
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
        //do the decryption given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
}