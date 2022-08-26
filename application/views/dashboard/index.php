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
              <span class="d-ib ">DASHBOARD</span>
            </h1>
          </div>
          <hr>
          <!-- mulai 1 -->
          <div class="container-fluid py-4">
            <div class="row">
              <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                  <div class="card-body p-3">
                    <div class="row">
                      <div class="col-8">
                        <div class="numbers">
                          <p class="text-sm mb-0 text-uppercase font-weight-bold">Surat Masuk</p>
                          <h5 class="font-weight-bolder">
                            <?= $jml_surat_masuk; ?>
                          </h5>
                        </div>
                      </div>
                      <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                          <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                  <div class="card-body p-3">
                    <div class="row">
                      <div class="col-8">
                        <div class="numbers">
                          <p class="text-sm mb-0 text-uppercase font-weight-bold">Surat Keluar</p>
                          <h5 class="font-weight-bolder">
                          <?= $jml_jml_surat_keluar; ?>
                          </h5>
                        </div>
                      </div>
                      <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                          <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                  <div class="card-body p-3">
                    <div class="row">
                      <div class="col-8">
                        <div class="numbers">
                          <p class="text-sm mb-0 text-uppercase font-weight-bold">Jumlah Divisi</p>
                          <h5 class="font-weight-bolder">
                            <?= $jml_divisi; ?>
                          </h5>                          
                        </div>
                      </div>
                      <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                          <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6">
                <div class="card">
                  <div class="card-body p-3">
                    <div class="row">
                      <div class="col-8">
                        <div class="numbers">
                          <p class="text-sm mb-0 text-uppercase font-weight-bold">Jumlah User</p>
                          <h5 class="font-weight-bolder">
                            <?= $jml_user; ?>
                          </h5>                          
                        </div>
                      </div>
                      <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                          <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>          
          </div>         
          <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
         '' <div class="card ">
            <div class="card-header pb-0 p-3">
              <div class="d-flex justify-content-between">
                <h6 class="mb-2">Tentang E-OFFICE</h6>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center ">
                <tbody>
                  <tr>
                    <td class="w-30">
                      <div class="d-flex px-2 py-1 align-items-center">                        
                        <div class="ms-4">
                          <h6 class="text-sm mb-0"># Merupakan Sistem Manajemen Pengelolaan Arsip Surat</h6>
                        </div>
                      </div>
                    </td>                   
                  </tr>
                  <tr>
                    <td class="w-30">
                      <div class="d-flex px-2 py-1 align-items-center">                        
                        <div class="ms-4">                          
                          <h6 class="text-sm mb-0"># aplikasi ini digunakan untuk pengelolaan surat masuk dan surat keluar di setiap bagian</h6>
                        </div>
                      </div>
                    </td>                    
                  </tr>
                  <tr>
                    <td class="w-30">
                      <div class="d-flex px-2 py-1 align-items-center">                        
                        <div class="ms-4">                          
                          <h6 class="text-sm mb-0"># aplikasi ini digunakan untuk pengelolaan surat masuk dan surat keluar di divisi Akademi Kepolisian Indonesia </h6>
                        </div>
                      </div>
                    </td>
                    <td>                      
                  </tr>
                  <tr>
                    <td class="w-30">
                      <div class="d-flex px-2 py-1 align-items-center">                        
                        <div class="ms-4">                          
                          <h6 class="text-sm mb-0"># Aplikasi ini didukung dengan fitur tembusan dan juga disposisi surat.</h6>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
         



          <!-- akhir 2 -->


        </div>
      </div>

