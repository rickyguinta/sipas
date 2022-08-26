<div class="layout-sidebar">
        <div class="layout-sidebar-backdrop"></div>
        <div class="layout-sidebar-body">
          <div class="custom-scrollbar">
            <nav id="sidenav" class="sidenav-collapse collapse">
              <ul class="sidenav">
                <li class="sidenav-search hidden-md hidden-lg">
                  <form class="sidenav-form" action="/">
                    <div class="form-group form-group-sm">
                      <div class="input-with-icon">
                        <input class="form-control" type="text" placeholder="Search…">
                        <span class="icon icon-search input-icon"></span>
                      </div>
                    </div>
                  </form>
                </li>
                <li class="sidenav-heading">DASHBOARD</li>
                <li class="sidenav-item <?php if($ngecek1 =='dashboard'){ echo 'active'; } ?>">
                  <a href="<?= base_url(); ?>dashboard">
                    <span class="sidenav-icon icon icon-home"></span>
                    <span class="sidenav-label">Dashboard</span>
                  </a>
                </li>
                <li class="sidenav-heading">MAIN</li>
                <li class="sidenav-item has-subnav <?php if($ngecek1 =='main'){ echo 'active'; } ?>">
                  <a href="#" aria-haspopup="true">
                    <span class="sidenav-icon icon icon-database"></span>
                    <span class="sidenav-label">Data</span>
                  </a>
                  <ul class="sidenav-subnav collapse">
                    <li class="sidenav-subheading">UI Elements</li>
                    <li class="<?php if($ngecek2 =='pimpinan'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>pimpinan">Pimpinan dan Penasehat</a></li>
                    <li class="<?php if($ngecek2 =='pelayanan'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>pelayanan">Pelayanan Staff</a></li>
                    <li class="<?php if($ngecek2 =='kompartemen'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>kompartemen">Kompartemen</a></li>
                    <li class="<?php if($ngecek2 =='satker'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>satker">Satuan Kerja</a></li>
                    <li class="<?php if($ngecek2 =='unker'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>unker">Unit Kerja</a></li>
                  </ul>
                </li>
                <li class="sidenav-item has-subnav <?php if($ngecek1 =='disposisi'){ echo 'active'; } ?>">
                  <a href="#" aria-haspopup="true">
                    <span class="sidenav-icon icon icon-server"></span>
                    <?php 
                      $idnya = $this->session->userdata('id');
                      $get2 = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_penerima_disposisi='$idnya' AND tgl_dibaca_disposisi='0000-00-00 00:00:00'")->num_rows(); 
                    ?>
                    <span class="sidenav-label">Disposisi <span style="color:red;"><?php if($this->session->userdata('level') != 1){ ?>(<?= $get2; ?>)</span></span> <?php } ?>
                  </a>
                  <ul class="sidenav-subnav collapse">
                  <?php if($this->session->userdata('level') == 1){ ?>
                    <li class="<?php if($ngecek2 =='history_disposisi'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>history/history_disposisi">History Disposisi</a></li>
                  <?php }else{ ?>
                    <li class="<?php if($ngecek2 =='disposisi_masuk'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>disposisi/index_masuk">Disposisi Masuk <span style="color:red;">(<?= $get2; ?>)</span></a></li>
                    <li class="<?php if($ngecek2 =='disposisi_keluar'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>disposisi/index_keluar">Disposisi Keluar</a></li>
                  <?php } ?>
                  </ul>
                </li>
                <li class="sidenav-item has-subnav <?php if($ngecek1 =='tembusan'){ echo 'active'; } ?>">
                  <a href="#" aria-haspopup="true">
                    <span class="sidenav-icon icon icon-reorder"></span>
                    <?php 
                      $get1 = $this->db->query("SELECT * FROM tembusan_kirim WHERE id_penerima_tembusan='$idnya' AND tanggal_dibaca_tembusan='0000-00-00 00:00:00' AND is_dibaca=1")->num_rows(); 
                    ?>
                    <span class="sidenav-label">Tembusan <?php if($this->session->userdata('level') != 1){ ?><span style="color:red;">(<?= $get1; ?>)</span><?php } ?></span>
                  </a>
                  <ul class="sidenav-subnav collapse">
                  <?php if($this->session->userdata('level') == 1){ ?>
                    <li class="<?php if($ngecek2 =='history_tembusan'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>history/history_tembusan">History Tembusan</a></li>
                  <?php }else{ ?>
                    <li class="<?php if($ngecek2 =='tembusan_masuk'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>tembusan_masuk">Tembusan Masuk <span style="color:red;">(<?= $get1; ?>)</span></a></li>
                    <li class="<?php if($ngecek2 =='tembusan_keluar'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>tembusan_keluar">Tembusan Keluar</a></li>
                  <?php } ?>
                  </ul>
                </li>
                <li class="sidenav-item has-subnav <?php if($ngecek1 =='notifikasi'){ echo 'active'; } ?>">
                  <a href="#" aria-haspopup="true">
                    <span class="sidenav-icon icon icon-bell"></span>
                    <?php 
                      $get3 = $this->db->query("SELECT * FROM notifikasi_surat WHERE id_penerima_notif='$idnya' AND is_read_notif=0")->num_rows(); 
                    ?>
                    <span class="sidenav-label">Notifikasi Surat <?php if($this->session->userdata('level') != 1){ ?><span style="color:red;">(<?= $get3; ?>)</span><?php } ?></span>
                  </a>
                  <ul class="sidenav-subnav collapse">
                  <?php if($this->session->userdata('level') == 1){ ?>
                    <li class="<?php if($ngecek2 =='history_notifikasi'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>history/history_notifikasi">History Notifikasi</a></li>
                  <?php }else{ ?>
                    <li class="<?php if($ngecek2 =='notifikasi_masuk'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>notifikasi_masuk">Notifikasi Masuk <span style="color:red;">(<?= $get3; ?>)</span></a></li>
                    <li class="<?php if($ngecek2 =='notifikasi_keluar'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>notifikasi_keluar">Notifikasi Keluar</a></li>
                  <?php } ?>
                  </ul>
                </li>
                <li class="sidenav-heading">SURAT</li>
                <li class="sidenav-item has-subnav <?php if($ngecek1 =='nota_dinas'){ echo 'active'; } ?>">
                  <a href="#" aria-haspopup="true">
                    <span class="sidenav-icon icon icon-file-text"></span>
                    <span class="sidenav-label">Nota Dinas</span>
                  </a>
                  <ul class="sidenav-subnav collapse">
                    <li class="sidenav-subheading">UI Elements</li>
                    <?php 
                      $get4 = $this->db->query("SELECT * FROM persetujuan_notdis WHERE id_penerima='$idnya' AND is_read=1 AND tanggal_diacc='0000-00-00 00:00:00'")->num_rows(); 
                    ?>
                    <?php if($this->session->userdata('level') == 1){ ?>
                    <li class="<?php if($ngecek2 =='history_nota_dinas'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>history/history_nota_dinas">History Nota Dinas</a></li>
                    <li class="<?php if($ngecek2 =='history_persetujuan'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>history/history_persetujuan">History Persetujuan</a></li>
                    <?php }else{ ?>
                    <li class="<?php if($ngecek2 =='nota_dinas_masuk'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>nota_dinas_masuk">Surat Masuk</a></li>
                    <li class="<?php if($ngecek2 =='nota_dinas_keluar'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>nota_dinas_keluar">Surat Keluar</a></li>
                    <li class="<?php if($ngecek2 =='persetujuan_masuk_notdis'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>persetujuan_masuk_notdis">Persetujuan Masuk <span style="color:red;">(<?= $get4; ?>)</span></a></li>
                    <li class="<?php if($ngecek2 =='persetujuan_keluar_notdis'){ echo 'active'; } ?>"><a href="<?= base_url(); ?>persetujuan_keluar_notdis">Persetujuan Keluar</a></li>
                    <?php } ?>
                  </ul>
                </li>
                
                <?php if($this->session->userdata('level') == 1){ ?>
                <li class="sidenav-heading">LAINNYA</li>
                <li class="sidenav-item <?php if($ngecek1 =='user'){ echo 'active'; } ?>">
                  <a href="<?= base_url(); ?>user">
                    <span class="sidenav-icon icon icon-user"></span>
                    <span class="sidenav-label">USERS</span>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>