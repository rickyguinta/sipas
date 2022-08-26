<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct(){
		parent::__construct();

		if($this->session->userdata('level') != 1){
			redirect(base_url());
		}
		
		$this->load->model('M_User');
		$this->load->model('M_Kompartemen');
		$this->load->model('M_Satker');
		$this->load->model('M_Pimpinan');
		$this->load->model('M_Pelayanan');
	}

	public function index(){
		$data['title'] = "SIPAS | User";
		$data['ngecek1'] = 'user';
		$data['ngecek2'] = 'user';

		$data['kompartemen'] = $this->M_Kompartemen->tampilData();
        $data['satker'] = $this->M_Satker->tampilData();
		$data['pimpinan'] = $this->M_Pimpinan->tampilData();
		$data['pelayanan'] = $this->M_Pelayanan->tampilData();
		
		$data['data'] = $this->M_User->tampilData();
		$data['level'] = ['0','1'];

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('user/index',$data);
		$this->load->view('templates/footer');
	}

	function tambah_data(){
		$this->form_validation->set_rules('username','Username','required|trim|min_length[3]|is_unique[user.username]',[
            'is_unique' => 'This username has already registered!'
        ]);

        $this->form_validation->set_rules('password1','Password','required|trim|min_length[3]|matches[password2]',[
            'matches' => 'Password dont match!',
            'min_length' => 'Password to short!'
        ]);

        $this->form_validation->set_rules('password2','Password','required|trim|matches[password1]');
        
        $this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[user.email]',[
            'is_unique' => 'This email has already registered!'
		]);
		
		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('msg2','Data gagal ditambahkan!');

			// data 
			$data['title'] = "SIPAS | User";
			$data['ngecek1'] = 'user';
			$data['ngecek2'] = 'user';

			$data['kompartemen'] = $this->M_Kompartemen->tampilData();
			$data['satker'] = $this->M_Satker->tampilData();
			$data['pimpinan'] = $this->M_Pimpinan->tampilData();
			$data['pelayanan'] = $this->M_Pelayanan->tampilData();
			
			$data['data'] = $this->M_User->tampilData();
			$data['level'] = ['0','1'];

			$this->load->view('templates/header',$data);
			$this->load->view('templates/sidebar',$data);
			$this->load->view('user/index',$data);
			$this->load->view('templates/footer');
		}
		else{
			$level = $this->input->post('level',TRUE);
			$pimpinan = $this->input->post('pimpinan',TRUE);
			$pelayanan = $this->input->post('pelayanan',TRUE);
            $kompartemen = $this->input->post('kompartemen',TRUE);
            $satker = $this->input->post('satker',TRUE);
			$unit_kerja = $this->input->post('unit_kerja',TRUE);
			$jabatan = $this->input->post('jabatan',TRUE);
			
			if($level == 0){
				if($pimpinan == '0' && $pelayanan == '0' && $kompartemen == '0' && $satker == '0' && $unit_kerja== '0'){
                    $this->session->set_flashdata('msg2','Data gagal ditambahkan, divisi harus di isi!');
                    redirect('user');
				}
				else if($pimpinan != '0' && $pelayanan != '0'){
					$this->session->set_flashdata('msg2','Data gagal ditambahkan, Pimpinan dan Pelayanan hanya dipilih salah satu!');
                    redirect('user');
				}
				else{
					if($pimpinan != '0'){
						$cek_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan=1 AND id_divisi='$pimpinan' AND jabatan='kepala'")->num_rows();

						if($jabatan == 'sekretaris'){
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, pimpinan tidak mempunyai sekretaris !');
							redirect('user');
						}
						else{
							if($cek_user == NULL){
								$this->M_User->tambahData(1,$pimpinan);
								$this->session->set_flashdata('msg','pimpinan');
								redirect('user');
							}
							else{
								$this->session->set_flashdata('msg2','Data gagal ditambahkan, data user yang ditambahkan sudah ada !');
								redirect('user');
							}	
						}	
					}
					else if($pelayanan != '0'){
						$cek_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_divisi='$pelayanan' AND jabatan='kepala'")->num_rows();

						if($jabatan == 'sekretaris'){
							$this->session->set_flashdata('msg2','Data gagal ditambahkan, pelayanan tidak mempunyai sekretaris !');
							redirect('user');
						}
						else{
							if($cek_user == NULL){
								$this->M_User->tambahData(2,$pelayanan);
								$this->session->set_flashdata('msg','pelayanan');
								redirect('user');
							}
							else{
								$this->session->set_flashdata('msg2','Data gagal ditambahkan, data user yang ditambahkan sudah ada !');
								redirect('user');
							}
						}
					}
					else{
						if($kompartemen != 0){
							if($satker != 0 ){
								if($unit_kerja != 0){
									$cek_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_divisi='$unit_kerja' AND jabatan='$jabatan'")->num_rows();

									if($cek_user == NULL){
										$this->M_User->tambahData(5,$unit_kerja);
										$this->session->set_flashdata('msg','unit kerja');
										redirect('user');
									}
									else{
										$this->session->set_flashdata('msg2','Data gagal ditambahkan, data user yang ditambahkan sudah ada !');
										redirect('user');
									}	
								}
								else{
									$cek_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_divisi='$satker' AND jabatan='$jabatan'")->num_rows();

									if($cek_user == NULL){
										$this->M_User->tambahData(4,$satker);
										$this->session->set_flashdata('msg','satker');
										redirect('user');
									}
									else{
										$this->session->set_flashdata('msg2','Data gagal ditambahkan, data user yang ditambahkan sudah ada !');
										redirect('user');
									}	
								}
							}
							else{	
								$cek_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_divisi='$kompartemen' AND jabatan='$jabatan'")->num_rows();
								
								if($cek_user == NULL){
									$this->M_User->tambahData(3,$kompartemen);
									$this->session->set_flashdata('msg','kompartemen');
									redirect('user');
								}
								else{
									$this->session->set_flashdata('msg2','Data gagal ditambahkan, data user yang ditambahkan sudah ada !');
									redirect('user');
								}
								
							}
						}
					}
				}
			}
			else{
				$this->M_User->tambahData(99,99);
				redirect('user');
			}
			// $this->session->set_flashdata('msg','Data Berhasil Ditambahkan');
            // redirect('user');
		}
	}

	function hapus_data(){
		$this->M_User->hapusData();
		$this->session->set_flashdata('msg',"Data Berhasil Dihapus");
		redirect('user');
	}

	function edit_data(){
		$this->M_User->editData();
		$this->session->set_flashdata('msg',"Data Berhasil Diubah");
		redirect('user');
	}
}
