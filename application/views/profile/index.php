<!-- MAIN CONTENT -->
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
            <h1 class="title-bar-title">
              <span class="d-ib">My Profile</span>
              <span class="d-ib">
                <a class="title-bar-shortcut" href="#" title="Add to shortcut list" data-container="body" data-toggle-text="Remove from shortcut list" data-trigger="hover" data-placement="right" data-toggle="tooltip">
                  <span class="sr-only">Add to shortcut list</span>
                </a>
              </span>
            </h1>
            <p class="title-bar-description">
              <small></small>
            </p>
          </div>
          <hr>
          <div class="row gutter-xs">
            <div class="col-xs-12">
            <div class="container-fluid">
              <!-- Page Heading -->
              <div class="container-fluid">
              <div class="card mb-3 col-lg-8">
              <div class="row no-gutters">
                <div class="col-md-8">
                    <!--begin: Datatable -->
                    <br>
                                <table style="width:100%; margin-left:3%;">
                                    <tr>
                                        <td width="30%" ><h4>Username</h4></td>
                                        <td width="2%"><h4>:</h4></td>
                                        <td><h4><?= $user['username']; ?></h4></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" ><br><h4>Nama User</h4></td>
                                        <td width="2%"><br><h4>:</h4></td>
                                        <td><h4><br><?= $user['nama_user']; ?></h4></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" ><br><h4>NRP</h4></td>
                                        <td width="2%"><br><h4>:</h4></td>
                                        <td><h4><br><?= $user['nrp']; ?></h4></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" ><br><h4>Jabatan</h4></td>
                                        <td width="2%"><br><h4>:</h4></td>
                                        <td><h4><br><?= $user['pangkat']; ?></h4></td>
                                    </tr>
                                    <tr>
                                        <td width="20%"><br><h4>Email</h4></td>
                                        <td width="2%"><br><h4>:</h4></td>
                                        <td><br><h4><?= $user['email']; ?></h4></td>
                                    </tr>
                                    <tr>
                                        <td width="20%"><br><h4>Tingkatan</h4></td>
                                        <td width="2%"><br><h4>:</h4></td>
                                        <td><br><h4>
                                            <?php if($user['is_tingkatan'] == 1){
                                                        echo 'Pimpinan';
                                                  }
                                                  else if($user['is_tingkatan'] == 2){
                                                      echo 'Pelayanan';
                                                  }
                                                  else if($user['is_tingkatan'] == 3){
                                                    echo 'Kompartemen';
                                                  }
                                                  else if($user['is_tingkatan'] == 4){
                                                    echo 'Satker';
                                                  }
                                                  else if($user['is_tingkatan'] == 5){
                                                    echo 'Unit Kerja';
                                                  }
                                            ?>
                                        </h4></td>
                                    </tr>
                                    <tr>
                                        <td width="20%"><br><h4>Divisi</h4></td>
                                        <td width="2%"><br><h4>:</h4></td>
                                        <td><br><h4>
                                        <?php  
                                                $tingkatan = $user['is_tingkatan'];
                                                           
                                                if($tingkatan == 1){
                                                    echo 'Super Admin';
                                                }
                                                else if($tingkatan == 2){
                                                    $idnya = $user['id_divisi'];
                                                    $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                                    echo $divisi['nama_pimpinan'];
                                                }
                                                else if($tingkatan == 3){
                                                    $idnya = $user['id_divisi'];
                                                    $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                                    echo $divisi['nama_kompartemen'];
                                                }
                                                else if($tingkatan == 4){
                                                    $idnya = $user['id_divisi'];
                                                    $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                                    echo $divisi['nama_satuan_kerja'];
                                                }
                                                else if($tingkatan == 5){
                                                    $idnya = $user['id_divisi'];
                                                    $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                                    echo $divisi['nama_unit_kerja'];
                                                }
                                            ?>
                                        </h4></td>
                                    </tr>
                                </table><br><br><br>
                                <center>
                                <div class="row">
                                    <div class="col-sm-9"><a class="btn btn-primary"  href="#modal-edit<?= $user['id_user']; ?>" data-toggle="modal"><span class="fas fa-fw fa-edit"></span>Ubah Profile</a> | <a class="btn btn-primary" href="#modal-password<?= $user['id_user']; ?>" data-toggle="modal"><span class="fas fa-fw fa-edit"></span>Ganti Password</a></div>
                                </div>
                                </center>
                            <br><br><br><br><br>
                                <!--end: Datatable -->
                </div>
              </div>
              </div>
        
              </div>
              <!--  -->
            </div>
          </div>
        </div>
      </div>


         <!-- MODAL BOX UBAH PROFILE -->
    <div id="modal-edit<?= $user['id_user']; ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Ubah Profile</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Profile/edit_profile') ?>
        <input type="hidden" readonly value="<?= $user['id_user']; ?>" name="id" class="form-control" >
            <div class="form-group">
              <label class="control-label" for="nama_user">Nama User</label>
              <input class="form-control" id="nama_user" type="text" name="nama_user" value="<?= $user['nama_user']; ?>">
            </div>
            <div class="form-group">
              <label class="control-label" for="nrp">NRP</label>
              <input class="form-control" id="nrp" type="text" name="nrp" value="<?= $user['nrp']; ?>">
            </div>
            <div class="form-group">
              <label class="control-label" for="pangkat">Pangkat</label>
              <input class="form-control" id="pangkat" type="text" name="pangkat" value="<?= $user['pangkat']; ?>">
            </div>
            <div class="form-group">
              <label class="control-label" for="email">Email</label>
              <input class="form-control" id="email" type="email" name="email" value="<?= $user['email']; ?>">
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
    <!-- AKHIR MODAL BOX UBAH PROFILE -->

     <!-- MODAL BOX CHANGE PASSWORD -->
     <div id="modal-password<?= $user['id_user']; ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Ubah Profile</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Profile/change_password') ?>
        <input type="hidden" readonly value="<?= $user['id_user']; ?>" name="id" class="form-control" >
              <div class="form-group">
                  <label for="current_password">Current Password : </label>
                  <input type="password" id="current_password" name="current_password" value="<?= set_value('current_password'); ?>" class="form-control" placeholder="Current Password"  required="">
                  <small class="form-text text-danger"><?= form_error('current_password');?></small>
              </div>

              <div class="form-group">
                  <label for="new_password">New Password : </label>
                  <input type="password" id="new_password" name="new_password1" value="<?= set_value('new_password1'); ?>" class="form-control" placeholder="New Password"  required="">
                  <small class="form-text text-danger"><?= form_error('new_password1');?></small>
                </div>

        
              <div class="form-group">
                  <label for="repeat_password">Repeat Password : </label>
                  <input type="password" id="repeat_password" name="new_password2" value="<?= set_value('new_password2'); ?>" class="form-control" placeholder="Repeat Password"  required="">
                  <small class="form-text text-danger"><?= form_error('new_password2');?></small>
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
    <!-- AKHIR MODAL BOX CHANGE PASSWORD -->