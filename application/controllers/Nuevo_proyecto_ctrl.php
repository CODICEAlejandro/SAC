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
		$data['tipo_proyecto'] = $this->db->query("SELECT id,clave FROM cattipoproyecto")->result();
		$data['menu'] = $this->load->view('Menu_principal',null,true);

		$this->load->view('Nuevo_proyecto_vw', $data);
	}

	public function consultarProyecto_AJAX($id){
		echo json_encode($this->Proyecto->traer($id));
	}

	public function actualizarProyecto_AJAX($id){
		$response['status'] = "ERROR";
		$response['data'] = array();

		$data = $this->input->post();
		if($this->Proyecto->actualizar($id, $data)){ 
			$response['status'] = "OK";
			$response['data'] = $this->Proyecto->traerTodo_AI();
		}else $response['status'] = "ERROR";

		echo json_encode($response);
	}

	public function nuevoProyecto_AJAX(){
		checkSession();

		$response['data'] = array();
		$response['status'] = "ERROR";

		$data = $this->input->post();
		if($this->Proyecto->insertar($data)){
			$response['status'] = "OK";
			$response['data'] = $this->Proyecto->traerTodo_AI();
		}else $response['status'] = "ERROR";

		echo json_encode($response);
	}
}
?>
