<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control_proveedor_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model("Proveedor");
	}

	public function index(){
		$data['proveedores'] = $this->Proveedor->traerTodo_AI();
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Control_proveedor_vw", $data);
	}

	public function consultarProveedor_AJAX($id){
		$result = $this->Proveedor->traer($id);

		echo json_encode($result);
	}

	public function nuevoProveedor_AJAX(){
		$data = $this->input->post();

		$response['status'] = "OK";
		$response['data'] = array();

		if(isset($data['nombre'])){
			
			$data['estadoActivo'] = 1;
			$data['tipo'] = 1;

			if( $this->Proveedor->insertar($data) ){
				$response['data'] = $this->Proveedor->traer_AI();
				$response['status'] = "OK";
			}else
				$response['status'] = "ERROR";
		}else $response['status'] = "ERROR";

		echo json_encode($response);
	}

	public function editarProveedor_AJAX(){
		$data = $this->input->post();

		$response['status'] = "OK";
		$response['data'] = array();

		if(
			isset($data['nombre'])
			&& isset($data['estadoActivo'])
			&& isset($data['id'])
		){

			$info['nombre'] = $data['nombre'];
			$info['estadoActivo'] = $data['estadoActivo'];

			if( $this->Proveedor->actualizar($data['id'], $data) ){ 
				$response['status'] = "OK";
				$response['data'] = $this->Proveedor->traer_AI();
			}else $response['status'] = "ERROR";

		}else
			$response['status'] = "ERROR";

		echo json_encode($response);
	}	
}
?>