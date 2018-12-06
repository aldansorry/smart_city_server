<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Laporan extends REST_Controller {

	function laporan_get(){
		$get_pembeli = $this->db->query("SELECT * FROM laporan")->result();
		$this->response(array("status"=>"success","result" => $get_pembeli));
	}
	function laporan_post() {

		$action = $this->post('action');
		$data_pembeli = array(
			'id_pembeli' => $this->post('id_pembeli'),
			'nama' => $this->post('nama'),
			'alamat' => $this->post('alamat'),
			'telpn' => $this->post('telpn'),
			'photo_id' => $this->post('photo_id')
		);
		if ($action==='post'){
			$this->insertPembeli($data_pembeli);
		}else if ($action==='put'){
			$this->updatePembeli($data_pembeli);
		}else if ($action==='delete'){
			$this->deletePembeli($data_pembeli);
		}else{
			$this->response(array("status"=>"failed","message" => "action harus

				diisi"));
		}
	}
}
	