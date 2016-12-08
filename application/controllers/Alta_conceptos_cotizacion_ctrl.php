<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alta_conceptos_cotizacion_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->model("Cliente");
		$this->load->model("Usuario");

		$data['clientes'] = $this->Cliente->traerTodo();
		$data['usuario'] = $this->Usuario->traerTodo();
		$data['account'] = $this->Usuario->traerPuesto(5);
		$data['tipoConcepto'] = $this->db->query("select * from cattipoconcepto")->result();
		$data['unidadMedida'] = $this->db->query("select * from catunidadmedida")->result();
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Alta_conceptos_cotizacion_vw", $data);
	}

	public function getRazonesSociales($idCliente){
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					DISTINCT
					df.`id` id,
					df.`razonSocial` razonSocial
				FROM
					`direccionfiscal` df
					INNER JOIN `catcliente` cc ON cc.`id` = df.`idPadre`
				WHERE
					cc.`id` = ".$idCliente."
					AND df.`estadoActivo` = 1
				";

		echo json_encode($this->db->query($query)->result());
	}

	public function getCotizaciones($idRazonSocial){
		$idRazonSocial = html_entity_decode($idRazonSocial, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					DISTINCT
					co.`id` id,
					co.`folio`
				FROM
					`cotizacion` co
					INNER JOIN `direccionfiscal` df ON df.`id` = co.`idRazonSocial`
				WHERE
					co.`idRazonSocial` = ".$idRazonSocial."
					AND co.`estadoActivo` = 1
				";

		echo json_encode($this->db->query($query)->result());
	}

	public function guardarCotizacion(){
		$post = $this->input->post();

		$idRazonSocial = htmlentities($post["id-razon-social"], ENT_QUOTES, 'UTF-8');
		$nota_cotizacion = htmlentities($post["nota-cotizacion"], ENT_QUOTES, 'UTF-8');
		$fecha_junta_arranque = htmlentities($post["alt_fecha_junta_arranque"], ENT_QUOTES, 'UTF-8');
		$fecha_inicio_proyecto = htmlentities($post["alt_fecha_inicio_proyecto"], ENT_QUOTES, 'UTF-8');
		$fecha_fin_proyecto = htmlentities($post["alt_fecha_fin_proyecto"], ENT_QUOTES, 'UTF-8');
		$fecha_venta = htmlentities($post["alt_fecha_venta"], ENT_QUOTES, 'UTF-8');
		$idCerrador = htmlentities($post["id_cerrador"], ENT_QUOTES, 'UTF-8');
		$idResponsable = htmlentities($post["id_responsable"], ENT_QUOTES, 'UTF-8');
		$accountManager = htmlentities($post["id_account_manager"], ENT_QUOTES, 'UTF-8');
		$titulo_cotizacion = htmlentities($post["titulo_cot"], ENT_QUOTES, 'UTF-8');
		$folioCotizacion = htmlentities($post["folio-cotizacion"], ENT_QUOTES, 'UTF-8');
		
		$query_cotizacion = "INSERT INTO `cotizacion` 
										(`idRazonSocial`, `folio`, `estadoActivo`, `nota`, 
										`fechaJuntaArranque`, `inicioProyecto`, `finProyecto`, 
										`fechaVenta`, `idCerrador`, `idResponsable`, `contrato`, 
										`accountManager`, `titulo`)
							VALUES
							(
								".$idRazonSocial.", 
								'".$folioCotizacion."',
								1, 
								'".$nota_cotizacion."', 
								'".$fecha_junta_arranque."', 
								'".$fecha_inicio_proyecto."', 
								'".$fecha_fin_proyecto."', 
								'".$fecha_venta."', 
								".$idCerrador.", 
								".$idResponsable.", 
								1, 
								".$accountManager.", 
								'".$titulo_cotizacion."'
							);
							";

		$this->db->query($query_cotizacion);
		$id_cotizacion = $this->db->insert_id();

		for($k=0, $n=count($post["descripcion-concepto"]); $k<$n; $k++){
			$descripcion_concepto = htmlentities($post["descripcion-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$tipo_concepto = htmlentities($post["id-tipo-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$referencia_concepto = htmlentities($post["referencia-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$nota_concepto = htmlentities($post["nota-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$cantidad_concepto = htmlentities($post["cantidad-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$unidad_medida_concepto = htmlentities($post["unidad-medida-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$valor_unitario_concepto = htmlentities($post["valor-unitario-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$importe_concepto = htmlentities($post["importe-concepto"][$k], ENT_QUOTES, 'UTF-8');
			$total_concepto = htmlentities($post["total-concepto"][$k], ENT_QUOTES, 'UTF-8');

			$query_concepto = "insert into concepto_cotizacion 
									(descripcion, idTipoConcepto, referencia, idCotizacion, 
									nota, cantidad, unidadDeMedida, valorUnitario, monto, 
									idEstadoFactura, total)
							values (
									'".$descripcion_concepto."',
									".$tipo_concepto.",
									'".$referencia_concepto."',
									".$id_cotizacion.",
									'".$nota_concepto."',
									".$cantidad_concepto.",
									'".$unidad_medida_concepto."',
									".$valor_unitario_concepto.",
									".$importe_concepto.",
									23,
									".$total_concepto.
								")";

			$this->db->query($query_concepto);
			$id_concepto = $this->db->insert_id();

		}

		redirect('/Panel_control_ctrl', 'refresh');
	}
}

?>