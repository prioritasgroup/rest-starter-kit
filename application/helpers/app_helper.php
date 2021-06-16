<?php

function getCI(){
	$CI =& get_instance();
	return $CI;
}

function getCfg($name, $data='')
{
	$ci 	= getCI();
	$data 	= !empty($data) ? $data : '';

	return $ci->config->item($name).$data;
}

function noImage() {
	$ci 	= getCI();
	return $ci->config->item('no_image');
}

function sizeImage() {
	$ci 	= getCI();
	return $ci->config->item('size_image');
}

function checkFile__($path) {
	return file_exists(getCfg('lokasi_file_upload').$path);
}

function checkFile($path) {
	$url = getCfg('files_url').$path;
	// dd($url);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_NOBODY, true); // this is what sets it as HEAD request
	curl_exec($ch);

	$output = true;
	if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '404') { // 404 = not found
			$output = false;
	}

	curl_close($ch);

	return $output;
}

function files_url($path) {
	return getCfg('files_url').$path;
}
function ToJson($r) {
	header('content-type: application/json');

	echo json_encode($r);
}

function dd($r=array(), $json=TRUE){
	// if($json) {
	// 	ToJson($r);
	// 	die;
	// }

	echo "<pre>";
	print_r($r);
	echo "</pre>";

	die;
}

function dq($f=TRUE){
	$CI = getCI();
	print_r($CI->db->last_query());
	if($f==TRUE)
		die;
}

function _now($format='Y-m-d')
{
	return gmdate($format, time()+60*60*7);
}

function currency($num, $sign='Rp. ')
{
	$num = $sign.number_format($num, 0, ',', '.');
	return $num;
}

function GetInt($str) {
	return preg_replace('/\D/', '', $str);
}

function _load_class($path, $class, $method, $params1='', $params2='') {
	require_once $path;
	$_declare = new $class();
	return $_declare->$method($params1, $params2);
}

function FormatDate($date, $format='Y-m-d') {
	return date($format, strtotime($date));
	// $tanggal_survey		= explode("-", $date);
	// return $tanggal_survey[2].'-'.$tanggal_survey[1].'-'.$tanggal_survey[0];
}

function getNextPage($CreateLink, $now_page) {

	$link = preg_replace("/(.*)\" data-ci-pagination-page=\"(.*)\" rel=\"next\"(.*)/", '$1',$CreateLink);
	$link = explode('<a href="link--/', $link);
	$num  = end($link);
	// dd($num);
	try {
		if($CreateLink) {
			return ((Int) $num) < $now_page ?'':((Int) $num);
		} else {
			return '';
		}
	} catch (\Throwable $th) {
		return "";
	}
}

function GetLevel($id) {
	getCI()->load->database();
	$db = getCI()->db;
	$db->where("id", $id);
	$result = $db->get('unitbisnis_nama')->row();
	return $result->type_organisasi_id;
}

function GetCabang($id) {
	$id_relasi_cabang   = $id;
	$level              = GetLevel($id_relasi_cabang);

	getCI()->load->database();
	$db = getCI()->db;
	$db->select('lv5.id');
	$db->from('unitbisnis_nama lv'.$level);

	if($level > 5) {
		for($i=$level-1; $i>=5; $i--){
			$parent = $i+1;
			$db->join('unitbisnis_nama lv'.$i, "lv$i.id = lv$parent.parent_id", 'LEFT');
		}
	}

	$db->where("lv$level.id", $id_relasi_cabang);
	$result = $db->get()->row();
	return $result->id;
}

function GetAdm($id, $type_id='') {
	$CI = getCI();
	$CI->load->database();

	$db = $CI->db;
	$result = $db->where('id', $id)->get('informasi_administratif')->result();
	return count($result) > 0 ? $result[0]->nama_administratif: '-';
}

