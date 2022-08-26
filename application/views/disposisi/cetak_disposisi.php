<html lang="en" moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Cetak Disposisi</title>
    <meta charset="utf-8">
</head>
<body onload="window.print()">
<div id="laporan">
<table width="100%" height="100%">
    <tr>
        <td align="left" valign="top"> 
        <table width="100%">
        <tr>
            <td width="55%" align="left">
            <table>
                <tr>
                    <td align="center"><?= $atas_surat1; ?></td>
                </tr>
                <tr>
                    <td align="center"><div style="margin-top:-2px;"><?= $atas_surat2; ?></div> <hr style="border-top: 1px solid black; margin-top:-1px;"></td>
                </tr>
            </table>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" align="right">
                <table>
                    <tr>
                        <td>KLASIFIKASI</td>
                        <td>:</td>
                        <td>BIASA</td>
                    </tr>
                    <tr>
                        <td>DERAJAT</td>
                        <td>:</td>
                        <td>BIASA</td>
                    </tr>
                </table><br><br>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="text-decoration:underline;"><strong>LEMBAR DISPOSISI SURAT / FAKSIMILI</strong><br><br></td>
        </tr>
        <tr>
            <td align="left" valign="top">NO. AGENDA : <?= $data['no_agenda']; ?></td>
            <td colspan="2" align="left" valign="top">DITERIMA TANGGAL : <?php if($data['tgl_dibaca_disposisi'] != '0000-00-00 00:00:00'){ echo $waktu; }?><br><br></td>
        </tr>
        <tr>
            <td colspan="3" align="center">
            <table border="1" width="100%" cellspacing="0" height="100%">
                    <tr>
                        <td align="center" width="50%">CATATAN TAUD</td>
                        <td align="center" width="50%">DISPOSISI</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" height="100%">
                                <tr>
                                    <td width="40%" align="left" valign="top"><br>KEPADA YTH</td>
                                    <td align="left" valign="top"><br>:</td>
                                    <td align="left" valign="top"><br>
                                    <?php $id_notdis = $notdis['id_surat_notdis']; 
                                        $get_kepada = $this->db->query("SELECT * FROM kepada_surat_notdis WHERE id_surat_notdis='$id_notdis'")->result_array();
                                        $no_kep= 1; foreach($get_kepada as $l){
                                    ?>
                                     <?php  
                                        $id2 = $l['kepada'];
                                        $get_user2 = $this->db->query("SELECT * FROM user WHERE id_user='$id2'")->row_array();

                                        $tingkatan2 = $get_user2['is_tingkatan'];
                                            
                                            if($tingkatan2 == 1){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya2'")->row_array();
                                                if($no_kep>1){ echo  $no_kep.'. '. strtoupper($divisi2['nama_pimpinan']);}else{ echo strtoupper($divisi2['nama_pimpinan']); }
                                            }
                                            else if($tingkatan2 == 2){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya2'")->row_array();
                                                if($no_kep>1){echo $no_kep.'. '. strtoupper($divisi2['nama_pelayanan']);}else{ echo strtoupper($divisi2['nama_pelayanan']);}
                                            }
                                            else if($tingkatan2 == 3){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya2'")->row_array();
                                                if($no_kep>1){ echo $no_kep.'. '.  strtoupper($divisi2['nama_kompartemen']);}else{ echo strtoupper($divisi2['nama_kompartemen']);}
                                            }
                                            else if($tingkatan2 == 4){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya2'")->row_array();
                                                echo $no_kep.'. '.  strtoupper($divisi2['nama_satuan_kerja']);
                                            }
                                            else if($tingkatan2 == 5){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya2'")->row_array();
                                                echo $no_kep.'. '.  strtoupper($divisi2['nama_unit_kerja']);
                                            }
                                        ?>
                                        <br>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">SURAT DARI</td>
                                    <td align="left" valign="top">:</td>
                                    <td align="left" valign="top">
                                    <?php  
                                    $id = $notdis['dari'];
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
                                    <td align="left" valign="top">NOMOR</td>
                                    <td align="left" valign="top">:</td>
                                    <td align="left" valign="top"><?= $notdis['nomer_surat_notdis']; ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">TANGGAL</td>
                                    <td align="left" valign="top">:</td>
                                    <td align="left" valign="top">
                                    <?php 
                                    $tampung_waktu = strtotime($notdis['tanggal_surat_notdis']);
                                    $fix_waktu = date('d',$tampung_waktu).'/'.date('m',$tampung_waktu).'/20'.date('y',$tampung_waktu);
                                    
                                    echo $fix_waktu;
                                     
                                    
                                    
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">PERIHAL</td>
                                    <td align="left" valign="top">:</td>
                                    <td align="left" valign="top" style="text-align:justify;">
                                    <?= $notdis['perihal_notdis']; ?> <br><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="bottom">
                                        <table border="1" width="100%" height="100%" cellspacing="0">
                                            <tr>
                                                <td colspan="3" align="center">DITERUSKAN</td>

                                            </tr>
                                            <tr>    
                                                <td width="50%">KEPADA YTH : </td>
                                                <td width="25%" align="center">TANGGAL</td>
                                                <td width="25%" align="center">PARAF</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                <?php 
                                                    $disposisi_all = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_surat_notdis='$id_notdis'")->result_array();
                                                    $no = 1; foreach($disposisi_all as $da){
                                                ?>
                                                <?= $no++; ?>. 
                                                <?php  
                                                    $id2 = $da['id_penerima_disposisi'];
                                                    $get_user2 = $this->db->query("SELECT * FROM user WHERE id_user='$id2'")->row_array();

                                                    $tingkatan2 = $get_user2['is_tingkatan'];
                                                        
                                                        if($tingkatan2 == 1){
                                                            $idnya2 = $get_user2['id_divisi'];
                                                            $divisi2 = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya2'")->row_array();
                                                            echo strtoupper($divisi2['nama_pimpinan']);
                                                        }
                                                        else if($tingkatan2 == 2){
                                                            $idnya2 = $get_user2['id_divisi'];
                                                            $divisi2 = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya2'")->row_array();
                                                            echo strtoupper($divisi2['nama_pelayanan']);
                                                        }
                                                        else if($tingkatan2 == 3){
                                                            $idnya2 = $get_user2['id_divisi'];
                                                            $divisi2 = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya2'")->row_array();
                                                            echo strtoupper($divisi2['nama_kompartemen']);
                                                        }
                                                        else if($tingkatan2 == 4){
                                                            $idnya2 = $get_user2['id_divisi'];
                                                            $divisi2 = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya2'")->row_array();
                                                            echo strtoupper($divisi2['nama_satuan_kerja']);
                                                        }
                                                        else if($tingkatan2 == 5){
                                                            $idnya2 = $get_user2['id_divisi'];
                                                            $divisi2 = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya2'")->row_array();
                                                            echo strtoupper($divisi2['nama_unit_kerja']);
                                                        }
                                                ?>

                                                 <br>
                                                <?php } ?>
                                                
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="left" valign="top">
                            <?php  
                               $id_pengirimnya = $data['id_pengirim_disposisi'];
                               $get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_pengirimnya'")->row_array();
                               $tingkatannya = $get_user['is_tingkatan'];

                            ?>
                            <table width="100%">
                                <tr height="290px;">
                                    <td align="left" valign="top">awa</td>
                                </tr>
                            <?php if($tingkatan == 1){ ?> 
                                <tr>
                                    <td align="left" valign="bottom">
                                    <table width="100%" border="1" cellspacing="0">
                                        <tr>
                                            <td colspan="3" align="center">SPRI</td>
                                        </tr>
                                        <tr>
                                            <td width="40%" align="left" valign="top">DITERIMA TGL</td>
                                            <td align="left" valign="top">:</td>
                                            <td align="left" valign="top"></td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">NO AGENDA </td>
                                            <td align="left" valign="top" width="10px;">:</td>
                                            <td align="left" valign="top"></td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                            <?php } ?>
                            </table>
                               
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

        </td>
    </tr>
    <tr>
        <td align="right" valign="bottom">
        <?php 
            $id = $data['id_surat_notdis'];
            $get_notdis = $this->db->query("SELECT * FROM surat_nota_dinas WHERE id_surat_notdis='$id'")->row_array();
        ?>
        <img src="<?= base_url(); ?>assets/images_barcode/<?= $get_notdis['qr_code']; ?>" alt="" width="90" height="90"> </td>
    </tr>
</table>
    
</div>
</body>
</html>