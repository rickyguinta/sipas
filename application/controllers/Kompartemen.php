<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kompartemen extends CI_Controller {

	function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
		
		$this->load->model('M_Kompartemen');
	}

	
	public function index(){
		$data['title'] = "SIPAS | Kompartemen";
		$data['ngecek1'] = 'main';
		$data['ngecek2'] = 'kompartemen';

		$data['data'] = $this->M_Kompartemen->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('kompartemen/index',$data);
		$this->load->view('templates/footer');
	}
	
	function tambah_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Kompartemen->tambah_data();
		$this->session->set_flashdata('msg','Data berhasil ditambahkan');
		redirect('kompartemen');
	}

	function edit_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Kompartemen->edit_data();
		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('kompartemen');
	}

	function hapus_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}
		
		$this->M_Kompartemen->hapus_data();
		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('kompartemen');
	}
}
