<?php
defined("BASEPATH") OR exit();

class NoAuthController extends MX_Controller
{
	var $upload_resize  = array(
        array('name'	=> 'thumb','width'	=> 200, 'height'	=> 200, 'quality'	=> '50%'),
        array('name'	=> 'small','width'	=> 350, 'height'	=> 350, 'quality'	=> '60%'),
        array('name'	=> 'large','width'	=> 500, 'height'	=> 500, 'quality'	=> '100%')
        );

    function __construct()
    {
        parent::__construct();
    }

    function _uploaded($data=array(
        'input' => '',
        'path'  => '../live/uploads/',
        'filename' => ''
    ), $resize_ = [0,1,2]) {


        $this->load->library(['upload' => 'UP']);
        $filename                  = strtolower( preg_replace('/\s+$/', '_', $data['filename']) );
        $config['upload_path']     = $data['path'];
        $config['file_name']       = $filename.'-'.time();
        $config['allowed_types']   = 'gif|jpg|png|jpeg';
        $config['max_size']        = 1024*10;
        $full_path                 = [];

        $this    ->UP->initialize($config);
        
        if(!$this->UP->do_upload($data['input']))
        {
            $out['status']   = false;
            $out['error']    = strip_tags($this->UP->display_errors());
        } else {

            $this->load->library('image_lib');

            $dataUpload  = $this->UP->data();
            $source      = $dataUpload['full_path'];
            $img         = getimagesize($source);
            $realWidth	 = $img[0];
            $realHeight  = $img[1];
            
            $resize = [];

            foreach($resize_ as $rs) {
                $r = $this->upload_resize;
                $resize[] = array(
                    "width"			=> $r[$rs]['width'],
                    "height"		=> $r[$rs]['height'],
                    "quality"		=> $r[$rs]['quality'],
                    "source_image"	=> $source,
                    "new_image"		=> $data['path'].'/'.$r[$rs]['name']."/".$dataUpload['file_name']
                );
            }

            // foreach($this->upload_resize as $r){
            // }

            foreach($resize as $k=>$v){
                $oriW = $v['width'];
                $oriH = $v['height'];
                $x = $v['width']/$realWidth;
                $y = $v['height']/$realHeight;
                if($x < $y) {
                    $v['width'] = round($realWidth*($v['height']/$realHeight));
                } else {
                    $v['height'] = round($realHeight*($v['width']/$realWidth));
                }
                $full_path[] = $v['new_image']; 
                $this->image_lib->initialize($v);
                if(!$this->image_lib->resize()){
                    $out['status'] = false;
                    $out['error'] = $this->image_lib->display_errors();
                } else {
                    $out['status']      = true;
                    $out['filename']    = $dataUpload['file_name'];
                }
                $this->image_lib->clear();
            }
            //delete original image
            if(file_exists($source)){
                unlink($source);
            }

        }
        $out['full_path'] = $full_path;
        return $out;
    }

    function _upload_to_temporary($params=FALSE) {

        $params = !$params ? [
            'input'     => '',
            'tmp_path'  => '',
            'filename'  => ''
        ]:$params;

        $this->load->library(['upload' => 'UP']);
        $tmp_path  = './tmp/'.$params['tmp_path'];
        
        if( !is_dir($tmp_path) ) {
            mkdir($tmp_path);
        }

        $filename                  = strtolower( preg_replace('/\s+$/', '_', $params['filename']) );
        $config['upload_path']     = $tmp_path;
        $config['file_name']       = $filename.'-'.time();
        $config['allowed_types']   = 'gif|jpg|png';
        $config['max_size']        = 1024*10;

        $this->UP->initialize($config);
        
        if( !$this->UP->do_upload($params['input']) ) {
            
            $out['status']          = FALSE;
            $out['message']         = strip_tags($this->UP->display_errors());
            
        } else {
            
            $out         = $this->UP->data();
            $out['status'] = true;
        }
        return $out;
    }

    function _image_resize($params=[], $delete) {
        $data = !$params ? [
            'lokasi_uploads'    => '',
            'full_path'         => '',
            'file_name'         => '',
        ]: $params;

        $this->load->library('image_lib');

        $source                 = $data['full_path'];
        $img                    = getimagesize($source);
        $realWidth	            = $img[0];
        $realHeight             = $img[1];

        foreach($this->upload_resize as $r){
            
            $resize[] = array(
                "width"			=> $r['width'],
                "height"		=> $r['height'],
                "quality"		=> $r['quality'],
                "source_image"	=> $source,
                "new_image"		=> $data['lokasi_uploads'].'/'.$r['name']."/".$data['file_name']
            );
        }
        $count_save = 0;
        foreach($resize as $k=>$v){
            $oriW       = $v['width'];
            $oriH       = $v['height'];
            $x          = $v['width']/$realWidth;
            $y          = $v['height']/$realHeight;

            if($x < $y) {
                
                $v['width']     = round($realWidth*($v['height']/$realHeight));
            } else {
                $v['height']    = round($realHeight*($v['width']/$realWidth));
            }

            $full_path[]        = $v['new_image']; 

            $this->image_lib->initialize($v);

            if(!$this->image_lib->resize()){
                
                $out['status']      = false;
                $out['error']       = $this->image_lib->display_errors();
            } else {
                
                $out['status']      = true;
                $out['filename']    = $data['file_name'];

            }
            $this->image_lib->clear();
            $count_save += 1;
        }

        if($count_save == 3) {
            if($delete && file_exists($source)){
                unlink($source);
            }
        }

        return $out;
    }
    
    function _save_image($option = array('lokasi_uploads' => '', 'full_path' => '') , $delete=true) {
        
        $lokasi_file_upload     = $this->config->item('lokasi_file_upload');
        
        $image_full_path        = $option['full_path'];
        $photo_ex               = explode('/', $image_full_path);
        $photo_file_name        = end($photo_ex);
        
        $params                 =  [
            'lokasi_uploads'    => $lokasi_file_upload.$option['lokasi_uploads'],
            'full_path'         => $option['full_path'],
            'file_name'         => $photo_file_name,
        ];

        $image_resize        = $this->_image_resize($params, $delete);

        if($image_resize['status']) {
            return $image_resize['filename'];
        
        } else {
            return false;
        }
    }
}