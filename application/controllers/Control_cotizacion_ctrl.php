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

	public function cancelarConcepto(){
		$post = $this->input->post();

		if(isset($post["idConcepto"])){
			$idConcepto = htmlentities($post["idConcepto"], ENT_QUOTES, 'UTF-8');

			// Nota: Se cancelan las fechas de factura no facturas y asociadas al concepto en cuestión.
			// No se cancela el concepto de la cotización.
			$queryCancelaFechasFactura = "update 
											fecha_factura
										set 
											idEstadoFactura = 22
										where
											idConceptoCotizacion = ".$idConcepto."
											and idEstadoFactura = 23
										";
			$this->db->query($queryCancelaFechasFactura);

			echo "Concepto y fechas asociadas cancelados";
		}else echo "Corrupci&oacute;n de la informaci&oacute;n";
	}

	public function retrieveQuotations_AJAX(){
		$result['status'] = 'OK';
		$result['data'] = array();

		$idCliente = $this->input->post('idCliente');
		$idCotizacion = $this->input->post('idCotizacion');

		if($idCliente != -1){
			if($idCotizacion == -1)
				$cotizaciones = $this->Cliente->traerCotizaciones($idCliente);
			else
				$cotizaciones = $this->Cliente->traerCotizacion($idCliente, $idCotizacion);
		}else
			$cotizaciones = $this->Cotizacion->traerTodo();
		
		//Asociar cotizaciones con conceptos_cotización y estos con fechas_factura
		/*
		Modelo de datos: arreglo = { 
									[
										"cotizacion" => cotizacion_1,
										"conceptos" =>  [
															[
																"concepto" => concepto_1,
																"fechas_factura" => [ fecha_1, fecha_N ]
															],
															...
															[
																"concepto" => concepto_N,
																"fechas_factura" => [ fecha_1, fecha_N ]
															]
														]
									],
									...
								}
		*/

		$resultado = array();
		$k = 0;

		foreach($cotizaciones as $c){
			$resultado[$k]["cotizacion"] = $c;

			$query_conceptos_cotizacion = "select c.id id, c.descripcion descripcion
										from 
											concepto_cotizacion c
										where c.idCotizacion = ".($c->id);
			$conceptos = $this->db->query($query_conceptos_cotizacion)->result();

			$m = 0;
			foreach($conceptos as $con){

				$resultado[$k]["conceptos"][$m]["concepto"] = $con;
				$query_fechas_factura = "select id, importe, referencia, 
											DATE_FORMAT(fecha,'%d/%m/%Y') fecha, nota, 
											idEstadoFactura,
											DATE_FORMAT(fecha_final,'%d/%m/%Y') fecha_final,
											DATE_FORMAT(fecha_final_confirmada,'%d/%m/%Y') fecha_final_confirmada,
											idBanco, key_cancelar 
										from fecha_factura where idEstadoFactura = 23 and idConceptoCotizacion = ".($con->id);
				$resultado[$k]["conceptos"][$m]["fechas_factura"] =  $this->db->query($query_fechas_factura)->result();

				$m++;
			}

			$k++;
		}

		echo json_encode($resultado);
	}

	public function cancelarFechaFactura(){
		$idFechaFactura = $this->input->post("idFechaFactura");
		$idFechaFactura = htmlentities($idFechaFactura, ENT_QUOTES, 'UTF-8');

		$queryFechaFactura = "update fecha_factura set idEstadoFactura = 22 where id = ".$idFechaFactura;
		$this->db->query($queryFechaFactura);
	}

	public function guardarFechaFactura(){
		$idFechaFactura = $this->input->post("idFechaFactura");
		$nota = $this->input->post("nota");
		$referencia = $this->input->post("referencia");
		$nuevaFecha = $this->input->post("nuevaFecha");

		$idFechaFactura = htmlentities($idFechaFactura, ENT_QUOTES, 'UTF-8');
		$nota = htmlentities($nota, ENT_QUOTES, 'UTF-8');
		$referencia = htmlentities($referencia, ENT_QUOTES, 'UTF-8');
		$nuevaFecha = htmlentities($nuevaFecha, ENT_QUOTES, 'UTF-8');

		$queryFechaFactura = "update fecha_factura 
								set 
									fecha_final = '".$nuevaFecha."', 
									nota = '".$nota."',
									referencia = '".$referencia."' 
								where id = ".$idFechaFactura;
		$this->db->query($queryFechaFactura);
	}


	public function saveNote_AJAX(){
		$id = $this->input->post('id');
		$data = $this->input->post();
		unset($data['id']);

		$this->Cotizacion->actualizar($id, $data);
	}


	public function traerCotizaciones_AJAX(){
		$idCliente = $this->input->post('idCliente');

		$result = $this->Cliente->traerCotizaciones($idCliente);

		echo json_encode($result);
	}
}

?>