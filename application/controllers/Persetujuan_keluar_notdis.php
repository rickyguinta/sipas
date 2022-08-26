<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persetujuan_keluar_notdis extends CI_Controller {

	function __construct(){
		parent::__construct();
		
		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}

		$this->load->model('Persetujuan_keluar_notdis_model');
    }

    function index(){
        $data['title'] = "SIPAS | Persetujuan Keluar";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'persetujuan_keluar_notdis';

		$data['data'] = $this->Persetujuan_keluar_notdis_model->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('persetujuan_keluar_notdis/index',$data);
		$this->load->view('templates/footer');
	}
}