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
              <span class="d-ib">HISTORY DISPOSISI</span>
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
                  <strong>History Disposisi</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-colreorder-2" class="table table-hover table-striped table-bordered dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th>Nomer Agenda</th>
                        <th>Dari</th>
                        <th>Kepada</th>
                        <th>Tanggal Dikirim</th>
                        <th>Tanggal Dibaca</th>
                        <th width="20%">Aksi</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Nomer Agenda</th>
                        <th>Dari</th>
                        <th>Kepada</th>
                        <th>Tanggal Dikirim</th>
                        <th>Tanggal Dibaca</th>
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                    <tbody>
                    <?php $no=1; foreach($data as $d){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['no_agenda']; ?></td>
                        <td>
                        <?php  
                                $tingkatan1 = $d['is_tingkatan_dari'];
                                
                                if($tingkatan1 == 1){
                                    $idnya1 = $d['id_divisi_dari'];
                                    $divisi1 = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya1'")->row_array();
                                    echo strtoupper($d['jabatan_dari']). ' '; echo strtoupper($divisi1['nama_pimpinan']);
                                }
                                else if($tingkatan1 == 2){
                                    $idnya1 = $d['id_divisi_dari'];
                                    $divisi1 = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya1'")->row_array();
                                    echo strtoupper($d['jabatan_dari']). ' '; echo strtoupper($divisi1['nama_pelayanan']);
                                }
                                else if($tingkatan1 == 3){
                                    $idnya1 = $d['id_divisi_dari'];
                                    $divisi1 = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya1'")->row_array();
                                    echo strtoupper($d['jabatan_dari']). ' '; echo strtoupper($divisi1['nama_kompartemen']);
                                }
                                else if($tingkatan1 == 4){
                                    $idnya1 = $d['id_divisi_dari'];
                                    $divisi1 = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya1'")->row_array();
                                    echo strtoupper($d['jabatan_dari']). ' '; echo strtoupper($divisi1['nama_satuan_kerja']);
                                }
                                else if($tingkatan1 == 5){
                                    $idnya1 = $d['id_divisi_dari'];
                                    $divisi1 = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya1'")->row_array();
                                    echo strtoupper($d['jabatan_dari']). ' '; echo strtoupper($divisi1['nama_unit_kerja']);
                                }
                        ?>
                        </td>
                        <td>
                        <?php  
                                $tingkatan = $d['is_tingkatan_kepada'];
                                
                                if($tingkatan == 1){
                                    $idnya = $d['id_divisi_kepada'];
                                    $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_kepada']). ' '; echo strtoupper($divisi['nama_pimpinan']);
                                }
                                else if($tingkatan == 2){
                                    $idnya = $d['id_divisi_kepada'];
                                    $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_kepada']). ' '; echo strtoupper($divisi['nama_pelayanan']);
                                }
                                else if($tingkatan == 3){
                                    $idnya = $d['id_divisi_kepada'];
                                    $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_kepada']). ' '; echo strtoupper($divisi['nama_kompartemen']);
                                }
                                else if($tingkatan == 4){
                                    $idnya = $d['id_divisi_kepada'];
                                    $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_kepada']). ' '; echo strtoupper($divisi['nama_satuan_kerja']);
                                }
                                else if($tingkatan == 5){
                                    $idnya = $d['id_divisi_kepada'];
                                    $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan_kepada']). ' '; echo strtoupper($divisi['nama_unit_kerja']);
                                }
                        ?>
                        </td>
                        <td><?= $d['tgl_dikirim_disposisi']; ?></td>
                        <td><?= $d['tgl_dibaca_disposisi']; ?></td>
                        <td class="text-center">
                        <a class="badge badge-primary" href="<?= base_url(); ?>disposisi/in_detail/<?= $d['id_surat_notdis']; ?>"><span class="icon icon-eye"></span> Detail</a>  
                        <!-- <a class="badge badge-success" href="#modal-edit<?= $d['id_disposisi_notdis']; ?>" data-toggle="modal"><span class="icon icon-edit"></span> Edit</a> ||
                        <a class="badge badge-danger" href="#modal-hapus<?= $d['id_disposisi_notdis']; ?>" data-toggle="modal"><span class="icon icon-trash-o"></span> Hapus</a> -->
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
    <div id="modal-hapus<?= $x['id_disposisi_notdis'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Hapus Data Disposisi</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('history/hapus_data_disposisi') ?>
        <input type="hidden" value="<?= $x['id_disposisi_notdis']; ?>" name="id" class="form-control" >
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
    <div id="modal-edit<?= $x['id_disposisi_notdis'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Ubah Data Disposisi</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('History/edit_data_disposisi') ?>
        <input type="hidden" value="<?= $x['id_disposisi_notdis']; ?>" name="id" class="form-control" >
            <div class="form-group">
              <label class="control-label">Nomer Agenda</label>
              <input class="form-control" type="text" name="nomer_agenda" value="<?= $x['no_agenda']; ?>">
            </div>

            <div class="form-group">
              <label class="control-label">Isi Disposisi</label>
              <textarea name="isi_disposisi" id="" class="form-control" cols="30" rows="10"><?= $x['isi_disposisi_notdis']; ?></textarea>
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