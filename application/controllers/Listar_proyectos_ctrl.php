<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listar_proyectos_ctrl extends CI_Controller {
	public function index(){		
		checkSession();

		$data['clientes_proyectos'] = $this->listar_proyectos();

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Listar_proyectos_vw',$data);
	}

	public function listar_proyectos(){
		$this->load->model('Proyecto');
		return $this->Proyecto->traer_cp();
	}
}
?>