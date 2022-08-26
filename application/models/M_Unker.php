<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Unker extends CI_Model {

    function tampilData(){
        $this->db->select('*');
        $this->db->from('unit_kerja');
        $this->db->join('satuan_kerja','satuan_kerja.id_satuan_kerja=unit_kerja.id_satuan_kerja');

        return $this->db->get()->result_array();
    }
    
    function tambah_data(){
        $data = [
            "id_satuan_kerja" => $this->input->post('satker',TRUE),
            "nama_unit_kerja" => $this->input->post('nama_unit_kerja',TRUE),
            "kepanjangan_unit_kerja" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->insert('unit_kerja',$data);
    }

    function edit_data(){
        $data = [
            "id_satuan_kerja" => $this->input->post('satker',TRUE),
            "nama_unit_kerja" => $this->input->post('nama_unit_kerja',TRUE),
            "kepanjangan_unit_kerja" => $this->input->post('kepanjangan',TRUE)
        ];

        $this->db->where('id_unit_kerja',$this->input->post('id',TRUE));
        $this->db->update('unit_kerja',$data);
    }

    function hapus_data(){
        $this->db->delete('unit_kerja',['id_unit_kerja' => $this->input->post('id',TRUE)]);
    }

    function satker($id){
		$satker="<option value='0'>----- Pilih -----</pilih>";

		$sat= $this->db->get_where('satuan_kerja',array('id_kompartemen'=>$id));
        
		foreach ($sat->result_array() as $data ){
            $nama = strtoupper($data['nama_satuan_kerja']);
            $satker.= "<option value='$data[id_satuan_kerja]'>$nama</option>";
		}

		return $satker;
    }
    
    function unit_kerja($id){
		$unit_kerja="<option value='0'>----- Pilih -----</pilih>";

		$uker= $this->db->get_where('unit_kerja',array('id_satuan_kerja'=>$id));

		foreach ($uker->result_array() as $data ){
            $nama = strtoupper($data['nama_unit_kerja']);
            $unit_kerja.= "<option value='$data[id_unit_kerja]'>$nama</option>";
		}

		return $unit_kerja;
	}
    
}
