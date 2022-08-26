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
              <span class="d-ib">PERSETUJUAN MASUK | NOTDIS</span>
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
                  <strong>Daftar Persetujuan Masuk</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-colreorder-2" class="table table-hover table-striped table-bordered table-nowrap dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="7%">No</th>
                        <th>Dari</th>
                        <th>Nomer Persetujuan</th>
                        <th>Tanggal Diacc</th>
                        <th>Status Persetujuan</th>
                        <th width="15%">Aksi</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Dari</th>
                        <th>Nomer Persetujuan</th>
                        <th>Tanggal Diacc</th>
                        <th>Status Persetujuan</th>
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                    <tbody>
                    <?php $no=1; foreach($data as $d){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                        <?php  
                                $tingkatan = $d['is_tingkatan'];
                                
                                if($tingkatan == 1){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM pimpinan where id_pimpinan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan']). ' '; echo strtoupper($divisi['nama_pimpinan']);
                                }
                                else if($tingkatan == 2){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM pelayanan where id_pelayanan='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan']). ' '; echo strtoupper($divisi['nama_pelayanan']);
                                }
                                else if($tingkatan == 3){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM kompartemen where id_kompartemen='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan']). ' '; echo strtoupper($divisi['nama_kompartemen']);
                                }
                                else if($tingkatan == 4){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM satuan_kerja where id_satuan_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan']). ' '; echo strtoupper($divisi['nama_satuan_kerja']);
                                }
                                else if($tingkatan == 5){
                                    $idnya = $d['id_divisi'];
                                    $divisi = $this->db->query("SELECT * FROM unit_kerja where id_unit_kerja='$idnya'")->row_array();
                                    echo strtoupper($d['jabatan']). ' '; echo strtoupper($divisi['nama_unit_kerja']);
                                }
                        ?>
                        </td>
                        <td><?= $d['nomer_surat_persetujuan_notdis']; ?></td>
                        <td><?= $d['tanggal_diacc']; ?></td>
                        <td><?= strtoupper($d['status_persetujuan_notdis']); ?></td>
                        <td class="text-center">
                        <?php if(($d['status_persetujuan_notdis'] == 'Menunggu' || $d['status_persetujuan_notdis'] == 'Diajukan' || $d['status_persetujuan_notdis'] == 'Ditolak') && $d['is_read'] == 1){ ?>
                        <a class="badge badge-success" href="#modal-tl<?= $d['id_persetujuan_notdis']; ?>" data-toggle="modal"><span class="icon icon-mail-reply"></span> Reply</a> ||
                        <?php } ?>
                        <a class="badge badge-primary" href="<?= base_url(); ?>nota_dinas_keluar/in_detail/<?= $d['id_surat_notdis']; ?>"><span class="icon icon-eye"></span> Detail</a>
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

      
 <!---------------------------------- TINDAK LANJUT SURAT ------------------------------------>
 
 <!---------------------------------- AKHIR TINDAK LANJUT SURAT------------------------------------>



 <?php $no=0; foreach($data as $x): $no++; ?>
    <div id="modal-tl<?= $x['id_persetujuan_notdis'] ?>" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Tindak Lanjut Surat</h4>
        </div>
        <div class="modal-body">
        <?php echo form_open('Persetujuan_masuk_notdis/tindak_lanjut_kasus') ?>
        <input type="hidden" readonly value="<?= $x['id_persetujuan_notdis']; ?>" name="id_persetujuan_notdis" class="form-control" >
         <!-- Get surat notdis -->
        <?php 
          $id_persetujuan_notdis = $x['id_persetujuan_notdis'];
          $get_persetujuan_notdis = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_persetujuan_notdis='$id_persetujuan_notdis'")->row_array();
          $id_notdis= $get_persetujuan_notdis['id_surat_notdis'];
          $get_kepada = $this->db->query("SELECT * FROM kepada_surat_notdis WHERE id_surat_notdis='$id_notdis' LIMIT 1")->row_array();
          $id_login = $this->session->userdata('id');
        ?>   
            
            <div class="form-group">
                <label for="tindak_lanjut" class="form-control-label">Tindak Lanjut Surat *</label>
                <select name="tindak_lanjut" id="tindak_lanjut" class="form-control" required>
                    <option value="">---------- Pilih -----------</option>
                    <?php if($get_kepada['kepada'] != $id_login){ ?>
                    <option value="diteruskan">Diteruskan</option>
                    <?php }else{ ?>
                    <option value="diterima">Diterima</option>
                    <?php } ?>
                    <option value="dikembalikan">Kembalikan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="isi" class="form-control-label">Keterangan / Nomer Surat *</label>
                <textarea name="isi"  cols="30" rows="9" class="form-control" required></textarea>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
          </form>
      </div>
    </div>
  </div>
  <?php endforeach;?>
    <!-- AKHIR MODAL BOX EDIT DATA -->