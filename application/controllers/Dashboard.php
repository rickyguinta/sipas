<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
    }

	
	public function index()
	{
		$data['title'] = "SIPAS | Dashboard";

		$data['ngecek1'] = 'dashboard';
		$data['ngecek2'] = 'dashboard2';

		$id = $this->session->userdata('id');
		
		// hitung pimpinan
		$pimpinan = $this->db->get('pimpinan')->num_rows();
		$pelayanan = $this->db->get('pelayanan')->num_rows();
		$kompartemen = $this->db->get('kompartemen')->num_rows();
		$satuan_kerja = $this->db->get('satuan_kerja')->num_rows();
		$unit_kerja = $this->db->get('unit_kerja')->num_rows();

		$data['jml_surat_masuk'] = $this->db->query("SELECT * FROM surat_nota_dinas LEFT JOIN kepada_surat_notdis ON surat_nota_dinas.id_surat_notdis=kepada_surat_notdis.kepada WHERE kepada_surat_notdis.kepada='$id' AND surat_nota_dinas.status_surat=1")->num_rows();
		$data['jml_jml_surat_keluar'] = $this->db->query("SELECT * FROM surat_nota_dinas WHERE id_pengirim_awal='$id' AND status_surat=1")->num_rows();
		$data['jml_user'] = $this->db->get('user')->num_rows();
		$data['jml_divisi'] = $pimpinan+$pelayanan+$kompartemen+$satuan_kerja+$unit_kerja;

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('dashboard/index',$data);
		$this->load->view('templates/footer');

    }
    
    
}
