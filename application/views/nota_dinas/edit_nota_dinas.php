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
              <span class="d-ib">EDIT DATA NOTA DINAS</span>
            </h1>
          </div>
          <hr>
          <div class="row gutter-xs">
            <div class="col-xs-12">
              <div class="card">
                <div class="card-header">
                  <strong>Edit Nota Dinas</strong>
                </div>
                <div class="card-body">
                  
                <div class="col-md-12">
              <div class="demo-form-wrapper">
                <div class="form form-horizontal">

                <?php echo form_open_multipart('Nota_dinas_masuk/proses_edit_notdis') ?>
                <input type="hidden" readonly value="<?= $data['id_surat_notdis']; ?>" name="id" class="form-control" >
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="nomer_surat">Nomer Surat</label>
                    <div class="col-sm-8">
              <input id="nomer_surat" class="form-control" name="nomer_surat" type="text" value="<?= $data['nomer_surat_notdis']; ?>" <?php if($data['dari'] != $this->input->post('id')){ ?>readonly <?php } ?>>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="perihal">Perihal</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" id="perihal" name="perihal" rows="3" required=""><?= $data['perihal_notdis']; ?></textarea>
                    </div>
                  </div>    

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="perihal">Rujukan</label>
                    <div class="col-sm-8">
                      <textarea class="ckeditor" id="ckedtor" name="rujukan" rows="3" required=""><?= $data['rujukan_notdis']; ?></textarea>
                    </div>
                  </div>    

                  <a onclick="tambah()" style="cursor:pointer;text-decoration:underline; float:right;">Tambah Isi Surat</a>
                  
                  <div class="form-group">
                  <label class="col-sm-2 control-label" for="isi_surat">Isi Surat</label>
                  <div class="col-sm-8">
                    <table class="table-common dt-responsive" cellpadding="7">
                    <tr>
                    <!-- <label class="col-sm-1 control-label"></label> -->
                    <td>
                    </td>
                        <td>
                        </td>
                    </tr>
                    <?php foreach($isinya as $i){ ?>
                    <tr id="item">
                      <td><textarea name="surat[]" multiple id="surat" class="form-control" rows="3" cols="121"><?= $i['isi_notdis']; ?></textarea><br></td>
                    </tr>
                    <?php } ?>
                    </table>
                    </div>
                    <br>
                  </div>  

                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="file">File</label>
                    <div class="col-sm-8">
                        <p><?= $data['nama_dokumen']; ?></p>
                      <input id="form-control-9" type="file" name="file_notdis" accept="image/*" multiple="multiple">
                    </div>
                  </div>

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

      <!-- Isi Surat -->
        <!-- TAMBAH FILE -->
        <script>
            function tambah(){
                  $(".table-common").append('<tr id="item"><td><textarea name="surat[]" multiple id="surat" class="form-control" rows="3" cols="121"></textarea><br></td></tr>').children(':last');
              }
        </script>
        <!-- AKHIR TAMBAH FILE -->

        <!-- AKHIR TAMBAH FILE -->