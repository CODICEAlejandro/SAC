<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consulta_conceptos_facturacion_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data["facturacion"] = $this->cargar_principal(); 
		$data["menu"] = $this->load->view("Menu_principal", null, true);
		die("POLLO");

		$this->load->view("Consulta_conceptos_facturacion_vw", $data);
	}

	//Carga la información principal 
	public function cargar_principal(){
		$query = "
					select 
						hf.id id, 
						c.nombre cliente, 
						u.nombre account, 
						hf.concepto concepto, 
						hf.monto monto, 
						DATE_FORMAT(hf.fecha,'%d/%m/%Y') fecha, 
						if(hf.es_nuevo=1, 'NUEVO', if(hf.es_nuevo=2, 'PENDIENTE', 'ACTUAL')) temporalidad 
					from historico_facturacion hf
						inner join catcliente c on c.id = hf.id_cliente
						inner join catusuario u on u.id = hf.id_account_manager
					";

		return $this->db->query($query)->result();
	}
}
?>