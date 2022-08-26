<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Kompartemen extends CI_Model {

    function tampilData(){
        return $this->db->get('kompartemen')->result_array();
    }
    
    function tambah_data(){
        $data = [
            "nama_kompartemen" => $this->input->post('nama_kompartemen',TRUE),
            "kepanjangan_kompartemen" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->insert('kompartemen',$data);
    }

    function edit_data(){
        $data = [
            "nama_kompartemen" => $this->input->post('nama_kompartemen',TRUE),
            "kepanjangan_kompartemen" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->where('id_kompartemen',$this->input->post('id',TRUE));
        $this->db->update('kompartemen',$data);
    }

    function hapus_data(){
        $this->db->delete('kompartemen',['id_kompartemen' => $this->input->post('id',TRUE)]);
    }
    
}
