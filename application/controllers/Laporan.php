<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Laporan extends REST_Controller {

	function laporan_get(){
		$get_pembeli = $this->db->query("SELECT laporan.*,kategori.nama as nama_kategori FROM laporan left join kategori on laporan.fk_kategori=kategori.id")->result();
		$this->response(array("status"=>"success","result" => $get_pembeli));
	}
	function laporan_post() {
		$uploaddir = str_replace("application/", "", APPPATH).'upload/';
		if(!file_exists($uploaddir) && !is_dir($uploaddir)) {
			echo mkdir($uploaddir, 0750, true);
		}
		if (!empty($_FILES)){
			$path = $_FILES['gambar']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$user_img = rand(10000,99999) . '.' . $ext;
			$uploadfile = $uploaddir . $user_img;
			$data_pembeli['gambar'] = "upload/".$user_img;
		}else{
			$data_pembeli['gambar']="";
		}


		if (empty($_FILES)) {
			$this->response(array("status"=>"failed","message" => "gambar harus idisi"));
		}else{

			$gambar = "";
			$data_pembeli = array(
				'judul' => $this->post('judul'),
				'nama' => $this->post('nama'),
				'email' => $this->post('email'),
				'deskripsi' => $this->post('deskripsi'),
				'lattitude' => $this->post('lattitude'),
				'longtitude' => $this->post('longtitude'),
				'gambar' => $user_img,
				'status' => $this->post('status'),
				'fk_kategori' => $this->db->where('nama',$this->post('kategori'))->get('kategori')->row(0)->id
			);

			$insert = $this->db->insert('laporan',$data_pembeli);
			if (!empty($_FILES)){
					if ($_FILES["gambar"]["name"]) {

						if

							(move_uploaded_file($_FILES["gambar"]["tmp_name"],$uploadfile))

						{
							$insert_image = "success";

						} else{
							$insert_image = "failed";

						}
					}else{
						$insert_image = "Image Tidak ada Masukan";
					}
					$data_pembeli['gambar'] = base_url()."upload/".$user_img;
				}else{

					$data_pembeli['gambar'] = "";

				}
				if ($insert){
					$this->response(array('status'=>'success','result' =>

						array($data_pembeli),"message"=>$insert));

				}
			$this->response(array("status"=>"success","message" => "Berhasil"));
		}
		
		
	}
}
