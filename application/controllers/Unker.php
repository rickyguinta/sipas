<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unker extends CI_Controller {

	function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
		
		$this->load->model('M_Unker');
	}

	
	public function index(){
		$data['title'] = "SIPAS | Unit Kerja";
		$data['ngecek1'] = 'main';
		$data['ngecek2'] = 'unker';

        $data['kompartemen'] = $this->db->get('kompartemen')->result_array();
        $data['satker'] = $this->db->get('satuan_kerja')->result_array();
		$data['data'] = $this->M_Unker->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('unker/index',$data);
		$this->load->view('templates/footer');
	}
	
	function tambah_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Unker->tambah_data();
		$this->session->set_flashdata('msg','Data berhasil ditambahkan');
		redirect('unker');
	}

	function edit_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}

		$this->M_Unker->edit_data();
		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('unker');
	}

	function hapus_data(){
		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}
		
		$this->M_Unker->hapus_data();
		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('unker');
    }
    
    public function ambil_data(){
		$modul=$this->input->post('modul');
		$id=$this->input->post('id');

		if($modul=="satker"){
            echo $this->M_Unker->satker($id);
        }
        else if($modul=="unit_kerja"){
            echo $this->M_Unker->unit_kerja($id);
        }
	}
}
