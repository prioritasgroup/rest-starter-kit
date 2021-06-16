<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	 function __construct() {
		 parent::__construct();

	 }
	public function index()
	{
		$this->load->view('welcome_message');

	}

	function external($link='') {
		if($link == 'ketentuan') {
			echo "LINK EXTERNAL KETENTUAN";
			
		} else {
			echo "LINK EXTERNAL KEBIJAKAN";

		}
	}

	function bantuan($token="") {
		if($token) {

			if(AUTHORIZATION::validateTimestamp($token)) {
				$this->session->set_userdata('token', $token);
				redirect('welcome/bantuan');
			} else {
				echo "Page Not Found.";
			}
		}
		if($this->session->userdata('token')) {
			$this->load->view('bantuan');
		}
	}

	function error_page() {
		header('content-type: application/json');
		$output = [
			'status' => false,
			'code'	 => 404,
			'message'=> 'Halaman tidak ditemukan.'
		];
		echo json_encode($output);
	}

	function test() {
		$output = sendMailRef('dani.webdev@gmail.com', 'TEST', 'KIRIM');

		echo json_encode($output);
	}
}
