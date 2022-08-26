<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_masuk extends CI_Controller {

	function __construct(){
    parent::__construct();
    
    if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
  }

    function index(){
        date_default_timezone_set("Asia/Jakarta");

        $data['title'] = "SIPAS | Notifikasi Masuk";
		    $data['ngecek1'] = 'notifikasi';
		    $data['ngecek2'] = 'notifikasi_masuk';

        // get data
        $id_penerima = $this->session->userdata('id');
        $this->db->select('*, a.id_user as id_pengirim,a.is_tingkatan as is_tingkatan_pengirim ,a.id_divisi as id_divisi_pengirim, a.jabatan as jabatan_pengirim , b.id_user as id_penerima, b.is_tingkatan as is_tingkatan_penerima,b.id_divisi as id_divisi_penerima, b.jabatan as jabatan_penerima');
        $this->db->from('notifikasi_surat');
        $this->db->join('user as a','notifikasi_surat.id_pengirim_notif=a.id_user');
        $this->db->join('user as b','notifikasi_surat.id_penerima_notif=b.id_user');
        $this->db->where('notifikasi_surat.id_penerima_notif',$id_penerima);
        $this->db->order_by("notifikasi_surat.id_notifikasi","desc");
        
        $data['data'] = $this->db->get()->result_array();
        
          // read surat
          $dataa = [
            "tgl_notif_baca" => date("Y-m-d H:i:s", time()),
            "is_read_notif" => 1
          ];

        $this->db->where('is_read_notif',0);
        $this->db->where('id_penerima_notif',$id_penerima);
        $this->db->update('notifikasi_surat',$dataa);

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('notifikasi_masuk/index',$data);
        $this->load->view('templates/footer');
    }

    function tindak_lanjut(){
        $tl_notif = $this->input->post('keterangan',TRUE);
        $id = $this->input->post('id',TRUE);
        $id_user = $this->session->userdata('id');

        // get pengirim notif
        $get_notif = $this->db->query("SELECT * FROM notifikasi_surat WHERE id_notifikasi='$id'")->row_array();
        $id_pengirim_notif = $get_notif['id_pengirim_notif'];

        $data =  [
            "id_surat" => $get_notif['id_surat'],
            "id_pengirim_notif" => $id_user,
            "id_penerima_notif" => $get_notif["id_pengirim_notif"],
            "tgl_notif_baca" => '0000-00-00 00:00:00',
            "isi_notif" => $tl_notif,
            "is_read_notif" => 0,
            "jenis_surat_notifikasi" => $get_notif['jenis_surat_notifikasi']
        ];

        $this->db->insert('notifikasi_surat',$data);

		$this->session->set_flashdata('msg','Pesan berhasil ditambahkan');
		redirect('notifikasi_masuk');
    }
}