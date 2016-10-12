<?php

defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','-1');
ini_set('max_execution_time', 30000);
set_time_limit(30000);

class Reporte_master_ctrl extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Cliente");
		$this->load->model("DireccionFiscal");
		$this->load->library("session");
	}

	public function index(){
		$data['menu'] = $this->load->view("Menu_principal", null, true);
		$data['clientes'] = $this->Cliente->traerTodo();
		$data['estadosFactura'] = $this->db->get("catestadofactura")->result();

		$this->load->view("Reporte_master_vw", $data);
	}

	public function getContent(
								$idCliente = -1, 
								$idRazonSocial = -1, 
								$idCotizacion = -1,
								$fechaFacturaDesde = "none",
								$fechaFacturaHasta = "none",
								$fechaPagoDesde = "none",
								$fechaPagoHasta = "none",
								$idEstadoFactura = -1,
								$folioFactura = "none"
							){
		$query = "
				SELECT
					conCot.`idCotizacion` idCotizacion,
					IFNULL(conCot.`id`, 'NO DISPONIBLE') idConceptoCotizacion,
					IFNULL(cli.`nombre`, 'NO DISPONIBLE') cliente,
					IFNULL(conCot.`referencia`, 'NO DISPONIBLE') referencia,
					IFNULL(DATE_FORMAT(cot.`inicioProyecto`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaInicio,
					IFNULL(DATE_FORMAT(cot.`finProyecto`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaFin,
					IFNULL(dirF.`razonSocial`, 'NO DISPONIBLE') razonSocial,
					IFNULL(DATE_FORMAT(cot.`fechaVenta`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaVenta,
					IFNULL(DATE_FORMAT(cot.`fechaJuntaArranque`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaJuntaArranque,
					IFNULL(catCerrador.`nombre`, 'NO DISPONIBLE') cerrador,
					IFNULL(catResponsable.`nombre`, 'NO DISPONIBLE') responsable,
					IFNULL(cot.`titulo`, 'NO DISPONIBLE') tituloCotizacion,
					IFNULL(catAccountManager.`nombre`, 'NO DISPONIBLE') accountManager,
					IF(cot.`contrato`=1, 'Sí', 'No') contrato,
					IFNULL(conCot.`monto`, 0) montoConceptoCotizacion,

					IFNULL(con.`idConcepto_cotizacion`, 'NO_BILL') estadoConcepto,
					IFNULL(con.`importe` * (1 + fact.`iva`), 'NO DISPONIBLE') total,
					IFNULL(con.`id`, 'NO DISPONIBLE') id,
					IFNULL(con.`importe`, 'NO DISPONIBLE') subtotal,
					IFNULL((con.`importe` * fact.`iva`), 'NO DISPONIBLE') montoIVA,
					IFNULL(conCot.`nota`, '') nota,
					IFNULL(con.`descripcion`, 'NO DISPONIBLE') descripcion,
					IFNULL(tiCon.`descripcion`, 'NO DISPONIBLE') tipoConcepto,
					IFNULL(fact.`folio`, 'NO DISPONIBLE') folio,
					IFNULL(DATE_FORMAT(fact.`fechaPago`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaPago,
					IFNULL(fact.`moneda`, 'NO DISPONIBLE') moneda,
					IFNULL(fact.`ordenCompra`, 'NO DISPONIBLE') ordenCompra,
					IFNULL(imp.`tasa`, 'NO DISPONIBLE') iva,
					IFNULL(DATE_FORMAT(fact.`fechaCancelacion`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaCancelacion,
					IFNULL(DATE_FORMAT(fact.`fechaFactura`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaFactura,
					IFNULL(fact.`importeEfectivo`, 'NO DISPONIBLE') importeEfectivo,
					IFNULL(edoF.`id`, 'NO DISPONIBLE') estadoFactura,
					IFNULL(edoF.`descripcion`, 'NO DISPONIBLE') estadoFacturaDescripcion

				FROM
					`concepto_cotizacion` conCot
					LEFT JOIN `cotizacion` cot ON conCot.`idCotizacion` = cot.`id`
					LEFT JOIN `catusuario` catCerrador ON catCerrador.`id` = cot.`idCerrador`
					LEFT JOIN `catusuario` catResponsable ON catResponsable.`id` = cot.`idResponsable`
					LEFT JOIN `catusuario` catAccountManager ON catAccountManager.`id` = cot.`accountManager`
					LEFT JOIN `direccionfiscal` dirF ON dirF.`id` = cot.`idRazonSocial`
					LEFT JOIN `catcliente` cli ON dirF.`idPadre` = cli.`id`

					LEFT JOIN `concepto_factura_cotizacion` relFC ON relFC.`idConceptoCotizacion` = conCot.`id`
					LEFT JOIN `concepto` con ON relFC.`idConceptoFactura` = con.`id`
					LEFT JOIN `cattipoconcepto` tiCon ON tiCon.`id` = conCot.`idTipoConcepto`
					LEFT JOIN `factura` fact ON fact.`folio` = conCot.`folioFactura`
					LEFT JOIN `catestadofactura` edoF ON edoF.`id` = conCot.`idEstadoFactura`
					LEFT JOIN `impuesto` imp ON imp.`idConcepto` = con.`id`					
				";

		$query = $query.($this->getWHERE(
											$idCliente, 
											$idRazonSocial, 
											$idCotizacion,
											$fechaFacturaDesde,
											$fechaFacturaHasta,
											$fechaPagoDesde,
											$fechaPagoHasta,
											$idEstadoFactura,
											$folioFactura
										)
						);


		$conceptos_cotizacion = $this->db->query($query)->result();

		$cotizacionesResultantes = array();
		$numeroConceptosFacturados = 0;
		$numeroConceptosSinFacturar = 0;

		$importeFacturadoPesos = 0;
		$importeFacturadoDolares = 0;
		$importeNoFacturadoPesos = 0;
		$importeNoFacturadoDolares = 0;

		foreach($conceptos_cotizacion as $c){
			if(! in_array($c->idCotizacion, $cotizacionesResultantes) )
				array_push($cotizacionesResultantes, $c->idCotizacion);

			if(trim($c->folio) == "NO DISPONIBLE"){
				$numeroConceptosSinFacturar++;

				if(trim($c->moneda) == "MXN"){
					$importeNoFacturadoPesos += (float) $c->subtotal;
				}else if(trim($c->moneda) == "USD"){
					$importeNoFacturadoDolares += (float) $c->subtotal;					
				}
			}else{ 
				$numeroConceptosFacturados++;

				if(trim($c->moneda) == "USD"){
					$importeFacturadoDolares += (float) $c->montoConceptoCotizacion;
				}else{
					//Siempre cae en esta condición porque aún no se tiene información de la moneda
					//La cotización siempre se emite en pesos
					$importeFacturadoPesos += (float) $c->montoConceptoCotizacion;					
				}
			}
		}

		$this->session->set_userdata("last_query_result", $conceptos_cotizacion);
		$this->session->set_userdata("last_query", $query);

		$data['mainData'] = $conceptos_cotizacion;
		
		$data['analytics'] = array();
		$data['analytics']['numeroCotizaciones'] = count($cotizacionesResultantes);
		$data['analytics']['numeroConceptosFacturados'] = $numeroConceptosFacturados;
		$data['analytics']['numeroConceptosSinFacturar'] = $numeroConceptosSinFacturar;

		$data['analytics']['importeNoFacturadoPesos'] = $importeNoFacturadoPesos;
		$data['analytics']['importeNoFacturadoDolares'] = $importeNoFacturadoDolares;
		$data['analytics']['importeFacturadoPesos'] = $importeFacturadoPesos;
		$data['analytics']['importeFacturadoDolares'] = $importeFacturadoDolares;

		return $data;
	}

	public function getWHERE(
								$idCliente = -1, 
								$idRazonSocial = -1, 
								$idCotizacion = -1,
								$fechaFacturaDesde = "none",
								$fechaFacturaHasta = "none",
								$fechaPagoDesde = "none",
								$fechaPagoHasta = "none",
								$idEstadoFactura = -1,
								$folioFactura = "none"
							){

		$appendQuery = " WHERE 1=1 ";
		$idCliente = (int) htmlentities($idCliente, ENT_QUOTES, 'UTF-8');
		$idRazonSocial = (int) htmlentities($idRazonSocial, ENT_QUOTES, 'UTF-8');
		$idCotizacion = (int) htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');

		$fechaFacturaDesde = htmlentities($fechaFacturaDesde, ENT_QUOTES, 'UTF-8');
		$fechaFacturaHasta = htmlentities($fechaFacturaHasta, ENT_QUOTES, 'UTF-8');
		$fechaPagoDesde = htmlentities($fechaPagoDesde, ENT_QUOTES, 'UTF-8');
		$fechaPagoHasta = htmlentities($fechaPagoHasta, ENT_QUOTES, 'UTF-8');

		$idEstadoFactura = htmlentities($idEstadoFactura, ENT_QUOTES, 'UTF-8');
		if($idEstadoFactura != -1) $appendQuery .= " AND conCot.`idEstadoFactura` = ".$idEstadoFactura;

		if($fechaFacturaDesde != "none") 
			$appendQuery .= " AND fact.`fechaFactura` BETWEEN '".$fechaFacturaDesde."' AND '".$fechaFacturaHasta."'";
		if($fechaPagoDesde != "none") 
			$appendQuery .= " AND fact.`fechaPago` BETWEEN '".$fechaPagoDesde."' AND '".$fechaPagoHasta."'";

		if($idCliente != -1) $appendQuery .= " AND cli.`id` = ".$idCliente;
		if($idRazonSocial != -1) $appendQuery .= " AND dirF.`id` = ".$idRazonSocial;
		if($idCotizacion != -1) $appendQuery .= " AND cot.`id` = ".$idCotizacion;

		if($folioFactura != "none") $appendQuery .= " AND conCot.`folioFactura` = '".$folioFactura."'";

		return $appendQuery;
	}

	public function getContentAJAX(){
		$idCliente = $this->input->post("idCliente");
		$idRazonSocial = $this->input->post("idRazonSocial"); 
		$idCotizacion = $this->input->post("idCotizacion");

		$fechaFacturaDesde = $this->input->post("facturaDesde");
		$fechaFacturaHasta = $this->input->post("facturaHasta");

		$fechaPagoDesde = $this->input->post("pagoDesde");
		$fechaPagoHasta = $this->input->post("pagoHasta");

		$idEstadoFactura = $this->input->post("idEstadoFactura");

		$data = $this->getContent(
											$idCliente, 
											$idRazonSocial, 
											$idCotizacion,
											$fechaFacturaDesde,
											$fechaFacturaHasta,
											$fechaPagoDesde,
											$fechaPagoHasta,
											$idEstadoFactura
										);

		echo json_encode($data);
	}

	public function getRazonesSociales(){
		$idCliente = $this->input->post("idCliente");

		echo json_encode($this->DireccionFiscal->traerAsociadas($idCliente));
	}

	public function getCotizaciones(){
		$idRazonSocial = $this->input->post("idRazonSocial");

		echo json_encode($this->DireccionFiscal->traerCotizaciones($idRazonSocial));
	}

	public function getABillAJAX(){
		$folioFactura = $this->input->post("folio");

		$data = $this->getContent(
											-1, 
											-1, 
											-1,
											"none",
											"none",
											"none",
											"none",
											-1,
											$folioFactura
										);

		echo json_encode($data);		
	}

	public function saveNote(){
		$idConceptoCotizacion = $this->input->post("idConceptoCotizacion");
		$nota = $this->input->post("nota");

		$idConceptoCotizacion = htmlentities($idConceptoCotizacion, ENT_QUOTES, 'UTF-8');
		$nota = htmlentities($nota, ENT_QUOTES, 'UTF-8');

		$data = array(
					"nota" => $nota
				);

		$this->db->where("id = ", $idConceptoCotizacion);
		return $this->db->update("concepto_cotizacion", $data);
	}

	public function saveEstadoFactura(){
		$idConceptoCotizacion = $this->input->post("idConceptoCotizacion");
		$idEstadoFactura = $this->input->post("idEstadoFactura");

		$idConceptoCotizacion = htmlentities($idConceptoCotizacion, ENT_QUOTES, 'UTF-8');
		$idEstadoFactura = htmlentities($idEstadoFactura, ENT_QUOTES, 'UTF-8');

		$data = array(
					"idEstadoFactura" => $idEstadoFactura
				);

		$this->db->where("id = ", $idConceptoCotizacion);
		return $this->db->update("concepto_cotizacion", $data);
	}

	public function getEstadosFactura(){
		echo json_encode($this->db->get("catestadofactura")->result());
	}

	public function setExcel(){
		$this->load->model("XLSSheetDriver");
		$xls = new $this->XLSSheetDriver();
		$xls->setTitle("Master - CODICE");

		$data = $this->session->userdata("last_query_result");
		// header("Content-type: application/vnd.ms-excel; name='excel'");
		// header("Content-Disposition: filename=ficheroExcel.xls");
		// header("Pragma: no-cache");
		// header("Expires: 0");

		$xls->setCellValue("Estatus"); $xls->nextCol();
		$xls->setCellValue("Folio"); $xls->nextCol();
		$xls->setCellValue("Total"); $xls->nextCol();
		$xls->setCellValue("Fecha de pago"); $xls->nextCol();
		$xls->setCellValue("Cliente"); $xls->nextCol();
		$xls->setCellValue("ID"); $xls->nextCol();
		$xls->setCellValue("Subtotal"); $xls->nextCol();
		$xls->setCellValue("Moneda"); $xls->nextCol();
		$xls->setCellValue("Fecha de factura"); $xls->nextCol();
		$xls->setCellValue("Orden de compra"); $xls->nextCol();
		$xls->setCellValue("Tipo de concepto"); $xls->nextCol();
		$xls->setCellValue("Referencia"); $xls->nextCol();
		$xls->setCellValue("Descripción"); $xls->nextCol();
		$xls->setCellValue("Proyecto"); $xls->nextCol();
		$xls->setCellValue("Inicio de proyecto"); $xls->nextCol();
		$xls->setCellValue("Fin de proyecto"); $xls->nextCol();
		$xls->setCellValue("Razón social"); $xls->nextCol();
		$xls->setCellValue("Fecha de venta"); $xls->nextCol();
		$xls->setCellValue("Junta de arranque"); $xls->nextCol();
		$xls->setCellValue("Cerrador"); $xls->nextCol();
		$xls->setCellValue("Responsable"); $xls->nextCol();
		$xls->setCellValue("Account Manager"); $xls->nextCol();
		$xls->setCellValue("IVA"); $xls->nextCol();
		$xls->setCellValue("Monto de IVA"); $xls->nextCol();
		$xls->setCellValue("Importe"); $xls->nextCol();
		$xls->setCellValue("Fecha de cancelación"); $xls->nextCol();
		$xls->setCellValue("Contrato"); $xls->nextCol();
		$xls->setCellValue("Nota");

		$xls->setCellBackground("FE9A2E", "A1:".$xls->getPosition());

		for($k=0, $n=count($data); $k<$n; $k++){
			$row = $data[$k];
			$xls->nextLine();

			$xls->setCellValue($row->estadoFacturaDescripcion); $xls->nextCol();
			$xls->setCellValue($row->folio); $xls->nextCol();
			$xls->setCellValue($row->total); $xls->nextCol();
			$xls->setCellValue($row->fechaPago); $xls->nextCol();
			$xls->setCellValue($row->cliente); $xls->nextCol();
			$xls->setCellValue($row->id); $xls->nextCol();
			$xls->setCellValue($row->subtotal); $xls->nextCol();
			$xls->setCellValue($row->moneda); $xls->nextCol();
			$xls->setCellValue($row->fechaFactura); $xls->nextCol();
			$xls->setCellValue($row->ordenCompra); $xls->nextCol();
			$xls->setCellValue($row->tipoConcepto); $xls->nextCol();
			$xls->setCellValue($row->referencia); $xls->nextCol();
			$xls->setCellValue($row->descripcion); $xls->nextCol();
			$xls->setCellValue($row->tituloCotizacion); $xls->nextCol();
			$xls->setCellValue($row->fechaInicio); $xls->nextCol();
			$xls->setCellValue($row->fechaFin); $xls->nextCol();
			$xls->setCellValue($row->razonSocial); $xls->nextCol();
			$xls->setCellValue($row->fechaVenta); $xls->nextCol();
			$xls->setCellValue($row->fechaJuntaArranque); $xls->nextCol();
			$xls->setCellValue($row->cerrador); $xls->nextCol();
			$xls->setCellValue($row->responsable); $xls->nextCol();
			$xls->setCellValue($row->accountManager); $xls->nextCol();
			$xls->setCellValue($row->iva); $xls->nextCol();
			$xls->setCellValue($row->montoIVA); $xls->nextCol();
			$xls->setCellValue($row->importeEfectivo); $xls->nextCol();
			$xls->setCellValue($row->fechaCancelacion); $xls->nextCol();
			$xls->setCellValue($row->contrato); $xls->nextCol();
			$xls->setCellValue($row->nota); $xls->nextCol();
		}

		$xls->autosizeColumns();
		$xls->out("Master_CODICE.xls");
	}
}
