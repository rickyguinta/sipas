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
              <span class="d-ib">DISPOSISI SURAT</span>
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
                  <strong>Disposisi Surat</strong>
                </div>
                <div class="card-body">
                  
                <div class="col-md-12">
              <div class="demo-form-wrapper">
                <div class="form form-horizontal">

                  <!-- <div class="form-group">
                    <label class="col-sm-3 control-label" for="nomer_surat">Kepada</label>
                    <div class="col-sm-9">

                    <select id="kepada" name="kepada[]" class="form-control" multiple="multiple" required="">
                          <option value=""></option>
                          <?php foreach($user as $s){ ?>
                              <?php  
                                  $tingkatan = $s['is_tingkatan'];
                                  if($tingkatan == 1){
                                      $idnya = $s['id_divisi'];
                                      $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                  ?>
                                      <option value="<?= $s['id_user']; ?>"><?= $divisi['nama_pimpinan']; ?></option>

                                  <?php  
                                  } else if($tingkatan == 2){
                                      $idnya = $s['id_divisi'];
                                      $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array(); 
                                  ?>
                                      <option value="<?= $s['id_user']; ?>"><?= $divisi['nama_pelayanan']; ?></option>
  
                                      
                                  <?php  
                                  } else if($tingkatan == 3){
                                      $idnya = $s['id_divisi'];
                                      $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array(); 
                                  ?>
                                      <option value="<?= $s['id_user']; ?>"><?= $divisi['nama_kompartemen']; ?></option>
                                  
                                  <?php  }
                                  else if($tingkatan == 4){
                                      $idnya = $s['id_divisi'];
                                      $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array(); 
                                  ?>
                                      <option value="<?= $s['id_user']; ?>"><?= $divisi['nama_satuan_kerja']; ?></option>

                                  <?php  }
                                  else if($tingkatan == 5){
                                      $idnya = $s['id_divisi'];
                                      $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array(); 
                                  ?>
                                      <option value="<?= $s['id_user']; ?>"><?= $divisi['nama_unit_kerja']; ?></option>
                                  <?php } ?>

                          <?php } ?>
                    </select>


                    </div>
                  </div> -->
                <?php if($this->session->userdata('is_tingkatan') <= 1){ ?>
                <form method="post">
                  <div class="form-group">
                    <label class="col-sm-1 control-label" for="pimpinan">Pimpinan</label>
                    <input type="text" hidden name="cek_tingkatan" value="pimpinan">
                    <div class="col-sm-6">
                      <select name="isinya" id="isinya" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($pimpinan as $p){ ?>
                            <option value="<?= $p['id_user'] ?>">
                            <?php  
                                $tingkatan = $p['is_tingkatan'];
                                
                                if($tingkatan == 1){
                                    $idnya = $p['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                    echo $divisi['nama_pimpinan'];
                                }
                            ?>
                            </option>
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                    </div>
                  </div>    
                </form>
                <?php } ?>

                <?php if($this->session->userdata('is_tingkatan') <= 2){ ?>
                <form method="post">  
                  <div class="form-group">
                    <label class="col-sm-1 control-label" for="pelayanan">Pelayanan</label>
                    <input type="text" hidden name="cek_tingkatan" value="pelayanan">
                    <div class="col-sm-6">
                      <select name="isinya" id="isinya" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($pelayanan as $pl){ ?>
                            <option value="<?= $pl['id_user'] ?>">
                            <?php  
                                $tingkatan = $pl['is_tingkatan'];
                                if($tingkatan == 2){
                                    $idnya = $pl['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                    echo $divisi['nama_pelayanan'];
                                }
                            ?>
                            </option>
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                    </div>
                  </div>    
                </form>
                <?php } ?>


                <?php if($this->session->userdata('is_tingkatan') <= 3){ ?>
                <form method="post">
                  <div class="form-group">
                    <label class="col-sm-1 control-label" for="kompartemen">Kompartemen</label>
                    <input type="text" hidden name="cek_tingkatan" value="kompartemen">
                    <div class="col-sm-6">
                      <select name="isinya" id="isinya" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($kompartemen as $k){ ?>
                            <option value="<?= $k['id_user'] ?>">
                            <?php  
                                $tingkatan = $k['is_tingkatan'];
                                if($tingkatan == 3){
                                    $idnya = $k['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                    echo $divisi['nama_kompartemen'];
                                }
                            ?>
                            </option>
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                    </div>
                  </div>    
                </form>
                <?php } ?>

                <?php if($this->session->userdata('is_tingkatan') <= 4){ ?>
                <form method="post">
                  <div class="form-group">
                    <label class="col-sm-1 control-label" for="satuan_kerja">Satuan Kerja</label>
                    <input type="text" hidden name="cek_tingkatan" value="satuan_kerja">
                    <div class="col-sm-6">
                      <select name="isinya" id="isinya" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($satker as $s){ ?>
                            <option value="<?= $s['id_user'] ?>">
                            <?php  
                                $tingkatan = $s['is_tingkatan'];
                                if($tingkatan == 4){
                                    $idnya = $s['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                    echo $divisi['nama_satuan_kerja'];
                                }
                            ?>
                            </option>
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                    </div>
                  </div>    
                </form>
                <?php } ?>

                <?php if($this->session->userdata('is_tingkatan') <= 5){ ?>
                <form method="post">
                  <div class="form-group">
                    <label class="col-sm-1 control-label" for="perihal">Unit Kerja</label>
                    <input type="text" hidden name="cek_tingkatan" value="unit_kerja">
                    <div class="col-sm-6">
                      <select name="isinya" id="isinya" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($unker as $u){ ?>
                            <option value="<?= $u['id_user'] ?>">
                            <?php  
                                $tingkatan = $u['is_tingkatan'];
                                if($tingkatan == 5){
                                    $idnya = $u['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                    echo $divisi['nama_unit_kerja'];
                                }
                            ?>
                            </option>
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                    </div>
                  </div>    
                </form>
                <?php } ?>

                <br>
                <table id="demo-datatables-colreorder-2" class="table table-hover table-striped table-bordered table-nowrap dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="10%">No</th>
                        <th>Tingkatan</th>
                        <th>Divisi</th>
                        <th width="25%">Aksi</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Tingkatan</th>
                        <th>Divisi</th>
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                    <tbody>
                      <?php $no=1; foreach($this->cart->contents() as $items): ?>
                      <?php echo form_hidden($no.'[rowid]', $items['rowid']); ?>
                      <tr>
                          <td><?= $no++; ?></td>
                          <td><?= $items['tingkatan']; ?> </td>
                          <td>
                          <?php  
                              $tingkatan = $items['is_tingkatan'];
                          
                              if($tingkatan == 1){
                                $idnya = $items['id_divisi_penerima_tujuan'];
                                $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                echo $divisi['nama_pimpinan'];
                              }
                              else if($tingkatan == 2){
                                $idnya = $items['id_divisi_penerima_tujuan'];
                                $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                echo $divisi['nama_pelayanan'];
                              }
                              else if($tingkatan == 3){
                                  $idnya = $items['id_divisi_penerima_tujuan'];
                                  $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                  echo $divisi['nama_kompartemen'];
                              }
                              else if($tingkatan == 4){
                                  $idnya = $items['id_divisi_penerima_tujuan'];
                                  $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                  echo $divisi['nama_satuan_kerja'];
                              }
                              else if($tingkatan == 5){
                                  $idnya = $items['id_divisi_penerima_tujuan'];
                                  $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                  echo $divisi['nama_unit_kerja'];
                              }
                          ?>
                          </td>
                          <td class="text-center">
                                  <a class="badge badge-danger" href="<?= base_url(); ?>disposisi/hapus/<?= $items['rowid']; ?>/<?= $id_surat; ?>" title="Hapus"><span class="icon icon-trash-o"></span></a>
                          </td>
                      </tr>
                          <?php endforeach; ?>
                    </tbody>
                  </table>
                <br><br>

                <form method="post" enctype="multipart/form-data">
                <input type="text" hidden name="cek_tingkatan" value="all">
                <input type="text" hidden name="id_surat" value="<?= $id_surat; ?>">
                  <div class="form-group">
                    <label class="col-sm-1 control-label" for="nomer_agenda">Nomer Agenda</label>
                    <div class="col-sm-9">
                      <input id="nomer_agenda" class="form-control" name="nomer_agenda" type="text" required="">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-1 control-label" for="isi_disposisi">Isi Disposisi</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" id="isi_disposisi" name="isi_disposisi" rows="3" required=""></textarea>
                    </div>
                  </div>    

                  <table class="table-common dt-responsive" cellpadding="7" >
                  <tr>
                  <!-- <label class="col-sm-1 control-label"></label> -->
                  <td>
                  </td>
                    <td>
                    </td>
                  </tr>
                  </table>
                  <br>

                  <br>

                </div>
              </div>
              <center><button type="submit" class="btn btn-sm btn-primary">Kirim</button></center>
              </form>
              <br><br><br><br>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

