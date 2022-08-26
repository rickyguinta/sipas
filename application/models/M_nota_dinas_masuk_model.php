<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_nota_dinas_masuk_model extends CI_Model {

    function tampilData(){
        $id = $this->session->userdata('id');

        $this->db->distinct();
        $this->db->select('*,b.id_divisi as id_divisi_dari, b.is_tingkatan as is_tingkatan_dari, b.jabatan as jabatan_dari, c.kepada as kepada_notdis');
        $this->db->from('surat_nota_dinas');
        $this->db->join('kepada_surat_notdis as c','surat_nota_dinas.id_surat_notdis=c.id_surat_notdis');
        $this->db->join('user as b','surat_nota_dinas.dari=b.id_user');
        $this->db->join('user as d','c.kepada=d.id_user');
        $this->db->where('c.kepada',$id);
        $this->db->order_by("surat_nota_dinas.id_surat_notdis","desc");

        return $this->db->get()->result_array();
    }

    function edit_notdis($filenya){
        $id = $this->input->post('id');
        
        $strAwal =  $this->input->post('rujukan',TRUE);
        $strAkhir = str_replace('<ol>','<ol type="a">',$strAwal);

        if($filenya == '-'){
            $data = [
                "nomer_surat_notdis" => $this->input->post('nomer_surat',TRUE),
                "perihal_notdis" => $this->input->post('perihal',TRUE),
                "rujukan_notdis" => $strAkhir,
            ];
        }
        else{
            $data = [
                "nomer_surat_notdis" => $this->input->post('nomer_surat',TRUE),
                "perihal_notdis" => $this->input->post('perihal',TRUE),
                "rujukan_notdis" => $strAkhir,
                "nama_dokumen" => $filenya,
            ];
        }

        $this->db->where('id_surat_notdis',$this->input->post('id',TRUE));
        $this->db->update('surat_nota_dinas',$data);

        $this->db->query("DELETE FROM isi_surat_notdis WHERE id_surat_notdis='$id'");

        // kirim isi surat
        $cek2 = $_POST['surat'];

        if($cek2!=NULL){
            $i=0;
            $n = count( $_POST['surat'] );
            while($i<$n){
                $a = $_POST['surat'][$i];
                    $data_isi_surat = [
                        "id_surat_notdis" => $id,
                        "isi_notdis" => $_POST['surat'][$i],
                    ];
                    $this->db->insert('isi_surat_notdis',$data_isi_surat);
                    $i++;
            }
        }
    }

}