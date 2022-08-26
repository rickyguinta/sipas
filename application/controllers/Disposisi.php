<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disposisi extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}

		$this->load->model('M_disposisi');
		$this->load->model('M_nota_dinas_keluar_model');
    }

	
	public function index($id_notdis)
	{
		$id = $this->session->userdata("id");
		$user = $this->db->query("SELECT * FROM user WHERE id_user='$id'")->row_array();

		$id_notdiss = $id_notdis;

		$data['title'] = "SIPAS | Disposisi";
		$data['ngecek1'] = 'disposisi';
		$data['ngecek2'] = 'disposisi_keluar';
		$data['id_surat'] = $id_notdis;

		$divisi = $this->session->userdata('id_divisi');
		$tingkatan = $this->session->userdata('is_tingkatan');
		$get_user = $this->db->query("SELECT * FROM user WHERE is_tingkatan='$tingkatan' AND id_divisi='$divisi' AND jabatan='kepala'")->row_array();
		$id_ = $get_user['id_user'];

		// get data user

		// jika pengirim gubernur
		if($user['is_tingkatan'] == 1){
			$data['pimpinan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=1 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['pelayanan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['kompartemen'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['satker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['unker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		}
		else if($user['is_tingkatan'] == 2){
			$data['pimpinan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=1 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['pelayanan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['kompartemen'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['satker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['unker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		}
		else if($user['is_tingkatan'] == 3){
			$data['pimpinan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=1 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['pelayanan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['kompartemen'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();

			$data['satker'] = $this->M_disposisi->tampil_3_kompartemen();
			$data['unker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
	
		}
		else if($user['is_tingkatan'] == 4){
			$data['pimpinan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=1 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['pelayanan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['kompartemen'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['satker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['unker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		}
		else if($user['is_tingkatan'] == 5){
			$data['pimpinan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=1 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['pelayanan'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=2 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['kompartemen'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=3 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['satker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=4 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
			$data['unker'] = $this->db->query("SELECT * FROM user WHERE is_tingkatan=5 AND id_user!='$id' AND jabatan='kepala' AND id_user!='$id_'")->result_array();
		}

		$data['user'] = $this->db->query("SELECT * FROM user WHERE jabatan='kepala' AND id_user!='$id_'")->result_array();

        $this->form_validation->set_rules('cek_tingkatan','Tingkatan','trim|required');

        if($this->form_validation->run()==FALSE){
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('disposisi/index',$data);
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
			
			if($cek_tingkatan != 'all'){
				// data penerima
				$id_penerima = $this->input->post('isinya',TRUE);
				$get_penerima = $this->db->query("SELECT * FROM user WHERE id_user='$id_penerima'")->row_array();
				$id_divisi_penerima = $get_penerima['id_divisi'];

				$isinya = "$id_penerima";

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
	
				redirect('disposisi/index/'.$id_notdis);
			}
			else{
				$this->M_disposisi->kirim_disposisi();

				$this->cart->destroy();
				$this->session->set_flashdata('msg','Disposisi berhasil dikirim');
				redirect('disposisi/index_keluar');
			}
		}
		
	}

	function in_detail($id_notdis){
		$data['title'] = "SIPAS | Detail Disposisi";
		$data['ngecek1'] = 'disposisi';
		$data['ngecek2'] = 'disposisi_keluar';

		$data['disposisi'] = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_surat_notdis='$id_notdis'")->row_array();

		$data['data'] = $this->M_nota_dinas_keluar_model->get_dt_notdis($id_notdis);
		$data['persetujuan_notdis'] = $this->M_nota_dinas_keluar_model->tampil_persetujuan($id_notdis);

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('disposisi/detail_disposisi',$data);
		$this->load->view('templates/footer');
	}

	function hapus_data(){
		$this->db->delete('disposisi_notdis',['id_disposisi_notdis' => $this->input->post('id',TRUE)]);

		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('disposisi/index_keluar');
	}

	function index_keluar(){
		$data['title'] = "SIPAS | Disposisi";
		$data['ngecek1'] = 'disposisi';
		$data['ngecek2'] = 'disposisi_keluar';

		$data['data'] = $this->M_disposisi->tampil_disposisi_keluar();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('disposisi/index_keluar',$data);
		$this->load->view('templates/footer');
	}

	function index_masuk(){
		$data['title'] = "SIPAS | Disposisi";
		$data['ngecek1'] = 'disposisi';
		$data['ngecek2'] = 'disposisi_masuk';

		$data['data'] = $this->M_disposisi->tampil_disposisi_masuk();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('disposisi/index_masuk',$data);
		$this->load->view('templates/footer');
	}

	function in_detail2($id_notdis ,$id_disposisi){
		date_default_timezone_set("Asia/Jakarta");
		$data['title'] = "SIPAS | Detail Disposisi";
		$data['ngecek1'] = 'disposisi';
		$data['ngecek2'] = 'disposisi_keluar';

		$data['disposisi'] = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_surat_notdis='$id_notdis'")->row_array();

		$data['data'] = $this->M_nota_dinas_keluar_model->get_dt_notdis($id_notdis);
		$data['persetujuan_notdis'] = $this->M_nota_dinas_keluar_model->tampil_persetujuan($id_notdis);

		$get_disposisi = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_disposisi_notdis='$id_disposisi'")->row_array();

		$timenya = str_replace(' ', '', $get_disposisi['tgl_dibaca_disposisi']);

		if($timenya == '0000-00-0000:00:00'){
	
			$dataa = [
				"tgl_dikirim_disposisi" => $get_disposisi['tgl_dikirim_disposisi'],
				"tgl_dibaca_disposisi" => date("Y-m-d H:i:s", time())
			];

			$this->db->where('id_disposisi_notdis',$id_disposisi);
			$this->db->update('disposisi_notdis',$dataa);
		}

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('disposisi/detail_disposisi',$data);
		$this->load->view('templates/footer');
	}
	
	function hapus($id,$id_notdis){

		$row_id=$this->uri->segment(3);
			$this->cart->update(array(
				   'rowid'      => $row_id,
				   'qty'     => 0
				));

		redirect('disposisi/index/'.$id_notdis); 
	}

	function cetak_disposisi($id){
		
		$get_disposisi = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_disposisi_notdis='$id'")->row_array();
		$id_notdis = $get_disposisi['id_surat_notdis'];
		$data['notdis'] = $this->db->query("SELECT * FROM surat_nota_dinas WHERE id_surat_notdis='$id_notdis'")->row_array();

		// kop surat
		$disposisi = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_disposisi_notdis='$id'")->row_array();
		$dari = $disposisi['id_pengirim_disposisi'];

		$get_user = $this->db->query("SELECT * FROM user WHERE id_user='$dari'")->row_array();
		$id_div = $get_user['id_divisi'];

		if($get_user['is_tingkatan'] == 1){
			$get_pimpinan = $this->db->query("SELECT * FROM pimpinan WHERE id_pimpinan='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_pimpinan['kepanjangan_pimpinan'];
		}
		else if($get_user['is_tingkatan'] == 2){
			$get_pelayanan = $this->db->query("SELECT * FROM pelayanan WHERE id_pelayanan='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_pelayanan['kepanjangan_pelayanan'];
		}
		else if($get_user['is_tingkatan'] == 3){
			$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_div'")->row_array();

			$data['atas_surat1'] = 'AKADEMI KEPOLISIAN INDONESIA';
			$data['atas_surat2'] = $get_kompartemen['kepanjangan_kompartemen'];
		}
		else if($get_user['is_tingkatan'] == 4){
			$get_satuan_kerja = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_div'")->row_array();
			$id_kompartemen = $get_satuan_kerja['id_kompartemen'];
			$get_kompartemen = $this->db->query("SELECT * FROM kompartemen WHERE id_kompartemen='$id_kompartemen'")->row_array();

			$data['atas_surat1'] = $get_kompartemen['kepanjangan_kompartemen'];
			$data['atas_surat2'] = $get_satuan_kerja['kepanjangan_satuan_kerja'];
		}
		else if($get_user['is_tingkatan'] == 5){
			$get_unit_kerja = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja='$id_div'")->row_array();
			$id_satker = $get_unit_kerja['id_satuan_kerja'];
			$get_satuan_kerja = $this->db->query("SELECT * FROM satuan_kerja WHERE id_satuan_kerja='$id_satker'")->row_array();

			$data['atas_surat1'] = $get_satuan_kerja['kepanjangan_satuan_kerja'];
			$data['atas_surat2'] = $get_unit_kerja['kepanjangan_unit_kerja'];
		}

		$get = $this->db->query("SELECT * FROM disposisi_notdis WHERE id_disposisi_notdis='$id'")->row_array();
		$t =  strtotime($get['tgl_dibaca_disposisi']);
		$data['waktu'] = date('d',$t).'/'.date('m',$t).'/20'.date('y',$t);

		$data['data'] = $this->M_disposisi->detail_disposisi($id);

		$this->load->view('disposisi/cetak_disposisi',$data);
	}

	function edit_data(){
		$this->M_disposisi->edit_data();
		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('disposisi/index_keluar');
	}
    
    
}
