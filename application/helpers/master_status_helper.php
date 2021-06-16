<?php

function MasterStatus($param=false, $key=false) {
	$status['analisa'] = array(
		''					=> '- Pilih -',
		'dibawa' 			=> 'Pembawaan',
		'rekomendasi' 		=> 'Rekomendasi',
		'tidakrekomendasi' 	=> 'Tidak Rekomendasi',
		'pertimbangan' 		=> 'Pertimbangan'
	);

	$status['komite'] = array(
		''			=> '- Pilih -',
		'dibawa' 	=> 'Pembawaan',
		'acc' 		=> 'ACC',
		'tolak' 	=> 'TOLAK',
		'batal' 	=> 'BATAL'
	);

	$status['pengiriman'] = array(
		''			=> '- Pilih -',
		'dikirim' 	=> 'Dikirim',
		'terkirim' 	=> 'Terkirim',
		'tao' 		=> 'Tidak Ada Orang (TAO)',
		'tsp' 		=> 'Tidak Sesuai Pesanan (TSP)',
		'ubs' 		=> 'Uang Belum Siap (UBS)',
		'atj' 		=> 'Alamat Tidak Jelas (ATJ)',
		'batal'     => 'Batal',
		'tolak'     => 'Tolak',
	);

	$status['collector'] = array(
		''			=> '- Pilih - ',
		'bayar'		=> 'Bayar',
		'jb'		=> 'Janji Bayar',
		'tao'		=> 'Tidak Ada Orang',
		'tarik'		=> 'Tarik Barang'	
	);

	$status['kuitansi_pengembalian'] = array(
		''			=> '- Pilih -',
		's_o_p'		=> "SOP",
		'rusak'		=> "Rusak"
	);

	$status['kuitansi_persediaan'] = array(
		''			=> '- Pilih -',
		'0'		=> "Tersedia",
		'1'		=> "Tidak Tersedia"
	);

	if ($param) {

		if($key) {
			return $status[$param][$key];
		} else {
			return '';
		}

	} else {
        return $status;
	}
}

function TypeTransaksi() {
	return ['konsumen', 'karyawan'];
}
