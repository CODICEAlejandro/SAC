<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_ctrl extends CI_Controller {
	public function index(){
		checkSession();

		$data["menu"] = $this->load->view("Menu_principal",null,true);
		$this->load->view("Reportes_vw",$data);
	}
}

?>
