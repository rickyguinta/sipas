<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Pelayanan extends CI_Model {

    function tampilData(){
        return $this->db->get('pelayanan')->result_array();
    }
    
    function tambah_data(){
        $data = [
            "nama_pelayanan" => $this->input->post('nama_pelayanan',TRUE),
            "kepanjangan_pelayanan" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->insert('pelayanan',$data);
    }

    function edit_data(){
        $data = [
            "nama_pelayanan" => $this->input->post('nama_pelayanan',TRUE),
            "kepanjangan_pelayanan" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->where('id_pelayanan',$this->input->post('id',TRUE));
        $this->db->update('pelayanan',$data);
    }

    function hapus_data(){
        $this->db->delete('pelayanan',['id_pelayanan' => $this->input->post('id',TRUE)]);
    }
    
}
