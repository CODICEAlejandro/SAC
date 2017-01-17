<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Captura_facturacion_ctrl extends CI_Controller {

	public function index(){
		$data['clientes'] = $this->db->query("select * from catcliente where tipo = 0 and estadoActivo = 1")->result();
		$data['accounts'] = $this->db->query("select * from account_manager where activo = 'S'")->result();
		$data['menu'] = $this->load->view('Menu_principal', null, true);
		$this->load->view("Facturacion/Control_facturacion_vw", $data);
	}

	public function guardarConceptos(){
		$idCliente = htmlentities($this->input->post("cliente"), ENT_QUOTES, 'UTF-8');
		$account = htmlentities($this->input->post("account"), ENT_QUOTES, 'UTF-8');
		$conceptos = $this->input->post("conceptos");

		for($k=0, $n=count($conceptos); $k<$n; $k++){
			$desc_concepto = htmlentities($conceptos[$k][0], ENT_QUOTES, 'UTF-8');
			$monto = htmlentities($conceptos[$k][1], ENT_QUOTES, 'UTF-8');
			$fecha = htmlentities($conceptos[$k][2], ENT_QUOTES, 'UTF-8');

			$query_cliente = "select tipo_periodo from catcliente where id = ".$idCliente;
			$cliente = $this->db->query($query_cliente)->row();
			$periodo = $cliente->tipo_periodo;

			if($periodo == 2) 
				$periodo = 1;
			else
				$periodo = 2;

			$query_insertar = "insert into historico_facturacion 
								(id_cliente, id_account_manager, concepto, monto, fecha, es_nuevo) 
								values (".$idCliente.",
										".$account.",
										'".$desc_concepto."',
										".$monto.",
										'".$fecha."',
										".$periodo.")";

			$this->db->query($query_insertar);
		}

		echo "OK";
	}

	public function buscarAccount(){
		$cliente = htmlentities($this->input->post("cliente"), ENT_QUOTES, 'UTF-8');

		$query_account = "select
							ifnull(u.nombre, 'NOT_FOUND') nombre,
							ifnull(u.id, 'NOT_FOUND') id_account
						from
							catcliente c
							left join account_manager u on u.id = c.id_account_manager
						where
							c.id = ".$cliente."
						";
		$res_account = $this->db->query($query_account)->row();

		echo json_encode($res_account);
	}

}

?>