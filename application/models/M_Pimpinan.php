<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Pimpinan extends CI_Model {

    function tampilData(){
        return $this->db->get('pimpinan')->result_array();
    }
    
    function tambah_data(){
        $data = [
            "nama_pimpinan" => $this->input->post('nama_pimpinan',TRUE),
            "kepanjangan_pimpinan" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->insert('pimpinan',$data);
    }

    function edit_data(){
        $data = [
            "nama_pimpinan" => $this->input->post('nama_pimpinan',TRUE),
            "kepanjangan_pimpinan" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->where('id_pimpinan',$this->input->post('id',TRUE));
        $this->db->update('pimpinan',$data);
    }

    function hapus_data(){
        $this->db->delete('pimpinan',['id_pimpinan' => $this->input->post('id',TRUE)]);
    }
    
}
