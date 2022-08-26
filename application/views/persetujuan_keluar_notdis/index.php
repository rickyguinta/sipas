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
              <span class="d-ib">PERSETUJUAN KELUAR | NOTDIS</span>
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
                  <strong>Daftar Persetujuan Keluar</strong>
                </div>
                <div class="card-body">
                <table id="demo-datatables-colreorder-2" class="table table-hover table-striped table-bordered table-nowrap dataTable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="7%">No</th>
                        <th>Kepada</th>
                        <th>Nomer Persetujuan</th>
                        <th>Tanggal Diacc</th>
                        <th>Status Persetujuan</th>
                        <th width="15%">Aksi</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Kepada</th>
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