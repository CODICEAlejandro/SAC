<?php 

defined('BASEPATH') OR exit('No direct script access allowed');


class Listado_cotizacion_ctrl extends CI_Controller{
	public function index(){
		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$data["cotizaciones"]= $this->db->query("SELECT cli.nombre nombre_cli, con.nombre nombre_acc, con.apellido apellido_acc, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
		FROM cotizacion_account cot
		JOIN catcliente cli ON cot.id_cliente = cli.id
		JOIN contacto con ON cot.id_contacto = con.id
		JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
		ORDER BY cot.fecha_alta DESC LIMIT 20")->result();

		$this->load->view("Cotizacion/Listado_cotizacion_vw", $data);
	}

	public function busquedaRegistros()
	{
		if (isset($_POST) && isset($_POST["parametro"])) {
			$parametro = $_POST["parametro"];
			$parametro = htmlentities($parametro,ENT_QUOTES,'UTF-8');

			$cotizaciones= $this->db->query("
				SELECT cli.nombre nombre_cli, con.nombre nombre_acc, con.apellido apellido_acc, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE (cot.folio LIKE '%".$parametro."%'
				OR cot.titulo LIKE '%".$parametro."%')
				ORDER BY cot.fecha_alta DESC
				")->result();

			echo json_encode($cotizaciones);
		}
	}

}


?>