function getUnitBisnisByLevel($id,$field,$type=''){//params : id, column, naik_x
	$CI = getCI();
	$type_str = substr($type,5,1);
	$naik_0 = $CI->db->get_where("unitbisnis_nama",array("id"=>$id))->row();

	if ($type_str > 0) {
		$parent_id_handle = $naik_0->parent_id;
		for($i = 1; $i <= $type_str; $i++) {
			$parent = "naik_{$i}";
			$var = $i-1;
			$child = "naik_{$var}";

			$ub_loop 							= $CI->db->get_where("unitbisnis_nama",array("id"=>$parent_id_handle))->row();
			$parent_id_handle 		= $ub_loop->parent_id;

			// $parent = $CI->db->get_where("unitbisnis_nama",array("id"=>$child->parent_id))->row();
		}
	}else {
		$naik_0 = $CI->db->get_where("unitbisnis_nama",array("id"=>$id))->row();
	}

	$y='-';
	if(isset($naik_0)){
		if ($type == 'naik_0') {
			$y = $naik_0->$field;
		}else {
			$y = $parent_id_handle;
		}
	}
	return $y;
}

function unitbisnisRef($id){
	$ci = getCI();

	// $sql = '
	// 	SELECT id
	// 	FROM (SELECT * FROM unitbisnis_nama ORDER BY parent_id, id) parent_sorted,
	// 		 (select @pv := "'.$id.'") initialisation
	// 	WHERE find_in_set(parent_id, @pv) AND length(@pv := concat(@pv, ",", id))';
	// $query = $ci->db->query($sql)->result();
	// foreach ($query as $k => $v) {
	// 	$a[] = $v->id;
	// }
	    // $data = (new Cache())->remember('mob:unitref:'.$id, 60*60, function() use($ci, $id) {
		$sql = 'EXEC SPUnitbisnisRef '.$id;
		$a = [$id];

		$query = $ci->db->query($sql)->result();
		foreach ($query as $k => $v) {
			$a[] = $v->id;
		}

		return $a;
	    // });

	$data = (new Cache())->remember('mob:unitref:'.$id, 60*60, function() use($ci, $id) { });

	return $data;

	// $recursive = unitbisnisRecursive($id);
	// sort($recursive);
	// return $recursive;
}


function unitbisnisRecursive($id_unit, $parent=TRUE) {
	$ci = getCI();
	$ci->load->database();
	$DB = $ci->db;

	$id = [$id_unit];
	$DB->trans_start();
	// $DB->cache_on();

	$DB->select('nama.id, nama.type_organisasi_id as level');
	$DB->from('unitbisnis_nama nama');
	$DB->where('nama.parent_id', $id_unit);
	$DB->where('nama.status', 1);

	$GET = $DB->get()->result_array();

	$DB->trans_complete();

	foreach ($GET as $key => $value) {
		if($value['level'] < 8) {
			$getRec[$key] 	= unitbisnisRecursive($value['id'], false, false);
			$id           = array_merge($getRec[$key], $id);
		} else {
			$id[]         = $value['id'];
		}
	}

	return $id;
}


function getUnitBisnis($id, $field='', $select=''){
	$CI = getCI();
	if(!empty($select))
	{
		$a = $CI->db->select($select)->get_where("unitbisnis_nama",array("id"=>$id))->row();
	} else {
		$a = $CI->db->get_where("unitbisnis_nama", array(
			"id" => $id
			))->row();
	}
	$y='';
	if(isset($a) > 0 && !empty($field)){
		$y = $a->$field;
	} elseif(empty($field))
	{
		$y = $a;
	}
	return $y;
}

//OUTLET S/D SUB GROUP MARKETING
function unitbisnisAsc($id='', $level='', $target_level=5, $params=false){
	error_reporting(0);

	$CI = getCI();

	$lev[8] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$id))->row();
	$lev[7] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$lev['8']->parent_id))->row();
	$lev[6] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$lev['7']->parent_id))->row();
	$lev[5] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$lev['6']->parent_id))->row();
	$lev[4] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$lev['5']->parent_id))->row();
	$lev[3] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$lev['4']->parent_id))->row();
	$lev[2] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$lev['3']->parent_id))->row();
	$lev[1] = $CI->db->select($params ? $params:'id'.', parent_id')->get_where("unitbisnis_nama",array("id"=>$lev['2']->parent_id))->row();

	if($target_level == 5 && $level > $target_level) {
		$array = [8 => 3, 7 => 2, 6 => 1];

		foreach($array as $key=> $val) {
			if($level == $key) {
				$getLevel = 8-$val;
			}
		}

	} elseif($target_level == 6 && $level > $target_level) {
		$array = [8 => 2, 7 => 1, 6 => 0];

		foreach($array as $key=> $val) {
			if($level == $key) {
				$getLevel = 8-$val;
			}
		}

	} elseif($target_level == 7 && $level > $target_level) {
		$array = [8 => 1, 7 => 0];

		foreach($array as $key=> $val) {
			if($level == $key) {
				$getLevel = 8-$val;
			}
		}

	}

	if(!$params) {
		return $lev[$getLevel]->id;
	} else {
		return $lev[$getLevel]->$params;
	}
}

