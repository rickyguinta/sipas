<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_nota_dinas_keluar_model extends CI_Model {

    function tampilData(){
        $id = $this->session->userdata('id');

        $this->db->distinct();
        $this->db->select('*,b.id_divisi as id_divisi_dari, b.is_tingkatan as is_tingkatan_dari, b.jabatan as jabatan_dari');
        $this->db->from('surat_nota_dinas');
        $this->db->join('user as b','surat_nota_dinas.dari=b.id_user');
        $this->db->where('surat_nota_dinas.id_pengirim_awal',$id);
        $this->db->order_by("surat_nota_dinas.id_surat_notdis","desc");

        return $this->db->get()->result_array();
    }

    function tampil_persetujuan($id_notdis){
        $id_penerima = $this->session->userdata('id');

        $this->db->select('*, b.jabatan as jabatan_pengirim, c.jabatan as jabatan_penerima');
        $this->db->from('persetujuan_notdis');
        $this->db->join('user as b','persetujuan_notdis.id_pengirim=b.id_user');
        $this->db->join('user as c','persetujuan_notdis.id_penerima=c.id_user');
        $this->db->where('persetujuan_notdis.id_surat_notdis',$id_notdis);

        return $this->db->get()->result_array();
    }
    
    function getUser(){
        $id = $this->session->userdata('id');

        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('level',0);
        $this->db->where("id_user != '$id'");

        return $this->db->get()->result_array();
    }

    function tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $dok, $status_surat,$qr_code,$key,$id_surat){
        date_default_timezone_set("Asia/Jakarta");
        $id_pengirim = $this->session->userdata('id');

        $strAwal =  $this->input->post('rujukan',TRUE);
        // $strAkhir = str_replace(['<ul>', '<ol type="a">'], ['</ul>', '<ol>'], $strAwal);
        $strAkhir = str_replace('<ol>','<ol type="a">',$strAwal);

         // insert ke tabel surat nota dinas
         $data = [
            "id_surat_notdis" => $id_surat,
            "dari" => $dari,
            "nomer_surat_notdis" => $nomer_surat,
            "perihal_notdis" => $this->input->post('perihal',TRUE),
            "rujukan_notdis" => $strAkhir,
            "tanggal_surat_notdis" => $tanggal_surat,
            "atas_surat_notdis" => $atas_surat,
            "nama_pegawai_surat_notdis" => $nama_pegawai,
            "pangkat_nrp_surat_notdis" =>  $pangkat_nrp,
            "id_pengirim_awal" => $id_pengirim,
            "nama_dokumen" => $dok,
            "status_surat" => $status_surat,
            "qr_code" => $qr_code,
            "key_surat" => $key
        ];

        $this->db->insert('surat_nota_dinas',$data);
    }

    function get_dt_notdis($id){
        $this->db->select('*');
        $this->db->from('surat_nota_dinas');
        $this->db->where('id_surat_notdis',$id);
        
        return $this->db->get()->row_array();
    }
    
    function hapus_data(){
        $id = $this->input->post('id',TRUE);
        
        $this->db->where('id_surat_notdis',$id);
        $query = $this->db->get('surat_nota_dinas');
        $row = $query->row();
        
        unlink("./assets/images_barcode/$row->qr_code"); 

        if($row->nama_dokumen != '-'){
            unlink("./assets/dokumen_notdis/$row->nama_dokumen"); 
        }
   
        $this->db->delete('surat_nota_dinas', array('id_surat_notdis' => $id));

        // 
        // $id = $this->input->post('id',TRUE);
        // $this->db->delete('surat_nota_dinas',['id_surat_notdis' => $id]);
        
        $this->db->query("DELETE FROM tembusan_kirim WHERE id_surat='$id' AND jenis_surat=1");
        $this->db->query("DELETE FROM notifikasi_surat WHERE id_surat='$id' AND jenis_surat_notifikasi=1");
    }
    
}
