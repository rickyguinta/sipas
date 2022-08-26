<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_History extends CI_Model {

    function tampil_history_disposisi(){
        $this->db->select('*,b.id_divisi as id_divisi_kepada, b.is_tingkatan as is_tingkatan_kepada, b.jabatan as jabatan_kepada, c.id_divisi as id_divisi_dari, c.is_tingkatan as is_tingkatan_dari, c.jabatan as jabatan_dari');
        $this->db->from('disposisi_notdis');
        $this->db->join('user as b','disposisi_notdis.id_penerima_disposisi=b.id_user');
        $this->db->join('user as c','disposisi_notdis.id_pengirim_disposisi=c.id_user');
        $this->db->order_by("disposisi_notdis.id_disposisi_notdis","desc");

        return $this->db->get()->result_array();
    }

    function tampil_history_tembusan(){
        $this->db->select('*,b.id_divisi as id_divisi_kepada, b.is_tingkatan as is_tingkatan_kepada, b.jabatan as jabatan_kepada, c.id_divisi as id_divisi_dari, c.is_tingkatan as is_tingkatan_dari, c.jabatan as jabatan_dari');
        $this->db->from('tembusan_kirim');
        $this->db->join('user as b','tembusan_kirim.id_penerima_tembusan=b.id_user');
        $this->db->join('user as c','tembusan_kirim.id_pengirim_tembusan=c.id_user');
        $this->db->order_by("tembusan_kirim.id_tembusan_kirim","desc");

        return $this->db->get()->result_array();
    }

    function tampil_history_notifikasi(){

        $this->db->select('*, a.id_user as id_pengirim,a.is_tingkatan as is_tingkatan_pengirim ,a.id_divisi as id_divisi_pengirim, a.jabatan as jabatan_pengirim , b.id_user as id_penerima, b.is_tingkatan as is_tingkatan_penerima,b.id_divisi as id_divisi_penerima, b.jabatan as jabatan_penerima');
        $this->db->from('notifikasi_surat');
        $this->db->join('user as a','notifikasi_surat.id_pengirim_notif=a.id_user');
        $this->db->join('user as b','notifikasi_surat.id_penerima_notif=b.id_user');
        $this->db->order_by("notifikasi_surat.id_notifikasi","desc");

        return $this->db->get()->result_array();
    }

    function tampil_history_notdis(){
        $this->db->distinct();
        $this->db->select('*,b.id_divisi as id_divisi_dari, b.is_tingkatan as is_tingkatan_dari, b.jabatan as jabatan_dari');
        $this->db->from('surat_nota_dinas');
        $this->db->join('user as b','surat_nota_dinas.dari=b.id_user');
        $this->db->order_by("surat_nota_dinas.id_surat_notdis","desc");

        return $this->db->get()->result_array();
    }

    function tampil_persetujuan(){
        
        $this->db->select('*,a.id_user as id_pengirim,a.is_tingkatan as is_tingkatan_pengirim ,a.id_divisi as id_divisi_pengirim, a.jabatan as jabatan_pengirim , b.id_user as id_penerima, b.is_tingkatan as is_tingkatan_penerima,b.id_divisi as id_divisi_penerima, b.jabatan as jabatan_penerima');
        $this->db->from('persetujuan_notdis');
        $this->db->join('user as a','persetujuan_notdis.id_pengirim=a.id_user');
        $this->db->join('user as b','persetujuan_notdis.id_penerima=b.id_user');
        $this->db->where('is_read',1);
        $this->db->order_by("id_persetujuan_notdis","desc");

        return $this->db->get()->result_array();
    }

}