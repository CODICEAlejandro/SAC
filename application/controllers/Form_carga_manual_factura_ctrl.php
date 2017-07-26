<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Form_carga_manual_factura_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Concepto');
		$this->load->model('Impuesto');
		$this->load->model('Factura');
		$this->load->model('Cliente');
	}

	public function index(){
		$data['menu'] = $this->load->view("Menu_principal", null, true);
		$data['clientes'] = $this->db->query('select id, nombre from catcliente where tipo = 0')->result();
		$data['estadosFactura'] = $this->db->query("select * from catestadofactura where id = 24")->result();
		$data["monedas"] = $this->db->query("select distinct moneda from factura WHERE moneda NOT IN ('','SININFOENXML')")->result();
		$folioEncontrado = $this->db->query("SELECT SUBSTR(f.folio,3,4) folio FROM factura f
			JOIN concepto_factura_rel fr ON f.id = fr.idFactura
			JOIN concepto c ON fr.idConcepto = c.id
			JOIN concepto_factura_cotizacion fc ON c.id = fc.idConceptoFactura
			JOIN fecha_factura ff ON fc.idFechaFactura = ff.id
			JOIN concepto_cotizacion cc ON ff.idConceptoCotizacion = cc.id
			JOIN cotizacion cot ON cc.idCotizacion = cot.id
			JOIN catcliente cli ON cot.idCliente = cli.id
			WHERE f.folio LIKE 'EF%'
			AND cli.tipo = 0
			ORDER BY f.folio DESC LIMIT 1")->row();

		if(!empty($folioEncontrado)){
			$data["folio"] = $this->generaFolio($folioEncontrado->folio);
		}else{
			$data["folio"] = "EF0001";
		}

		

		$this->load->view("Form_carga_manual_factura_vw", $data);
		
	}

	public function generaFolio($folio){

		$folio = (int) $folio;
		$folio++;

		if(mb_strlen($folio)<4){
			for($i=mb_strlen($folio);$i<4;$i++){
				$folio = "0".$folio;
			}
		}
		$folio = "EF".$folio;

		return $folio;
		
	}

	public function guardarFactura(){
		$conceptos = $this->input->post("rows");
		$general = $this->input->post("general");
		$cliente = $this->input->post("cliente");
		$razonSocial = $this->input->post("razonSocial");

		$conceptos = json_decode($conceptos, false);
		$general = json_decode($general, false);

		$fechaExpedicion = htmlentities($general->fechaExpedicion, ENT_QUOTES, 'UTF-8');
		$moneda = htmlentities($general->moneda, ENT_QUOTES, 'UTF-8');
		$tipoDeCambioVenta = htmlentities($general->tipoDeCambioVenta, ENT_QUOTES, 'UTF-8');
		$subtotalFactura = htmlentities($general->subtotalFactura, ENT_QUOTES, 'UTF-8');
		$totalFactura = htmlentities($general->totalFactura, ENT_QUOTES, 'UTF-8');
		$totalEnLetra = htmlentities($general->totalEnLetra, ENT_QUOTES, 'UTF-8');
		$formaDePago = htmlentities($general->formaDePago, ENT_QUOTES, 'UTF-8');
		$totalTrasladosFederales = htmlentities($general->totalTrasladosFederales, ENT_QUOTES, 'UTF-8');
		$totalIVATrasladado = htmlentities($general->totalIVATrasladado, ENT_QUOTES, 'UTF-8');
		$totalTrasladosLocales = htmlentities($general->totalTrasladosLocales, ENT_QUOTES, 'UTF-8');
		$totalRetencionesLocales = htmlentities($general->totalRetencionesLocales, ENT_QUOTES, 'UTF-8');
		$subtotalBruto = htmlentities($general->subtotalBruto, ENT_QUOTES, 'UTF-8');
		$folioFactura = htmlentities($general->folioFactura, ENT_QUOTES, 'UTF-8');
		$estadoFactura = htmlentities($general->estadoFactura, ENT_QUOTES, 'UTF-8');
		$fechaDePago = htmlentities($general->fechaDePago, ENT_QUOTES, 'UTF-8');
		$fechaDeCancelacion = htmlentities($general->fechaDeCancelacion, ENT_QUOTES, 'UTF-8');
		$ordenDeCompra = htmlentities($general->ordenDeCompra, ENT_QUOTES, 'UTF-8');
		$ivaFactura = (float) htmlentities($general->ivaFactura, ENT_QUOTES, 'UTF-8');
		$importeFactura = htmlentities($general->importeFactura, ENT_QUOTES, 'UTF-8');
		$notasFactura = htmlentities($general->notasFactura, ENT_QUOTES, 'UTF-8');
		$totalIEPSTrasladado = htmlentities($general->totalIEPSTrasladado, ENT_QUOTES, 'UTF-8');
		$totalRetencionesFederales = htmlentities($general->totalRetencionesFederales, ENT_QUOTES, 'UTF-8');
		$totalISRRetenido = htmlentities($general->totalISRRetenido, ENT_QUOTES, 'UTF-8');
		$totalIVARetenido = htmlentities($general->totalIVARetenido, ENT_QUOTES, 'UTF-8');

		//Guardar data de la factura
		$query_factura = "INSERT INTO `factura` 
							(`idEstadoFactura`, `estadoActivo`, 
							`folio`, `total`, 
							`fechaPago`, `subtotal`, 
							`moneda`, `ordenCompra`, `iva`, 
							`nota`, `tipoDeCambioVenta`, 
							`totalEnLetra`, `formaDePago`, 
							`totalTrasladosFederales`, `totalIVATrasladado`, 
							`totalIEPSTrasladado`, `totalRetencionesFederales`, 
							`totalISRRetenido`, `totalIVARetenido`, 
							`totalTrasladosLocales`, `totalRetencionesLocales`, 
							`fechaCancelacion`, `fechaFactura`, 
							`xml`, `importe`)
						VALUES
							(".$estadoFactura.", 1, 
							'".$folioFactura."', ".$totalFactura.", '".$fechaDePago."', 
							".$subtotalFactura.", '".$moneda."', '".$ordenDeCompra."', 
							".$ivaFactura.", '".$notasFactura."', ".$tipoDeCambioVenta.", 
							'".$totalEnLetra."', '".$formaDePago."', ".$totalTrasladosFederales.",
							".$totalIVATrasladado.", ".$totalIEPSTrasladado.", ".$totalRetencionesFederales.", 
							".$totalISRRetenido.", ".$totalIVARetenido.",
							".$totalTrasladosLocales.", ".$totalRetencionesLocales.", 
							'".$fechaDeCancelacion."', '".$fechaExpedicion."','CARGA MANUAL', 
							".$importeFactura.")
						";

		$this->db->query($query_factura);
		$id_factura = $this->db->insert_id();
		//Guardar data de los conceptos
		for($k_concepto=0, $n_conceptos=count($conceptos); $k_concepto < $n_conceptos; $k_concepto++){
			//$concepto = htmlentities($conceptos[$k_concepto], ENT_QUOTES, 'UTF-8');
			$concepto = $conceptos[$k_concepto];
			$monto = htmlentities($concepto->monto, ENT_QUOTES, 'UTF-8');
			$descripcion = htmlentities($concepto->descripcion, ENT_QUOTES, 'UTF-8');
			$nota = htmlentities($concepto->notas, ENT_QUOTES, 'UTF-8');
			$cantidad = htmlentities($concepto->cantidad, ENT_QUOTES, 'UTF-8');
			$unidadDeMedida = htmlentities($concepto->unidadDeMedida, ENT_QUOTES, 'UTF-8');
			$textosDePosicion = htmlentities($concepto->textosDePosicion, ENT_QUOTES, 'UTF-8');
			$valorUnitario = htmlentities($concepto->valorUnitario, ENT_QUOTES, 'UTF-8');
			$importe = htmlentities($concepto->importe, ENT_QUOTES, 'UTF-8');
			$cantidadIVA = htmlentities($monto - $importe, ENT_QUOTES, 'UTF-8');
			$impuestos = $concepto->impuestos;
			$matches = $concepto->matches;

			$query_concepto = "INSERT INTO `concepto` 
									(`monto`, `estadoActivo`, `descripcion`, 
									`nota`, `cantidad`, `unidadDeMedida`, 
									`textosDePosicion`, 
									`valorUnitario`, `importe`, `cantidadIVA`)
								VALUES
									(".$monto.", 1, '".$descripcion."', 
									'".$nota."', ".$cantidad.", '".$unidadDeMedida."', 
									'".$textosDePosicion."', 
									".$valorUnitario.", ".$importe.", ".$cantidadIVA.")
							";

			$this->db->query($query_concepto);
			$id_concepto = $this->db->insert_id();

			//Guardar data de los impuestos asociados al concepto actual
			for($k_impuesto = 0, $n_impuestos = count($impuestos); $k_impuesto<$n_impuestos; $k_impuesto++){
				$impuesto = $impuestos[$k_impuesto];
				$contexto = htmlentities($impuesto->contexto, ENT_QUOTES, 'UTF-8');
				$operacion = htmlentities($impuesto->operacion, ENT_QUOTES, 'UTF-8');
				$codigo = htmlentities($impuesto->codigo, ENT_QUOTES, 'UTF-8');
				$base = htmlentities($impuesto->base, ENT_QUOTES, 'UTF-8');
				$tasa = htmlentities($impuesto->tasa, ENT_QUOTES, 'UTF-8');
				$monto = htmlentities($impuesto->monto, ENT_QUOTES, 'UTF-8');

				$query_impuesto = "INSERT INTO `impuesto` 
										(`idConcepto`, `contexto`, `operacion`, 
										`codigo`, `base`, `tasa`, `monto`)
									VALUES
										(".$id_concepto.", '".$contexto."', 
										'".$operacion."', '".$operacion."', 
										".$base.", 
										".$tasa.", ".$monto.");
									";

				$this->db->query($query_impuesto);
			}

			//Asociar el concepto con la factura
			$query_concepto_factura_rel = "INSERT INTO `concepto_factura_rel` 
										(`idConcepto`, `idFactura`, `nota`)
										VALUES
											(".$id_concepto.", ".$id_factura.", '');
										";

			//Asociar concepto de factura actual con conceptos de la cotización correspondientes
			for($k_match = 0, $n_matches = count($matches); $k_match<$n_matches; $k_match++){
				$m = $matches[$k_match];
				$idMatch = htmlentities($m->id, ENT_QUOTES, 'UTF-8');
				$subtotal = (float) htmlentities($m->importe, ENT_QUOTES, 'UTF-8');
				$total = htmlentities($subtotal*( 1 + ($ivaFactura/100)), ENT_QUOTES, 'UTF-8');
				$cantidadIVA = htmlentities($total-$subtotal, ENT_QUOTES, 'UTF-8');

				$query_relacional_factura_cotizacion = "INSERT INTO `concepto_factura_cotizacion` 
														(`idConceptoFactura`, `idFechaFactura`, 
															`total`, `subtotal`, `cantidadIVA`)
													VALUES
														(".$id_concepto.", ".$idMatch.", 
															".$total.", ".$subtotal.", ".$cantidadIVA.");
													";
				$this->db->query($query_relacional_factura_cotizacion);

				//Cambiar el estado de la fecha de factura a no pagado
				$query_estado_fecha_factura = "UPDATE fecha_factura SET idEstadoFactura = 24 WHERE id =".$idMatch;

				$this->db->query($query_estado_fecha_factura);
			}

			$this->db->query($query_concepto_factura_rel);
		}

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

	public function getConceptosCotizacion($idCotizacion){
		$idCotizacion = htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					con.`id` id,
					con.`descripcion` descripcion
				FROM
					`concepto_cotizacion` con
					INNER JOIN `cotizacion` co ON con.`idCotizacion` =  co.`id`
				WHERE
					co.`id` = ".$idCotizacion."
					AND con.`estadoActivo` = 1
				";

		echo json_encode($this->db->query($query)->result());				
	}

	public function getFechasFacturacion(){
		$idCliente = $this->input->post("idCliente");
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');

		$queryFechas = "select
							f.id idFechaFactura,
							f.referencia referenciaFecha,
							date_format(f.fecha, '%d/%m/%Y') fechaFactura,
							ifnull(cot.folio, 'Sin cotizacion asociada') folioCotizacion
						from
							fecha_factura f
							left join concepto_cotizacion con on con.id = f.idConceptoCotizacion
							left join cotizacion cot on cot.id = con.idCotizacion
						where
							cot.idCliente = ".$idCliente."
						";

		$result = $this->db->query($queryFechas)->result();
		echo json_encode($result);
	}

	public function getFechaFacturacion($idFechaFacturacion){
		$idFechaFacturacion = htmlentities($idFechaFacturacion, ENT_QUOTES, 'UTF-8');

		$queryFechas = "select
							f.id idFechaFactura,
							f.importe importeFecha,
							f.referencia referenciaFecha,
							date_format(f.fecha, '%d/%m/%Y') fechaFactura,
							f.nota notaFecha,
							ifnull(cot.folio, 'Sin cotizacion asociada') folioCotizacion,
							ifnull(cas.clave, 'Sin servicio definido') claveServicio
						from
							fecha_factura f
							left join concepto_cotizacion con on con.id = f.idConceptoCotizacion
							left join cotizacion cot on cot.id = con.idCotizacion
							left join catservicio cas on cas.id = con.idClasificacion_servicio
						where
							f.id = ".$idFechaFacturacion."
						";

		$result = $this->db->query($queryFechas)->row();
		echo json_encode($result);		
	}

}