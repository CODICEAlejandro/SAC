<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalle_factura_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Factura");
		$this->load->model("RelacionConceptoFactura");
	}

	public function detallarFactura($idFactura){
		$data['currentFactura'] = $idFactura;
		$data['conceptos'] = $this->Factura->traerConceptos($idFactura);
		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$this->load->view("Detalle_factura_vw", $data);
	}


	public function updateNote($idFactura, $idConcepto){
		$this->RelacionConceptoFactura->actualizar($idFactura);
	}
}

?>