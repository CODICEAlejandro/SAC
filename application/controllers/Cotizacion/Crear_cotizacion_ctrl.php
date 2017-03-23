<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crear_cotizacion_ctrl extends CI_Controller {

	public function index(){
		$data["menu"] = $this->load->view("Menu_principal", null, true);
		$data["clientes"] = $this->db->query("select * from catcliente where estadoActivo=1 and tipo=0")->result();
		$data["forma_pago"] = $this->db->query("select * from cat_tipo_cotizacion")->result();
		$data["servicio_alcance"] = $this->db->query("select * from cattipoconcepto where tipo = 0")->result();
		$this->load->view("Cotizacion/Crear_cotizacion_vw", $data); 
	}

} 

?>