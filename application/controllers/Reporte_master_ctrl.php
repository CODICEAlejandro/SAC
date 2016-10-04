<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_master_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Cliente");
		$this->load->model("DireccionFiscal");
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
								$fechaCancelacionDesde = "none",
								$fechaCancelacionHasta = "none",
								$idEstadoFactura = -1
							){
		$query = "
				SELECT
					edoF.`descripcion` estadoFactura,
					fact.`folio` folio,
					(con.`importe` * (1 + fact.`iva`)) total,
					DATE_FORMAT(fact.`fechaPago`, '%d/%m/%Y') fechaPago,
					cli.`nombre` cliente,
					con.`id` id,
					con.`importe` subtotal,
					fact.`moneda` moneda,
					fact.`ordenCompra` ordenCompra,
					tiCon.`descripcion` tipoConcepto,
					conCot.`referencia` referencia,
					con.`descripcion` descripcion,
					DATE_FORMAT(cot.`inicioProyecto`, '%d/%m/%Y') fechaInicio,
					DATE_FORMAT(cot.`finProyecto`, '%d/%m/%Y') fechaFin,
					dirF.`razonSocial` razonSocial,
					DATE_FORMAT(cot.`fechaVenta`, '%d/%m/%Y') fechaVenta,
					DATE_FORMAT(cot.`fechaJuntaArranque`, '%d/%m/%Y') fechaJuntaArranque,
					catCerrador.`nombre` cerrador,
					catResponsable.`nombre` responsable,
					fact.`iva` iva,
					(con.`importe` * fact.`iva`) montoIVA,
					con.`nota` nota,
					cot.`titulo` tituloCotizacion,
					catAccountManager.`nombre` accountManager,
					DATE_FORMAT(fact.`fechaCancelacion`, '%d/%m/%Y') fechaCancelacion,
					DATE_FORMAT(fact.`fechaFactura`, '%d/%m/%Y') fechaFactura,
					IF(cot.`contrato`=1, 'Sí', 'No') contrato,
					fact.`importeEfectivo` importeEfectivo
				FROM
					`cotizacion` cot
					INNER JOIN `catusuario` catCerrador ON catCerrador.`id` = cot.`idCerrador`
					INNER JOIN `catusuario` catResponsable ON catResponsable.`id` = cot.`idResponsable`
					INNER JOIN `catusuario` catAccountManager ON catAccountManager.`id` = cot.`accountManager`
					INNER JOIN `concepto_cotizacion` conCot ON conCot.`idCotizacion` = cot.`id`
					INNER JOIN `concepto` con ON con.`idConcepto_cotizacion` = conCot.`id`
					INNER JOIN `cattipoconcepto` tiCon ON tiCon.`id` = con.`idTipoConcepto`
					INNER JOIN `direccionFiscal` dirF ON dirF.`id` = cot.`idRazonSocial`
					INNER JOIN `cotizacion_factura_rel` cotfactrel ON cotfactrel.`idCotizacion` = cot.`id`
					INNER JOIN `concepto_factura_rel` confactrel ON confactrel.`idConcepto` = con.`id`
					INNER JOIN `factura` fact ON (fact.`id` = confactrel.`idFactura` AND fact.`id` = cotfactrel.`idFactura`)
					INNER JOIN `catestadofactura` edoF ON edoF.`id` = fact.`idEstadoFactura`
					INNER JOIN `catcliente` cli ON dirF.`idPadre` = cli.`id`
				";

		$query = $query.($this->getWHERE(
											$idCliente, 
											$idRazonSocial, 
											$idCotizacion,
											$fechaFacturaDesde,
											$fechaFacturaHasta,
											$fechaPagoDesde,
											$fechaPagoHasta,
											$fechaCancelacionDesde,
											$fechaCancelacionHasta,
											$idEstadoFactura
										)
						);

		return $this->db->query($query)->result();
	}

	public function getWHERE(
								$idCliente = -1, 
								$idRazonSocial = -1, 
								$idCotizacion = -1,
								$fechaFacturaDesde = "none",
								$fechaFacturaHasta = "none",
								$fechaPagoDesde = "none",
								$fechaPagoHasta = "none",
								$fechaCancelacionDesde = "none",
								$fechaCancelacionHasta = "none",
								$idEstadoFactura = -1
							){

		$appendQuery = " WHERE 1=1 ";
		$idCliente = (int) htmlentities($idCliente, ENT_QUOTES, 'UTF-8');
		$idRazonSocial = (int) htmlentities($idRazonSocial, ENT_QUOTES, 'UTF-8');
		$idCotizacion = (int) htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');

		$fechaFacturaDesde = htmlentities($fechaFacturaDesde, ENT_QUOTES, 'UTF-8');
		$fechaFacturaHasta = htmlentities($fechaFacturaHasta, ENT_QUOTES, 'UTF-8');
		$fechaPagoDesde = htmlentities($fechaPagoDesde, ENT_QUOTES, 'UTF-8');
		$fechaPagoHasta = htmlentities($fechaPagoHasta, ENT_QUOTES, 'UTF-8');
		$fechaCancelacionDesde = htmlentities($fechaCancelacionDesde, ENT_QUOTES, 'UTF-8');
		$fechaCancelacionHasta = htmlentities($fechaCancelacionHasta, ENT_QUOTES, 'UTF-8');

		$idEstadoFactura = htmlentities($idEstadoFactura, ENT_QUOTES, 'UTF-8');

		if($idCliente != -1) $appendQuery .= " AND cli.`id` = ".$idCliente;
		if($idRazonSocial != -1) $appendQuery .= " AND dirF.`id` = ".$idRazonSocial;
		if($idCotizacion != -1) $appendQuery .= " AND cot.`id` = ".$idCotizacion;
		if($idEstadoFactura != -1) $appendQuery .= " AND fact.`idEstadoFactura` = ".$idEstadoFactura;

		if($fechaFacturaDesde != "none") 
			$appendQuery .= " AND fact.`fechaFactura` BETWEEN '".$fechaFacturaDesde."' AND '".$fechaFacturaHasta."'";
		if($fechaPagoDesde != "none") 
			$appendQuery .= " AND fact.`fechaPago` BETWEEN '".$fechaPagoDesde."' AND '".$fechaPagoHasta."'";
		if($fechaCancelacionDesde != "none") 
			$appendQuery .= " AND fact.`fechaCancelacion` BETWEEN '".$fechaCancelacionDesde."' AND '".$fechaCancelacionHasta."'";

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

		$fechaCancelacionDesde = $this->input->post("cancelacionDesde");
		$fechaCancelacionHasta = $this->input->post("cancelacionHasta");

		$idEstadoFactura = $this->input->post("idEstadoFactura");

		echo json_encode($this->getContent(
											$idCliente, 
											$idRazonSocial, 
											$idCotizacion,
											$fechaFacturaDesde,
											$fechaFacturaHasta,
											$fechaPagoDesde,
											$fechaPagoHasta,
											$fechaCancelacionDesde,
											$fechaCancelacionHasta,
											$idEstadoFactura
										)
		);
	}

	public function getRazonesSociales(){
		$idCliente = $this->input->post("idCliente");

		echo json_encode($this->DireccionFiscal->traerAsociadas($idCliente));
	}

	public function getCotizaciones(){
		$idRazonSocial = $this->input->post("idRazonSocial");

		echo json_encode($this->DireccionFiscal->traerCotizaciones($idRazonSocial));
	}

}
