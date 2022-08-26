<div class="layout-content">
        <div class="layout-content-body">
        <!-- FLASH DATA -->    
      <?php 
      $dat = $this->session->flashdata('msg');
          if($dat!=""){ ?>
                <div id="notifikasi" class="alert alert-success"><strong>Sukses! </strong> <?=$dat;?></div>
      <?php } ?>  
      <!-- AKHIR FLASH DATA -->
          <div class="title-bar">
            <h1 class="title-bar-title">
              <span class="d-ib">DETAIL DISPOSISI</span>
            </h1>
          </div>
          <hr>
        <!-- ISI -->
        <div class="row gutter-xs">
            <div class="col-md-6 col-lg-12 col-lg-push-0">
              <div class="card">
                <div class="card-header">
                  <strong>DISPOSISI | <a href="<?= base_url(); ?>disposisi/cetak_disposisi/<?= $disposisi['id_disposisi_notdis']; ?>" target="_blank">Cetak</a></strong>
                </div>
                <div class="card-body">
                <!--  -->
                <!-- tabel -->
                <center><h4>DETAIL DISPOSISI</h4></center>
                <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th class="text-left" width="25%">Nomer Agenda</th>
                        <th width="2%">:</th>
                        <th class="text-left"><?= $disposisi['no_agenda']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">Pengirim Disposisi</td>
                        <td>:</td>
                        <td class="text-left">
                            <?php  
                              $id = $disposisi['id_pengirim_disposisi'];
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
                        <td class="text-left">Penerima Disposisi</td>
                        <td>:</td>
                        <td class="text-left">
                          <ul style="margin-left:-20px;">
                            <?php 
                              $id_notdis = $disposisi['id_surat_notdis'];
                              $get_kepada = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_surat_notdis='$id_notdis'")->result_array();
                                
                              foreach($get_kepada as $l){
                            ?>
                            <li>
                            <?php  
                              $id2 = $l['id_penerima_disposisi'];
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

                            </li>
                            <?php } ?>
                          </ul>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-left">Tanggal Dikirim Disposisi</td>
                        <td>:</td>
                        <td class="text-left"><?= $disposisi['tgl_dikirim_disposisi']; ?></td>
                      </tr>
                      <tr>
                        <td class="text-left" height="100">Isi Disposisi</td>
                        <td>:</td>
                        <td class="text-left"><?= $disposisi['isi_disposisi_notdis']; ?></td>
                      </tr>
                    </tbody>
                  </table>
            <br>
            <hr><br>
            <center><h4>DETAIL SURAT</h4></center>
            <strong style="text-decoration:underline;">DETAIL SURAT | <a href="<?= base_url(); ?>nota_dinas_masuk/cetak_notdis/<?= $data['id_surat_notdis']; ?>" target="_blank">Cetak</a></strong><br>
                <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th class="text-left" width="25%">Nomer Surat</th>
                        <th width="2%">:</th>
                        <th class="text-left"><?= $data['nomer_surat_notdis']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">Pengirim Surat</td>
                        <td>:</td>
                        <td class="text-left">
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
                        <td class="text-left">Penerima Surat</td>
                        <td>:</td>
                        <td class="text-left">
                          <ul style="margin-left:-20px;">
                            <?php 
                              $id_notdis = $data['id_surat_notdis'];
                              $get_kepada = $this->db->query("SELECT * FROM kepada_surat_notdis WHERE id_surat_notdis='$id_notdis'")->result_array();
                                
                              foreach($get_kepada as $l){
                            ?>
                            <li>
                            <?php  
                              $id2 = $l['kepada'];
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

                            </li>
                            <?php } ?>
                          </ul>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-left">Tanggal Surat</td>
                        <td>:</td>
                        <td class="text-left"><?= $data['tanggal_surat_notdis']; ?></td>
                      </tr>
                      <tr>
                        <td class="text-left" height="100">Perihal</td>
                        <td>:</td>
                        <td class="text-left"><?= $data['perihal_notdis']; ?></td>
                      </tr>
                      <tr>
                        <td class="text-left">Rujukan</td>
                        <td>:</td>
                        <td class="text-left">
                        <?php 
                        $get_isi_surat = $this->db->query("SELECT * FROM isi_surat_notdis WHERE id_surat_notdis='$id_notdis'")->result_array();
                        
                        foreach($get_isi_surat as $s){
                          echo $s['isi_notdis'];
                        }
                        ?>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-left">File</td>
                        <td>:</td>
                      <td class="text-left"><?php if($data['nama_dokumen']!='-'){ ?> <a href="<?= base_url(); ?>assets/dokumen_notdis/<?= $data['nama_dokumen']; ?>"> download </a> <?php } ?></td>
                      </tr>
                    </tbody>
                  </table>
                  
                  <?php  
                    foreach($get_kepada as $ll){
                      if($ll['kepada'] == $this->session->userdata('id')){
                  ?>
                  <br>
                 <?php } }?>

                <!-- akhir tabel -->
                
                <br><br><hr>
                <center>
                <h4>Pengajuan hingga penetapan surat tersebut melalui beberapa proses, antara lain sebagai berikut : </h4>
                </center><hr>
                <!-- tabel persetujuan -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Pengirim</th>
                          <th>Penerima</th>
                          <th>Proses</th>
                          <th>Waktu</th>
                          <th>File</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; foreach($persetujuan_notdis as $l){ ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?= strtoupper($l['jabatan_pengirim']); ?> | 
                            <?php
                            
                            $id_pengirim = $l['id_pengirim']; 
                            $get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_pengirim'")->row_array();
                            $id_divisi = $get_user['id_divisi'];

                            if($get_user['is_tingkatan'] == 1){
                              $divisi = $this->db->query("SELECT * FROM pimpinan WHERE id_pimpinan='$id_divisi'")->row_array();
                              echo strtoupper($divisi['nama_pimpinan']);
                            }
                            else if($get_user['is_tingkatan'] == 2){
                              $divisi = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan='$id_divisi'")->row_array();
                              echo strtoupper($divisi['nama_pelayanan']);
                            }
                            else if($get_user['is_tingkatan'] == 3){
                              $divisi = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_divisi'")->row_array();
                              echo strtoupper($divisi['nama_kompartemen']);
                            }
                            else if($get_user['is_tingkatan'] == 4){
                              $divisi = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi'")->row_array();
                              echo strtoupper($divisi['nama_satuan_kerja']);
                            }
                            else if($get_user['is_tingkatan'] == 5){
                              $divisi = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
                              echo strtoupper($divisi['nama_unit_kerja']);
                            }
                            ?>
                          
                          </td>
                          <td><?=  strtoupper($l['jabatan_penerima']); ?> | 
                            <?php
                              
                              $id_penerima = $l['id_penerima']; 
                              $get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_penerima'")->row_array();
                              $id_divisi = $get_user['id_divisi'];

                              if($get_user['is_tingkatan'] == 1){
                                $divisi = $this->db->query("SELECT * FROM pimpinan WHERE id_pimpinan='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_pimpinan']);
                              }
                              else if($get_user['is_tingkatan'] == 2){
                                $divisi = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_pelayanan']);
                              }
                              else if($get_user['is_tingkatan'] == 3){
                                $divisi = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_kompartemen']);
                              }
                              else if($get_user['is_tingkatan'] == 4){
                                $divisi = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_satuan_kerja']);
                              }
                              else if($get_user['is_tingkatan'] == 5){
                                $divisi = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_unit_kerja']);
                              }
                              ?>
                          </td>
                          <td><?= strtoupper($l['status_persetujuan_notdis']); ?></td>
                          <td><?= $l['tanggal_diacc']; ?></td>
                          <td class="text-center"><a href="<?= base_url(); ?>nota_dinas_masuk/cetak_persetujuan/<?= $l['id_persetujuan_notdis']; ?>">download</a> </td>
                        </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>

                <!-- tabel persetujuan -->
                <!--  -->
                </div>
              </div>
        </div>

        </div>
      </div>