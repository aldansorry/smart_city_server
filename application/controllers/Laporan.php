<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Laporan extends REST_Controller {

	function laporan_get($kata=null){
		$this->db->select("laporan.*,kategori.nama as nama_kategori");
		$this->db->join('kategori',"laporan.fk_kategori=kategori.id","left");
		if ($kata != null) {
			$this->db->or_like('laporan.nama',$kata);
			$this->db->or_like('laporan.judul',$kata);
			$this->db->or_like('laporan.deskripsi',$kata);
		}
		$get_pembeli = $this->db->get('laporan')->result();
		$this->response(array("status"=>"success","result" => $get_pembeli));
	}
	function laporan_post() {

		if ($this->post('judul') == "") {
			$this->response(array("status"=>"failed","message" => "Judul harus di isi"));
		}else if ($this->post('deskripsi') == "") {
			$this->response(array("status"=>"failed","message" => "deskripsi harus di isi"));
		}else if ($this->post('lattitude') == "") {
			$this->response(array("status"=>"failed","message" => "lattitude harus di isi"));
		}else if ($this->post('longtitude') == "") {
			$this->response(array("status"=>"failed","message" => "longtitude harus di isi"));
		}else if ($this->post('kategori') == "") {
			$this->response(array("status"=>"failed","message" => "kategori harus di isi"));
		}else{
			$kategori_query = $this->db->where('nama',$this->post('kategori'))->get('kategori');
			if ($kategori_query->num_rows() == 0) {
				$this->response(array("status"=>"failed","message" => "kategori invalid"));
			}else{
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']  = '100';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('gambar')){
					$error = array('error' => $this->upload->display_errors());
					$this->response(array("status"=>"failed","message" => $error['error']));
				}
				else{
					$upload_data = $this->upload->data();
					$kategori_data = $kategori_query->row(0);
					$post_data = array(
						'judul' => $this->post('judul'),
						'nama' => $this->post('nama'),
						'email' => $this->post('email'),
						'deskripsi' => $this->post('deskripsi'),
						'lattitude' => $this->post('lattitude'),
						'longtitude' => $this->post('longtitude'),
						'gambar' => $upload_data['file_name'],
						'status' => $this->post('status'),
						'fk_kategori' => $kategori_data->id
					);
					$insert_query = $this->db->insert('laporan',$post_data);
					if ($insert_query) {
						$this->response(array("status"=>"success","message" => "Berhasil Tambah Data"));
					}else{
						$this->response(array("status"=>"failed","message" => "Gagal Tambah Data"));
					}
				}
			}
		}
	}

	function laporanput_post() {

		if ($this->post('judul') == "") {
			$this->response(array("status"=>"failed","message" => "Judul harus di isi"));
		}else if ($this->post('deskripsi') == "") {
			$this->response(array("status"=>"failed","message" => "deskripsi harus di isi"));
		}else if ($this->post('lattitude') == "") {
			$this->response(array("status"=>"failed","message" => "lattitude harus di isi"));
		}else if ($this->post('longtitude') == "") {
			$this->response(array("status"=>"failed","message" => "longtitude harus di isi"));
		}else if ($this->post('kategori') == "") {
			$this->response(array("status"=>"failed","message" => "kategori harus di isi"));
		}else{
			$kategori_query = $this->db->where('nama',$this->post('kategori'))->get('kategori');
			if ($kategori_query->num_rows() == 0) {
				$this->response(array("status"=>"failed","message" => "kategori invalid"));
			}else{
				
				$kategori_data = $kategori_query->row(0);
				$post_data = array(
					'judul' => $this->post('judul'),
					'nama' => $this->post('nama'),
					'email' => $this->post('email'),
					'deskripsi' => $this->post('deskripsi'),
					'lattitude' => $this->post('lattitude'),
					'longtitude' => $this->post('longtitude'),
					'status' => $this->post('status'),
					'fk_kategori' => $kategori_data->id,
				);

				$is_error = false;
				$error = "";
				if ($_FILES['gambar']['name'] != "") {
					$config['upload_path'] = './uploads/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']  = '100';
					$config['max_width']  = '1024';
					$config['max_height']  = '768';

					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('gambar')){
						$error = $this->upload->display_errors();
						$is_error = true;
					}
					else{
						$upload_data = $this->upload->data();
						$post_data['gambar'] = $upload_data['file_name'];
					}
				}

				if ($is_error) {
					$this->response(array("status"=>"failed","message" => $error));
				}else{
					$this->db->where('id',$this->post('id'));
					$update_query = $this->db->update('laporan',$post_data);
					if ($update_query) {
						$this->response(array("status"=>"success","message" => "Berhasil Edit Data"));
					}else{
						$this->response(array("status"=>"failed","message" => "Gagal Edit Data"));
					}
				}



			}
		}

	}
	function laporan_delete($id) {
		$this->db->where('id', $id);
		$delete = $this->db->delete('laporan');
		if ($delete) {
			$this->response(array("status"=>"success","message" => "Berhasil"));
		} else {
			$this->response(array('status' => 'fail', 502));
		}
	}
}
