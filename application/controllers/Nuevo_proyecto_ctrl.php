<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nuevo_proyecto_ctrl extends CI_Controller {
	public function index(){
		checkSession();

		$this->load->model('Cliente');

		$data['clientes'] = $this->Cliente->traerTodo();
		$data['menu'] = $this->load->view('Menu_principal',null,true);

		$this->load->view('Nuevo_proyecto_vw', $data);
	}

	public function insertar(){
		checkSession();

		$data = $this->input->post();

		$this->load->model('Proyecto');
		$this->Proyecto->insertar($data);
		redirect(base_url().'index.php/Listar_proyectos_ctrl');
	}
}
?>
