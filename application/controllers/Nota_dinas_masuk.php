<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_dinas_masuk extends CI_Controller {

	function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
		
		$this->load->model('M_nota_dinas_masuk_model');
		$this->load->model('M_nota_dinas_keluar_model');
    }

    function index(){
        $data['title'] = "SIPAS | Nota Dinas Masuk";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'nota_dinas_masuk';

		$data['data'] = $this->M_nota_dinas_masuk_model->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('nota_dinas/nota_dinas_masuk',$data);
		$this->load->view('templates/footer');
	}

	function edit_notdis($id){
		$data['title'] = "SIPAS | Edit Nota Dinas";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'nota_dinas_keluar';

		$data['data'] = $this->db->query("SELECT * FROM surat_nota_dinas WHERE id_surat_notdis='$id'")->row_array();
		$data['isinya'] = $this->db->query("SELECT * FROM isi_surat_notdis WHERE id_surat_notdis='$id'")->result_array();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('nota_dinas/edit_nota_dinas',$data);
		$this->load->view('templates/footer');
	}

	function proses_edit_notdis(){
		// kode file
		$kode = $this->input->post('id');

		$tampung  = $this->db->query("SELECT * FROM surat_nota_dinas WHERE id_surat_notdis='$kode'")->row();

		//  Cek jika ada gambar yang ingin di upload
		$upload_image = $_FILES['file_notdis']['name'];

		if($upload_image){
			// Setting konfigurasi
			$config['upload_path'] = './assets/dokumen_notdis/';  // foler upload 
			$config['allowed_types'] = 'pdf|doc|docx'; // jenis file
			
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('file_notdis')) {
				// $error = $this->upload->display_errors();
				// menampilkan pesan error
				// print_r($error);
				$this->session->set_flashdata('msg2','Data gagal ditambahkan, file harus docx atau pdf!');
				redirect('nota_dinas_keluar/in_detail/'.$kode);
			} else {
				unlink('assets/dokumen_notdis/'.$tampung->nama_dokumen);

				$result = $this->upload->data('file_name');
				$this->M_nota_dinas_masuk_model->edit_notdis($result);
				$this->session->set_flashdata('msg','Data Berhasil Diubah');
				redirect('nota_dinas_keluar/in_detail/'.$kode);
			}
		}
		else{
			$result = '-';
			$this->M_nota_dinas_masuk_model->edit_notdis($result);
			$this->session->set_flashdata('msg','Data Berhasil Diubah');
			redirect('nota_dinas_keluar/in_detail/'.$kode);
		}
	}

	function cetak_notdis($id){
		$data['title'] = "Cetak Nota Dinas";
		$data['data'] = $this->M_nota_dinas_keluar_model->get_dt_notdis($id);

		$notdis = $this->M_nota_dinas_keluar_model->get_dt_notdis($id);
		$dari = $notdis['dari'];

		$get_user = $this->db->query("SELECT * FROM user WHERE id_user='$dari'")->row_array();
		$id_div = $get_user['id_divisi'];

		if($get_user['is_tingkatan'] == 1){
			$get_pimpinan = $this->db->query("SELECT * FROM pimpinan WHERE id_pimpinan='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_pimpinan['kepanjangan_pimpinan'];
		}
		else if($get_user['is_tingkatan'] == 2){
			$get_pelayanan = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_pelayanan['kepanjangan_pelayanan'];
		}
		else if($get_user['is_tingkatan'] == 3){
			$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_kompartemen['kepanjangan_kompartemen'];
		}
		else if($get_user['is_tingkatan'] == 4){
			$get_satuan_kerja = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_div'")->row_array();
			$id_kompartemen = $get_satuan_kerja['id_kompartemen'];
			$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen'")->row_array();

			$data['atas_surat1'] = $get_kompartemen['kepanjangan_kompartemen'];
			$data['atas_surat2'] = $get_satuan_kerja['kepanjangan_satuan_kerja'];
		}
		else if($get_user['is_tingkatan'] == 5){
			$get_unit_kerja = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_div'")->row_array();
			$id_satker = $get_unit_kerja['id_satuan_kerja'];
			$get_satuan_kerja = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();

			$data['atas_surat1'] = $get_satuan_kerja['kepanjangan_satuan_kerja'];
			$data['atas_surat2'] = $get_unit_kerja['kepanjangan_unit_kerja'];
		}


		$get = $this->M_nota_dinas_keluar_model->get_dt_notdis($id);
		$time = strtotime($get['tanggal_surat_notdis']); 

		$bulan = date('m',$time);

		if($bulan == '01'){
			$nama_bulan = 'Januari';
		}
		else if($bulan == '02'){
			$nama_bulan = 'Februari';
		}
		else if($bulan == '03'){
			$nama_bulan = 'Maret';
		}
		else if($bulan == '04'){
			$nama_bulan = 'April';
		}
		else if($bulan == '05'){
			$nama_bulan = 'Mei';
		}
		else if($bulan == '06'){
			$nama_bulan = 'Juni';
		}
		else if($bulan == '07'){
			$nama_bulan = 'Juli';
		}
		else if($bulan == '08'){
			$nama_bulan = 'Agustus';
		}
		else if($bulan == '09'){
			$nama_bulan = 'September';
		}
		else if($bulan == '10'){
			$nama_bulan = 'Oktober';
		}
		else if($bulan == '11'){
			$nama_bulan = 'November';
		}
		else if($bulan == '12'){
			$nama_bulan = 'Desember';
		}

		$data['nama_bulan'] = $nama_bulan;

		$this->load->view('nota_dinas/cetak_notdis',$data);
	}

	function cetak_persetujuan($id){
		$data['title'] = "Cetak Persetujuan Nota Dinas";
		$get_notdis = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_persetujuan_notdis='$id'")->row_array();
		$id_notdis = $get_notdis['id_surat_notdis'];

		$data['data'] = $this->M_nota_dinas_keluar_model->get_dt_notdis($id_notdis);
		$data['persetujuan'] = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_persetujuan_notdis='$id'")->row_array();
		
		// kop surat
		$notdis = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_persetujuan_notdis='$id'")->row_array();
		$dari = $notdis['id_pengirim'];

		$get_user = $this->db->query("SELECT * FROM user WHERE id_user='$dari'")->row_array();
		$id_div = $get_user['id_divisi'];

		if($get_user['is_tingkatan'] == 1){
			$get_pimpinan = $this->db->query("SELECT * FROM pimpinan WHERE id_pimpinan='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_pimpinan['kepanjangan_pimpinan'];
		}
		else if($get_user['is_tingkatan'] == 2){
			$get_pelayanan = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_pelayanan['kepanjangan_pelayanan'];
		}
		else if($get_user['is_tingkatan'] == 3){
			$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_kompartemen['kepanjangan_kompartemen'];
		}
		else if($get_user['is_tingkatan'] == 4){
			$get_satuan_kerja = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_div'")->row_array();
			$id_kompartemen = $get_satuan_kerja['id_kompartemen'];
			$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen'")->row_array();

			$data['atas_surat1'] = $get_kompartemen['kepanjangan_kompartemen'];
			$data['atas_surat2'] = $get_satuan_kerja['kepanjangan_satuan_kerja'];
		}
		else if($get_user['is_tingkatan'] == 5){
			$get_unit_kerja = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_div'")->row_array();
			$id_satker = $get_unit_kerja['id_satuan_kerja'];
			$get_satuan_kerja = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();

			$data['atas_surat1'] = $get_satuan_kerja['kepanjangan_satuan_kerja'];
			$data['atas_surat2'] = $get_unit_kerja['kepanjangan_unit_kerja'];
		}

		// akhir kop surat

		$get = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_persetujuan_notdis='$id'")->row_array();
		$time = strtotime($get['tanggal_diacc']); 

		$bulan = date('m',$time);

		if($bulan == '01'){
			$nama_bulan = 'Januari';
		}
		else if($bulan == '02'){
			$nama_bulan = 'Februari';
		}
		else if($bulan == '03'){
			$nama_bulan = 'Maret';
		}
		else if($bulan == '04'){
			$nama_bulan = 'April';
		}
		else if($bulan == '05'){
			$nama_bulan = 'Mei';
		}
		else if($bulan == '06'){
			$nama_bulan = 'Juni';
		}
		else if($bulan == '07'){
			$nama_bulan = 'Juli';
		}
		else if($bulan == '08'){
			$nama_bulan = 'Agustus';
		}
		else if($bulan == '09'){
			$nama_bulan = 'September';
		}
		else if($bulan == '10'){
			$nama_bulan = 'Oktober';
		}
		else if($bulan == '11'){
			$nama_bulan = 'November';
		}
		else if($bulan == '12'){
			$nama_bulan = 'Desember';
		}

		$data['nama_bulan'] = $nama_bulan;

		$data['tanggal_diterima'] = $nama_bulan;
		
		$this->load->view('nota_dinas/cetak_persetujuan_notdis',$data);
	}
	
}