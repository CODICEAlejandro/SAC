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

		$appendQuery = "";
		$fechaFacturaDesde = htmlentities($fechaFacturaDesde, ENT_QUOTES, 'UTF-8');
		$fechaFacturaHasta = htmlentities($fechaFacturaHasta, ENT_QUOTES, 'UTF-8');
		$fechaPagoDesde = htmlentities($fechaPagoDesde, ENT_QUOTES, 'UTF-8');
		$fechaPagoHasta = htmlentities($fechaPagoHasta, ENT_QUOTES, 'UTF-8');

		// Obtener los todos los conceptos de las cotizaciones
		$query1 = "select
					ct.id id,
					ct.referencia referencia,
					ct.descripcion descripcion,
					ct.importe importe,
					ct.nota nota,
					ct.idEstadoFactura idEstadoFactura,
					ct.idTipoConcepto idTipoConcepto,
					ct.monto montoConceptoCotizacion,
					ct.total totalConceptoCotizacion,
					ct.folioFactura folio,
					IFNULL(c.inicioProyecto, 'NO DISPONIBLE') inicioProyecto,
					IFNULL(c.finProyecto, 'NO DISPONIBLE') finProyecto,
					IFNULL(c.fechaVenta, 'NO DISPONIBLE') fechaVenta,
					IFNULL(c.fechaJuntaArranque, 'NO DISPONIBLE') fechaJuntaArranque,
					IFNULL(c.contrato, 'NO DISPONIBLE') contrato,
					IFNULL(c.titulo, 'NO DISPONIBLE') tituloCotizacion,
					IFNULL(dc.cliente, 'NO DISPONIBLE') cliente,
					IFNULL(dc.razonSocial, 'NO DISPONIBLE') razonSocial,
					IFNULL(ce.nombre, 'NO DISPONIBLE') cerrador,
					IFNULL(ac.nombre, 'NO DISPONIBLE') responsable,
					IFNULL(r.nombre, 'NO DISPONIBLE') accountManager
				from
					concepto_cotizacion ct
					left join cotizacion c on c.id = ct.idCotizacion
					left join (select
									d.id id,
									d.razonSocial razonSocial,
									cl.nombre cliente,
									cl.id idCliente
								from
									direccionfiscal d
									inner join catcliente cl on cl.id = d.idPadre
					) dc on dc.id = c.idRazonSocial
					left join catusuario ce on ce.id = c.idCerrador
					left join catusuario ac on ac.id = c.accountManager
					left join catusuario r on r.id = c.idResponsable
				where
					ct.estadoActivo = 1
				";

		if($idEstadoFactura != -1) $appendQuery .= " AND ct.`idEstadoFactura` = ".$idEstadoFactura;
		if($folioFactura != "none") $appendQuery .= " AND ct.`folioFactura` = '".$folioFactura."'";
		if($idCliente != -1) $appendQuery .= " AND dc.`idCliente` = ".$idCliente;
		if($idRazonSocial != -1) $appendQuery .= " AND dc.`id` = ".$idRazonSocial;
		if($idCotizacion != -1) $appendQuery .= " AND c.`id` = ".$idCotizacion;

		$query1 .= $appendQuery;
		$conceptos_cotizacion = $this->db->query($query1)->result();
		$result_array = array();

		//Recorrer cada concepto en cotización y asociar con conceptos en facturación
		foreach($conceptos_cotizacion as $concepto){
			//Concepto hace referencia al concepto de la cotización

			//Obtener:
			//-concepto de factura
			//	-factura
			//	-estado de factura
			//	-tipo de concepto
			//	-impuestos de concepto de factura
			//relacionados con el concepto de la cotización
			$query2 = "select
						cf.descripcion estadoFactura,
						cc.descripcion tipoConcepto,
						f.folio folio,
						f.fechaPago fechaPago,
						f.moneda moneda,
						f.fechaFactura fechaFactura,
						f.ordenCompra ordenCompra,
						f.fechaCancelacion fechaCancelacion,
						i.tasa tasa,
						fc.cantidadIVA cantidadIVA,
						fc.subtotal subtotal,
						fc.total total,
						c.id idConceptoFactura
					from
						concepto_factura_cotizacion fc
						inner join concepto_factura_rel cr 
							on (
									cr.idConcepto = fc.idConceptoFactura 
									and fc.idConceptoCotizacion = ".($concepto->id)."
							)
						inner join concepto c on c.id = cr.idConcepto
						inner join factura f on f.id = cr.idFactura
						inner join catestadofactura cf on cf.id = ".($concepto->idEstadoFactura)."
						inner join cattipoconcepto cc on cc.id = ".($concepto->idTipoConcepto)."
						inner join impuesto i on i.idConcepto = c.id
					where
						1 = 1
				";

			$appendQuery = "";

			if($fechaFacturaDesde != "none") 
				$appendQuery .= " AND f.`fechaFactura` BETWEEN '".$fechaFacturaDesde."' AND '".$fechaFacturaHasta."'";
			if($fechaPagoDesde != "none") 
				$appendQuery .= " AND f.`fechaPago` BETWEEN '".$fechaPagoDesde."' AND '".$fechaPagoHasta."'";

			$query2 .= $appendQuery;
			$conceptosFactura = $this->db->query($query2)->result();

			if(count($conceptosFactura)>0){
				//Vaciar resultados homogéneos de facturación en concepto de cotización correspondiente
				$conceptoHomogeneo = $conceptosFactura[0];
				$concepto->estadoFactura = $conceptoHomogeneo->estadoFactura;
				$concepto->tipoConcepto = $conceptoHomogeneo->tipoConcepto;
				$concepto->folio = $conceptoHomogeneo->folio;
				$concepto->fechaPago = $conceptoHomogeneo->fechaPago;
				$concepto->moneda = $conceptoHomogeneo->moneda;
				$concepto->fechaFactura = $conceptoHomogeneo->fechaFactura;
				$concepto->ordenCompra = $conceptoHomogeneo->ordenCompra;
				$concepto->fechaCancelacion = $conceptoHomogeneo->fechaCancelacion;
				$concepto->tasa = $conceptoHomogeneo->tasa;
				$concepto->idConceptoFactura = "";
				$concepto->cantidadIVA = 0;
				$concepto->subtotal = 0;
				$concepto->total = 0;

				if($concepto->idEstadoFactura == 22){
					//Cancelado
					$concepto->estadoFactura = "CANCELADA";
					$concepto->subtotal = $concepto->importe;
					$concepto->total = ($concepto->subtotal)*(1.16);
					$concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);
				}if($concepto->idEstadoFactura == 24){
					//No pagado
					$concepto->estadoFactura = "NO PAGADO";
					$concepto->subtotal = $concepto->montoConceptoCotizacion;
					$concepto->total = $concepto->totalConceptoCotizacion;
					$concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);
				}else{
					//Calcular la suma de los totales y subtotales de todos los conceptos
					//de la factura asociados con el mismo de la cotización
					foreach($conceptosFactura as $concepto_factura){
						$concepto->cantidadIVA += ($concepto_factura->cantidadIVA);
						$concepto->subtotal += ($concepto_factura->subtotal);
						$concepto->total += ($concepto_factura->total);

						//Concatena los conceptos asociados de la factura
						$concepto->idConceptoFactura .= ($concepto_factura->idConceptoFactura).", ";
					}
				}
			}else{
				$concepto->tipoConcepto = 'NO DISPONIBLE';
				$concepto->folio = 'NO DISPONIBLE';
				$concepto->fechaPago = 'NO DISPONIBLE';
				$concepto->moneda = 'NO DISPONIBLE';
				$concepto->fechaFactura = 'NO DISPONIBLE';
				$concepto->ordenCompra = 'NO DISPONIBLE';
				$concepto->fechaCancelacion = 'NO DISPONIBLE';
				$concepto->tasa = 'NO DISPONIBLE';
				$concepto->idConceptoFactura = "NO DISPONIBLE";

				if($concepto->idEstadoFactura == 22){
					//Cancelado
					$concepto->estadoFactura = "CANCELADA";
					$concepto->subtotal = $concepto->montoConceptoCotizacion;
					$concepto->total = $concepto->totalConceptoCotizacion;
					$concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);
				}else if($concepto->idEstadoFactura == 23){
					//Por facturar
					$concepto->estadoFactura = "POR FACTURAR";
					$concepto->subtotal = $concepto->montoConceptoCotizacion;
					$concepto->total = $concepto->totalConceptoCotizacion;
					$concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);
				}else{
					$concepto->estadoFactura = "NO DISPONIBLE";
					$concepto->cantidadIVA = 0;
					$concepto->subtotal = 0;
					$concepto->total = 0;
				}
			}

			array_push($result_array, $concepto);
		}

		$cotizacionesResultantes = array();
		$numeroConceptosFacturados = 0;
		$numeroConceptosSinFacturar = 0;

		$importeFacturadoPesos = 0;
		$importeFacturadoDolares = 0;
		$importeNoFacturadoPesos = 0;
		$importeNoFacturadoDolares = 0;

		//Almacenar datos para su posible exportación en excel
		$this->session->set_userdata("last_query_result", $result_array);

		$data['mainData'] = $result_array;
		
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
		if($folioFactura != "none") $appendQuery .= " AND conCot.`folioFactura` = '".$folioFactura."'";
		if($idCliente != -1) $appendQuery .= " AND cli.`id` = ".$idCliente;
		if($idRazonSocial != -1) $appendQuery .= " AND dirF.`id` = ".$idRazonSocial;
		if($idCotizacion != -1) $appendQuery .= " AND cot.`id` = ".$idCotizacion;

		if($fechaFacturaDesde != "none") 
			$appendQuery .= " AND fact.`fechaFactura` BETWEEN '".$fechaFacturaDesde."' AND '".$fechaFacturaHasta."'";
		if($fechaPagoDesde != "none") 
			$appendQuery .= " AND fact.`fechaPago` BETWEEN '".$fechaPagoDesde."' AND '".$fechaPagoHasta."'";

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

		$xls->setCellValue("Estatus"); $xls->nextCol();
		$xls->setCellValue("Folio"); $xls->nextCol();
		$xls->setCellValue("Total"); $xls->nextCol();
		$xls->setCellValue("Fecha de pago"); $xls->nextCol();
		$xls->setCellValue("Cliente"); $xls->nextCol();
		$xls->setCellValue("ID Concepto Cotizacion"); $xls->nextCol();
		$xls->setCellValue("ID Concepto Factura"); $xls->nextCol();
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

			$xls->setCellValue($row->estadoFactura); $xls->nextCol();
			$xls->setCellValue($row->folio); $xls->nextCol();
			$xls->setCellValue($row->total); $xls->nextCol();
			$xls->setCellValue($row->fechaPago); $xls->nextCol();
			$xls->setCellValue($row->cliente); $xls->nextCol();
			$xls->setCellValue($row->id); $xls->nextCol();
			$xls->setCellValue($row->idConceptoFactura); $xls->nextCol();
			$xls->setCellValue($row->subtotal); $xls->nextCol();
			$xls->setCellValue($row->moneda); $xls->nextCol();
			$xls->setCellValue($row->fechaFactura); $xls->nextCol();
			$xls->setCellValue($row->ordenCompra); $xls->nextCol();
			$xls->setCellValue($row->tipoConcepto); $xls->nextCol();
			$xls->setCellValue($row->referencia); $xls->nextCol();
			$xls->setCellValue($row->descripcion); $xls->nextCol();
			$xls->setCellValue($row->tituloCotizacion); $xls->nextCol();
			$xls->setCellValue($row->inicioProyecto); $xls->nextCol();
			$xls->setCellValue($row->finProyecto); $xls->nextCol();
			$xls->setCellValue($row->razonSocial); $xls->nextCol();
			$xls->setCellValue($row->fechaVenta); $xls->nextCol();
			$xls->setCellValue($row->fechaJuntaArranque); $xls->nextCol();
			$xls->setCellValue($row->cerrador); $xls->nextCol();
			$xls->setCellValue($row->responsable); $xls->nextCol();
			$xls->setCellValue($row->accountManager); $xls->nextCol();
			$xls->setCellValue($row->tasa); $xls->nextCol();
			$xls->setCellValue($row->cantidadIVA); $xls->nextCol();
			$xls->setCellValue($row->importe); $xls->nextCol();
			$xls->setCellValue($row->fechaCancelacion); $xls->nextCol();
			$xls->setCellValue($row->contrato); $xls->nextCol();
			$xls->setCellValue($row->nota); $xls->nextCol();
		}

		$xls->autosizeColumns();
		$xls->out("Master_CODICE.xls");
	}
}
