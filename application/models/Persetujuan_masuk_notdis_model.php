<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persetujuan_masuk_notdis_model extends CI_Model {

    function tampilData(){
        $id_penerima = $this->session->userdata('id');

        $this->db->select('*');
        $this->db->from('persetujuan_notdis');
        $this->db->join('user as b','persetujuan_notdis.id_pengirim=b.id_user');
        $this->db->where('id_penerima',$id_penerima);
        $this->db->where('is_read',1);
        $this->db->order_by("id_persetujuan_notdis","desc");

        return $this->db->get()->result_array();
    }

    function send_Notif($id_penerima, $id_notdis){
        date_default_timezone_set("Asia/Jakarta");

        // id pengirim tindak lanjut kasus
        $id =$this->session->userdata('id');

        $data = [
            "id_surat" => $id_notdis,
            "id_pengirim_notif" => $id,
            "id_penerima_notif" => $id_penerima,
            "tgl_notif_kirim" => date("Y-m-d H:i:s", time()),
            "isi_notif" => $this->input->post('isi',TRUE),
            "is_read_notif" => 0,
            "jenis_surat_notifikasi" => 1
        ];

        $this->db->insert('notifikasi_surat',$data);
    } 

}