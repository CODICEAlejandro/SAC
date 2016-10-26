<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Listado_facturas_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Factura");
		$this->load->model("Cotizacion");
	}

	public function loadWindow($idCotizacion){
		$data['currentCotizacion'] = $idCotizacion;
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Listado_facturas_vw", $data);
	}

	public function saveNote($idFactura){
		$data = $this->input->post();

		$this->Factura->actualizar($idFactura, $data);
	}

	public function retrieveAll_AJAX(){
		$idCotizacion = $this->input->post('idCotizacion');
		$cPage = $this->input->post('cPage');
		$cCount = $this->input->post('cCount');
		$dateFrom = '2016-01-01';
		$dateTo = date("Y-m-d");

		$response['status'] = 'OK';
		$response['data'] = $this->Cotizacion->traerFacturas_Interval($idCotizacion, $cPage, $cCount, $dateFrom, $dateTo);

		echo json_encode($response);		
	}

	public function retrieveBills_AJAX(){
		$idCotizacion = $this->input->post('idCotizacion');
		$cPage = $this->input->post('cPage');
		$cCount = $this->input->post('cCount');
		$dateFrom = $this->input->post('dateFrom');
		$dateTo = $this->input->post('dateTo');

		$response['status'] = 'OK';
		$response['data'] = $this->Cotizacion->traerFacturas_Interval($idCotizacion, $cPage, $cCount, $dateFrom, $dateTo);

		echo json_encode($response);
	}

	public function updateNote_AJAX(){
		$data = array('nota' => $this->input->post('nota'));
		$id = $this->input->post('id');

		$this->Factura->actualizar($id, $data);
	}
}

?>