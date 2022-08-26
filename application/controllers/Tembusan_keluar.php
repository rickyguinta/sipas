<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tembusan_keluar extends CI_Controller {

	function __construct(){
		parent::__construct();
	
		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
    }

    function index(){
        $data['title'] = "SIPAS | Tembusan Keluar";
		$data['ngecek1'] = 'tembusan';
        $data['ngecek2'] = 'tembusan_keluar';
        
        $id = $this->session->userdata('id');

		$data['data'] = $this->db->query("SELECT * FROM tembusan_kirim LEFT JOIN user ON tembusan_kirim.id_penerima_tembusan=user.id_user WHERE tembusan_kirim.id_pengirim_tembusan='$id' AND tembusan_kirim.is_dibaca=1")->result_array();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('tembusan_keluar/index',$data);
		$this->load->view('templates/footer');
	}


}