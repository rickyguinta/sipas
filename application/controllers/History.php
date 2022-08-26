<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if($this->session->userdata('level') == NULL){
			redirect(base_url());
		}

        $this->load->model('M_History');
    }

    public function history_disposisi(){
        $data['title'] = "SIPAS | History Disposisi";
		$data['ngecek1'] = 'disposisi';
		$data['ngecek2'] = 'history_disposisi';

		$data['data'] = $this->M_History->tampil_history_disposisi();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('history/index_disposisi',$data);
		$this->load->view('templates/footer');
    }

    public function history_tembusan(){
        $data['title'] = "SIPAS | History Tembusan";
		$data['ngecek1'] = 'tembusan';
        $data['ngecek2'] = 'history_tembusan';

		$data['data'] = $this->M_History->tampil_history_tembusan();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('history/index_tembusan',$data);
		$this->load->view('templates/footer');
    }

    public function history_notifikasi(){
        $data['title'] = "SIPAS | History Notifikasi";
		$data['ngecek1'] = 'notifikasi';
		$data['ngecek2'] = 'history_notifikasi';

		$data['data'] = $this->M_History->tampil_history_notifikasi();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('history/index_notifikasi',$data);
		$this->load->view('templates/footer');
	}
	
	public function history_nota_dinas(){
		$data['title'] = "SIPAS | History Nota Dinas";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'history_nota_dinas';

		$data['data'] = $this->M_History->tampil_history_notdis();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('history/index_nota_dinas',$data);
		$this->load->view('templates/footer');
	}

	public function history_persetujuan(){
		$data['title'] = "SIPAS | History Persetujuan";
		$data['ngecek1'] = 'nota_dinas';
		$data['ngecek2'] = 'history_persetujuan';

		$data['data'] = $this->M_History->tampil_persetujuan();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('history/index_persetujuan',$data);
		$this->load->view('templates/footer');
	}

    function hapus_data_disposisi($id){
        $this->db->delete('disposisi_notdis',['id_disposisi_notdis' => $this->input->post('id',TRUE)]);

		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('history/history_disposisi');
    }

    function hapus_data_notifikasi(){
        $id_notifikasi = $this->input->post('id',TRUE);

        $this->db->delete('notifikasi_surat',['id_notifikasi' => $id_notifikasi]);

        $this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('history/history_notifikasi');
	}
	
	function hapus_notdis(){
		$this->load->model('M_nota_dinas_keluar_model');

		$this->M_nota_dinas_keluar_model->hapus_data();
		$this->session->set_flashdata('msg','Data berhasil dihapus');
		redirect('history/history_nota_dinas');
	}

	function edit_data_disposisi(){
		$this->load->model('M_disposisi');

		$this->M_disposisi->edit_data();
		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('history/history_disposisi');
	}

	function edit_data_notifikasi(){
		$data = [
            "isi_notif" => $this->input->post('isi_notifikasi',TRUE)
        ];

        $this->db->where('id_notifikasi',$this->input->post('id',TRUE));
        $this->db->update('notifikasi_surat',$data);

		$this->session->set_flashdata('msg','Data berhasil diubah');
		redirect('history/history_notifikasi');
	}

}