function GetUnitbisnisByKmo($id_kmo, $alias='nama_unitbisnis') {
	return "(SELECT nama.nama_unitbisnis FROM unitbisnis_nama nama WHERE nama.id = (SELECT kmo.unitbisnis_mutasi_id FROM karyawan_mutasi_organisasi kmo WHERE kmo.id=".$id_kmo.") ) as ".$alias;
}

function sendMailRef($to, $subject, $message, $attach="", $email_no=0) {

	$CI = getCI();

	$email = "predator.dev@prioritas-group.com";

	$emails = [
		[
			'mail'=> 'postmaster@sandboxee9af36a440f4268b86d6f7d20d160fe.mailgun.org',
			'pass'=> '9557af1d2f0470c9592dfbe9f168cd54-73ae490d-ca36e79a',
			'smtp'=> 'ssl://smtp.mailgun.org'
		],
		[
			'mail' => 'predator.dev@prioritas-group.com',
			'smtp' => 'ssl://smtp.googlemail.com',
			'pass' => 'predator030317'
		],
		[
			'mail'=> 'mydan3.msc@gmail.com',
			'smtp' => 'ssl://smtp.googlemail.com',
			'pass'=> '@mydan396'
		],
		[
			'mail'=> 'dhanie.storeage@gmail.com',
			'smtp' =>'ssl://smtp.googlemail.com',
			'pass'=> '@mydan396'
		],
		[
			'mail'=>  'dani.aliciatj@gmail.com',
			'smtp'=>  'ssl://smtp.googlemail.com',
			'pass'=>  '@bogor123'
		]
	];

	if($email_no > count($emails) - 1) {
		$o["status"] = false;
		$o["message"] = "Ada kesalahan pada saat kirim email, Periksa kembali di SMTP server";
		return $o;
	}

	$email = $emails[$email_no];

	$CI->load->library('email');

	$config = Array(
	'protocol' 	=> 'smtp',
	'smtp_host'	=> $email['smtp'],
	'smtp_port'	=> 465,
	'smtp_user'	=> $email['mail'], // change it to yours
	'smtp_pass'	=> $email['pass'], // change it to yours
	'mailtype' 	=> 'html',
	'charset'  	=> 'iso-8859-1',
	'wordwrap' 	=> TRUE,
	'crlf'			=> "\r\n"
	);

	$CI->email->initialize($config);
	$CI->email->clear(TRUE);
	$CI->email->set_newline("\r\n");
	$CI->email->from($email['mail'], "PREDATOR SYSTEM"); // change it to yours
	$CI->email->to($to);// change it to yours
	$CI->email->subject($subject);
	if(!empty($attach)) {

		$CI->email->attach($attach);

	}

	$CI->email->message($message);
	if($CI->email->send()) {

		$o["status"] = true;
		$o["message"] = "Email Berhasil dikirim";

	} else {

		return sendMailRef($to, $subject, $message, $attach="", $email_no=$email_no+1);
		$o["status"] = false;
		$o["error"] = $CI->email->print_debugger();

	}

	return $o;
}

