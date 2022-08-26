<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satker extends CI_Controller {

	function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
		
		$this->load->model('M_Satker');
	}

	
	public function index(){
		$data['title'] = "SIPAS | Satker";
		$data['ngecek1'] = 'main';
		$data['ngecek2'] = 'satker';

        $data['kompartemen'] = $this->db->get('kompartemen')->result_array();
		$data['data'] = $this->M_Satker->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('satker/index',$data);
		$this->load->view('templates/footer');
	}
	
	function tambah_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Satker->tambah_data();
		$this->session->set_flashdata('msg','Data berhasil ditambahkan');
		redirect('satker');
	}

	function edit_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Satker->edit_data();
		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('satker');
	}

	function hapus_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}
		
		$this->M_Satker->hapus_data();
		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('satker');
	}
}
