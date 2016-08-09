<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nuevo_proyecto_ctrl extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('Cliente');
		$this->load->model('Proyecto');
	}

	public function index(){
		checkSession();

		$data['proyectos'] = $this->Proyecto->traerTodo_AI();
		$data['clientes'] = $this->Cliente->traerTodo();
		$data['menu'] = $this->load->view('Menu_principal',null,true);

		$this->load->view('Nuevo_proyecto_vw', $data);
	}

	public function consultarProyecto_AJAX($id){
		echo json_encode($this->Proyecto->traer($id));
	}

	public function actualizarProyecto_AJAX($id){
		$data = $this->input->post();
		if($this->Proyecto->actualizar($id, $data)) echo "OK";
		else echo "ERROR";
	}

	public function insertar(){
		checkSession();

		$data = $this->input->post();
		$this->Proyecto->insertar($data);
		redirect(base_url().'index.php/Listar_proyectos_ctrl');
	}
}
?>
