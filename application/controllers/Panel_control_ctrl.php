<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panel_control_ctrl extends CI_Controller {
	public function index(){
		checkSession();

		$data["menu"] = $this->load->view("Menu_principal",null,true);
		$this->load->view("Panel_control_vw", $data);
	}
}
?>
