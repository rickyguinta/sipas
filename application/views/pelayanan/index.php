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
              <span class="d-ib">DATA PELAYANAN</span>
            </h1>
          </div>
          <hr>
          <?php if($this->session->userdata('level') == 1){ ?>
          <div class="text-left m-b">
              <button class="btn btn-info" data-toggle="modal" data-target="#modalSignUpSm" type="button">(+) Tambah Data</button>
          </div>
          <?php } ?>
          <br>
          <div class="row gutter-xs">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <div class="card-actions">
                    <button type="button" class="card-action card-toggler" title="Collapse"></button>
                    <button type="button" class="card-action card-reload" title="Reload"></button>
                    <button type="button" class="card-action card-remove" title="Remove"></button>
                  </div>
                  <strong>Daftar Pelayanan</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-fixedheader-1" class="table table-hover table-striped dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="10%">No</th>
                        <th width="30%">Nama Pelayanan</th>
                        <th width="30%">Kepanjangan</th>
                        <?php if($this->session->userdata('level') == 1){ ?>
                        <th width="30%">Aksi</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Nama Pelayanan</th>
                        <th>Kepanjangan</th>
                        <?php if($this->session->userdata('level') == 1){ ?>
                        <th>Aksi</th>
                        <?php } ?>
                      </tr>
                    </tfoot>
                    <tbody>
                    <?php $no=1; foreach($data as $d){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['nama_pelayanan']; ?></td>
                        <td><?= $d['kepanjangan_pelayanan']; ?></td>
                        <?php if($this->session->userdata('level') == 1){ ?>
                        <td class="text-center">
                          <a class="badge badge-success" href="#modal-edit<?= $d['id_pelayanan']; ?>" data-toggle="modal"><span class="icon icon-edit"></span> Edit</a> ||
                          <a class="badge badge-danger" href="#modal-hapus<?= $d['id_pelayanan']; ?>" data-toggle="modal"><span class="icon icon-trash-o"></span> Hapus</a>
                        </td>
                        <?php } ?>
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

      <?php if($this->session->userdata('level') == 1){ ?>
    <!-- MODAL BOX TAMBAH DATA -->
    <div id="modalSignUpSm" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Tambah Data Pelayanan</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Pelayanan/tambah_data') ?>
            <div class="form-group">
              <label class="control-label">Nama Pelayanan</label>
              <input class="form-control" type="text" name="nama_pelayanan" required="">
            </div>

            <div class="form-group">
              <label class="control-label">Kepanjangan</label>
              <input class="form-control" type="text" name="kepanjangan" required="">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
          </form>
      </div>
    </div>
  </div>
    <!-- AKHIR MODAL BOX TAMBAH DATA -->


    <!-- MODAL BOX EDIT DATA -->
    <?php $no=0; foreach($data as $x): $no++; ?>
    <div id="modal-edit<?= $x['id_pelayanan'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Ubah Data Pelayanan</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Pelayanan/edit_data') ?>
        <input type="hidden" value="<?= $x['id_pelayanan']; ?>" name="id" class="form-control" >
            <div class="form-group">
              <label class="control-label">Nama Pelayanan</label>
              <input class="form-control" type="text" name="nama_pelayanan" value="<?= $x['nama_pelayanan'] ?>" required="">
            </div>

            <div class="form-group">
              <label class="control-label">Kepanjangan</label>
              <input class="form-control" type="text" name="kepanjangan" value="<?= $x['kepanjangan_pelayanan'] ?>" required="">
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


     <!-- MODAL BOX HAPUS DATA -->
     <?php $no=0; foreach($data as $x): $no++; ?>
    <div id="modal-hapus<?= $x['id_pelayanan'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Hapus Data Pelayanan</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Pelayanan/hapus_data') ?>
        <input type="hidden" value="<?= $x['id_pelayanan']; ?>" name="id" class="form-control" >
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

      <?php } ?>