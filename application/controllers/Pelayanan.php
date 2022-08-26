<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelayanan extends CI_Controller {

	function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
		
		$this->load->model('M_Pelayanan');
	}

	
	public function index(){
		$data['title'] = "SIPAS | Pelayanan Staff";

		$data['data'] = $this->M_Pelayanan->tampilData();
		$data['ngecek1'] = 'main';
		$data['ngecek2'] = 'pelayanan';

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('pelayanan/index',$data);
		$this->load->view('templates/footer');
	}
	
	function tambah_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Pelayanan->tambah_data();
		$this->session->set_flashdata('msg','Data berhasil ditambahkan');
		redirect('pelayanan');
	}

	function edit_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Pelayanan->edit_data();
		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('pelayanan');
	}

	function hapus_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}
		
		$this->M_Pelayanan->hapus_data();
		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('pelayanan');
	}
}