function write_log($log_message='', $user='') {
	$datetime 		= _now('d-m-Y H:i:s');
	$name     		= strtolower(explode('@', $user)[0]);
	$log 		  	= "$datetime | ".strtolower($name)." \t\t$log_message\t\t| ".current_url()."\n";

	file_put_contents(APPPATH.'logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
}

function write_log_custom($log_message='', $log_name, $user='') {
	$datetime 		= _now('d-m-Y H:i:s');
	$name     		= strtolower(explode('@', $user)[0]);
	$log 		  	= "$datetime | ".strtolower($name)." \t\t$log_message\t\t| ".current_url()."\n";

	file_put_contents(APPPATH.'logs/log_'.$log_name.'.log', $log, FILE_APPEND);
}


//+++++++++++++++++++++++++++++++++++++++++++++++++
function get_data($table, $where=[], $select='*', $is_row=true) {

	$db = getCI()->db;

	$db->select($select);

	if(count($where)) {
		$db->where($where);
		foreach($where as $key => $value) {
			$db->where($key, $value);
		}
	}

	if($is_row) {
		return $db->get($table)->row();
	} else {
		return $db->get($table)->result();
	}
}

function update_data($table, $set, $where) {
	$db = getCI()->db;

	$db->trans_start();
		$db->set($set);
		$db->where($where);
		$db->update($table);
		$effected = $db->affected_rows();
	$db->trans_complete();

	return $effected;
}


function symClean($string){
	$a = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	return $a;
}

function api_data() {
	$headers = getCI()->input->request_headers();
	$tkn = empty($headers['Authorization']) ? $headers['authorization'] : $headers['Authorization'];
	$data = AUTHORIZATION::validateToken($tkn);

	return $data;
}

function getDataById($table,$id,$field){
	$CI = getCI();
	$a = $CI->db->get_where($table,array("id"=>$id))->row();

	$y='';
	if(isset($a)){
		$y = $a->$field;
	}
	return $y;
}

/** ACCOUNTING ===============================================================*/
function getPerkiraanGroup($id,$field){
	$CI = getCI();
	$a = $CI->db->get_where("acc_perkiraan_group",array("id"=>$id))->row();

	$y='';
	if(isset($a)){
		$y = $a->$field;
	}
	return $y;
}

function getPerkiraanSetting($id){

	$CI = getCI();

	$sql = $CI->db->query("SELECT * FROM acc_perkiraan_setting WHERE id='$id'")->row_array();

	return $sql;
}

function getAccPerkiraan($id) {

	$sql = get_data('acc_perkiraan_nama', ['id' => $id]);

	return $sql;
}


function getJurnalTmp($nama_jurnal){

	$CI = getCI();

	$sql = $CI->db->query("SELECT * FROM acc_jurnal_tmp WHERE nama_jurnal='$nama_jurnal'")->result_array();

	return $sql;
}

function SaveJurnal($data, $update_condition=[]) {
	// $data = [
	// 	"tanggal",
	// 	"jenis",
	// 	"unitbisnis_id",
	// 	"unitbisnis_id_level",
	// 	"transaksi_id",
	// 	"no_transaksi_kas",
	// 	"no_urut",
	// 	"posting",
	// 	"keterangan",
	// 	"acc_coa_id",
	// 	"acc_coa_kode",
	// 	"nilai_debet",
	// 	"nilai_kredit",
	// 	"nilai_total",
	// ];

	$count = get_data('acc_jurnal', $update_condition, 'COUNT(id) as count')->count;

	$c = getCI();
	$db = $c->db;

	$db->set($data);

	if($count > 0) {
		$db->where($update_condition);
		$db->update('acc_jurnal');
		return true;
	} else {
		$db->insert('acc_jurnal');

		return $db->insert_id();
	}
}

function DeleteJurnal($condition=[]) {
	$c = getCI();
	$db = $c->db;

	$db->where($condition)->delete('acc_jurnal');
}
/*============================================================================*/

function dateRev($d, $format='Y-m-d'){
	$nd 	=  date($format, strtotime($d));
	return $nd;
}


function get_bayar_full($faktur_id) {
    $ci = getCI();
    $ci->load->database();

    $db = $ci->db;

    $db->select('header.penjualan_sb_hd_id, detail.tenor_bayar');
    $db->from('penjualan_sb_byr_piutang_dt detail');
	$db->join('penjualan_sb_byr_piutang_hd header', 'header.id = detail.header_id');
	$db->where('header.penjualan_sb_hd_id', $faktur_id);

    $results = $db->get()->result();

    $tenor_bayar = [];

    foreach($results as $key => $value) {
        if(!in_array($value->tenor_bayar, $tenor_bayar)) {
            $tenor_bayar[] = $value->tenor_bayar;
        }
    }

    return $tenor_bayar;
}
