<?php

use Google\Cloud\Storage\StorageClient;

class Gstorage {

    private $storage;
    private $bucket;
    
    private $bucket_path = 'predator_uploads/';
    private $domain      = 'https://files.prioritas-group.com/uploads/';

    function __construct()
    {
        $this->storage = new StorageClient([
            'keyFilePath' => APPPATH.'/../prioritasgroup-firebase-adminsdk-dci1s-f4250e51c8.json'
        ]);

        $this->bucket = $this->storage->bucket('prioritasgroup.appspot.com');
    }

    function upload($content, $filename) {
        return $this->bucket->upload(
            $content,
            [
                'name' => $this->bucket_path.$filename,
                'predefinedAcl' => 'publicRead'
            ]
        );
    }

    function url($filename) {
        $file = str_replace('gs://', '', $filename);
        $file = str_replace('_original', '', $file);
        return  $this->domain.$file;
    }

    function get_size($filename, $size='') {
        $file = str_replace('gs://', '', $filename);
        $file = '/thumb/'.str_replace('_original', '_'.$size, $filename);
        $url = $this->url($file);
        return $url;
    }

}