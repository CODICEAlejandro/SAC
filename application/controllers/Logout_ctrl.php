<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Logout_ctrl extends CI_Controller {
	public function index(){
		$this->session->sess_destroy();
		redirect(base_url().'index.php/Login_ctrl/load_vw');
	}
}
?>