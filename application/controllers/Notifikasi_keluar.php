<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_keluar extends CI_Controller {

	function __construct(){
        parent::__construct();
        
        if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
    }

    function index(){
        $data['title'] = "SIPAS | Notifikasi keluar";
		$data['ngecek1'] = 'notifikasi';
		$data['ngecek2'] = 'notifikasi_keluar';

        // get data
        $pengirim = $this->session->userdata('id');
        $this->db->select('*, a.id_user as id_pengirim,a.is_tingkatan as is_tingkatan_pengirim ,a.id_divisi as id_divisi_pengirim, a.jabatan as jabatan_pengirim , b.id_user as id_penerima, b.is_tingkatan as is_tingkatan_penerima,b.id_divisi as id_divisi_penerima, b.jabatan as jabatan_penerima');
        $this->db->from('notifikasi_surat');
        $this->db->join('user as a','notifikasi_surat.id_pengirim_notif=a.id_user');
        $this->db->join('user as b','notifikasi_surat.id_penerima_notif=b.id_user');
        $this->db->where('notifikasi_surat.id_pengirim_notif',$pengirim);
        $this->db->order_by("notifikasi_surat.id_notifikasi","desc");
        
		$data['data'] = $this->db->get()->result_array();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('notifikasi_keluar/index',$data);
		$this->load->view('templates/footer');
    }

    function hapus_data(){
        $id_notifikasi = $this->input->post('id',TRUE);

        $this->db->delete('notifikasi_surat',['id_notifikasi' => $id_notifikasi]);

        $this->session->set_flashdata('msg','Pesan berhasil dihapus');
		redirect('notifikasi_keluar');
    }


    function edit_data(){
        $data = [
            "isi_notif" => $this->input->post('isi_notifikasi',TRUE)
        ];

        $this->db->where('id_notifikasi',$this->input->post('id',TRUE));
        $this->db->update('notifikasi_surat',$data);

        $this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('notifikasi_keluar');
    }
}