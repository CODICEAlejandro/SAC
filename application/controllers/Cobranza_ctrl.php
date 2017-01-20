<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cobranza_ctrl extends CI_Controller {
	public function index(){
		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$query_fechas_no_pagadas = "select 
										f.id id, f.importe importe_fecha, f.referencia ref_fecha, 
										DATE_FORMAT(f.fecha_final, '%d/%m/%Y') fecha_final,
										f.nota nota_fecha, f.fecha_final_confirmada confirmada,
										cc.descripcion desc_concepto_asociado,
										f.idEstadoFactura idEstadoFactura
									from fecha_factura f inner join concepto_cotizacion cc on
										cc.id = f.idConceptoCotizacion
										inner join cotizacion cot on cot.id = cc.idCotizacion
										inner join catcliente catcli on catcli.id = cot.idCliente
									where f.idEstadoFactura in (24,25) 
										and catcli.tipo = 0
									order by f.idEstadoFactura, f.fecha_final asc";

		$data['fechas'] = $this->db->query($query_fechas_no_pagadas)->result();
		$this->load->view("Cobranza_vw", $data);
	}

	public function pagar($idFechaFactura){
		$idFechaFactura = htmlentities($idFechaFactura, ENT_QUOTES, 'UTF-8');
		$query_actualiza_estado = "update fecha_factura set idEstadoFactura = 21 where id = ".$idFechaFactura;

		$this->db->query($query_actualiza_estado);
		echo "OK";
	}

	public function cancelar($idFechaFactura, $key_candidate, $action){
		die("POLLO");
		$idFechaFactura = htmlentities($idFechaFactura, ENT_QUOTES, 'UTF-8');
		$query_traer_key = "select key_cancelar key from fecha_factura
							where id = ".$idFechaFactura;
		$res_key = $this->db->query($query_traer_key)->row();
		$key = $res_key->key;

		if($key_candidate == $key){
			if($action == "confirma"){
				$query_actualiza_estado = "update fecha_factura set idEstadoFactura = 22 where id = ".$idFechaFactura;
				echo "Fecha de facturación cancelada";
			}else if($action == "refuta"){
				$query_actualiza_estado = "update fecha_factura set idEstadoFactura = 24 where id = ".$idFechaFactura;
				echo "Fecha de facturación regresada a estado no pagado";
			}

			$this->db->query($query_actualiza_estado);
		}else echo "Imposible proceder con la cancelación de la fecha de facturación.";
	}

	public function cancelar_email($idFechaFactura){
		$this->load->model("Mailer");
		$mailer = new Mailer();

		//Generar secret key
		$private_key = "J0bsK3y";
		$key = md5(microtime().$private_key);

		$query_actualiza_key = "update fecha_factura 
									set key_cancelar = '".$key."',
									idEstadoFactura = 25
								where id = ".$idFechaFactura;
		$this->db->query($query_actualiza_key);

		//Data de fecha
		$query_fecha = "select 
							f.id id, f.importe importe, f.referencia referencia, 
							DATE_FORMAT(f.fecha_final, '%d/%m/%Y') fecha_final,
							DATE_FORMAT(f.fecha, '%d/%m/%Y') fecha,
							cc.descripcion desc_concepto,
							cot.folio folio_cotizacion
						from fecha_factura f inner join concepto_cotizacion cc on
							cc.id = f.idConceptoCotizacion
							inner join cotizacion cot on cot.id = cc.idCotizacion
						where f.id = ".$idFechaFactura;
		$f = $this->db->query($query_fecha)->row();

		//Mandar mail
		$url_confirmacion = base_url()."index.php/Cobranza_ctrl/cancelar/".$idFechaFactura."/".$key."/confirma";
		$url_denegado = base_url()."index.php/Cobranza_ctrl/cancelar/".$idFechaFactura."/".$key."/refuta";

		$to = array("massmy@live.com.mx");
		$subject = "JOBS - Solicitud de cancelación de una fecha de facturación";
		$body = "Se ha solicitado su aprobación para cancelar la fecha de facturación:";
		$body .= "<br>- ".$idFechaFactura;
		$body .= "<br>- Importe: ".$f->importe;
		$body .= "<br>- Referencia: ".$f->referencia;
		$body .= "<br>- Fecha original: ".$f->fecha;
		$body .= "<br>- Fecha recorrida: ".$f->fecha_final;
		$body .= "<br>- Concepto relacionado: ".$f->desc_concepto;
		$body .= "<br>- Cotización: ".$f->folio_cotizacion;
		$body .= "<br>- Liga de confirmación: ".$url_confirmacion;
		$body .= "<br>- Liga de refutación: ".$url_denegado;

		$mailer->sendEmail($subject, $body, $to);
	}

	public function refacturar($idFechaFactura){
		$idFechaFactura = htmlentities($idFechaFactura, ENT_QUOTES, 'UTF-8');
		$fecha = $this->db->query("select * from fecha_factura where id = ".$idFechaFactura)->result_array();

		if(count($fecha) == 1){			
			$fecha = $fecha[0];
			//$fecha['idEstadoFactura'] = 23;	//Genera fecha clon por facturar
			unset($fecha['id']);			//Quita ID actual, para autoincrementarlo con DBMS

			$this->db->insert('fecha_factura', $fecha);
			echo $this->db->insert_id();
		}else
			echo "FAIL";
	}

	public function actualizarConfirmacion($idFechaFactura, $estado){
		$idFechaFactura = htmlentities($idFechaFactura, ENT_QUOTES, 'UTF-8');
		$estado = htmlentities($estado, ENT_QUOTES, 'UTF-8');
		$query_actualiza_estado = "update fecha_factura set fecha_final_confirmada = ".$estado." where id = ".$idFechaFactura;

		$this->db->query($query_actualiza_estado);
		echo "OK";
	}

	public function actualizarFecha($idFechaFactura){
		$fecha = htmlentities($this->input->post("fecha"), ENT_QUOTES, 'UTF-8');
		$query_actualiza_estado = "update fecha_factura set fecha_final = '".$fecha."' where id = ".$idFechaFactura;
		$this->db->query($query_actualiza_estado);
		echo "OK";
	}
}
?>