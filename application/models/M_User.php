<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_User extends CI_Model {

    function tampilData(){
        return $this->db->get('user')->result_array();
    }

    function tambahData($tingkatan, $divisi){
        $data = [
            "username" => $this->input->post('username',TRUE),
            "password" => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
            "nama_user" => $this->input->post('nama',TRUE),
            "email" => $this->input->post('email',TRUE),
            "nrp" => $this->input->post('nrp',TRUE),
            "pangkat" => $this->input->post('pangkat',TRUE),
            "level" => $this->input->post('level',TRUE),
            "id_divisi" => $divisi,
            "is_tingkatan" => $tingkatan,
            "jabatan" => $this->input->post('jabatan',TRUE)
        ];

        $this->db->insert('user',$data);
    }
    
    function hapusData(){
        $this->db->delete('user',['id_user' => $this->input->post('id',TRUE)]);
    }

    function editData(){
        $data = [
            "nama_user" => $this->input->post('nama_user',TRUE)
        ];

        $this->db->where('id_user',$this->input->post('id',TRUE));
        $this->db->update('user',$data);
    }
}
