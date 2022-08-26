<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Profile extends CI_Model {

    function editProfile(){
        $data = [
            "nama_user" => $this->input->post('nama_user',TRUE),
            "email" => $this->input->post('email',TRUE),
            "nrp" => $this->input->post('nrp',TRUE),
            "pangkat" => $this->input->post('pangkat',TRUE),
        ];

        $this->db->where('id_user',$this->input->post('id',TRUE));
        $this->db->update('user',$data);
    }

}

