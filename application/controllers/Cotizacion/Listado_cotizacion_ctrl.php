<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Listado_cotizacion_ctrl extends CI_Controller{
	public function index(){
		$data["clientes"] = $this->db->query("select * from catcliente where estadoActivo=1 and tipo=0")->result();
		$data["forma_pago"]= $this->db->query("select * from cat_tipo_cotizacion")->result();
		$data["servicio_alcance"] = $this->db->query("select * from cattipoconcepto where tipo = 0")->result();
		$data["cotizaciones"]= $this->db->query("SELECT cli.nombre nombre_cli, usu.nombre nombre_acc, usu.correo correo,cot.* 
												FROM cotizacion cot
												JOIN catcliente cli ON cot.idCliente = cli.id
												JOIN catusuario usu ON cot.accountManager = usu.id
												ORDER BY creacion DESC LIMIT 10")->result();
		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view("Cotizacion/Listado_cotizacion_vw", $data);
	}
}
?>