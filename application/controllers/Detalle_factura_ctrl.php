<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalle_factura_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Factura");
		$this->load->model("Concepto");
		$this->load->model("RelacionConceptoFactura");
	}

	public function detallarFactura($idFactura, $folioFactura){
		$data['currentFactura'] = $idFactura;
		$data['folioFactura'] = $folioFactura;
		$data['conceptos'] = $this->Factura->traerConceptos($idFactura);
		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$this->load->view("Detalle_factura_vw", $data);
	}


	public function updateNote(){
		$idConcepto = htmlentities($this->input->post("idConcepto"), ENT_QUOTES, "UTF-8");
		$nuevaNota = htmlentities($this->input->post("nuevaNota"), ENT_QUOTES, "UTF-8");

		$data = array("nota" => $nuevaNota);

		$this->Concepto->actualizar($idFactura, $data);
	}
}

?>