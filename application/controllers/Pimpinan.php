<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pimpinan extends CI_Controller {

	function __construct(){
		parent::__construct();
		
		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}

		$this->load->model('M_Pimpinan');
	}

	
	public function index(){
		$data['title'] = "SIPAS | Pimpinan";
		$data['ngecek1'] = 'main';
		$data['ngecek2'] = 'pimpinan';

		$data['data'] = $this->M_Pimpinan->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('pimpinan/index',$data);
		$this->load->view('templates/footer');
	}
	
	function tambah_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}
		
		$this->M_Pimpinan->tambah_data();
		$this->session->set_flashdata('msg','Data berhasil ditambahkan');
		redirect('pimpinan');
	}

	function edit_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Pimpinan->edit_data();
		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('pimpinan');
	}

	function hapus_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Pimpinan->hapus_data();
		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('pimpinan');
	}
}
