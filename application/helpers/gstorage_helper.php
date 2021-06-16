<?php

function gs_url($filename) {
    $ci = getCI();
    $domain = $ci->config->item('gs_domain');

    $file = str_replace('gs://', '', $filename);
    $file = str_replace('_original', '', $file);
    return  $domain.$file;
}

function gs_get_size($filename, $size='') {
    $file = str_replace('gs://', '', $filename);
    $file = 'thumb/'.str_replace('_original', '_'.$size, $filename);
    $url = gs_url($file);
    return $url;
}

function gs_check($filename, $default, $size=false) {
    $return = $default;
    if(preg_match("/gs:\/\/(.*)/", $filename)) {
        if($size == false) {
            $return = gs_url($filename);
        } else {
            $return = gs_get_size($filename, $size);
        }
    }

    return $return;
}