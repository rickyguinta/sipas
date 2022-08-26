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
              <span class="d-ib">DATA UNIT KERJA</span>
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
                  <strong>Daftar Unit Kerja</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-fixedheader-1" class="table table-hover table-striped dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="10%">No</th>
                        <th>Satuan Kerja</th>
                        <th>Nama Unit Kerja</th>
                        <th>Kepanjangan</th>
                        <?php if($this->session->userdata('level') == 1){ ?>
                        <th width="25%">Aksi</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th width="5%">No</th>
                        <th width="20%">Satuan Kerja</th>
                        <th width="20%">Nama Unit Kerja</th>
                        <th width="30%">Kepanjangan</th>
                        <?php if($this->session->userdata('level') == 1){ ?>
                        <th width="25%">Aksi</th>
                        <?php } ?>
                      </tr>
                    </tfoot>
                    <tbody>
                    <?php $no=1; foreach($data as $d){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['nama_satuan_kerja']; ?></td>
                        <td><?= $d['nama_unit_kerja']; ?></td>
                        <td><?= $d['kepanjangan_unit_kerja']; ?></td>
                        <?php if($this->session->userdata('level') == 1){ ?>
                        <td class="text-center">
                          <a class="badge badge-success" href="#modal-edit<?= $d['id_unit_kerja']; ?>" data-toggle="modal"><span class="icon icon-edit"></span> Edit</a> ||
                          <a class="badge badge-danger" href="#modal-hapus<?= $d['id_unit_kerja']; ?>" data-toggle="modal"><span class="icon icon-trash-o"></span> Hapus</a>
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
          <h4 class="modal-title">Tambah Data Unit Kerja</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Unker/tambah_data') ?>
            <div class="form-group">
                <label for="kompartemen" class="form-control-label">Kompartemen *</label>
                <select name="kompartemen" id="kompartemen" class="form-control" required="">
                    <option value="">----- Pilih ------</option>
                    <?php foreach($kompartemen as $k){ ?>
                        <option value="<?= $k['id_kompartemen']; ?>"><?= strtoupper($k['nama_kompartemen']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="satker" class="form-control-label">Satker *</label>
                <select name="satker" id="satker" class="form-control" required="">
                    <option value="" style="display:none;">Silahkan pilih kompartemen terlebih dahulu</option>
                </select>
            </div>
            
            <div class="form-group">
              <label class="control-label">Nama Unit Kerja</label>
              <input class="form-control" id="nama_unit_kerja" type="text" name="nama_unit_kerja" required="">
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
    <div id="modal-edit<?= $x['id_unit_kerja'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Ubah Data Unit Kerja</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Unker/edit_data') ?>
        <input type="hidden" value="<?= $x['id_unit_kerja']; ?>" name="id" class="form-control" >

            <div class="form-group">
                <label for="kompartemen" class="form-control-label">Kompartemen *</label>
                <select name="kompartemen" id="edit_kompartemen" class="form-control" required="">
                    <?php foreach($kompartemen as $k){ ?>
                        <?php if($k['id_kompartemen'] == $x['id_kompartemen']){ ?>
                            <option value="<?= $k['id_kompartemen']; ?>" selected><?= strtoupper($k['nama_kompartemen']); ?></option>
                        <?php }else{?>
                            <option value="<?= $k['id_kompartemen']; ?>"><?= strtoupper($k['nama_kompartemen']); ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="satker" class="form-control-label">Satker *</label>
                <select name="satker" id="edit_satker" class="form-control" required="">
                    <?php foreach($satker as $s){ ?>
                        <?php if($s['id_satuan_kerja'] == $x['id_satuan_kerja']){ ?>
                            <option value="<?= $s['id_satuan_kerja']; ?>" selected><?= strtoupper($s['nama_satuan_kerja']); ?></option>
                        <?php }else{?>
                            <option value="<?= $s['id_satuan_kerja']; ?>"><?= strtoupper($s['nama_satuan_kerja']); ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
              <label class="control-label">Nama Unit Kerja</label>
              <input class="form-control" id="nama_unit_kerja" type="text" value="<?= $x['nama_unit_kerja']; ?>" name="nama_unit_kerja" required="">
            </div>

            <div class="form-group">
              <label class="control-label">Kepanjangan</label>
              <input class="form-control" type="text" value="<?= $x['kepanjangan_unit_kerja']; ?>" name="kepanjangan" required="">
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
    <div id="modal-hapus<?= $x['id_unit_kerja'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Hapus Data Unit Kerja</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Unker/hapus_data') ?>
        <input type="hidden" value="<?= $x['id_unit_kerja']; ?>" name="id" class="form-control" >
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


        <!-- AJAX SELECT DINAMIS -->
        <script type="text/javascript">
           $(function(){

            $.ajaxSetup({
            type:"POST",
            url: "<?php echo base_url('unker/ambil_data') ?>",
            cache: false,
            });

            $("#kompartemen").change(function(){

            var value=$(this).val();
            if(value>0){
            $.ajax({
            data:{modul:'satker',id:value},
            success: function(respond){
            $("#satker").html(respond);
            }
            })
            }

            });


            })

         </script>

         <!-- AJAX SELECT DINAMIS -->
        <!-- AJAX SELECT DINAMIS -->
        <script type="text/javascript">
           $(function(){

            $.ajaxSetup({
            type:"POST",
            url: "<?php echo base_url('unker/ambil_data') ?>",
            cache: false,
            });

            $("#edit_kompartemen").change(function(){

            var value=$(this).val();
            if(value>0){
            $.ajax({
            data:{modul:'satker',id:value},
            success: function(respond){
            $("#edit_satker").html(respond);
            }
            })
            }

            });


            })

        </script>
      <?php } ?>