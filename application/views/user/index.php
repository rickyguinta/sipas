<div class="layout-content">
        <div class="layout-content-body">
         <!-- FLASH DATA -->    
          <?php 
          $dat = $this->session->flashdata('msg');
              if($dat!=""){ ?>
                    <div id="notifikasi" class="alert alert-success"><strong>Sukses! </strong> <?=$dat;?></div>
          <?php } ?>  
         <!-- AKHIR FLASH DATA -->
         <!-- FLASH DATA -->    
         <?php 
          $dat = $this->session->flashdata('msg2');
              if($dat!=""){ ?>
                    <div id="notifikasi" class="alert alert-warning"><?=$dat;?></div>
          <?php } ?>  
         <!-- AKHIR FLASH DATA -->

          <div class="title-bar">
            <h1 class="title-bar-title text-white">
              <span class="d-ib">DATA USER</span>
            </h1>
          </div>
          <hr>
          <div class="text-left m-b">
              <button class="btn btn-info" data-toggle="modal" data-target="#modalSignUpSm" type="button">(+) Tambah Data</button>
          </div>
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
                  <strong>Daftar User</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-colreorder-2" class="table table-hover table-striped table-bordered table-nowrap dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="10%">No</th>
                        <th>Username</th>
                        <th>NRP</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Divisi</th>
                        <th width="20%">Aksi</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>NRP</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Divisi</th>
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                    <tbody>
                    <?php $no=1; foreach($data as $d){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['username']; ?></td>
                        <td><?= $d['nrp']; ?></td>
                        <td><?= $d['nama_user']; ?></td>
                        <td><?= $d['email']; ?></td>
                        <td>
                            <?php  
                                $tingkatan = $d['is_tingkatan'];
                                
                                if($tingkatan == 1){
                                    echo '(Pimpinan)';
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                    echo $divisi['nama_pimpinan'];
                                }
                                else if($tingkatan == 2){
                                    echo '(Pelayanan)';
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                    echo $divisi['nama_pelayanan'];
                                }
                                else if($tingkatan == 3){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                    echo '(Kompartemen) '; echo $divisi['nama_kompartemen'];
                                }
                                else if($tingkatan == 4){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                    echo '(Satker) '; echo $divisi['nama_satuan_kerja'];
                                }
                                else if($tingkatan == 5){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                    echo '(Unit Kerja) '; echo $divisi['nama_unit_kerja'];
                                }
                            ?>
                        </td>
                        <td class="text-center">
                          <a class="badge badge-success" href="#modal-edit<?= $d['id_user']; ?>" data-toggle="modal"><span class="icon icon-edit"></span> Edit</a> ||
                          <a class="badge badge-danger" href="#modal-hapus<?= $d['id_user']; ?>" data-toggle="modal"><span class="icon icon-trash-o"></span> Hapus</a>
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


    <!-- MODAL BOX TAMBAH DATA -->
    <div id="modalSignUpSm" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Tambah Data User</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('User/tambah_data') ?>
        <div class="form-group">
                <label for="username" class="form-control-label">Username *</label>
                <input class="form-control" name="username" id="username" type="text" value="<?= set_value('username'); ?>" required>
                <small class="form-text text-danger"><?= form_error('username');?></small>
        </div>

        <div class="form-group">
                <label for="nama" class="form-control-label">Nama User *</label>
                <input class="form-control" name="nama" id="nama" type="text" value="<?= set_value('nama'); ?>" required>
                <small class="form-text text-danger"><?= form_error('nama');?></small>
        </div>

        <div class="form-group">
                <label for="nrp" class="form-control-label">NRP *</label>
                <input class="form-control" name="nrp" id="nrp" type="number" value="<?= set_value('nrp'); ?>" required>
                <small class="form-text text-danger"><?= form_error('nrp');?></small>
        </div>

        <div class="form-group">
                <label for="pangkat" class="form-control-label">Pangkat *</label>
                <input class="form-control" name="pangkat" id="pangkat" type="text" value="<?= set_value('pangkat'); ?>" required>
                <small class="form-text text-danger"><?= form_error('pangkat');?></small>
        </div>

        <div class="form-group">
                <label for="password1" class="form-control-label">Password *</label>
                <input class="form-control" name="password1" id="password1" type="password" value="<?= set_value('password1'); ?>" required>
                <small class="form-text text-danger"><?= form_error('password1');?></small>
        </div>

        <div class="form-group">
                <label for="password2" class="form-control-label">Retype Password *</label>
                <input class="form-control" name="password2" id="password2" type="password" value="<?= set_value('password2'); ?>" required>
                <small class="form-text text-danger"><?= form_error('password2');?></small>
        </div>

        <div class="form-group">
                <label for="email" class="form-control-label">Email *</label>
                <input class="form-control" name="email" id="email" type="email" value="<?= set_value('email'); ?>" required>
                <small class="form-text text-danger"><?= form_error('email');?></small>
        </div>

        <div class="form-group">
                <label for="level" class="form-control-label">Level *</label>
                <select name="level" id="level" class="form-control" required>
                    <option value="">----- Pilih ------</option>
                    <option value="0">User</option>
                    <option value="1">Super Admin</option>
                </select>
        </div>

        <div class="form-group">
                <label for="pimpinan" class="form-control-label">Pimpinan *</label>
                <select name="pimpinan" id="pimpinan" class="form-control">
                    <option value="0">----- Pilih ------</option>
                    <?php foreach($pimpinan as $j){ ?>
                        <option value="<?= $j['id_pimpinan']; ?>"><?= strtoupper($j['nama_pimpinan']); ?></option>
                    <?php } ?>
                </select>
        </div>

        <div class="form-group">
                <label for="pelayanan" class="form-control-label">Pelayanan *</label>
                <select name="pelayanan" id="pelayanan" class="form-control">
                    <option value="0">----- Pilih ------</option>
                    <?php foreach($pelayanan as $u){ ?>
                        <option value="<?= $u['id_pelayanan']; ?>"><?= strtoupper($u['nama_pelayanan']); ?></option>
                    <?php } ?>
                </select>
        </div>

        <div class="form-group">
                <label for="kompartemen" class="form-control-label">Kompartemen *</label>
                <select name="kompartemen" id="kompartemen" class="form-control">
                    <option value="0">----- Pilih ------</option>
                    <?php foreach($kompartemen as $k){ ?>
                        <option value="<?= $k['id_kompartemen']; ?>"><?= strtoupper($k['nama_kompartemen']); ?></option>
                    <?php } ?>
                </select>
        </div>

        <div class="form-group">
                <label for="satker" class="form-control-label">Satker *</label>
                <select name="satker" id="satker" class="form-control">
                    <option value="0" style="display:none;">Silahkan pilih kompartemen terlebih dahulu</option>
                </select>
        </div>

        <div class="form-group">
                <label for="unit_kerja" class="form-control-label">Unit Kerja *</label>
                <select name="unit_kerja" id="unit_kerja" class="form-control">
                    <option value="0" style="display:none;">Silahkan pilih satker terlebih dahulu</option>
                </select>
        </div>

        <div class="form-group">
                <label for="jabatan" class="form-control-label">Jabatan Divisi *</label>
                <select name="jabatan" id="jabatan" class="form-control" required>
                    <option value="kepala">Kepala</option>
                    <option value="sekretaris">Sekretaris</option>
                </select>
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
    <div id="modal-edit<?= $x['id_user'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Ubah Data User</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('User/edit_data') ?>
        <input type="hidden" value="<?= $x['id_user']; ?>" name="id" class="form-control" >
            <div class="form-group">
              <label class="control-label">Nama User</label>
              <input class="form-control" type="text" name="nama_user" value="<?= $x['nama_user'] ?>" required="">
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
    <div id="modal-hapus<?= $x['id_user'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Hapus Data User</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('User/hapus_data') ?>
        <input type="hidden" value="<?= $x['id_user']; ?>" name="id" class="form-control" >
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

            // Load Unit kerja
            $("#satker").change(function(){

            var value=$(this).val();
            if(value>0){
            $.ajax({
            data:{modul:'unit_kerja',id:value},
            success: function(respond){
            $("#unit_kerja").html(respond);
            }
            })
            }

            });


            })

         </script>
        