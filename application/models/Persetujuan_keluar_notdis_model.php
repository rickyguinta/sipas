<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persetujuan_keluar_notdis_model extends CI_Model {

    function tampilData(){
        $id = $this->session->userdata('id');

        $this->db->select('*');
        $this->db->from('persetujuan_notdis');
        $this->db->join('user as b','persetujuan_notdis.id_penerima=b.id_user');
        $this->db->where('id_pengirim',$id);
        $this->db->where('is_read',1);
        $this->db->order_by("id_persetujuan_notdis","desc");

        return $this->db->get()->result_array();
    }

}