<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alta_cliente_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model("Cliente");
	}

	public function index(){
		$data['clientes'] = $this->Cliente->traer_AI();
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Alta_cliente_vw", $data);
	}

	public function consultarCliente_AJAX($id){
		$result = $this->Cliente->traer($id);

		echo json_encode($result);
	}

	public function nuevoCliente_AJAX(){
		$data = $this->input->post();

		if(isset($data['nombre'])){
			
			$data['estadoActivo'] = 1;
			$data['tipo'] = 0;

			if( $this->Cliente->insertar($data) ) echo "OK";
			else echo "ERROR";
		}else echo "ERROR";
	}

	public function editarCliente_AJAX(){
		$data = $this->input->post();

		if(
			isset($data['nombre'])
			&& isset($data['estadoActivo'])
			&& isset($data['id'])
		){

			$info['nombre'] = $data['nombre'];
			$info['estadoActivo'] = $data['estadoActivo'];

			if( $this->Cliente->actualizar($data['id'], $data) ) echo "OK";
			else echo "ERROR";

		}else
			echo "ERROR";

	}	
}
?>