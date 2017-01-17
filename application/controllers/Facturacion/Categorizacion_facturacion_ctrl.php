<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categorizacion_facturacion_ctrl extends CI_Controller {
	public function index(){
		$query_conceptos_pendientes = "select c.nombre cliente, a.nombre account, f.concepto concepto,
											f.monto monto, DATE_FORMAT(f.fecha, '%d/%m/%Y') fecha,
											f.id id 
										from historico_facturacion f
											inner join catcliente c on c.id = f.id_cliente
											inner join account_manager a on a.id = f.id_account_manager
										where
											f.es_nuevo = 2";
		$data["conceptos"] = $this->db->query($query_conceptos_pendientes)->result();
		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$this->load->view("Facturacion/Categorizacion_facturacion_vw", $data);
	}

	public function categorizar(){
		$categoria = htmlentities($this->input->post("cat"), ENT_QUOTES, 'UTF-8');
		$id_concepto = htmlentities($this->input->post("id_concepto"), ENT_QUOTES, 'UTF-8');

		$query_actualiza = "update historico_facturacion set es_nuevo = ".$categoria
							." where id = ".$id_concepto;

		$this->db->query($query_actualiza);

		echo "OK";
	}
}