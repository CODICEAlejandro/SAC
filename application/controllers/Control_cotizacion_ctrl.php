<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control_cotizacion_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Cliente');
		$this->load->model('Cotizacion');
		$this->load->model('DireccionFiscal');
	}

	public function index(){
		$data['clientes'] = $this->Cliente->traerTodo();
		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$this->load->view('Control_cotizacion_vw', $data);
	}

	public function retrieveSocialReasons_AJAX(){
		$result['status'] = 'OK';
		$result['data'] = array();

		$idCliente = $this->input->post('idCliente');

		if($idCliente == -1)
			$result['data'] = $this->DireccionFiscal->traerTodo($idCliente);
		else
			$result['data'] = $this->DireccionFiscal->traerAsociadas($idCliente);

		echo json_encode($result);
	}

	public function retrieveQuotations_AJAX(){
		$result['status'] = 'OK';
		$result['data'] = array();

		$idCliente = $this->input->post('idCliente');
		$idRazonSocial = $this->input->post('idRazonSocial');

		if($idRazonSocial != -1)
			$result['data'] = $this->DireccionFiscal->traerCotizaciones($idRazonSocial);
		else{
			if($idCliente != -1)
				$result['data'] = $this->Cliente->traerCotizaciones($idCliente);
			else
				$result['data'] = $this->Cotizacion->traerTodo();
		}

		echo json_encode($result);
	}

	public function saveNote_AJAX(){
		$id = $this->input->post('id');
		$data = $this->input->post();
		unset($data['id']);

		$this->Cotizacion->actualizar($id, $data);
	}
}

?>