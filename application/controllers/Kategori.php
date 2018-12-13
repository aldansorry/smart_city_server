<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kategori extends REST_Controller {

	function kategori_get(){
		$get_kategori = $this->db->get("kategori")->result();
		$this->response(array("status"=>"success","result" => $get_kategori));
	}
	
}
