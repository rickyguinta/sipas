<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persetujuan_masuk_notdis extends CI_Controller {

	function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
		
		$this->load->model('Persetujuan_masuk_notdis_model');
    }

    function index(){
        $data['title'] = "SIPAS | Persetujuan Masuk";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'persetujuan_masuk_notdis';

		$data['data'] = $this->Persetujuan_masuk_notdis_model->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('persetujuan_masuk_notdis/index',$data);
		$this->load->view('templates/footer');
	}

	function tindak_lanjut_kasus(){
		$tl_surat = $this->input->post('tindak_lanjut',TRUE);
		$isi = $this->input->post('isi',TRUE);
		$id_persetujuan_notdis = $this->input->post('id_persetujuan_notdis',TRUE);
		// get persetujuan notdis
		$get_persetujuan_notdis = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_persetujuan_notdis='$id_persetujuan_notdis'")->row_array();
		$id_notdis= $get_persetujuan_notdis['id_surat_notdis'];

		// awal pengirim persetujuan
		$get_persetujuan_notdis_awal = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_surat_notdis='$id_notdis' AND is_read=1 ORDER BY id_persetujuan_notdis ASC LIMIT 1")->row_array();
		// is read = 1 yang terakhir
		$get_persetujuan_notdis_sebelum = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_surat_notdis='$id_notdis' AND is_read=1 ORDER BY id_persetujuan_notdis DESC LIMIT 1")->row_array();
		
		// persetujuan yang is_read = 0 akan di 1 kan
		$get_persetujuan_sesudah = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_surat_notdis='$id_notdis' AND is_read=0")->row_array();

		if($tl_surat == 'diteruskan'){

			if($get_persetujuan_notdis_awal['id_persetujuan_notdis'] == $get_persetujuan_notdis_sebelum['id_persetujuan_notdis']){

				// sebelum
				$data = [
					"status_persetujuan_notdis" => 'Diajukan dan Disetujui'
				];
	
				$this->db->where('id_persetujuan_notdis',$id_persetujuan_notdis);
				$this->db->update('persetujuan_notdis',$data);
			}
			else{
				// sebelum
				$data = [
					"status_persetujuan_notdis" => 'Disetujui'
				];
	
				$this->db->where('id_persetujuan_notdis',$id_persetujuan_notdis);
				$this->db->update('persetujuan_notdis',$data);
			}

			// sesudah
			$data = [
				"nomer_surat_persetujuan_notdis" => $isi,
				"tanggal_diacc" => '0000-00-00 00:00:00',
				"is_read" => 1
			];

			$this->db->where('id_persetujuan_notdis',$get_persetujuan_sesudah['id_persetujuan_notdis']);
			$this->db->update('persetujuan_notdis',$data);

			// get notdis
			$get_notdis = $this->db->query("SELECT * FROM surat_nota_dinas WHERE id_surat_notdis='$id_notdis'")->row_array();
			$id_dari = $get_notdis['dari'];
			$id = $this->session->userdata('id');

			if($id_dari == $id){
				$data = [
					"nomer_surat_notdis" => $isi
				];

				$this->db->where('id_surat_notdis',$id_notdis);
				$this->db->update('surat_nota_dinas',$data);
			}
			
			
			$this->session->set_flashdata('msg','Surat berhasil diterukan');
			redirect('persetujuan_masuk_notdis');
		}
		else if($tl_surat == 'diterima'){
			// persetujuan notdis
			$data = [
				"status_persetujuan_notdis" => 'Disetujui'
			];

			$this->db->where('id_persetujuan_notdis',$id_persetujuan_notdis);
			$this->db->update('persetujuan_notdis',$data);


			// kirim tembusan 
			$data = [
				"is_dibaca" => 1
			];

			$this->db->where('id_surat',$id_notdis);
			$this->db->where('jenis_surat',1);
			$this->db->update('tembusan_kirim',$data);


			// edit status surat
			$data = [
				"status_surat" => 1
			];
			
			$this->db->where('id_surat_notdis',$id_notdis);
			$this->db->update('surat_nota_dinas',$data);

			$this->session->set_flashdata('msg','Surat berhasil diterima');
			redirect('persetujuan_masuk_notdis');
		}
		else if($tl_surat == 'dikembalikan'){
			// persetujuan notdis
			$data = [
				"status_persetujuan_notdis" => 'Ditolak'
			];

			$this->db->where('id_persetujuan_notdis',$id_persetujuan_notdis);
			$this->db->update('persetujuan_notdis',$data);

			$this->Persetujuan_masuk_notdis_model->send_Notif($get_persetujuan_notdis_awal['id_pengirim'], $id_notdis);

            $this->session->set_flashdata('msg','Pesan berhasil dikirim');
            redirect('persetujuan_masuk_notdis');
		}
	}
}