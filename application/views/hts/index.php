<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <title>Digital Assigment AKPOL</title>
</head>
<body>
    <table class="table">
    <thead>
        <tr>
        <th scope="col"><br><h2>Terverifikasi! <i class='far fa-calendar-check' style='font-size:36px; float:right;'></i></h2></th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td>
            <center>
            <img src="<?= base_url(); ?>assets/admin/img/logo_akpol.png" alt="" width="20%" height="20%">
            <br><br>NOTA DINAS <br>
            AKADEMI KEPOLISIAN INDONESIA <br>
            NOMOR : <?= $data['nomer_surat_notdis']; ?> <br><br>
            PERIHAL :<br>
            <?= strtoupper( $data['perihal_notdis']); ?> 

            </center>
            <br><hr>
            Pengajuan surat nota dinas tersebut melalui beberapa proses, antara lain sebagai berikut : 
            <br><br>
                        <!-- tabel -->
                        <div class="table-responsive">
                <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>#</th>
                    <th scope="col">Penerima</th>
                    <th scope="col">Status</th>
                    <th scope="col">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach($persetujuan_notdis as $l){ ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?=  strtoupper($l['jabatan_penerima']); ?> | 
                            <?php
                              
                              $id_penerima = $l['id_penerima']; 
                              $get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_penerima'")->row_array();
                              $id_divisi = $get_user['id_divisi'];

                              if($get_user['is_tingkatan'] == 1){
                                $divisi = $this->db->query("SELECT * FROM pimpinan WHERE id_pimpinan='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_pimpinan']);
                              }
                              else if($get_user['is_tingkatan'] == 2){
                                $divisi = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_pelayanan']);
                              }
                              else if($get_user['is_tingkatan'] == 3){
                                $divisi = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_kompartemen']);
                              }
                              else if($get_user['is_tingkatan'] == 4){
                                $divisi = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_satuan_kerja']);
                              }
                              else if($get_user['is_tingkatan'] == 5){
                                $divisi = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
                                echo strtoupper($divisi['nama_unit_kerja']);
                              }
                              ?>
                          </td>
                          <td><?= strtoupper($l['status_persetujuan_notdis']); ?></td>
                          <td><?= $l['tanggal_diacc']; ?></td>
                        </tr>
                      <?php } ?>
                </tbody>
            </table>
            </div>
            <!-- akhir tabel --> 
         
            Dengan ini menjadikan dokumen tersebut sah dan dapat dipergunakan sebagaimana mestinya. <br><br>
            <i>Last Generated : <?= $data['last_genrated']; ?></i><br><br>
        </td>
        </tr>
        <tr>
        <td style="background-color:Beige;" align="center">&copy; 2018-2019 | Akademi Kepolisian Indonesia. SIAK</td>
        </tr>
    </tbody>
    </table>
</body>
</html>