<html lang="en" moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Cetak Persetujuan Nota Dinas</title>
    <meta charset="utf-8">
</head>
<body onload="window.print()">
<div id="laporan">
<table width="100%" height="100%">
    <tr>
        <td align="left" valign="top">
            <!--  -->
            <table width="100%">
                <tr>
                    <td colspan="4">
                        <table width="50%">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td align="center"><?= $atas_surat1; ?></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><div style="margin-top:-2px;"><?= $atas_surat2; ?></div> <hr style="border-top: 1px solid black; margin-top:-1px;"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table><br><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="center">
                        <table width="50%" align="center">
                            <tr>
                                <td colspan="3" align="center">
                                    <table>
                                        <tr>
                                            <td align="center">NOTA DINAS</td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-decoration: overline;">Nomor : <?= $data['nomer_surat_notdis']; ?><br><br></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="70" align="left" valign="top">Kepada</td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top">
                                <?php  
                                    $id = $persetujuan['id_penerima'];
                                    $get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id'")->row_array();

                                    $tingkatan = $get_user['is_tingkatan'];
                                        
                                        if($tingkatan == 1){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                            echo $divisi['nama_pimpinan'];
                                        }
                                        else if($tingkatan == 2){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                            echo $divisi['nama_pelayanan'];
                                        }
                                        else if($tingkatan == 3){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                            echo $divisi['nama_kompartemen'];
                                        }
                                        else if($tingkatan == 4){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                            echo $divisi['nama_satuan_kerja'];
                                        }
                                        else if($tingkatan == 5){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                            echo $divisi['nama_unit_kerja'];
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">Dari</td>
                                <td align="left" valign="top" >:</td>
                                <td align="left" valign="top">
                                <?php  
                                    $id = $persetujuan['id_pengirim'];
                                    $get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id'")->row_array();

                                    $tingkatan = $get_user['is_tingkatan'];
                                        
                                        if($tingkatan == 1){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                            echo $divisi['nama_pimpinan'];
                                        }
                                        else if($tingkatan == 2){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                            echo $divisi['nama_pelayanan'];
                                        }
                                        else if($tingkatan == 3){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                            echo $divisi['nama_kompartemen'];
                                        }
                                        else if($tingkatan == 4){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                            echo $divisi['nama_satuan_kerja'];
                                        }
                                        else if($tingkatan == 5){
                                            $idnya = $get_user['id_divisi'];
                                            $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                            echo $divisi['nama_unit_kerja'];
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">Perihal</td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top" align="justify"><?= $data['perihal_notdis']; ?> <hr style="border-top: 1px solid black;"></td>
                            </tr>
                        </table><br><br>
                    </td>
                </tr>
                <tr>
                    <td width="10" align="left" valign="top">1.</td>
                    <td align="left" valign="top"></td>
                    <td colspan="2" align="justify" valign="top">Rujukan :  <div style="margin-top:-10px; margin-left:-25px;"> <?= $data['rujukan_notdis']; ?> </div>
                    

                    <!-- <ol type="a" style="margin-left:-25px;">
                        <li style="margin-top:-10px;"><p style="text-align: justify;">awaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawa awaawawaawa</p></li>
                        <li style="margin-top:-10px;"><p style="text-align: justify;">awaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawaawaawawaawa awaawawaawa</p></li>

                    </ol><br> -->
                    </td>
                </tr>
                <?php  
                $id_notdis = $data['id_surat_notdis'];
                $get_isi = $this->db->query("SELECT * FROM isi_surat_notdis WHERE id_surat_notdis='$id_notdis'")->result_array();
                $no=2; foreach($get_isi as $x){
                ?>
                <tr>
                    <td width="10" align="left" valign="top" ><?= $no++; ?>.</td>
                    <td align="left" valign="top"></td>
                    <td colspan="2" align="left" valign="top"><div style="text-align: justify;"><?= $x['isi_notdis']; ?></div> <br>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"></td>
                    <td><br>
                        <table  style="margin-left:370px;">
                            <tr>
                                <td align="center">Semarang, <?php  
                                $t =  strtotime($persetujuan['tanggal_dikirim']); echo date('d',$t); 
                                echo ' '. $nama_bulan .' ' . date('Y',$t);
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><?= $persetujuan['atas_surat_persetujuan_notdis']; ?> <br><br><br><br></td>
                            </tr>
                            <tr>
                                <td align="center"><?= $persetujuan['nama_pegawai_persetujuan_notdis']; ?></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: overline;"><?= $persetujuan['pangkat_nrp_persetujuan_notdis']; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><div style="margin-top:-35px;"> Tembusan : </div></td>
                </tr>
                <tr>
                    <td colspan="4">
                    <table cellpadding="0" style="margin-top:-15px;">
                        <tr>
                            <td>
                            <?php 
                            $get_tembusan = $this->db->query("SELECT * FROM tembusan_kirim WHERE id_surat='$id_notdis'")->result_array();
                            $no1 = 1; foreach($get_tembusan as $a){
                            ?>
                            <?= $no1++; ?>.
                            <?php  
                                $id2 = $a['id_penerima_tembusan'];
                                $get_user2 = $this->db->query("SELECT * FROM user WHERE id_user='$id2'")->row_array();

                                $tingkatan2 = $get_user2['is_tingkatan'];
                                    
                                    if($tingkatan2 == 1){
                                        $idnya2 = $get_user2['id_divisi'];
                                        $divisi2 = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya2'")->row_array();
                                        echo $divisi2['nama_pimpinan'];
                                    }
                                    else if($tingkatan2 == 2){
                                        $idnya2 = $get_user2['id_divisi'];
                                        $divisi2 = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya2'")->row_array();
                                        echo $divisi2['nama_pelayanan'];
                                    }
                                    else if($tingkatan2 == 3){
                                        $idnya2 = $get_user2['id_divisi'];
                                        $divisi2 = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya2'")->row_array();
                                        echo $divisi2['nama_kompartemen'];
                                    }
                                    else if($tingkatan2 == 4){
                                        $idnya2 = $get_user2['id_divisi'];
                                        $divisi2 = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya2'")->row_array();
                                        echo $divisi2['nama_satuan_kerja'];
                                    }
                                    else if($tingkatan2 == 5){
                                        $idnya2 = $get_user2['id_divisi'];
                                        $divisi2 = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya2'")->row_array();
                                        echo $divisi2['nama_unit_kerja'];
                                    }
                            ?> 
                            <br>
                            <?php } ?>
                            <hr style="border-top: 1px solid black; margin-top:-0px;">
                            </td>
                        </tr>
                        
                    </table>
                    </td>
                </tr>
                
            </table>
            <!--  -->
        </td>
    </tr>
    <tr>
        <td align="right" valign="bottom"><img src="<?= base_url(); ?>assets/images_barcode/<?= $data['qr_code']; ?>" alt="" width="90" height="90" style="margin-bottom:-11px;"> </td>  
    </tr>
</table>

</div>
</body>
</html>