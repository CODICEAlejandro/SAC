<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alta_cliente_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model("Cliente");
		$this->load->model("DireccionFiscal");
		$this->load->model("DireccionOperativa");
		$this->load->model("Pais");
		$this->load->model("Estado");
		$this->load->model("Ciudad");
		$this->load->model("Banco");
		$this->load->model("BancoAsociado");
		$this->load->model("Contacto");
		$this->load->model("PerfilAsociado");
		$this->load->model("ServicioAsociado");
		$this->load->model("SitioWeb");
	}

	public function index(){
		$data['paises'] = $this->Pais->traerTodo();
		$data['clientes'] = $this->Cliente->traer_AI();
		$data['bancos'] = $this->Banco->traerTodo();

		$data['form_direccion_fiscal'] = $this->load->view("Form_direccion_fiscal_vw", $data, true);
		$data['form_agenda'] = $this->load->view("Form_agenda_vw", $data, true);
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Alta_cliente_vw", $data);
	}

	public function consultarCliente_AJAX($id){
		$result['cliente'] = $this->Cliente->traer($id);
		$result['direccionesFiscales'] = $this->DireccionFiscal->traerAsociadas($id);
		$result['direccionesOperativas'] = $this->DireccionOperativa->traerAsociadas($id);
		$result['bancosAsociados'] = $this->BancoAsociado->traerAsociados($id);

		echo json_encode($result);
	}

	public function nuevoCliente_AJAX(){
		$data = $this->input->post();

		$response['status'] = "OK";
		$response['data'] = array();

		if(isset($data['nombre'])){
			
			$data['estadoActivo'] = 1;
			$data['tipo'] = 0;

			if( $this->Cliente->insertar($data) ){
				$response['data'] = $this->Cliente->traer_AI();
				$response['status'] = "OK";
			}else
				$response['status'] = "ERROR";
		}else $response['status'] = "ERROR";

		echo json_encode($response);
	}

	public function editarCliente_AJAX(){
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

			if( $this->Cliente->actualizar($data['id'], $data) ){ 
				$response['status'] = "OK";
				$response['data'] = $this->Cliente->traer_AI();
			}else $response['status'] = "ERROR";

		}else
			$response['status'] = "ERROR";

		echo json_encode($response);
	}

	public function nuevaDireccionFiscal_AJAX(){
		$response['status'] = 'ERROR';
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->DireccionFiscal->insertar($data);

		if($result != -1){
			$response['status'] = "OK";
			$response['data']['id'] = $result;
		}

		echo json_encode($response);
	}

	public function editarDireccionFiscal_AJAX($id){
		$response['status'] = 'ERROR';
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->DireccionFiscal->actualizar($id, $data);

		if($result){
			$response['status'] = "OK";
		}

		echo json_encode($response);		
	}

	public function nuevaDireccionOperativa_AJAX(){
		$response['status'] = 'ERROR';
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->DireccionOperativa->insertar($data);

		if($result != -1){
			$response['status'] = "OK";
			$response['data']['id'] = $result;
		}

		echo json_encode($response);
	}

	public function editarDireccionOperativa_AJAX($id){
		$response['status'] = 'ERROR';
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->DireccionOperativa->actualizar($id, $data);

		if($result){
			$response['status'] = "OK";
		}

		echo json_encode($response);		
	}

	public function traerEstados_AJAX($idPais){
		$response['data'] = $this->Estado->traerAsociados($idPais);
		$response['status'] = "OK";

		echo json_encode($response);
	}	

	public function traerCiudades_AJAX($idEstado){
		$response['data'] = $this->Ciudad->traerAsociados($idEstado);
		$response['status'] = "OK";

		echo json_encode($response);
	}

	public function traerEstados($idPais){
		$response = $this->Estado->traerAsociados($idPais);

		return $response;
	}

	public function traerCiudades($idEstado){
		$response = $this->Ciudad->traerAsociados($idEstado);

		return $response;
	}

	public function nuevoBanco_AJAX(){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->BancoAsociado->insertar($data);

		if($result)
			$response['status'] = "OK";

		$response['data'] = $result;

		echo json_encode($response);
	}

	public function editarBanco_AJAX($id){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();

		if($this->BancoAsociado->actualizar($id, $data))
			$response['status'] = "OK";

		echo json_encode($response);
	}

	public function nuevoContacto_AJAX(){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->Contacto->insertar($data);

		if($result)
			$response['status'] = "OK";

		$response['data'] = $result;

		echo json_encode($response);		
	}

	public function editarContacto_AJAX($id){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();

		if($this->Contacto->actualizar($id, $data))
			$response['status'] = "OK";

		echo json_encode($response);
	}

	public function nuevoPerfil_AJAX(){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->PerfilAsociado->insertar($data);

		if($result)
			$response['status'] = "OK";

		$response['data'] = $result;

		echo json_encode($response);		
	}

	public function editarPerfil_AJAX($id){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->PerfilAsociado->post();

		if($this->Contacto->actualizar($id, $data))
			$response['status'] = "OK";

		echo json_encode($response);
	}

	public function nuevoServicio_AJAX(){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->ServicioAsociado->insertar($data);

		if($result)
			$response['status'] = "OK";

		$response['data'] = $result;

		echo json_encode($response);		
	}

	public function editarServicio_AJAX($id){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();

		if($this->ServicioAsociado->actualizar($id, $data))
			$response['status'] = "OK";

		echo json_encode($response);
	}

	public function nuevoSitioWeb_AJAX(){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();
		$result = $this->SitioWeb->insertar($data);

		if($result)
			$response['status'] = "OK";

		$response['data'] = $result;

		echo json_encode($response);		
	}

	public function editarSitioWeb_AJAX($id){
		$response['status'] = "ERROR";
		$response['data'] = array();
		$data = $this->input->post();

		if($this->SitioWeb->actualizar($id, $data))
			$response['status'] = "OK";

		echo json_encode($response);
	}		

	public function traerDireccionesFiscales_AJAX($idPadre){
		echo json_encode( $this->DireccionFiscal->traerAsociadas($idPadre) );
	}

	public function traerDireccionesFiscales($idPadre){
		return $this->DireccionFiscal->traerAsociadas($idPadre);
	}
}
?>