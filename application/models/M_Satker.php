<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Satker extends CI_Model {

    function tampilData(){
        $this->db->select('*');
        $this->db->from('satuan_kerja');
        $this->db->join('kompartemen','kompartemen.id_kompartemen=satuan_kerja.id_kompartemen');

        return $this->db->get()->result_array();
    }
    
    function tambah_data(){
        $data = [
            "id_kompartemen" => $this->input->post('kompartemen',TRUE),
            "nama_satuan_kerja" => $this->input->post('nama_satuan_kerja',TRUE),
            "kepanjangan_satuan_kerja" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->insert('satuan_kerja',$data);
    }

    function edit_data(){
        $data = [
            "id_kompartemen" => $this->input->post('kompartemen',TRUE),
            "nama_satuan_kerja" => $this->input->post('nama_satuan_kerja',TRUE),
            "kepanjangan_satuan_kerja" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->where('id_satuan_kerja',$this->input->post('id',TRUE));
        $this->db->update('satuan_kerja',$data);
    }

    function hapus_data(){
        $this->db->delete('satuan_kerja',['id_satuan_kerja' => $this->input->post('id',TRUE)]);
    }
    
}
