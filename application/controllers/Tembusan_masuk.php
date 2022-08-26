<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tembusan_masuk extends CI_Controller {

	function __construct(){
        parent::__construct();
        
        if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}
	
    }

    function index(){
        $data['title'] = "SIPAS | Tembusan Masuk";
		$data['ngecek1'] = 'tembusan';
        $data['ngecek2'] = 'tembusan_masuk';
        
        $id = $this->session->userdata('id');

		$data['data'] = $this->db->query("SELECT * FROM tembusan_kirim LEFT JOIN user ON tembusan_kirim.id_pengirim_tembusan=user.id_user WHERE tembusan_kirim.id_penerima_tembusan='$id' AND tembusan_kirim.is_dibaca=1")->result_array();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('tembusan_masuk/index',$data);
		$this->load->view('templates/footer');
    }
    
    function in_detail_tembusan($id){
        // how to get tembusan
        $get = $this->db->query("SELECT * FROM tembusan_kirim WHERE id_tembusan_kirim='$id'")->row_array();
        $id_notdis = $get['id_surat'];

        // get timestamp
        $timenya = str_replace(' ', '', $get['tanggal_dibaca_tembusan']);

        if($timenya == '0000-00-0000:00:00'){
            date_default_timezone_set("Asia/Jakarta");

            $dataa = [
                "tanggal_dikirim_tembusan" => $get['tanggal_dikirim_tembusan'],
                "tanggal_dibaca_tembusan" => date("Y-m-d H:i:s", time()),
                "is_dibaca" => 1
            ];

            $this->db->where('id_tembusan_kirim',$id);
            $this->db->update('tembusan_kirim',$dataa);
        }

        redirect('nota_dinas_keluar/in_detail/'.$id_notdis);
    }


}