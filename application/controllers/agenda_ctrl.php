<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class agenda_ctrl extends CI_Controller {

	public function index(){
		$data["menu"] = $this->load->view("Menu_principal", null, true);
		$data["contactos"] = $this->db
									->query("select nombre,correo,telefono from catusuario where activo = 'S'")
									->result();

		$this->load->view("agenda_vw", $data);
	}

}

?>