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
					IFNULL(conCot.`id`, 'NO DISPONIBLE') idConceptoCotizacion,
					IFNULL(cli.`nombre`, 'NO DISPONIBLE') cliente,
					IFNULL(conCot.`referencia`, 'NO DISPONIBLE') referencia,
					IFNULL(DATE_FORMAT(cot.`inicioProyecto`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaInicio,
					IFNULL(dirF.`razonSocial`, 'NO DISPONIBLE') razonSocial,
					IFNULL(DATE_FORMAT(cot.`fechaVenta`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaVenta,
					IFNULL(DATE_FORMAT(cot.`fechaJuntaArranque`, '%d/%m/%Y'), 'NO DISPONIBLE') fechaJuntaArranque,
					IFNULL(catCerrador.`nombre`, 'NO DISPONIBLE') cerrador,
					IFNULL(catResponsable.`nombre`, 'NO DISPONIBLE') responsable,
					IFNULL(cot.`titulo`, 'NO DISPONIBLE') tituloCotizacion,
					IFNULL(catAccountManager.`nombre`, 'NO DISPONIBLE') accountManager,
					IF(cot.`contrato`=1, 'Sí', 'No') contrato
				FROM
					`concepto_cotizacion` conCot
					LEFT JOIN `cotizacion` cot ON conCot.`idCotizacion` = cot.`id`
					LEFT JOIN `catusuario` catCerrador ON catCerrador.`id` = cot.`idCerrador`
					LEFT JOIN `catusuario` catResponsable ON catResponsable.`id` = cot.`idResponsable`
					LEFT JOIN `catusuario` catAccountManager ON catAccountManager.`id` = cot.`accountManager`
					LEFT JOIN `direccionfiscal` dirF ON dirF.`id` = cot.`idRazonSocial`
					LEFT JOIN `catcliente` cli ON dirF.`idPadre` = cli.`id`
				";

		$query = $query.($this->getWHERE(
											$idCliente, 
											$idRazonSocial, 
											$idCotizacion
										)
						);

		$conceptos_cotizacion = $this->db->query($query)->result();

		foreach($conceptos_cotizacion as $c){
			$queryRelacionFactura = "
					SELECT
						IFNULL(con.`importe` * (1 + fact.`iva`), 'NO DISPONIBLE') total,
						IFNULL(con.`id`, 'NO DISPONIBLE') id,
						IFNULL(con.`importe`, 'NO DISPONIBLE') subtotal,
						IFNULL((con.`importe` * fact.`iva`), 'NO DISPONIBLE') montoIVA,
						IFNULL(con.`nota`, '') nota,
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
						IFNULL(edoF.`descripcion`, 'NO DISPONIBLE') estadoFactura

					FROM
						`concepto_cotizacion` conCot
						LEFT JOIN `concepto` con ON con.`idConcepto_cotizacion` = conCot.`id`
						LEFT JOIN `cattipoconcepto` tiCon ON tiCon.`id` = conCot.`idTipoConcepto`
						LEFT JOIN `factura` fact ON fact.`folio` = conCot.`folioFactura`
						LEFT JOIN `catestadofactura` edoF ON edoF.`id` = conCot.`idEstadoFactura`
						LEFT JOIN `impuesto` imp ON imp.`idConcepto` = con.`id`
				";

			$queryRelacionFactura = $queryRelacionFactura.($this->getWHEREFactura(
												$c->idConceptoCotizacion,
												$fechaFacturaDesde,
												$fechaFacturaHasta,
												$fechaPagoDesde,
												$fechaPagoHasta,
												$fechaCancelacionDesde,
												$fechaCancelacionHasta,
												$idEstadoFactura
											)
						);

			$resultRelacionFactura = $this->db->query($queryRelacionFactura)->row();

			if(count($resultRelacionFactura) > 0){
				$c->total = $resultRelacionFactura->total ;
				$c->id = $resultRelacionFactura->id ;
				$c->subtotal = $resultRelacionFactura->subtotal ;
				$c->montoIVA = $resultRelacionFactura->montoIVA ;
				$c->nota = $resultRelacionFactura->nota ;
				$c->descripcion = $resultRelacionFactura->descripcion ;
				$c->tipoConcepto = $resultRelacionFactura->tipoConcepto ;
				$c->folio = $resultRelacionFactura->folio ;
				$c->fechaPago = $resultRelacionFactura->fechaPago ;
				$c->moneda = $resultRelacionFactura->moneda ;
				$c->ordenCompra = $resultRelacionFactura->ordenCompra ;
				$c->iva = $resultRelacionFactura->iva ;
				$c->fechaCancelacion = $resultRelacionFactura->fechaCancelacion ;
				$c->fechaFactura = $resultRelacionFactura->fechaFactura ;
				$c->importeEfectivo = $resultRelacionFactura->importeEfectivo ;
				$c->estadoFactura = $resultRelacionFactura->estadoFactura ;
			}
		}

		return $conceptos_cotizacion;
	}

	public function getWHEREFactura(
								$idConceptoCotizacion = -1,
								$fechaFacturaDesde = "none",
								$fechaFacturaHasta = "none",
								$fechaPagoDesde = "none",
								$fechaPagoHasta = "none",
								$fechaCancelacionDesde = "none",
								$fechaCancelacionHasta = "none",
								$idEstadoFactura = -1
							){
		$appendQuery = " WHERE 1=1 ";

		$idConceptoCotizacion = (int) htmlentities($idConceptoCotizacion, ENT_QUOTES, 'UTF-8');

		$fechaFacturaDesde = htmlentities($fechaFacturaDesde, ENT_QUOTES, 'UTF-8');
		$fechaFacturaHasta = htmlentities($fechaFacturaHasta, ENT_QUOTES, 'UTF-8');
		$fechaPagoDesde = htmlentities($fechaPagoDesde, ENT_QUOTES, 'UTF-8');
		$fechaPagoHasta = htmlentities($fechaPagoHasta, ENT_QUOTES, 'UTF-8');
		$fechaCancelacionDesde = htmlentities($fechaCancelacionDesde, ENT_QUOTES, 'UTF-8');
		$fechaCancelacionHasta = htmlentities($fechaCancelacionHasta, ENT_QUOTES, 'UTF-8');

		$idEstadoFactura = htmlentities($idEstadoFactura, ENT_QUOTES, 'UTF-8');
		if($idEstadoFactura != -1) $appendQuery .= " AND conCot.`idEstadoFactura` = ".$idEstadoFactura;
		if($idConceptoCotizacion != -1) $appendQuery .= " AND conCot.`id` = ".($idConceptoCotizacion);

		if($fechaFacturaDesde != "none") 
			$appendQuery .= " AND fact.`fechaFactura` BETWEEN '".$fechaFacturaDesde."' AND '".$fechaFacturaHasta."'";
		if($fechaPagoDesde != "none") 
			$appendQuery .= " AND fact.`fechaPago` BETWEEN '".$fechaPagoDesde."' AND '".$fechaPagoHasta."'";
		if($fechaCancelacionDesde != "none") 
			$appendQuery .= " AND fact.`fechaCancelacion` BETWEEN '".$fechaCancelacionDesde."' AND '".$fechaCancelacionHasta."'";

		return $appendQuery;		
	}

	public function getWHERE(
								$idCliente = -1, 
								$idRazonSocial = -1, 
								$idCotizacion = -1
							){

		$appendQuery = " WHERE 1=1 ";
		$idCliente = (int) htmlentities($idCliente, ENT_QUOTES, 'UTF-8');
		$idRazonSocial = (int) htmlentities($idRazonSocial, ENT_QUOTES, 'UTF-8');
		$idCotizacion = (int) htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');


		if($idCliente != -1) $appendQuery .= " AND cli.`id` = ".$idCliente;
		if($idRazonSocial != -1) $appendQuery .= " AND dirF.`id` = ".$idRazonSocial;
		if($idCotizacion != -1) $appendQuery .= " AND cot.`id` = ".$idCotizacion;

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
