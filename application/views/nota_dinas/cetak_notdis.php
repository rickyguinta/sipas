<html lang="en" moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Cetak Nota Dinas</title>
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
                                <td align="justify" valign="top">Yth. 
                                <?php $id_notdis = $data['id_surat_notdis']; 
                                        $get_kepada = $this->db->query("SELECT * FROM kepada_surat_notdis WHERE id_surat_notdis='$id_notdis'")->result_array();
                                        $cek_jml = $this->db->query("SELECT * FROM kepada_surat_notdis WHERE id_surat_notdis='$id_notdis'")->num_rows();
                                        $no_kep= 1; foreach($get_kepada as $l){
                                    ?>
                                     <?php  
                                        $id2 = $l['kepada'];
                                        $get_user2 = $this->db->query("SELECT * FROM user WHERE id_user='$id2'")->row_array();

                                        $tingkatan2 = $get_user2['is_tingkatan'];

                                            if($tingkatan2 == 1){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya2'")->row_array();

                                                if($cek_jml == 1){
                                                    echo $divisi2['nama_pimpinan'];
                                                }
                                                else if($cek_jml == 2){
                                                    if($no_kep == 1){
                                                        echo  $divisi2['nama_pimpinan']; echo ' dan ';
                                                    }
                                                    else if($no_kep == 2){
                                                        echo  $divisi2['nama_pimpinan']; 
                                                    }
                                                }
                                                else{
                                                    if($cek_jml == $no_kep){
                                                        echo ' dan '; echo  $divisi2['nama_pimpinan']; 
                                                    }
                                                    else{
                                                        echo  $divisi2['nama_pimpinan']; echo ', ';
                                                    }
                                                }
                                                
                                            }
                                            else if($tingkatan2 == 2){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya2'")->row_array();

                                                if($cek_jml == 1){
                                                    echo $divisi2['nama_pelayanan'];
                                                }
                                                else if($cek_jml == 2){
                                                    if($no_kep == 1){
                                                        echo  $divisi2['nama_pelayanan']; echo ' dan ';
                                                    }
                                                    else if($no_kep == 2){
                                                        echo  $divisi2['nama_pelayanan']; 
                                                    }
                                                }
                                                else{
                                                    if($cek_jml == $no_kep){
                                                        echo ' dan '; echo  $divisi2['nama_pelayanan']; 
                                                    }
                                                    else{
                                                        echo  $divisi2['nama_pelayanan']; echo ', ';
                                                    }
                                                }
                                            }
                                            else if($tingkatan2 == 3){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya2'")->row_array();

                                                if($cek_jml == 1){
                                                    echo $divisi2['nama_kompartemen'];
                                                }
                                                else if($cek_jml == 2){
                                                    if($no_kep == 1){
                                                        echo  $divisi2['nama_kompartemen']; echo ' dan ';
                                                    }
                                                    else if($no_kep == 2){
                                                        echo  $divisi2['nama_kompartemen']; 
                                                    }
                                                }
                                                else{
                                                    if($cek_jml == $no_kep){
                                                        echo ' dan '; echo  $divisi2['nama_kompartemen']; 
                                                    }
                                                    else{
                                                        echo  $divisi2['nama_kompartemen']; echo ', ';
                                                    }
                                                }
                                            }
                                            else if($tingkatan2 == 4){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya2'")->row_array();
                                                
                                                if($cek_jml == 1){
                                                    echo $divisi2['nama_satuan_kerja'];
                                                }
                                                else if($cek_jml == 2){
                                                    if($no_kep == 1){
                                                        echo  $divisi2['nama_satuan_kerja']; echo ' dan ';
                                                    }
                                                    else if($no_kep == 2){
                                                        echo  $divisi2['nama_satuan_kerja']; 
                                                    }
                                                }
                                                else{
                                                    if($cek_jml == $no_kep){
                                                        echo ' dan '; echo  $divisi2['nama_satuan_kerja']; 
                                                    }
                                                    else{
                                                        echo  $divisi2['nama_satuan_kerja']; echo ', ';
                                                    }
                                                }
                                                
                                                
                                            }
                                            else if($tingkatan2 == 5){
                                                $idnya2 = $get_user2['id_divisi'];
                                                $divisi2 = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya2'")->row_array();
                                                
                                                if($cek_jml == 1){
                                                    echo $divisi2['nama_unit_kerja'];
                                                }
                                                else if($cek_jml == 2){
                                                    if($no_kep == 1){
                                                        echo  $divisi2['nama_unit_kerja']; echo ' dan ';
                                                    }
                                                    else if($no_kep == 2){
                                                        echo  $divisi2['nama_unit_kerja']; 
                                                    }
                                                }
                                                else{
                                                    if($cek_jml == $no_kep){
                                                        echo ' dan '; echo  $divisi2['nama_unit_kerja']; 
                                                    }
                                                    else{
                                                        echo  $divisi2['nama_unit_kerja']; echo ', ';
                                                    }
                                                }
                                            
                                            }
                                    ?>

                                        <?php $no_kep++; } ?>
                                        
                                        <br>
             
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">Dari</td>
                                <td align="left" valign="top" >:</td>
                                <td align="left" valign="top">
                                <?php  
                                    $id = $data['dari'];
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
                                <td valign="top" align="justify"><?= $data['perihal_notdis']; ?> <hr style="border-top: 1px solid black;"></td>
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
                                <td align="center">Semarang, <?php if($data['tanggal_surat_notdis'] != '0000-00-00 00:00:00'){  
                                $t =  strtotime($data['tanggal_surat_notdis']); echo date('d',$t); 
                                echo ' '. $nama_bulan .' ' . date('Y',$t); }
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><?= $data['atas_surat_notdis']; ?> <br><br><br><br></td>
                            </tr>
                            <tr>
                                <td align="center"><?= $data['nama_pegawai_surat_notdis']; ?> <hr style="border-top: 1px solid black; margin-top:-1px; margin-bottom:-1px;"> </td>
                            </tr>
                            <tr>
                                <td align="center"><?= $data['pangkat_nrp_surat_notdis']; ?></td>
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
                                    echo '<br>';
                                }?> 
                           
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