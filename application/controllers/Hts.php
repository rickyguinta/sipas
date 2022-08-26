<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hts extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('M_nota_dinas_keluar_model');
    }

    function index_history($key){

         // update last genrated
         $notdis = $this->db->query("SELECT * FROM surat_nota_dinas WHERE key_surat='$key'")->row_array();
         date_default_timezone_set("Asia/Jakarta");
         
         $dataa = [
             "last_genrated" => date("Y-m-d H:i:s", time())
         ];
 
         $this->db->where('key_surat',$key);
         $this->db->update('surat_nota_dinas',$dataa);

        $data['data'] = $this->db->query("SELECT * FROM surat_nota_dinas WHERE key_surat='$key'")->row_array();
        $tampung = $this->db->query("SELECT * FROM surat_nota_dinas WHERE key_surat='$key'")->row_array();

        $id = $tampung['id_surat_notdis'];

        $data['persetujuan_notdis'] = $this->M_nota_dinas_keluar_model->tampil_persetujuan($id);

        $this->load->view('hts/index',$data);
    }

}