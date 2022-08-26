<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_disposisi extends CI_Model {

    function kirim_disposisi(){
        $id = $this->session->userdata('id');

        foreach($this->cart->contents() as $item) {
            date_default_timezone_set("Asia/Jakarta");

            $data = [
                "id_surat_notdis" => $this->input->post('id_surat',TRUE),
                "no_agenda" => $this->input->post('nomer_agenda',TRUE),
                "id_pengirim_disposisi" => $id,
                "id_penerima_disposisi" => $item['id'],
                "tgl_dikirim_disposisi" => date("Y-m-d H:i:s", time()),
                "tgl_dibaca_disposisi" => '0000-00-00 00:00:00',
                "isi_disposisi_notdis" => $this->input->post('isi_disposisi',TRUE)
            ];
    
            $this->db->insert('disposisi_notdis',$data);
        }
        
    }

    function detail_disposisi($id){

        $this->db->select('*');
        $this->db->from('disposisi_notdis');
        $this->db->where('id_disposisi_notdis',$id);

        return $this->db->get()->row_array();
    }

    function tampil_disposisi_keluar(){
        $id = $this->session->userdata('id');

        $this->db->select('*,b.id_divisi as id_divisi_kepada, b.is_tingkatan as is_tingkatan_kepada, b.jabatan as jabatan_kepada');
        $this->db->from('disposisi_notdis');
        $this->db->join('user as b','disposisi_notdis.id_penerima_disposisi=b.id_user');
        $this->db->where('disposisi_notdis.id_pengirim_disposisi',$id);
        $this->db->order_by("disposisi_notdis.id_disposisi_notdis","desc");

        return $this->db->get()->result_array();
    }

    
    function tampil_disposisi_masuk(){
        $id = $this->session->userdata('id');

        $this->db->select('*,b.id_divisi as id_divisi_dari, b.is_tingkatan as is_tingkatan_dari, b.jabatan as jabatan_dari');
        $this->db->from('disposisi_notdis');
        $this->db->join('user as b','disposisi_notdis.id_pengirim_disposisi=b.id_user');
        $this->db->where('disposisi_notdis.id_penerima_disposisi',$id);
        $this->db->order_by("disposisi_notdis.id_disposisi_notdis","desc");

        return $this->db->get()->result_array();
    }

    // kompartemen
    function tampil_3_kompartemen(){
        $id = $this->session->userdata("id");
        $user = $this->db->query("SELECT * FROM user WHERE id_user='$id'")->row_array();
        $divisi = $this->session->userdata('id_divisi');

        $satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_kompartemen='$divisi'")->result_array();

		$tingkatan = $this->session->userdata('is_tingkatan');
		$get_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan='$tingkatan' AND id_divisi='$divisi' AND jabatan='kepala'")->row_array();
		$id_ = $get_user['id_user'];
        
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('is_tingkatan',4);
        $this->db->where('id_user != ', $id);
        $this->db->where('jabatan','kepala');
        $this->db->where('id_user != ', $id_);
        // $this->db->where('id_divisi', 4);
        
        // foreach($satker as $a){
        //     $this->db->where('id_divisi', $a['id_satuan_kerja']);
        // }
        
        return $this->db->get()->result_array();
    }

    function edit_data(){
        $data = [
            "no_agenda" => $this->input->post('nomer_agenda',TRUE),
            "isi_disposisi_notdis" => $this->input->post('isi_disposisi',TRUE)
        ];

        $this->db->where('id_disposisi_notdis',$this->input->post('id',TRUE));
        $this->db->update('disposisi_notdis',$data);
    }

}