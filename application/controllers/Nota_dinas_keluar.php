<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_dinas_keluar extends CI_Controller {

	function __construct(){
		parent::__construct();
		
		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}

		$this->load->helper('string');
		$this->load->model('M_nota_dinas_keluar_model');
		$this->load->library('ciqrcode'); //pemanggilan library QR CODE
    }

    function index(){
        $data['title'] = "SIPAS | Nota Dinas Keluar";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'nota_dinas_keluar';

		$data['data'] = $this->M_nota_dinas_keluar_model->tampilData();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('nota_dinas/nota_dinas_keluar',$data);
		$this->load->view('templates/footer');
	}

	function hapus_notdis(){
		$this->M_nota_dinas_keluar_model->hapus_data();
		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('nota_dinas_keluar');
	}
	
	function in_detail($id_notdis){
		$data['title'] = "SIPAS | Detail Nota Dinas Keluar";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'nota_dinas_keluar';

		$data['data'] = $this->M_nota_dinas_keluar_model->get_dt_notdis($id_notdis);
		$data['persetujuan_notdis'] = $this->M_nota_dinas_keluar_model->tampil_persetujuan($id_notdis);

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('nota_dinas/detail_notdis',$data);
		$this->load->view('templates/footer');
	}

    function in_tambah(){
		$id = $this->session->userdata("id");

        $data['title'] = "SIPAS | Tambah Nota Dinas Keluar";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'nota_dinas_keluar';

		$divisi = $this->session->userdata('id_divisi');
		$tingkatan = $this->session->userdata('is_tingkatan');
		$get_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan='$tingkatan' AND id_divisi='$divisi' AND jabatan='kepala'")->row_array();
		$id_ = $get_user['id_user'];

		// get data user
		$data['pimpinan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=1 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		$data['pelayanan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		$data['kompartemen'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		$data['satker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		$data['unker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();

		$data['user'] = $this->db->query("SELECT * FROM user WHERE jabatan='kepala' AND id_user!='$id_'")->result_array();

		// untuk ngecek apakah cek_tingkatan terkirim belum
		$this->form_validation->set_rules('cek_tingkatan','Tingkatan','trim|required');

		if($this->form_validation->run()==FALSE){
			$this->load->view('templates/header',$data);
			$this->load->view('templates/sidebar',$data);
			$this->load->view('nota_dinas/in_tambah',$data);
			$this->load->view('templates/footer');
		}
		else{
			date_default_timezone_set("Asia/Jakarta");

			$cek_tingkatan = $this->input->post('cek_tingkatan',TRUE);
			
			if($cek_tingkatan == 'pimpinan'){
				$tingkatan_penerima = 1;
			}
			else if($cek_tingkatan == 'pelayanan'){
				$tingkatan_penerima = 2;
			}
			else if($cek_tingkatan == 'kompartemen'){
				$tingkatan_penerima = 3;
			}
			else if($cek_tingkatan == 'satuan_kerja'){
				$tingkatan_penerima = 4;
			}
			else if($cek_tingkatan == 'unit_kerja'){
				$tingkatan_penerima = 5;
			}

			// data pengirim
			$id_pengirim = $this->session->userdata('id');
			$tingkatan_pengirim = $this->session->userdata('is_tingkatan');

			if($cek_tingkatan != 'all'){
				// data penerima (id user)
				$id_penerima = $this->input->post('isinya',TRUE);
				// mendapatkan data penerima
				$get_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_penerima'")->row_array();
				// untuk nampung id divisi penerima
				$id_divisi_penerima = $get_penerima['id_divisi'];

				// id penerima
				$isinya = "$id_penerima";

				// pengirim pimpinan
				if($tingkatan_pengirim == 1){
					// penerima tujuan itu belum ke get id usernya
					$dataa = array(
						'id' => $isinya,
						'id_divisi_penerima_tujuan' => $id_divisi_penerima,
						'qty'     => 1,
						'price'   =>1,
						'name'    => $id_penerima,
						'tingkatan'  =>  $cek_tingkatan,
						'is_tingkatan'     => $tingkatan_penerima,
						);
	
					$this->cart->insert($dataa);
		
					redirect('nota_dinas_keluar/in_tambah');
				}
				// pengirim pelayanan
				else if($tingkatan_pengirim == 2){
					$cek_gub = 0;
					$cek_biasa = 0;
					
					foreach($this->cart->contents() as $con){
						$t = $con['is_tingkatan'];

						if($t == 1){
							$cek_gub = 1;
						}
						else{
							$cek_biasa = 1;
						}
					}

					if($tingkatan_penerima == 1){
						if($cek_gub == 0){
							if($cek_biasa == 0){
								// penerima tujuan itu belum ke get id usernya
								$dataa = array(
									'id' => $isinya,
									'id_divisi_penerima_tujuan' => $id_divisi_penerima,
									'qty'     => 1,
									'price'   =>1,
									'name'    => $id_penerima,
									'tingkatan'  =>  $cek_tingkatan,
									'is_tingkatan'     => $tingkatan_penerima,
									);
				
								$this->cart->insert($dataa);
								redirect('nota_dinas_keluar/in_tambah');
							}
							else{
								$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    			redirect('nota_dinas_keluar/in_tambah');
							}
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
					else{
						if($cek_gub == 0){
							// penerima tujuan itu belum ke get id usernya
							$dataa = array(
								'id' => $isinya,
								'id_divisi_penerima_tujuan' => $id_divisi_penerima,
								'qty'     => 1,
								'price'   =>1,
								'name'    => $id_penerima,
								'tingkatan'  =>  $cek_tingkatan,
								'is_tingkatan'     => $tingkatan_penerima,
								);

							$this->cart->insert($dataa);
							redirect('nota_dinas_keluar/in_tambah');
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
				}
				// pengirim kompartemen
				else if($tingkatan_pengirim == 3){
					$cek_atasan = 0;
					$cek_biasa = 0;
					$cek_divisi_beda = 0;
					$cek_lvl_4 = 0;
					$cek_lvl_5 = 0;

					foreach($this->cart->contents() as $con){
						$t = $con['is_tingkatan'];
						$id_user = $con['id'];

						if($t == 1 || $t == 2){
							$cek_atasan = 1;	
						}
						else if($t == 3){
							// get data penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_user'")->row_array();
							// how to get id kompartemen penerima surat
							$id_komp= $tampung_penerima['id_divisi'];


							// how to get data id kompartemen pengirim surat
							$id_komp2 = $this->session->userdata('id_divisi');

							if($id_komp != $id_komp2){
								$cek_divisi_beda = 1;
							}
							else{
								$cek_biasa = 1;
							}
						}
						else if($t == 4){
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_user'")->row_array();
							// how to get data id kompartemen
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data id kompartemen pengirim surat
							$id_komp2 = $this->session->userdata('id_divisi');

							if($id_komp != $id_komp2){
								$cek_divisi_beda = 1;
							}
							else{
								$cek_biasa = 1;
								$cek_lvl_4 = 1;
							}
						}
						else if($t == 5){
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_user'")->row_array();
							// how to get data unit kerja
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
							// how to get satker
							$id_satker = $get_unker['id_satuan_kerja'];
							// how to get id kompartemen
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data id kompartemen pengirim surat
							$id_komp2 = $this->session->userdata('id_divisi');

							if($id_komp != $id_komp2){
								$cek_divisi_beda = 1;
							}
							else{
								$cek_biasa = 1;
								$cek_lvl_5 = 1;
							}
						}

					}

					if($tingkatan_penerima == 1 || $tingkatan_penerima == 2 || $tingkatan_penerima == 3){
						if($cek_atasan == 0 && $cek_divisi_beda == 0){
							if($cek_biasa == 0 && $cek_divisi_beda == 0){
								// penerima tujuan itu belum ke get id usernya
								$dataa = array(
									'id' => $isinya,
									'id_divisi_penerima_tujuan' => $id_divisi_penerima,
									'qty'     => 1,
									'price'   =>1,
									'name'    => $id_penerima,
									'tingkatan'  =>  $cek_tingkatan,
									'is_tingkatan'     => $tingkatan_penerima,
									);
				
								$this->cart->insert($dataa);
								redirect('nota_dinas_keluar/in_tambah');
							}
							else{
								$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    			redirect('nota_dinas_keluar/in_tambah');
							}
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
					else if($tingkatan_penerima == 4){
						if($cek_atasan == 0 && $cek_divisi_beda == 0){
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_penerima'")->row_array();
							// how to get data id kompartemen
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data id kompartemen pengirim surat
							$id_komp2 = $this->session->userdata('id_divisi');

							// ngecek kompartemen sama
							if($id_komp == $id_komp2){
									$dataa = array(
										'id' => $isinya,
										'id_divisi_penerima_tujuan' => $id_divisi_penerima,
										'qty'     => 1,
										'price'   =>1,
										'name'    => $id_penerima,
										'tingkatan'  =>  $cek_tingkatan,
										'is_tingkatan'     => $tingkatan_penerima,
										);
					
									$this->cart->insert($dataa);
									redirect('nota_dinas_keluar/in_tambah');
							}
							else{
								if($cek_lvl_4 != 0 ){
									$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke divisi yang berbeda secara bersamaan');
									redirect('nota_dinas_keluar/in_tambah');
								}
								else{
									$dataa = array(
										'id' => $isinya,
										'id_divisi_penerima_tujuan' => $id_divisi_penerima,
										'qty'     => 1,
										'price'   =>1,
										'name'    => $id_penerima,
										'tingkatan'  =>  $cek_tingkatan,
										'is_tingkatan'     => $tingkatan_penerima,
										);
					
									$this->cart->insert($dataa);
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke divisi yang berbeda, tingkatan yang lebih tinggi dan kecil secara bersamaan');
							redirect('nota_dinas_keluar/in_tambah');
						}
					}
					else if($tingkatan_penerima == 5){
						if($cek_atasan == 0 && $cek_divisi_beda == 0){
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_penerima'")->row_array();
							// how to get data unit kerja
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
							// how to get satker
							$id_satker = $get_unker['id_satuan_kerja'];
							// how to get id kompartemen
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data id kompartemen pengirim surat
							$id_komp2 = $this->session->userdata('id_divisi');

							// ngecek kompartemen sama
							if($id_komp == $id_komp2){
								$dataa = array(
									'id' => $isinya,
									'id_divisi_penerima_tujuan' => $id_divisi_penerima,
									'qty'     => 1,
									'price'   =>1,
									'name'    => $id_penerima,
									'tingkatan'  =>  $cek_tingkatan,
									'is_tingkatan'     => $tingkatan_penerima,
									);
				
								$this->cart->insert($dataa);
								redirect('nota_dinas_keluar/in_tambah');
							}
							else{
								if($cek_lvl_4 != 0){
									$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke divisi yang berbeda secara bersamaan');
									redirect('nota_dinas_keluar/in_tambah');
								}
								else{
									$dataa = array(
										'id' => $isinya,
										'id_divisi_penerima_tujuan' => $id_divisi_penerima,
										'qty'     => 1,
										'price'   =>1,
										'name'    => $id_penerima,
										'tingkatan'  =>  $cek_tingkatan,
										'is_tingkatan'     => $tingkatan_penerima,
										);
					
									$this->cart->insert($dataa);
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke divisi yang berbeda, tingkatan yang lebih tinggi dan kecil secara bersamaan');
							redirect('nota_dinas_keluar/in_tambah');
						}
					}					
				}
				// pengirimnya satuan kerja
				else if($tingkatan_pengirim == 4){
					$cek_atasan = 0;
					$cek_biasa = 0;
					$cek_divisi_beda = 0;
					$cek_lvl_4 = 0;
					$cek_lvl_5 = 0;

					foreach($this->cart->contents() as $con){
						$t = $con['is_tingkatan'];
						$id_user = $con['id'];


						if($t == 1 || $t == 2 || $t == 3){
							$cek_atasan = 1;	
						}
						// how to get cek divisi beda
						else if($t == 4){ 
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_user'")->row_array();
							// how to get data id kompartemen
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data id kompartemen pengirim surat
							$id_divisi2 = $this->session->userdata('id_divisi');
							$get_satker2 = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi2'")->row_array();
							$id_komp2 = $get_satker2['id_kompartemen'];

							if($id_komp != $id_komp2){
								$cek_divisi_beda = 1;
							}else{
								$cek_biasa = 1;
								$cek_lvl_4 = 1;
							}
						}
						else if($t == 5){
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_user'")->row_array();
							// how to get data unit kerja
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
							// how to get satker
							$id_satker = $get_unker['id_satuan_kerja'];
							// how to get id kompartemen
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data id kompartemen pengirim surat
							$id_divisi2 = $this->session->userdata('id_divisi');
							$get_satker2 = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi2'")->row_array();
							$id_komp2 = $get_satker2['id_kompartemen'];

							if($id_komp != $id_komp2){
								$cek_divisi_beda = 1;
							}
							else{
								$cek_biasa = 1;
								$cek_lvl_5 = 1;
							}
						}
					}

					if($tingkatan_penerima == 1 || $tingkatan_penerima == 2 || $tingkatan_penerima == 3 || $tingkatan_penerima == 4){
						if($cek_atasan == 0 && $cek_divisi_beda == 0){
							if($cek_biasa == 0 && $cek_divisi_beda == 0){
								// penerima tujuan itu belum ke get id usernya
								$dataa = array(
									'id' => $isinya,
									'id_divisi_penerima_tujuan' => $id_divisi_penerima,
									'qty'     => 1,
									'price'   =>1,
									'name'    => $id_penerima,
									'tingkatan'  =>  $cek_tingkatan,
									'is_tingkatan'     => $tingkatan_penerima,
									);
				
								$this->cart->insert($dataa);
								redirect('nota_dinas_keluar/in_tambah');
							}
							else{
								$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    			redirect('nota_dinas_keluar/in_tambah');
							}
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
					else if($tingkatan_penerima == 5){
						if($cek_atasan == 0 && $cek_divisi_beda == 0){
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_penerima'")->row_array();
							// how to get data unit kerja
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
							// how to get satker
							$id_satker = $get_unker['id_satuan_kerja'];
							// how to get id kompartemen
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data id kompartemen pengirim surat
							$id_satker2 = $this->session->userdata('id_divisi');
							// how to get id kompartemen
							$get_satker2 = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker2'")->row_array();
							$id_komp2 = $get_satker2['id_kompartemen'];

							// ngecek kompartemen sama
							if($id_komp == $id_komp2){
								$dataa = array(
									'id' => $isinya,
									'id_divisi_penerima_tujuan' => $id_divisi_penerima,
									'qty'     => 1,
									'price'   =>1,
									'name'    => $id_penerima,
									'tingkatan'  =>  $cek_tingkatan,
									'is_tingkatan'     => $tingkatan_penerima,
									);
				
								$this->cart->insert($dataa);
								redirect('nota_dinas_keluar/in_tambah');
							}
							else{
								if($cek_lvl_5 != 0){
									$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke divisi yang berbeda secara bersamaan');
									redirect('nota_dinas_keluar/in_tambah');
								}
								else{
									$dataa = array(
										'id' => $isinya,
										'id_divisi_penerima_tujuan' => $id_divisi_penerima,
										'qty'     => 1,
										'price'   =>1,
										'name'    => $id_penerima,
										'tingkatan'  =>  $cek_tingkatan,
										'is_tingkatan'     => $tingkatan_penerima,
										);
					
									$this->cart->insert($dataa);
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
						}
						else{
							$this->session->set_flashdata('msg2','Data2 gagal ditambahkan, tidak bisa mengirim ke divisi yang berbeda kompartemen lebih dari 1');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
					else{
						if($cek_atasan == 0){
							// penerima tujuan itu belum ke get id usernya
							$dataa = array(
								'id' => $isinya,
								'id_divisi_penerima_tujuan' => $id_divisi_penerima,
								'qty'     => 1,
								'price'   =>1,
								'name'    => $id_penerima,
								'tingkatan'  =>  $cek_tingkatan,
								'is_tingkatan'     => $tingkatan_penerima,
								);

							$this->cart->insert($dataa);
							redirect('nota_dinas_keluar/in_tambah');
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
				}
				// pengirim unit kerja
				else if($tingkatan_pengirim == 5){
					$cek_atasan = 0;
					$cek_biasa = 0;
					$cek_divisi_beda = 0;

					foreach($this->cart->contents() as $con){
						$t = $con['is_tingkatan'];
						$id_user = $con['id'];

						if($t == 1 || $t == 2 || $t == 3 || $t == 4){
							$cek_atasan = 1;	
						}
						else if($t == 5){
							// get data user penerima surat
							$tampung_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_user'")->row_array();
							// how to get data unit kerja
							$id_divisi = $tampung_penerima['id_divisi'];
							$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi'")->row_array();
							// how to get satker
							$id_satker = $get_unker['id_satuan_kerja'];
							// how to get id kompartemen
							$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
							$id_komp = $get_satker['id_kompartemen'];

							// how to get data data id kompartemen pengirim surat
							$id_divisi2 = $this->session->userdata('id_divisi');
							$get_unker2 = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi2'")->row_array();
							// how to get satker
							$id_satker2 = $get_unker2['id_satuan_kerja'];
							// how to get id kompartemen
							$get_satker2 = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker2'")->row_array();
							$id_komp2 = $get_satker2['id_kompartemen']; 

							if($id_komp != $id_komp2){
								$cek_divisi_beda = 1;
							}
							else{
								$cek_biasa = 1;
								$cek_lvl_5 = 1;
							}
						}
					}

					if($tingkatan_penerima == 1 || $tingkatan_penerima == 2 || $tingkatan_penerima == 3 || $tingkatan_penerima == 4 || $tingkatan_penerima == 5){
						if($cek_atasan == 0 && $cek_divisi_beda == 0){
							if($cek_biasa == 0 && $cek_divisi_beda == 0){
								// penerima tujuan itu belum ke get id usernya
								$dataa = array(
									'id' => $isinya,
									'id_divisi_penerima_tujuan' => $id_divisi_penerima,
									'qty'     => 1,
									'price'   =>1,
									'name'    => $id_penerima,
									'tingkatan'  =>  $cek_tingkatan,
									'is_tingkatan'     => $tingkatan_penerima,
									);
				
								$this->cart->insert($dataa);
								redirect('nota_dinas_keluar/in_tambah');
							}
							else{
								$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    			redirect('nota_dinas_keluar/in_tambah');
							}
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
					else{
						if($cek_atasan == 0){
							// penerima tujuan itu belum ke get id usernya
							$dataa = array(
								'id' => $isinya,
								'id_divisi_penerima_tujuan' => $id_divisi_penerima,
								'qty'     => 1,
								'price'   =>1,
								'name'    => $id_penerima,
								'tingkatan'  =>  $cek_tingkatan,
								'is_tingkatan'     => $tingkatan_penerima,
								);

							$this->cart->insert($dataa);
							redirect('nota_dinas_keluar/in_tambah');
						}
						else{
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, tidak bisa mengirim ke tingkatan yang lebih tinggi dan kecil secara bersamaan');
                    		redirect('nota_dinas_keluar/in_tambah');
						}
					}
				}	
			}
			// selain all
			else{
				date_default_timezone_set("Asia/Jakarta");
				// get id surat nota dinas
				$notdis = $this->db->query("SELECT * FROM surat_nota_dinas ORDER BY id_surat_notdis DESC LIMIT 1")->row_array();

				if($notdis['id_surat_notdis'] == NULL){
					$id_notdis = 1;
				}
				else{
					$id_notdis = $notdis['id_surat_notdis']+1;
				}
				
				$token = date('s');

				// how to make key surat
				$key_surat =  time().$token;

				// barcode configuration
				$n = base_url("hts/index_history/".$key_surat);
				$m = $key_surat;
				$config['cacheable']    = true; //boolean, the default is true
				$config['cachedir']     = './assets/'; //string, the default is application/cache/
				$config['errorlog']     = './assets/'; //string, the default is application/logs/
				$config['imagedir']     = './assets/images_barcode/'; //direktori penyimpanan qr code
				$config['quality']      = true; //boolean, the default is true
				$config['size']         = '1024'; //interger, the default is 1024
				$config['black']        = array(224,255,255); // array, default is array(255,255,255)
				$config['white']        = array(70,130,180); // array, default is array(0,0,0)
				$this->ciqrcode->initialize($config);
		
				$image_name=$m.'.png'; //buat name dari qr code sesuai dengan nim
		
				$params['data'] = $n; //data yang akan di jadikan QR CODE
				$params['level'] = 'H'; //H=High
				$params['size'] = 50;
				$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
				$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

				if($tingkatan_pengirim == 1 || $tingkatan_pengirim == 2){
					// get pengirim
					$get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_pengirim'")->row_array();
					$id_divisi = $this->session->userdata('id_divisi');
						
					if($tingkatan_pengirim == 1){
						$get = $this->db->query("SELECT * FROM pimpinan WHERE id_pimpinan='$id_divisi'")->row_array();
						$nama_divisi = $get['nama_pimpinan'];
						$atas_surat = strtoupper($nama_divisi).' AKADEMI KEPOLISIAN';
						$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
					}
					else if($tingkatan_pengirim == 2){
						$get = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan='$id_divisi'")->row_array();
						$nama_divisi = $get['nama_pelayanan'];
						$atas_surat = 'KA'.strtoupper($nama_divisi);
						$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
					}
				
					$dari = $id_pengirim;
					$nomer_surat = $this->input->post('nomer_surat',TRUE);
					$tanggal_surat = date("Y-m-d H:i:s", time());
					$nama_pegawai = strtoupper($get_user['nama_user']);
					$status_surat = 0;

					//  Cek jika ada gambar yang ingin di upload
					$upload_file = $_FILES['file_notdis']['name'];
						
					if($upload_file){
						$config['allowed_types'] = 'pdf|doc|docx';
						$config['upload_path'] = './assets/dokumen_notdis/';

						$this->load->library('upload', $config);

						if($this->upload->do_upload('file_notdis')){
							$new_file = $this->upload->data('file_name');
							// kirim nota dinas
							$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
						}
						else{
							$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
							redirect('nota_dinas_keluar/in_tambah');
						}
					}
					else{
						// kirim nota dinas
						$a = '-';
						$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name,$key_surat,$id_notdis);
					}

					foreach($this->cart->contents() as $item) {
						// kirim data kepada surat nota dinas
						$data_kepada = [
							"id_surat_notdis" => $id_notdis,
							"kepada" => $item['id']
						];

						$this->db->insert('kepada_surat_notdis',$data_kepada);

						// kirim data persetujuan
						$data_persetujuan = [
							"id_surat_notdis" => $id_notdis,
							"id_pengirim" => $id_pengirim,
							"id_penerima" => $item['id'],
							"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
							"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
							"tanggal_diacc" => date("Y-m-d H:i:s", time()),
							"status_persetujuan_notdis" => 'Disetujui',
							"is_read" => 1,
							"atas_surat_persetujuan_notdis" => $atas_surat,
							"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
							"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
						];

						$this->db->insert('persetujuan_notdis',$data_persetujuan);
					}

					// kirim tembusan
					$cek = $_POST['tembusan'];

					if($cek!=NULL){
						$i=0;
						$n = count( $_POST['tembusan'] );
						while($i<$n){

							$data_tembusan_kirim = [
								"id_surat" => $id_notdis,
								"id_pengirim_tembusan" => $id_pengirim,
								"id_penerima_tembusan" => $_POST['tembusan'][$i],
								"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
								"jenis_surat" => 1,
								"is_dibaca" => 1,
							];
							$this->db->insert('tembusan_kirim',$data_tembusan_kirim);
							$i++;
						}
					}
				}
				if($tingkatan_pengirim == 3){
					$id_divisi = $this->session->userdata('id_divisi');
					$id = 0;
					$jmlh_data = 0;
					$tingkatan = 0;

					foreach($this->cart->contents() as $con){
						$id = $con['id'];
						$tingkatan = $con['is_tingkatan'];
						$jmlh_data++;
					}

					$get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_pengirim'")->row_array();
					// get komp kepala

					if($get_user['jabatan'] == 'sekretaris'){
						$get_komp_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_divisi' AND jabatan='kepala'")->row_array();
						$id_kepala_komp = $get_komp_kepala['id_user'];

						$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_divisi'")->row_array();
						$nama_kompartemen = $get_kompartemen['nama_kompartemen'];

						// detail surat
						$dari = $id_kepala_komp;
						$nomer_surat = '';
						$tanggal_surat = '0000-00-00 00:00:00';
						$atas_surat = 'KA'.strtoupper($nama_kompartemen);
						$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
						$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

						if($jmlh_data >1 ){
							//  Cek jika ada gambar yang ingin di upload
							$upload_file = $_FILES['file_notdis']['name'];

							if($upload_file){
								$config['allowed_types'] = 'pdf|doc|docx';
								$config['upload_path'] = './assets/dokumen_notdis/';

								$this->load->library('upload', $config);

								if($this->upload->do_upload('file_notdis')){
									$new_file = $this->upload->data('file_name');
									// kirim nota dinas
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
								}
								else{
									$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
							else{
								// kirim nota dinas
								$a = '-';
								$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
							}

							// kirim data persetujuan ke kepala kompartemen
							$data_persetujuan = [
								"id_surat_notdis" => $id_notdis,
								"id_pengirim" => $id_pengirim,
								"id_penerima" => $dari,
								"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
								"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
								"tanggal_diacc" =>'0000-00-00 00:00:00',
								"status_persetujuan_notdis" => 'Diajukan',
								"is_read" => 1,
								"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($nama_kompartemen),
								"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
								"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
							];
							$this->db->insert('persetujuan_notdis',$data_persetujuan);


							foreach($this->cart->contents() as $item) {
								// kirim data kepada surat nota dinas
								$data_kepada = [
									"id_surat_notdis" => $id_notdis,
									"kepada" => $item['id']
								];
								$this->db->insert('kepada_surat_notdis',$data_kepada);
	
								// kirim data persetujuan
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $dari,
									"id_penerima" => $item['id'],
									"nomer_surat_persetujuan_notdis" =>  ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" =>'0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => $atas_surat,
									"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
									"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
								];
	
								$this->db->insert('persetujuan_notdis',$data_persetujuan);
							}


							// kirim tembusan
							$cek = $_POST['tembusan'];

							if($cek!=NULL){
								$i=0;
								$n = count( $_POST['tembusan'] );
								while($i<$n){

									$data_tembusan_kirim = [
										"id_surat" => $id_notdis,
										"id_pengirim_tembusan" => $dari,
										"id_penerima_tembusan" => $_POST['tembusan'][$i],
										"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
										"jenis_surat" => 1,
										"is_dibaca" => 0,
									];
									$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

									$i++;
								}
							}

						}
						else{
							// ngirim ke gubernur
							if($tingkatan == 1){
								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}

								// kirim data persetujuan ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $dari,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" =>'0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($nama_kompartemen),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
		
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									// get pelayanan taud
									$get_pelayanan_taud = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_divisi=2")->row_array();
									$get_taud = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan=2")->row_array();
		
									// kirim data persetujuan
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $dari,
										"id_penerima" => $get_pelayanan_taud['id_user'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($nama_kompartemen),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan
									$data_persetujuan2 = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $get_pelayanan_taud['id_user'],
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"id_penerima" => $item['id'],
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_taud['nama_pelayanan']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_pelayanan_taud['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_pelayanan_taud['pangkat']).' NRP '.strtoupper($get_pelayanan_taud['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan2);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);
										$i++;
									}
								}

							}
							// ngirim ke pelayanan taud atau kompartemen lain
							else if($tingkatan == 2 || $tingkatan == 3){
								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';

									$this->load->library('upload', $config);

									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
								}

								// kirim data persetujuan ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $dari,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" =>'0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($nama_kompartemen),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);
		
									// kirim data persetujuan
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $dari,
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" =>  ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" =>'0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => $atas_surat,
										"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
										"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
									];
		
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}
							}

							else if($tingkatan == 4){
								foreach($this->cart->contents() as $item) {
									$penerima = $item['id'];
								}

								// get id penerima, kompartemen satuan kerja
								$get_penerima_satker = $this->db->query("SELECT * FROM user WHERE id_user='$penerima'")->row_array();
								// id satker penerima surat
								$id_satker = $get_penerima_satker['id_divisi'];
								$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
								// id komp penerima surat
								$id_komp = $get_satker['id_kompartemen'];

								// ------------------------------------------------------
								// get id user kompartemen pada penerima tujuan satkernya
								$get_user_komp = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_komp' AND jabatan='kepala'")->row_array();
								$id_penerima_komp = $get_user_komp['id_user'];

								// sama tidak kompartemennya dengan penerima surat
								if($id_komp == $id_divisi){
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
									}

									// kirim data persetujuan ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $dari,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" =>'0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($nama_kompartemen),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" =>  ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" =>'0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
			
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}

								}
								// pengirim kompartemen ke satker (beda kompartemen)
								else{
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
									}

									// kirim data persetujuan ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $dari,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" =>'0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($nama_kompartemen),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									foreach($this->cart->contents() as $item) {
										$to =  $item['id'];
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_penerima_komp
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $id_penerima_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							}

							else if($tingkatan == 5){
								foreach($this->cart->contents() as $item) {
									$penerima = $item['id'];
								}

								// get id penerima, kompartemen unit kerja
								$get_penerima_unker = $this->db->query("SELECT * FROM user WHERE id_user='$penerima'")->row_array();
								$id_unker = $get_penerima_unker['id_divisi'];
								$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_unker'")->row_array();
								$id_satker = $get_unker['id_satuan_kerja'];
								$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
								$id_komp = $get_satker['id_kompartemen'];

								// get id user kompartemen pada penerima tujuan satkernya
								$get_user_komp = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_komp' AND jabatan='kepala'")->row_array();
								$id_penerima_komp = $get_user_komp['id_user'];

								if($id_komp == $id_divisi){
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
									}

									// kirim data persetujuan ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $dari,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" =>'0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($nama_kompartemen),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" =>  ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" =>'0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
			
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
									
								}
								else{
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
									}

									// kirim data persetujuan ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $dari,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" =>'0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'PLH. SEK '.strtoupper($nama_kompartemen),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									foreach($this->cart->contents() as $item) {
										$to =  $item['id'];
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_penerima_komp
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $id_penerima_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							}
						}

					}
					else if($get_user['jabatan'] == 'kepala'){
						$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_divisi'")->row_array();
						$nama_kompartemen = $get_kompartemen['nama_kompartemen'];

						// detail surat
						$dari = $id_pengirim;
						$nomer_surat = $this->input->post('nomer_surat',TRUE);
						$tanggal_surat = date("Y-m-d H:i:s", time());
						$atas_surat = 'KA'.strtoupper($nama_kompartemen);
						$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
						$nama_pegawai = strtoupper($get_user['nama_user']);

						if($jmlh_data >1 ){
							//  Cek jika ada gambar yang ingin di upload
							$upload_file = $_FILES['file_notdis']['name'];

							if($upload_file){
								$config['allowed_types'] = 'pdf|doc|docx';
								$config['upload_path'] = './assets/dokumen_notdis/';

								$this->load->library('upload', $config);

								if($this->upload->do_upload('file_notdis')){
									$new_file = $this->upload->data('file_name');
									// kirim nota dinas
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat, $id_notdis);
								}
								else{
									$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
							else{
								// kirim nota dinas
								$a = '-';
								$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat, $id_notdis);
							}


							foreach($this->cart->contents() as $item) {
								// kirim data kepada surat nota dinas
								$data_kepada = [
									"id_surat_notdis" => $id_notdis,
									"kepada" => $item['id']
								];
								$this->db->insert('kepada_surat_notdis',$data_kepada);
	
								// kirim data persetujuan
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $dari,
									"id_penerima" => $item['id'],
									"nomer_surat_persetujuan_notdis" =>  $nomer_surat,
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => date("Y-m-d H:i:s", time()),
									"status_persetujuan_notdis" => 'Disetujui',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => $atas_surat,
									"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
									"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
								];
	
								$this->db->insert('persetujuan_notdis',$data_persetujuan);
							}


							// kirim tembusan
							$cek = $_POST['tembusan'];

							if($cek!=NULL){
								$i=0;
								$n = count( $_POST['tembusan'] );
								while($i<$n){

									$data_tembusan_kirim = [
										"id_surat" => $id_notdis,
										"id_pengirim_tembusan" => $id_pengirim,
										"id_penerima_tembusan" => $_POST['tembusan'][$i],
										"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
										"jenis_surat" => 1,
										"is_dibaca" => 1
									];
									$this->db->insert('tembusan_kirim',$data_tembusan_kirim);
									$i++;
								}
							}

						}
						else{
							if($tingkatan == 1){
								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
								}

								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
		
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									// get pelayanan taud
									$get_pelayanan_taud = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_divisi=2")->row_array();
									$get_taud = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan=2")->row_array();
		
									// kirim data persetujuan
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $dari,
										"id_penerima" => $get_pelayanan_taud['id_user'],
										"nomer_surat_persetujuan_notdis" => $nomer_surat,
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => $atas_surat,
										"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
										"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan
									$data_persetujuan2 = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $get_pelayanan_taud['id_user'],
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_taud['nama_pelayanan']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_pelayanan_taud['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_pelayanan_taud['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan2);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $id_pengirim,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
											"jenis_surat" => 1,
											"is_dibaca" => 1
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}

							} /* akhir ngirim ke gubernur */

							// ngirim ke pelayanan taud atau kompartemen lain
							else if($tingkatan == 2 || $tingkatan == 3){
								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';

									$this->load->library('upload', $config);

									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
								}

								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);
		
									// kirim data persetujuan
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $dari,
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" =>  $nomer_surat,
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => date("Y-m-d H:i:s", time()),
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => $atas_surat,
										"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
										"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
									];
		
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $id_pengirim,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}
							}
							
							else if($tingkatan == 4){
								foreach($this->cart->contents() as $item) {
									$penerima = $item['id'];
								}

								// get id penerima, kompartemen satuan kerja
								$get_penerima_satker = $this->db->query("SELECT * FROM user WHERE id_user='$penerima'")->row_array();
								// id satker penerima surat
								$id_satker = $get_penerima_satker['id_divisi'];
								$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
								// id komp penerima surat
								$id_komp = $get_satker['id_kompartemen'];

								// ------------------------------------------------------
								// get id user kompartemen pada penerima tujuan satkernya
								$get_user_komp = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_komp' AND jabatan='kepala'")->row_array();
								$id_penerima_komp = $get_user_komp['id_user'];

								// sama tidak kompartemennya dengan penerima surat
								if($id_komp == $id_divisi){
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat, $id_notdis);
									}

									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => date("Y-m-d H:i:s", time()),
											"status_persetujuan_notdis" => 'Disetujui',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
			
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $id_pengirim,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
												"jenis_surat" => 1,
												"is_dibaca" => 1,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}

								}
								// pengirim kompartemen ke satker (beda kompartemen)
								else{
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
									}

									foreach($this->cart->contents() as $item) {
										$to =  $item['id'];
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_penerima_komp
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $id_penerima_komp,
											"nomer_surat_persetujuan_notdis" => $nomer_surat,
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $id_pengirim,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							}

							else if($tingkatan == 5){
								foreach($this->cart->contents() as $item) {
									$penerima = $item['id'];
								}

								// get id penerima, kompartemen unit kerja
								$get_penerima_unker = $this->db->query("SELECT * FROM user WHERE id_user='$penerima'")->row_array();
								$id_unker = $get_penerima_unker['id_divisi'];
								$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_unker'")->row_array();
								$id_satker = $get_unker['id_satuan_kerja'];
								$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
								$id_komp = $get_satker['id_kompartemen'];

								// get id user kompartemen pada penerima tujuan satkernya
								$get_user_komp = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_komp' AND jabatan='kepala'")->row_array();
								$id_penerima_komp = $get_user_komp['id_user'];

								if($id_komp == $id_divisi){
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat, $id_notdis);
									}

									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" =>  $nomer_surat,
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => date("Y-m-d H:i:s", time()),
											"status_persetujuan_notdis" => 'Disetujui',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
			
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $id_pengirim,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
												"jenis_surat" => 1,
												"is_dibaca" => 1,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
									
								}
								else{
									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
									}

									foreach($this->cart->contents() as $item) {
										$to =  $item['id'];
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_penerima_komp
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
			
										// kirim data persetujuan
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $dari,
											"id_penerima" => $id_penerima_komp,
											"nomer_surat_persetujuan_notdis" => $nomer_surat,
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => date("Y-m-d H:i:s", time()),
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => $atas_surat,
											"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
											"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){
										
											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $id_pengirim,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							}					
						}
					}
				} /* akhir pengirim kompartemen */
				else if($tingkatan_pengirim == 4){
					$jmlh_data = 0;
					$id = 0;
					$tingkatan = 0;
					$id_divisi_penerima_tujuan = 0;

					$id_divisi = $this->session->userdata('id_divisi');

					foreach($this->cart->contents() as $con){
						$id = $con['id'];
						$tingkatan = $con['is_tingkatan'];
						$id_divisi_penerima_tujuan = $con['id_divisi_penerima_tujuan'];
						$jmlh_data++;
					}

					$get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_pengirim'")->row_array();

					// get komp kepala
					$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi'")->row_array();
					$divisi_komp = $get_satker['id_kompartemen'];

					// get kompartemen pengirim surat
					$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$divisi_komp'")->row_array();

					// get id user kepala dan sekretaris kompartemen pengirim surat
					$get_komp_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$divisi_komp' AND jabatan='kepala'")->row_array();
					$get_komp_sek = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$divisi_komp' AND jabatan='sekretaris'")->row_array();
					$id_kepala_komp = $get_komp_kepala['id_user'];
					$id_sek_komp = $get_komp_sek['id_user'];

					// get satker kepala
					$get_kepala_satker = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_divisi='$id_divisi' AND jabatan='kepala'")->row_array();
					$get_sek_satker = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_divisi='$id_divisi' AND jabatan='sekretaris'")->row_array();
					$id_kepala_satker = $get_kepala_satker['id_user'];
					$id_sek_satker = $get_sek_satker['id_user'];

					if($get_user['jabatan'] == 'sekretaris'){
						if($jmlh_data >1 ){
							// detail surat
							$dari = $id_kepala_satker;
							$nomer_surat = '';
							$tanggal_surat = '0000-00-00 00:00:00';
							$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
							$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
							$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

							//  Cek jika ada gambar yang ingin di upload
							$upload_file = $_FILES['file_notdis']['name'];

							if($upload_file){
								$config['allowed_types'] = 'pdf|doc|docx';
								$config['upload_path'] = './assets/dokumen_notdis/';

								$this->load->library('upload', $config);

								if($this->upload->do_upload('file_notdis')){
									$new_file = $this->upload->data('file_name');
									// kirim nota dinas
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat, $id_notdis);
								}
								else{
									$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
							else{
								// kirim nota dinas
								$a = '-';
								$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat, $id_notdis);
							}

							// kirim data persetujuan ke kepala kompartemen
							$data_persetujuan = [
								"id_surat_notdis" => $id_notdis,
								"id_pengirim" => $id_pengirim,
								"id_penerima" => $dari,
								"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
								"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
								"tanggal_diacc" =>'0000-00-00 00:00:00',
								"status_persetujuan_notdis" => 'Diajukan',
								"is_read" => 1,
								"atas_surat_persetujuan_notdis" => 'PLH. SEK '.strtoupper($get_satker['nama_satuan_kerja']),
								"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
								"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
							];
							$this->db->insert('persetujuan_notdis',$data_persetujuan);

							foreach($this->cart->contents() as $item) {
								// kirim data kepada surat nota dinas
								$data_kepada = [
									"id_surat_notdis" => $id_notdis,
									"kepada" => $item['id']
								];
								$this->db->insert('kepada_surat_notdis',$data_kepada);
	
								// kirim data persetujuan
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $dari,
									"id_penerima" => $item['id'],
									"nomer_surat_persetujuan_notdis" =>  ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" =>'0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => $atas_surat,
									"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
									"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
								];
	
								$this->db->insert('persetujuan_notdis',$data_persetujuan);
							}

							// kirim tembusan
							$cek = $_POST['tembusan'];

							if($cek!=NULL){
								$i=0;
								$n = count( $_POST['tembusan'] );
								while($i<$n){

									$data_tembusan_kirim = [
										"id_surat" => $id_notdis,
										"id_pengirim_tembusan" => $dari,
										"id_penerima_tembusan" => $_POST['tembusan'][$i],
										"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
										"jenis_surat" => 1,
										"is_dibaca" => 0,
									];
									$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

									$i++;
								}
							}

						} /* akhir jummlah>1 */
						else{
							// ngirim ke gubernur
							if($tingkatan == 1){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}

								// kirim data persetujuan dari sekretaris satker ke kepala satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_kepala_satker,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_satker,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan); 

								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala kompartemen ke kepala taud
								// get pelayanan taud
								$get_taud = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan=2")->row_array();
								// get user pelayanan taud
								$get_pelayanan_taud = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_divisi=2")->row_array();

								
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_komp,
									"id_penerima" => $get_pelayanan_taud['id_user'],
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala kompartemen ke kepala pimpinan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $get_pelayanan_taud['id_user'],
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_taud['nama_pelayanan']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_pelayanan_taud['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_pelayanan_taud['pangkat']).' NRP '.strtoupper($get_pelayanan_taud['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}

							} /* akhir ngirim ke gubernur */


							// ngirim ke pelayanan
							if($tingkatan == 2){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}

								// kirim data persetujuan dari sekretaris satker ke kepala satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_kepala_satker,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_satker,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan); 

								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala kompartemen ke pelayanan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_komp,
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}

							} /* akhir ngirim ke pelayanan */
							else if($tingkatan == 3){
								if($divisi_komp == $id_divisi_penerima_tujuan){
									// detail surat
									$dari = $id_kepala_satker;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
									$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sek kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
								else{
									// detail surat
									$dari = $id_kepala_komp;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala kompartemen ke pelayanan
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_komp,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							}
							else if($tingkatan == 4){
								// get kompartemen penerima surat ya bukan pengirim surat
								$satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_kompartemen = $satker['id_kompartemen'];
								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen'")->row_array();

								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='kepala'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								if($divisi_komp == $id_kompartemen){
									// detail surat
									$dari = $id_kepala_satker;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
									$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);


									// kirim data persetujuan dari sek kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_satker,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $id_kepala_satker,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
								else{
									// detail surat
									$dari = $id_kepala_komp;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala kompartemen ke pelayanan
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_komp_penerima_kepala
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada); 

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_komp,
											"id_penerima" => $id_komp_penerima_kepala,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}

							}
							else if($tingkatan == 5){
								// get kompartemen penerima surat ya bukan pengirim surat
								$unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_satker = $unker['id_satuan_kerja'];
								$satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
								$id_kompartemen = $satker['id_kompartemen'];
								

								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='kepala'")->row_array();
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								if($divisi_komp == $id_kompartemen){
									// detail surat
									$dari = $id_kepala_satker;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
									$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);


									// kirim data persetujuan dari sek kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_satker,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $id_kepala_satker,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
								else{
									// detail surat
									$dari = $id_kepala_komp;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala kompartemen ke pelayanan
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_komp_penerima_kepala
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada); 

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_komp,
											"id_penerima" => $id_komp_penerima_kepala,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							}
						}
					}
					else if($get_user['jabatan'] == 'kepala'){
						if($jmlh_data >1 ){
							// detail surat
							$dari = $id_pengirim;
							$nomer_surat = $this->input->post('nomer_surat',TRUE);
							$tanggal_surat = date("Y-m-d H:i:s", time());
							$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
							$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
							$nama_pegawai = strtoupper($get_user['nama_user']);

							//  Cek jika ada gambar yang ingin di upload
							$upload_file = $_FILES['file_notdis']['name'];

							if($upload_file){
								$config['allowed_types'] = 'pdf|doc|docx';
								$config['upload_path'] = './assets/dokumen_notdis/';

								$this->load->library('upload', $config);

								if($this->upload->do_upload('file_notdis')){
									$new_file = $this->upload->data('file_name');
									// kirim nota dinas
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
								}
								else{
									$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
							else{
								// kirim nota dinas
								$a = '-';
								$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
							}

							foreach($this->cart->contents() as $item) {
								// kirim data kepada surat nota dinas
								$data_kepada = [
									"id_surat_notdis" => $id_notdis,
									"kepada" => $item['id']
								];
								$this->db->insert('kepada_surat_notdis',$data_kepada);
	
								// kirim data persetujuan
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $dari,
									"id_penerima" => $item['id'],
									"nomer_surat_persetujuan_notdis" => $nomer_surat,
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => date("Y-m-d H:i:s", time()),
									"status_persetujuan_notdis" => 'Disetujui',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => $atas_surat,
									"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
									"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
								];
	
								$this->db->insert('persetujuan_notdis',$data_persetujuan);
							}

							// kirim tembusan
							$cek = $_POST['tembusan'];

							if($cek!=NULL){
								$i=0;
								$n = count( $_POST['tembusan'] );
								while($i<$n){

									$data_tembusan_kirim = [
										"id_surat" => $id_notdis,
										"id_pengirim_tembusan" => $dari,
										"id_penerima_tembusan" => $_POST['tembusan'][$i],
										"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
										"jenis_surat" => 1,
										"is_dibaca" => 1,
									];
									$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

									$i++;
								}
							}

						} /* akhir jummlah>1 */
						else{
							// ngirim ke gubernur
							if($tingkatan == 1){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}

								// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan); 

								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala kompartemen ke kepala taud
								// get pelayanan taud
								$get_taud = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan=2")->row_array();
								// get user pelayanan taud
								$get_pelayanan_taud = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_divisi=2")->row_array();

								
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_komp,
									"id_penerima" => $get_pelayanan_taud['id_user'],
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala kompartemen ke kepala pimpinan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $get_pelayanan_taud['id_user'],
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_taud['nama_pelayanan']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_pelayanan_taud['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_pelayanan_taud['pangkat']).' NRP '.strtoupper($get_pelayanan_taud['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $id_kepala_komp,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}

							} /* akhir ngirim ke gubernur */

							// ngirim ke pelayanan
							if($tingkatan == 2){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}

								// kirim data persetujuan dari sekretaris satker ke kepala satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala kompartemen ke pelayanan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_komp,
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $id_kepala_komp,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}

							} /* akhir ngirim ke pelayanan */
							else if($tingkatan == 3){
								if($divisi_komp == $id_divisi_penerima_tujuan){
									// detail surat
									$dari = $id_pengirim;
									$nomer_surat = $this->input->post('nomer_surat',TRUE);
									$tanggal_surat = date("Y-m-d H:i:s", time());
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
									$nama_pegawai = strtoupper($get_user['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sek kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
								else{
									// detail surat
									$dari = $id_kepala_komp;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => $nomer_surat,
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala kompartemen ke pelayanan
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_komp,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							} /* akhir ngirim ke kompartemen */
							else if($tingkatan == 4){
								// get kompartemen penerima surat ya bukan pengirim surat
								$satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_kompartemen = $satker['id_kompartemen'];
								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen'")->row_array();

								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='kepala'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								if($divisi_komp == $id_kompartemen){
									// detail surat
									$dari = $id_pengirim;
									$nomer_surat = $this->input->post('nomer_surat',TRUE);
									$tanggal_surat = date("Y-m-d H:i:s", time());;
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
									$nama_pegawai = strtoupper($get_user['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sek kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => $nomer_surat,
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => date("Y-m-d H:i:s", time()),
											"status_persetujuan_notdis" => 'Disetujui',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $id_pengirim,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
												"jenis_surat" => 1,
												"is_dibaca" => 1,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								} /* akhir ngirim ke satker yang sama kompartemennya */
								else{
									// detail surat
									$dari = $id_kepala_komp;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala kompartemen ke pelayanan
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_komp_penerima_kepala
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada); 

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_komp,
											"id_penerima" => $id_komp_penerima_kepala,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								} /* akhir ngirim ke satker yang beda kompartemen */

							} /* akhir ngirim ke satker */
							else if($tingkatan == 5){
								// get kompartemen penerima surat ya bukan pengirim surat
								$unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_satker = $unker['id_satuan_kerja'];
								$satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();
								$id_kompartemen = $satker['id_kompartemen'];
			

								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='kepala'")->row_array();
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								if($divisi_komp == $id_kompartemen){
									// detail surat
									$dari = $id_pengirim;
									$nomer_surat = $this->input->post('nomer_surat',TRUE);
									$tanggal_surat = date("Y-m-d H:i:s", time());
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
									$nama_pegawai = strtoupper($get_user['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sek kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => $nomer_surat,
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => date("Y-m-d H:i:s", time()),
											"status_persetujuan_notdis" => 'Disetujui',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => date("Y-m-d H:i:s", time()),
												"jenis_surat" => 1,
												"is_dibaca" => 1,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
								else{
									// detail surat
									$dari = $id_kepala_komp;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala kompartemen ke pelayanan
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_komp_penerima_kepala
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada); 

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_komp,
											"id_penerima" => $id_komp_penerima_kepala,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}
							} /* akhir ngirim ke unit kerja */

						} /* akhir ngirim jumlah=1 kepala satker */

					} /* akhir pengirim kepala satker */

				} /* akhir ngirim sebagai satker */
				else if($tingkatan_pengirim == 5){
					$jmlh_data = 0;
					$id = 0;
					$tingkatan = 0;
					$id_divisi_penerima_tujuan = 0;

					foreach($this->cart->contents() as $con){
						$id = $con['id'];
						$tingkatan = $con['is_tingkatan'];
						$id_divisi_penerima_tujuan = $con['id_divisi_penerima_tujuan'];
						$jmlh_data++;
					}

					$get_user = $this->db->query("SELECT * FROM user WHERE id_user='$id_pengirim'")->row_array();

					// get kompartemen pengirim surat
					$divisi_unker = $this->session->userdata('id_divisi');
					$get_unker = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$divisi_unker'")->row_array();
					$divisi_satker = $get_unker['id_satuan_kerja'];
					$get_satker = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$divisi_satker'")->row_array();
					$divisi_komp = $get_satker['id_kompartemen'];
					$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$divisi_komp'")->row_array();

					// get id kompartemen pengirim surat
					$get_komp_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$divisi_komp' AND jabatan='kepala'")->row_array();
					$get_komp_sek = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$divisi_komp' AND jabatan='sekretaris'")->row_array();
					$id_kepala_komp = $get_komp_kepala['id_user'];
					$id_sek_komp = $get_komp_sek['id_user'];

					// get satker pengirim surat
					$get_kepala_satker = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_divisi='$divisi_satker' AND jabatan='kepala'")->row_array();
					$get_sekretaris_satker = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_divisi='$divisi_satker' AND jabatan='sekretaris'")->row_array();
					$id_kepala_satker = $get_kepala_satker['id_user'];
					$id_sekretaris_satker = $get_sekretaris_satker['id_user'];

					// get unker kepala
					$get_kepala_unker= $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_divisi='$divisi_unker' AND jabatan='kepala'")->row_array();
					$id_kepala_unker= $get_kepala_unker['id_user'];

					
					if($get_user['jabatan'] == 'sekretaris'){
						if($jmlh_data >1 ){
							// detail surat
							$dari = $id_kepala_unker;
							$nomer_surat = '';
							$tanggal_surat = '0000-00-00 00:00:00';
							$atas_surat = 'KA'.strtoupper($get_unker['nama_unit_kerja']);
							$pangkat_nrp = strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']);
							$nama_pegawai = strtoupper($get_kepala_unker['nama_user']);

							//  Cek jika ada gambar yang ingin di upload
							$upload_file = $_FILES['file_notdis']['name'];

							if($upload_file){
								$config['allowed_types'] = 'pdf|doc|docx';
								$config['upload_path'] = './assets/dokumen_notdis/';

								$this->load->library('upload', $config);

								if($this->upload->do_upload('file_notdis')){
									$new_file = $this->upload->data('file_name');
									// kirim nota dinas
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
								}
								else{
									$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
									redirect('nota_dinas_keluar/in_tambah');
								}
							}
							else{
								// kirim nota dinas
								$a = '-';
								$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
							}

							// kirim data persetujuan ke kepala kompartemen
							$data_persetujuan = [
								"id_surat_notdis" => $id_notdis,
								"id_pengirim" => $id_pengirim,
								"id_penerima" => $dari,
								"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
								"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
								"tanggal_diacc" =>'0000-00-00 00:00:00',
								"status_persetujuan_notdis" => 'Diajukan',
								"is_read" => 1,
								"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
								"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
								"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
							];
							$this->db->insert('persetujuan_notdis',$data_persetujuan);

							foreach($this->cart->contents() as $item) {
								// kirim data kepada surat nota dinas
								$data_kepada = [
									"id_surat_notdis" => $id_notdis,
									"kepada" => $item['id']
								];
								$this->db->insert('kepada_surat_notdis',$data_kepada);
	
								// kirim data persetujuan
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $dari,
									"id_penerima" => $item['id'],
									"nomer_surat_persetujuan_notdis" =>  ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" =>'0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => $atas_surat,
									"nama_pegawai_persetujuan_notdis" => $nama_pegawai,
									"pangkat_nrp_persetujuan_notdis" => $pangkat_nrp,
								];
	
								$this->db->insert('persetujuan_notdis',$data_persetujuan);
							}

							// kirim tembusan
							$cek = $_POST['tembusan'];

							if($cek!=NULL){
								$i=0;
								$n = count( $_POST['tembusan'] );
								while($i<$n){

									$data_tembusan_kirim = [
										"id_surat" => $id_notdis,
										"id_pengirim_tembusan" => $dari,
										"id_penerima_tembusan" => $_POST['tembusan'][$i],
										"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
										"jenis_surat" => 1,
										"is_dibaca" => 0,
									];
									$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

									$i++;
								}
							}
						}
						else{
							if($tingkatan == 1){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}


								// kirim data persetujuan dari sekretaris unker ke kepala unker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_kepala_unker,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala unker ke sekretaris satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_unker,
									"id_penerima" => $id_sekretaris_satker,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan); 

								// kirim data persetujuan dari sekretaris satker ke kepala satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sekretaris_satker,
									"id_penerima" => $id_kepala_satker,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_satker,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								// kirim data persetujuan dari kepala kompartemen ke kepala taud
								// get pelayanan taud
								$get_taud = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan=2")->row_array();
								// get user pelayanan taud
								$get_pelayanan_taud = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_divisi=2")->row_array();

								
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_komp,
									"id_penerima" => $get_pelayanan_taud['id_user'],
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								// kirim data persetujuan dari kepala kompartemen ke kepala pimpinan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $get_pelayanan_taud['id_user'],
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_taud['nama_pelayanan']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_pelayanan_taud['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_pelayanan_taud['pangkat']).' NRP '.strtoupper($get_pelayanan_taud['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}
							} /* akhir ngirim ke gubernur */
							else if($tingkatan == 2){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}

								// kirim data persetujuan dari sekretaris unker ke kepala unker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_kepala_unker,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala unker ke sekretaris satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_unker,
									"id_penerima" => $id_sekretaris_satker,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan); 

								// kirim data persetujuan dari sekretaris satker ke kepala satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sekretaris_satker,
									"id_penerima" => $id_kepala_satker,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_satker,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								// kirim data persetujuan dari kepala kompartemen ke kepala pelayanan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_komp,
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}
							} /* akhir ngirim ke pelayanan sebagai sekretaris unker */
							else if($tingkatan == 3){
								// get sekretaris kompartemen penerima surat
								$get_sekretaris_kompartemen_penerima = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_divisi_penerima_tujuan' AND jabatan='sekretaris'")->row_array();
								$id_sekretaris_kompartemen_penerima = $get_sekretaris_kompartemen_penerima['id_user'];

								// get kepala kompartemen penerima surat
								$get_kepala_kompartemen_penerima = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_divisi_penerima_tujuan' AND jabatan='kepala'")->row_array();
								$id_kepala_kompartemen_penerima = $get_kepala_kompartemen_penerima['id_user'];

								// get kompartemen penerima surat
								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_divisi_penerima_tujuan'")->row_array();

								if($divisi_komp == $id_divisi_penerima_tujuan){
									$dari = $id_kepala_satker;
									$nomer_surat = ' ';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
									$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris unker ke kepala unker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_unker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari kepala unker ke sekretaris satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_unker,
										"id_penerima" => $id_sekretaris_satker,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sekretaris_satker,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}

								} /* akhir ngirim ke kompartemen yang sama */
								else{
									$dari = $id_kepala_komp;
									$nomer_surat = ' ';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris unker ke kepala unker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_unker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 

									// kirim data persetujuan dari kepala unker ke sekretaris satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_unker,
										"id_penerima" => $id_sekretaris_satker,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sekretaris_satker,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);


									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);


									// kirim data persetujuan dari kepala kompartemen ke kepala kompartemen lain
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_kepala_kompartemen_penerima
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										// kirim data persetujuan dari kepala kompartemen ke kompartemen lain
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_komp,
											"id_penerima" => $id_kepala_kompartemen_penerima,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}

							} /* akhir ngirim ke kompartemen */
							else if($tingkatan == 4){

								// get kompartemen penerima surat ya bukan pengirim surat
								$satker_penerima = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_kompartemen_penerima = $satker_penerima['id_kompartemen'];
								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen_penerima'")->row_array();

								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='kepala'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								if($divisi_satker == $id_divisi_penerima_tujuan){
									// detail surat
									$dari = $id_kepala_unker;
									$nomer_surat = '';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_unker['nama_unit_kerja']);
									$pangkat_nrp = strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']);
									$nama_pegawai = strtoupper($get_kepala_unker['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari sekretaris unker ke kepala unker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_unker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 


									// kirim data persetujuan dari kepala unker ke kepala unker
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										// ngirim dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_unker,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}

								}
								// ngirim ke satuan kerja yang tidak satu jenis dengan dia
								else{
									// jika ngirim ke beda satker tapi masih satu kompartemen
									if($divisi_komp == $id_kompartemen_penerima){
										// detail surat
										$dari = $id_kepala_satker;
										$nomer_surat = '';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
										$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
										$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';

											$this->load->library('upload', $config);

											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
										}

										// kirim data persetujuan dari sekretaris unker ke kepala unker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_kepala_unker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 

										// ngirim dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_unker,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari kepala unker ke kepala unker
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $item['id']
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);

											
											// ngirim dari sekretaris satker ke kepala satker
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_satker,
												"id_penerima" => $item['id'],
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);
										}

										// kirim tembusan
										$cek = $_POST['tembusan'];

										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){

												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

												$i++;
											}
										}
									}

									// jika ngirim ke beda satker dan juga beda kompartemen
									else{
										$dari = $id_kepala_komp;
										$nomer_surat = ' ';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
										$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
										$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';

											$this->load->library('upload', $config);

											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
										}

										// kirim data persetujuan dari sekretaris unker ke kepala unker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_kepala_unker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 

										// kirim data persetujuan dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_unker,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_satker,
											"id_penerima" => $id_sek_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $id_kepala_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari kepala kompartemen ke kepala kompartemen lain
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $id_komp_penerima_kepala
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);

											// kirim data persetujuan dari kepala kompartemen ke kompartemen lain
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_komp,
												"id_penerima" => $id_komp_penerima_kepala,
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);

										}

										// kirim tembusan
										$cek = $_POST['tembusan'];

										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){

												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

												$i++;
											}
										}

									} // akhir jika ngirim ke beda satker dan juga beda kompartemen
								}

							} /* akhir ngirim ke satuan kerja */
							else if($tingkatan == 5){

								// get kompartemen penerima surat ya bukan pengirim surat
								$unker_penerima = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_satker_penerima = $unker_penerima['id_satuan_kerja'];
								$satker_penerima = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker_penerima'")->row_array();
								$id_kompartemen_penerima = $satker_penerima['id_kompartemen'];

								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen_penerima'")->row_array();

								// get user
								$get_satker_penerima_kepala =  $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_divisi='$id_satker_penerima' AND jabatan='kepala'")->row_array();
								$id_satker_penerima_kepala  = $get_satker_penerima_kepala['id_user'];
								
								// get user
								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='kepala'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								// get user
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								// ngirim ke unit kerja yang satuan kerjanya sama
								if($divisi_satker == $id_satker_penerima){
									// detail surat
									$dari = $id_kepala_unker;
         							$nomer_surat = '';
            						$tanggal_surat = '0000-00-00 00:00:00';
            						$atas_surat = 'KA'.strtoupper($get_unker['nama_unit_kerja']);
            						$pangkat_nrp = strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']);
            						$nama_pegawai = strtoupper($get_kepala_unker['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
						
										$this->load->library('upload', $config);
						
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}
						

									// kirim data persetujuan dari sekretaris unker ke kepala unker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_kepala_unker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan); 
						
									// kirim data persetujuan dari kepala unker ke kepala unker lain
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
						
										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_unker,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => $nomer_surat,
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}
						
									// kirim tembusan
									$cek = $_POST['tembusan'];
						
									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){
						
											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);
						
											$i++;
										}
									}

								} // akhir ngirim ke unit kerja yang satuan kerjanya sama
								// ngirim ke unit kerja yang satuan kerjanya beda
								else{
									// jika ngirim ke beda satker tapi masih satu kompartemen
									if($divisi_komp == $id_kompartemen_penerima){
										// detail surat
										$dari = $id_kepala_satker;
										$nomer_surat = '';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
										$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
										$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';
						
											$this->load->library('upload', $config);
						
											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
										}

										// kirim data persetujuan dari sekretaris unker ke kepala unker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_kepala_unker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 
						
										// ngirim dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_unker,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
						
										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
						
										// kirim data persetujuan dari kepala unker ke kepala unker
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $id_satker_penerima_kepala
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);
						
											
											// ngirim dari sekretaris satker ke kepala satker
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_satker,
												"id_penerima" => $id_satker_penerima_kepala,
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);
										}
						
										// kirim tembusan
										$cek = $_POST['tembusan'];
						
										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){
						
												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);
						
												$i++;
											}
										}


									}
									// jika ngirim ke beda satker dan juga beda kompartemen
									else{
										$dari = $id_kepala_komp;
										$nomer_surat = ' ';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
										$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
										$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';

											$this->load->library('upload', $config);

											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
										}

										// kirim data persetujuan dari sekretaris unker ke kepala unker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_kepala_unker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 

										// kirim data persetujuan dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_unker,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_unker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_unker['pangkat']).' NRP '.strtoupper($get_kepala_unker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_satker,
											"id_penerima" => $id_sek_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $id_kepala_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari kepala kompartemen ke kepala kompartemen lain
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $id_komp_penerima_kepala
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);

											// kirim data persetujuan dari kepala kompartemen ke kompartemen lain
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_komp,
												"id_penerima" => $id_komp_penerima_kepala,
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);

										}

										// kirim tembusan
										$cek = $_POST['tembusan'];

										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){

												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

												$i++;
											}
										}

									} // akhir jika ngirim ke beda satker dan juga beda kompartemen
								
								}

							}
							
						}
					} /* akhir pengirim sekretaris unker */
					else if($get_user['jabatan'] == 'kepala'){
						if($jmlh_data == 1 ){
							// ngirim ke gubernur
							if($tingkatan == 1){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}


								// kirim data persetujuan dari kepala unker ke sekretaris satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_sekretaris_satker,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan); 

								// kirim data persetujuan dari sekretaris satker ke kepala satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sekretaris_satker,
									"id_penerima" => $id_kepala_satker,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_satker,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								// kirim data persetujuan dari kepala kompartemen ke kepala taud
								// get pelayanan taud
								$get_taud = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan=2")->row_array();
								// get user pelayanan taud
								$get_pelayanan_taud = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_divisi=2")->row_array();

								
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_komp,
									"id_penerima" => $get_pelayanan_taud['id_user'],
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								// kirim data persetujuan dari kepala kompartemen ke kepala pimpinan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $get_pelayanan_taud['id_user'],
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_taud['nama_pelayanan']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_pelayanan_taud['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_pelayanan_taud['pangkat']).' NRP '.strtoupper($get_pelayanan_taud['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}
							} /* akhir ngirim ke gubernur */
							else if($tingkatan == 2){
								// detail surat
								$dari = $id_kepala_komp;
								$nomer_surat = '';
								$tanggal_surat = '0000-00-00 00:00:00';
								$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
								$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
								$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

								//  Cek jika ada gambar yang ingin di upload
								$upload_file = $_FILES['file_notdis']['name'];

								if($upload_file){
									$config['allowed_types'] = 'pdf|doc|docx';
									$config['upload_path'] = './assets/dokumen_notdis/';
	
									$this->load->library('upload', $config);
	
									if($this->upload->do_upload('file_notdis')){
										$new_file = $this->upload->data('file_name');
										// kirim nota dinas
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
									}
									else{
										$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
										redirect('nota_dinas_keluar/in_tambah');
									}
								}
								else{
									// kirim nota dinas
									$a = '-';
									$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
								}


								// kirim data persetujuan dari kepala unker ke sekretaris satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_pengirim,
									"id_penerima" => $id_sekretaris_satker,
									"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Diajukan',
									"is_read" => 1,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan); 

								// kirim data persetujuan dari sekretaris satker ke kepala satker
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sekretaris_satker,
									"id_penerima" => $id_kepala_satker,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_kepala_satker,
									"id_penerima" => $id_sek_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);

								// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
								$data_persetujuan = [
									"id_surat_notdis" => $id_notdis,
									"id_pengirim" => $id_sek_komp,
									"id_penerima" => $id_kepala_komp,
									"nomer_surat_persetujuan_notdis" => ' ',
									"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
									"tanggal_diacc" => '0000-00-00 00:00:00',
									"status_persetujuan_notdis" => 'Menunggu',
									"is_read" => 0,
									"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
									"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
									"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
								];
								$this->db->insert('persetujuan_notdis',$data_persetujuan);


								// kirim data persetujuan dari kepala kompartemen ke kepala pelayanan
								foreach($this->cart->contents() as $item) {
									// kirim data kepada surat nota dinas
									$data_kepada = [
										"id_surat_notdis" => $id_notdis,
										"kepada" => $item['id']
									];
									$this->db->insert('kepada_surat_notdis',$data_kepada);

									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_komp,
										"id_penerima" => $item['id'],
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);
								}

								// kirim tembusan
								$cek = $_POST['tembusan'];

								if($cek!=NULL){
									$i=0;
									$n = count( $_POST['tembusan'] );
									while($i<$n){

										$data_tembusan_kirim = [
											"id_surat" => $id_notdis,
											"id_pengirim_tembusan" => $dari,
											"id_penerima_tembusan" => $_POST['tembusan'][$i],
											"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
											"jenis_surat" => 1,
											"is_dibaca" => 0,
										];
										$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

										$i++;
									}
								}
							} /* akhir ngirim ke pelayanan sebagai kepala unker */

							else if($tingkatan == 3){
								// get sekretaris kompartemen penerima surat
								$get_sekretaris_kompartemen_penerima = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_divisi_penerima_tujuan' AND jabatan='sekretaris'")->row_array();
								$id_sekretaris_kompartemen_penerima = $get_sekretaris_kompartemen_penerima['id_user'];

								// get kepala kompartemen penerima surat
								$get_kepala_kompartemen_penerima = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_divisi_penerima_tujuan' AND jabatan='kepala'")->row_array();
								$id_kepala_kompartemen_penerima = $get_kepala_kompartemen_penerima['id_user'];

								// get kompartemen penerima surat
								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_divisi_penerima_tujuan'")->row_array();

								if($divisi_komp == $id_divisi_penerima_tujuan){
									$dari = $id_kepala_satker;
									$nomer_surat = ' ';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
									$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
									$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}

									// kirim data persetujuan dari kepala unker ke sekretaris satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_sekretaris_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sekretaris_satker,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}

								} /* akhir ngirim ke kompartemen yang sama */
								else{
									$dari = $id_kepala_komp;
									$nomer_surat = ' ';
									$tanggal_surat = '0000-00-00 00:00:00';
									$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
									$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
									$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
		
										$this->load->library('upload', $config);
		
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}


									// kirim data persetujuan dari kepala unker ke sekretaris satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_pengirim,
										"id_penerima" => $id_sekretaris_satker,
										"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Diajukan',
										"is_read" => 1,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari sekretaris satker ke kepala satker
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sekretaris_satker,
										"id_penerima" => $id_kepala_satker,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);

									// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_satker,
										"id_penerima" => $id_sek_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);


									// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_sek_komp,
										"id_penerima" => $id_kepala_komp,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);


									// kirim data persetujuan dari kepala kompartemen ke sekretaris kompartemen lain
									$data_persetujuan = [
										"id_surat_notdis" => $id_notdis,
										"id_pengirim" => $id_kepala_komp,
										"id_penerima" => $id_sekretaris_kompartemen_penerima,
										"nomer_surat_persetujuan_notdis" => ' ',
										"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
										"tanggal_diacc" => '0000-00-00 00:00:00',
										"status_persetujuan_notdis" => 'Menunggu',
										"is_read" => 0,
										"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
										"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
										"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
									];
									$this->db->insert('persetujuan_notdis',$data_persetujuan);


									// kirim data persetujuan dari kepala kompartemen ke kepala kompartemen lain
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $id_kepala_kompartemen_penerima
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_kompartemen_penerima,
											"id_penerima" => $id_kepala_kompartemen_penerima,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen_penerima['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_kompartemen_penerima['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_kompartemen_penerima['pangkat']).' NRP '.strtoupper($get_sekretaris_kompartemen_penerima['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}
								}

							} /* akhir ngirim ke kompartemen */

							// ngirim ke satuan kerja dari kepala unit kerja
							else if($tingkatan == 4){

								// get kompartemen penerima surat ya bukan pengirim surat
								$satker_penerima = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_kompartemen_penerima = $satker_penerima['id_kompartemen'];
								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen_penerima'")->row_array();

								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='kepala'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								if($divisi_satker == $id_divisi_penerima_tujuan){
									// detail surat
									$dari = $id_pengirim;
									$nomer_surat = $this->input->post('nomer_surat',TRUE);
									$tanggal_surat = date("Y-m-d H:i:s", time());
									$atas_surat = 'KA'.strtoupper($get_unker['nama_unit_kerja']);
									$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
									$nama_pegawai = strtoupper($get_user['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';

										$this->load->library('upload', $config);

										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}


									// kirim data persetujuan dari kepala unker ke kepala satker
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);

										// ngirim dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}

									// kirim tembusan
									$cek = $_POST['tembusan'];

									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){

											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

											$i++;
										}
									}

								}
								// ngirim ke satuan kerja yang tidak satu jenis dengan dia
								else{
									// jika ngirim ke beda satker tapi masih satu kompartemen
									if($divisi_komp == $id_kompartemen_penerima){

										// detail surat
										$dari = $id_kepala_satker;
										$nomer_surat = '';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
										$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
										$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';

											$this->load->library('upload', $config);

											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
										}


										// ngirim dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari kepala unker ke kepala unker
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $item['id']
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);

											
											// ngirim dari sekretaris satker ke kepala satker
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_satker,
												"id_penerima" => $item['id'],
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);
										}

										// kirim tembusan
										$cek = $_POST['tembusan'];

										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){

												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

												$i++;
											}
										}
									}

									// jika ngirim ke beda satker dan juga beda kompartemen
									else{

										$dari = $id_kepala_komp;
										$nomer_surat = ' ';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
										$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
										$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';

											$this->load->library('upload', $config);

											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
										}


										// kirim data persetujuan dari kepala unker ke sekretaris satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_satker,
											"id_penerima" => $id_sek_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $id_kepala_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari kepala kompartemen ke kepala kompartemen lain
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $id_komp_penerima_kepala
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);

											// kirim data persetujuan dari kepala kompartemen ke kompartemen lain
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_komp,
												"id_penerima" => $id_komp_penerima_kepala,
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);

										}

										// kirim tembusan
										$cek = $_POST['tembusan'];

										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){

												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

												$i++;
											}
										}

									} // akhir jika ngirim ke beda satker dan juga beda kompartemen
								}

							} /* akhir ngirim ke satuan kerja dari kepala unit kerja */

							// ngirim ke unit kerja dari kepala unit kerja
							else if($tingkatan == 5){

								// get kompartemen penerima surat ya bukan pengirim surat
								$unker_penerima = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_divisi_penerima_tujuan'")->row_array();
								$id_satker_penerima = $unker_penerima['id_satuan_kerja'];
								$satker_penerima = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker_penerima'")->row_array();
								$id_kompartemen_penerima = $satker_penerima['id_kompartemen'];

								$get_kompartemen_penerima = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen_penerima'")->row_array();

								// get user
								$get_satker_penerima_kepala =  $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_divisi='$id_satker_penerima' AND jabatan='kepala'")->row_array();
								$id_satker_penerima_kepala  = $get_satker_penerima_kepala['id_user'];
								
								// get user
								$get_komp_penerima_kepala = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='kepala'")->row_array();
								$id_komp_penerima_kepala = $get_komp_penerima_kepala['id_user'];
								// get user
								$get_komp_penerima_sekretaris = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$id_kompartemen_penerima' AND jabatan='sekretaris'")->row_array();
								$id_komp_penerima_sekretaris = $get_komp_penerima_sekretaris['id_user'];

								// ngirim ke unit kerja yang satuan kerjanya sama
								if($divisi_satker == $id_satker_penerima){
									// detail surat
									$dari = $id_pengirim;
         							$nomer_surat = $this->input->post('nomer_surat',TRUE);
            						$tanggal_surat = date("Y-m-d H:i:s", time());
            						$atas_surat = 'KA'.strtoupper($get_unker['nama_unit_kerja']);
            						$pangkat_nrp = strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']);
            						$nama_pegawai = strtoupper($get_user['nama_user']);

									//  Cek jika ada gambar yang ingin di upload
									$upload_file = $_FILES['file_notdis']['name'];

									if($upload_file){
										$config['allowed_types'] = 'pdf|doc|docx';
										$config['upload_path'] = './assets/dokumen_notdis/';
						
										$this->load->library('upload', $config);
						
										if($this->upload->do_upload('file_notdis')){
											$new_file = $this->upload->data('file_name');
											// kirim nota dinas
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
										}
										else{
											$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
											redirect('nota_dinas_keluar/in_tambah');
										}
									}
									else{
										// kirim nota dinas
										$a = '-';
										$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
									}
						
						
									// kirim data persetujuan dari kepala unker ke kepala unker lain
									foreach($this->cart->contents() as $item) {
										// kirim data kepada surat nota dinas
										$data_kepada = [
											"id_surat_notdis" => $id_notdis,
											"kepada" => $item['id']
										];
										$this->db->insert('kepada_surat_notdis',$data_kepada);
						
										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $item['id'],
											"nomer_surat_persetujuan_notdis" => $nomer_surat,
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => date("Y-m-d H:i:s", time()),
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
									}
						
									// kirim tembusan
									$cek = $_POST['tembusan'];
						
									if($cek!=NULL){
										$i=0;
										$n = count( $_POST['tembusan'] );
										while($i<$n){
						
											$data_tembusan_kirim = [
												"id_surat" => $id_notdis,
												"id_pengirim_tembusan" => $dari,
												"id_penerima_tembusan" => $_POST['tembusan'][$i],
												"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
												"jenis_surat" => 1,
												"is_dibaca" => 0,
											];
											$this->db->insert('tembusan_kirim',$data_tembusan_kirim);
						
											$i++;
										}
									}

								} // akhir ngirim ke unit kerja yang satuan kerjanya sama
								// ngirim ke unit kerja yang satuan kerjanya beda
								else{
									// jika ngirim ke beda satker tapi masih satu kompartemen
									if($divisi_komp == $id_kompartemen_penerima){
										// detail surat
										$dari = $id_kepala_satker;
										$nomer_surat = '';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_satker['nama_satuan_kerja']);
										$pangkat_nrp = strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']);
										$nama_pegawai = strtoupper($get_kepala_satker['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';
						
											$this->load->library('upload', $config);
						
											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 0, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 0, $image_name, $key_surat,$id_notdis);
										}

										// kirim data persetujuan dari sekretaris unker ke kepala unker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 
						
						
										// ngirim dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);
						
										// kirim data persetujuan dari kepala unker ke kepala unker
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $id_satker_penerima_kepala
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);
						
											
											// ngirim dari sekretaris satker ke kepala satker
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_satker,
												"id_penerima" => $id_satker_penerima_kepala,
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);
										}
						
										// kirim tembusan
										$cek = $_POST['tembusan'];
						
										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){
						
												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);
						
												$i++;
											}
										}


									}
									// jika ngirim ke beda satker dan juga beda kompartemen
									else{
										$dari = $id_kepala_komp;
										$nomer_surat = ' ';
										$tanggal_surat = '0000-00-00 00:00:00';
										$atas_surat = 'KA'.strtoupper($get_kompartemen['nama_kompartemen']);
										$pangkat_nrp = strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']);
										$nama_pegawai = strtoupper($get_komp_kepala['nama_user']);

										//  Cek jika ada gambar yang ingin di upload
										$upload_file = $_FILES['file_notdis']['name'];

										if($upload_file){
											$config['allowed_types'] = 'pdf|doc|docx';
											$config['upload_path'] = './assets/dokumen_notdis/';

											$this->load->library('upload', $config);

											if($this->upload->do_upload('file_notdis')){
												$new_file = $this->upload->data('file_name');
												// kirim nota dinas
												$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $new_file, 1, $image_name,$key_surat,$id_notdis);
											}
											else{
												$this->session->set_flashdata('msg2','Gagal ditambahkan, file tidak sesuai ketentuan');
												redirect('nota_dinas_keluar/in_tambah');
											}
										}
										else{
											// kirim nota dinas
											$a = '-';
											$this->M_nota_dinas_keluar_model->tambah_data_surat_notdis2($dari, $nomer_surat, $tanggal_surat, $atas_surat, $nama_pegawai, $pangkat_nrp, $a, 1, $image_name, $key_surat,$id_notdis);
										}

										// kirim data persetujuan dari sekretaris unker ke kepala unker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_pengirim,
											"id_penerima" => $id_sekretaris_satker,
											"nomer_surat_persetujuan_notdis" => $this->input->post('nomer_surat',TRUE),
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Diajukan',
											"is_read" => 1,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_unker['nama_unit_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_user['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_user['pangkat']).' NRP '.strtoupper($get_user['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan); 

										// kirim data persetujuan dari sekretaris satker ke kepala satker
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sekretaris_satker,
											"id_penerima" => $id_kepala_satker,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_sekretaris_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_sekretaris_satker['pangkat']).' NRP '.strtoupper($get_sekretaris_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);

										// kirim data persetujuan dari kepala satker ke sekretaris kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_kepala_satker,
											"id_penerima" => $id_sek_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_satker['nama_satuan_kerja']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_kepala_satker['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_kepala_satker['pangkat']).' NRP '.strtoupper($get_kepala_satker['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari sekretaris kompartemen ke kepala kompartemen
										$data_persetujuan = [
											"id_surat_notdis" => $id_notdis,
											"id_pengirim" => $id_sek_komp,
											"id_penerima" => $id_kepala_komp,
											"nomer_surat_persetujuan_notdis" => ' ',
											"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
											"tanggal_diacc" => '0000-00-00 00:00:00',
											"status_persetujuan_notdis" => 'Menunggu',
											"is_read" => 0,
											"atas_surat_persetujuan_notdis" => 'SEK'.strtoupper($get_kompartemen['nama_kompartemen']),
											"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_sek['nama_user']),
											"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_sek['pangkat']).' NRP '.strtoupper($get_komp_sek['nrp']),
										];
										$this->db->insert('persetujuan_notdis',$data_persetujuan);


										// kirim data persetujuan dari kepala kompartemen ke kepala kompartemen lain
										foreach($this->cart->contents() as $item) {
											// kirim data kepada surat nota dinas
											$data_kepada = [
												"id_surat_notdis" => $id_notdis,
												"kepada" => $id_komp_penerima_kepala
											];
											$this->db->insert('kepada_surat_notdis',$data_kepada);

											// kirim data persetujuan dari kepala kompartemen ke kompartemen lain
											$data_persetujuan = [
												"id_surat_notdis" => $id_notdis,
												"id_pengirim" => $id_kepala_komp,
												"id_penerima" => $id_komp_penerima_kepala,
												"nomer_surat_persetujuan_notdis" => ' ',
												"tanggal_dikirim" => date("Y-m-d H:i:s", time()),
												"tanggal_diacc" => '0000-00-00 00:00:00',
												"status_persetujuan_notdis" => 'Menunggu',
												"is_read" => 0,
												"atas_surat_persetujuan_notdis" => 'KA'.strtoupper($get_kompartemen['nama_kompartemen']),
												"nama_pegawai_persetujuan_notdis" => strtoupper($get_komp_kepala['nama_user']),
												"pangkat_nrp_persetujuan_notdis" => strtoupper($get_komp_kepala['pangkat']).' NRP '.strtoupper($get_komp_kepala['nrp']),
											];
											$this->db->insert('persetujuan_notdis',$data_persetujuan);

										}

										// kirim tembusan
										$cek = $_POST['tembusan'];

										if($cek!=NULL){
											$i=0;
											$n = count( $_POST['tembusan'] );
											while($i<$n){

												$data_tembusan_kirim = [
													"id_surat" => $id_notdis,
													"id_pengirim_tembusan" => $dari,
													"id_penerima_tembusan" => $_POST['tembusan'][$i],
													"tanggal_dikirim_tembusan" => '0000-00-00 00:00:00',
													"jenis_surat" => 1,
													"is_dibaca" => 0,
												];
												$this->db->insert('tembusan_kirim',$data_tembusan_kirim);

												$i++;
											}
										}

									} // akhir jika ngirim ke beda satker dan juga beda kompartemen
								
								}

							} /* akhir ngirim ke unit kerja dari kepala unit kerja */


						} /* akhir ngirim sebagai kepala unit kerja */

					} /* akhir else unit kerja */
				
				} /* akhir ngirim sebagai unit kerja */
				
				// cek cart kosong atau tidak
				$xxx = 0;
				foreach($this->cart->contents() as $item) {
					$xxx = $item['id_divisi_penerima_tujuan'];
				}

				// cek ada penerima surat tidak
				if($xxx == 0){
					$this->session->set_flashdata('msg2','Gagal ditambahkan, penerima surat tidak ada!');
					redirect('nota_dinas_keluar/in_tambah');
				}

				// kirim isi surat
				$cek2 = $_POST['surat'];

				if($cek2!=NULL){
					$i=0;
					$n = count( $_POST['surat'] );
					while($i<$n){
						$data_isi_surat = [
							"id_surat_notdis" => $id_notdis,
							"isi_notdis" => $_POST['surat'][$i],
						];
						$this->db->insert('isi_surat_notdis',$data_isi_surat);
						$i++;
					}
				}

				// berhasil lur
				$this->cart->destroy();
				$this->session->set_flashdata('msg','Surat berhasil dikirim');
				redirect('nota_dinas_keluar');

			} /* akhir selain all */
    	}
	}   

	function remove($id){
        $row_id=$this->uri->segment(3);
            $this->cart->update(array(
                   'rowid'      => $row_id,
                   'qty'     => 0
                ));

        redirect('nota_dinas_keluar/in_tambah'); 
    }
}