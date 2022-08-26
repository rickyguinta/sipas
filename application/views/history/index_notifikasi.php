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
            <h1 class="title-bar-title text-white">
              <span class="d-ib">HISTORY NOTIFIKASI</span>
            </h1>
          </div>
          <hr>
          <div class="row gutter-xs">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <div class="card-actions">
                    <button type="button" class="card-action card-toggler" title="Collapse"></button>
                    <button type="button" class="card-action card-reload" title="Reload"></button>
                    <button type="button" class="card-action card-remove" title="Remove"></button>
                  </div>
                  <strong>History Notifikasi</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-colreorder-2" class="table table-hover table-striped table-bordered dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th>Dari</th>
                        <th>Kepada</th>
                        <th>Tanggal Dikirim</th>
                        <th>Tanggal Dibaca</th>
                        <th width="30%">Isi Notifikasi</th>
                        <th width="20%">Aksi</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Dari</th>
                        <th>Kepada</th>
                        <th>Tanggal Dikirim</th>
                        <th>Tanggal Dibaca</th>
                        <th>Isi Notifikasi</th>
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                    <tbody>
                    <?php $no=1; foreach($data as $d){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                        <?php  
                                $tingkatan = $d['is_tingkatan_pengirim'];
                                
                                if($tingkatan == 1){
                                    $idnya = $d['id_divisi_pengirim'];
                                    $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_pengirim']). ' '; echo strtoupper($divisi['nama_pimpinan']);
                                }
                                else if($tingkatan == 2){
                                    $idnya = $d['id_divisi_pengirim'];
                                    $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_pengirim']). ' '; echo strtoupper($divisi['nama_pelayanan']);
                                }
                                else if($tingkatan == 3){
                                    $idnya = $d['id_divisi_pengirim'];
                                    $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_pengirim']). ' '; echo strtoupper($divisi['nama_kompartemen']);
                                }
                                else if($tingkatan == 4){
                                    $idnya = $d['id_divisi_pengirim'];
                                    $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_pengirim']). ' '; echo strtoupper($divisi['nama_satuan_kerja']);
                                }
                                else if($tingkatan == 5){
                                    $idnya = $d['id_divisi_pengirim'];
                                    $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_pengirim']). ' '; echo strtoupper($divisi['nama_unit_kerja']);
                                }
                        ?>
                        </td>
                        <td>
                        <?php  
                                $tingkatan = $d['is_tingkatan_penerima'];
                                
                                if($tingkatan == 1){
                                    $idnya = $d['id_divisi_penerima'];
                                    $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_penerima']). ' '; echo strtoupper($divisi['nama_pimpinan']);
                                }
                                else if($tingkatan == 2){
                                    $idnya = $d['id_divisi_penerima'];
                                    $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_penerima']). ' '; echo strtoupper($divisi['nama_pelayanan']);
                                }
                                else if($tingkatan == 3){
                                    $idnya = $d['id_divisi_penerima'];
                                    $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_penerima']). ' '; echo strtoupper($divisi['nama_kompartemen']);
                                }
                                else if($tingkatan == 4){
                                    $idnya = $d['id_divisi_penerima'];
                                    $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_penerima']). ' '; echo strtoupper($divisi['nama_satuan_kerja']);
                                }
                                else if($tingkatan == 5){
                                    $idnya = $d['id_divisi_penerima'];
                                    $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_penerima']). ' '; echo strtoupper($divisi['nama_unit_kerja']);
                                }
                        ?>
                        </td>
                        <td><?= $d['tgl_notif_kirim']; ?></td>
                        <td><?= $d['tgl_notif_baca']; ?></td>
                        <td><?= $d['isi_notif']; ?></td>
                        <td class="text-center">
                        <a class="badge badge-primary" href="<?php if($d['jenis_surat_notifikasi'] == 1 ){ ?> <?= base_url(); ?>nota_dinas_keluar/in_detail/<?= $d['id_surat']; ?> <?php } ?>"><span class="icon icon-eye"></span> Detail</a>  
                        <!-- <a class="badge badge-success" href="#modal-edit<?= $d['id_notifikasi']; ?>" data-toggle="modal"><span class="icon icon-edit"></span> Edit</a> ||
                        <a class="badge badge-danger" href="#modal-hapus<?= $d['id_notifikasi']; ?>" data-toggle="modal"><span class="icon icon-trash-o"></span> Hapus</a> -->
                        </td>
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      
  <!-- MODAL BOX HAPUS DATA -->
  <?php $no=0; foreach($data as $x): $no++; ?>
    <div id="modal-hapus<?= $x['id_notifikasi'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Hapus Data Notifikasi Keluar</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('history/hapus_data_notifikasi') ?>
        <input type="hidden" value="<?= $x['id_notifikasi']; ?>" name="id" class="form-control" >
            <strong>Apakah Anda Yakin Akan Menghapus Data ?</strong>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Hapus</button>
        </div>
          </form>
      </div>
    </div>
  </div>
  <?php endforeach;?>
    <!-- AKHIR MODAL BOX HAPUS DATA -->

    <!-- MODAL BOX EDIT DATA -->
    <?php $no=0; foreach($data as $x): $no++; ?>
    <div id="modal-edit<?= $x['id_notifikasi'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Ubah Data Notifikasi</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('History/edit_data_notifikasi') ?>
        <input type="hidden" value="<?= $x['id_notifikasi']; ?>" name="id" class="form-control" >

            <div class="form-group">
              <label class="control-label">Isi Notifikasi</label>
              <textarea name="isi_notifikasi" id="" class="form-control" cols="30" rows="10"><?= $x['isi_notif']; ?></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
          </form>
      </div>
    </div>
  </div>
  <?php endforeach;?>
    <!-- AKHIR MODAL BOX EDIT DATA -->