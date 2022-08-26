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
              <span class="d-ib">DATA NOTA DINAS KELUAR</span>
            </h1>
          </div>
          <hr>
          <div class="text-left m-b">
              <a class="btn btn-info" href="<?= base_url(); ?>nota_dinas_keluar/in_tambah">(+) Tambah Data</a>
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
                  <strong>Daftar Nota Dinas Keluar</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-colreorder-2" class="table table-hover table-striped table-bordered dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="7%">No</th>
                        <th width="80">Dari</th>
                        <th width="180">Nomer Surat</th>
                        <th width="250">Perihal</th>
                        <th width="50">Status</th>
                        <th width="100">Aksi</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Dari</th>
                        <th>Nomer Surat</th>
                        <th>Perihal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                    <tbody>
                    <?php $no=1; foreach($data as $d){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                        <?php  
                                $tingkatan2 = $d['is_tingkatan_dari'];
                                
                                if($tingkatan2 == 1){
                                    $idnya2 = $d['id_divisi_dari'];
                                    $divisi2 = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya2'")->row_array();
                                    echo strtoupper($divisi2['nama_pimpinan']);
                                }
                                else if($tingkatan2 == 2){
                                    $idnya2 = $d['id_divisi_dari'];
                                    $divisi2 = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya2'")->row_array();
                                    echo strtoupper($divisi2['nama_pelayanan']);
                                }
                                else if($tingkatan2 == 3){
                                    $idnya2 = $d['id_divisi_dari'];
                                    $divisi2 = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya2'")->row_array();
                                    echo strtoupper($divisi2['nama_kompartemen']);
                                }
                                else if($tingkatan2 == 4){
                                    $idnya2 = $d['id_divisi_dari'];
                                    $divisi2 = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya2'")->row_array();
                                    echo strtoupper($divisi2['nama_satuan_kerja']);
                                }
                                else if($tingkatan2 == 5){
                                    $idnya2 = $d['id_divisi_dari'];
                                    $divisi2 = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya2'")->row_array();
                                    echo strtoupper($divisi2['nama_unit_kerja']);
                                }
                        ?>
                        </td>
                        <td><?= $d['nomer_surat_notdis']; ?></td>
                        <td><?= $d['perihal_notdis']; ?></td>
                        <td>
                        <?php if($d['status_surat'] == 0){
                                echo 'Progres';
                              }
                              else{
                                echo 'Finish';
                              }
                        ?>

                        </td>
                        <td class="text-center">
                        <a class="badge badge-primary" href="<?= base_url(); ?>nota_dinas_keluar/in_detail/<?= $d['id_surat_notdis']; ?>"><span class="icon icon-eye"></span> Detail</a> ||
                        <a class="badge badge-danger" href="#modal-hapus<?= $d['id_surat_notdis']; ?>" data-toggle="modal"><span class="icon icon-trash-o"></span> Hapus</a>
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
    <div id="modal-hapus<?= $x['id_surat_notdis'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Hapus Data Nota Dinas Keluar</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('nota_dinas_keluar/hapus_notdis') ?>
        <input type="hidden" value="<?= $x['id_surat_notdis']; ?>" name="id" class="form-control" >
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