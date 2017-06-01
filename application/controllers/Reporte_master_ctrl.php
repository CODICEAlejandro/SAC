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

		$cotizacionesResultantes = array();
		$numeroConceptosFacturados = 0;
		$numeroConceptosSinFacturar = 0;

		$importeFacturadoPesos = 0;
		$importeFacturadoDolares = 0;
		$importeNoFacturadoPesos = 0;
		$importeNoFacturadoDolares = 0;

		$subtotalFacturadoPesos = 0;
		$subtotalFacturadoDolares = 0;

		$subtotalNoFacturadoPesos = 0;
		$subtotalNoFacturadoDolares = 0;

		$ivaFacturadoPesos = 0;
		$ivaFacturadoDolares = 0;

		$ivaNoFacturadoPesos = 0;
		$ivaNoFacturadoDolares = 0;

		// Obtener los todos los conceptos de las cotizaciones
		$query1 = "select
					ff.id id,
					ff.referencia referencia,
					ff.nota nota,
					ff.importe montoFechaFactura,
					ff.idEstadoFactura idEstadoFactura,
					ff.importe subtotal,
					ff.fecha_final fechaPago,
					round((ff.importe * ((con_cot.iva/100)+1)),2) total,
					round((ff.importe - (ff.importe * ((con_cot.iva/100)+1))),2) cantidadIVA,
					if(ff.fecha_final_confirmada=1,'SÍ','NO') fechaConfirmada,
					IFNULL(ff.idBanco,'NO ASIGNADO') idBanco,
					con_cot.iva tasa,
					con_cot.descripcion descripcion,
					con_cot.total totalConceptoCotizacion,
					IFNULL(clas_serv.clave, 'SIN SERVICIO') servicio,
					tipo_con.descripcion clasificacion,
					c.inicioProyecto inicioProyecto,
					c.finProyecto finProyecto,
					c.fechaVenta fechaVenta,
					c.fechaJuntaArranque fechaJuntaArranque,
					c.titulo tituloCotizacion,
					c.folio folioCotizacion,
					cli.nombre cliente,
					edo_fac.descripcion estadoFactura,
					IF(c.contrato=1, 'SÍ', 'NO') contrato,
					IFNULL(ce.nombre, 'NO ASIGNADO') cerrador,
					IFNULL(ac.nombre, 'NO ASIGNADO') accountManager
				from
					fecha_factura ff
					inner join catestadofactura edo_fac on edo_fac.id = ff.idEstadoFactura
					left join concepto_cotizacion con_cot on con_cot.id = ff.idConceptoCotizacion
					left join catclasificacion_servicio clas_serv on con_cot.idClasificacion_servicio = clas_serv.id
					left join cotizacion c on c.id = con_cot.idCotizacion
					left join catusuario ce on ce.id = c.idCerrador
					left join catusuario ac on ac.id = c.accountManager
					left join catcliente cli on cli.id = c.idCliente
					left join cattipoconcepto tipo_con on tipo_con.id = con_cot.idTipoConcepto
				where
					con_cot.estadoActivo = 1				
				";

		if($idCliente != -1) $appendQuery .= " AND c.`idCliente` = ".$idCliente;
		if($idCotizacion != -1) $appendQuery .= " AND c.`id` = ".$idCotizacion;
		if($idEstadoFactura != -1) $appendQuery .= " AND ff.`idEstadoFactura` = ".$idEstadoFactura;
		if($fechaPagoDesde != "none") $appendQuery .= " AND ff.`fecha_final` BETWEEN '".$fechaPagoDesde."' AND '".$fechaPagoHasta."'";

		$query1 .= $appendQuery;
		$conceptos_cotizacion = $this->db->query($query1)->result();
		$result_array = array();

		//Recorrer cada fecha de facturación y asociar con conceptos en facturación
		foreach($conceptos_cotizacion as $concepto){
			//Concepto hace referencia al concepto de la cotización

			//Obtener:
			//  -concepto de factura
			//	-factura
			//	-estado de factura
			//	-tipo de concepto
			//	-impuestos de concepto de factura
			//relacionados con el concepto de la cotización
			$query2 = "select
						f.folio folio,
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
									and fc.idFechaFactura = ".($concepto->id)."
							)
						inner join concepto c on c.id = cr.idConcepto
						inner join factura f on f.id = cr.idFactura
						inner join impuesto i on i.idConcepto = c.id
					where
						1 = 1
				";

			$appendQuery = "";

			if($fechaFacturaDesde != "none") 
				$appendQuery .= " AND f.`fechaFactura` BETWEEN '".$fechaFacturaDesde."' AND '".$fechaFacturaHasta."'";
			if($folioFactura != "none") 
				$appendQuery .= " AND f.`folio` = '".$folioFactura."'";

			$query2 .= $appendQuery;
			$conceptosFactura = $this->db->query($query2)->result();

			if(count($conceptosFactura)>0){
				//Vaciar resultados homogéneos de facturación en concepto de cotización correspondiente
				$conceptoHomogeneo = $conceptosFactura[0];
				//$concepto->estadoFactura = $conceptoHomogeneo->estadoFactura;
				//$concepto->tipoConcepto = $conceptoHomogeneo->tipoConcepto;
				$concepto->folio = $conceptoHomogeneo->folio;
				//$concepto->fechaPago = $conceptoHomogeneo->fechaPago;
				$concepto->moneda = $conceptoHomogeneo->moneda;
				$concepto->fechaFactura = $conceptoHomogeneo->fechaFactura;
				$concepto->ordenCompra = $conceptoHomogeneo->ordenCompra;
				$concepto->fechaCancelacion = $conceptoHomogeneo->fechaCancelacion;
				$concepto->tasa = $conceptoHomogeneo->tasa;
				$concepto->idConceptoFactura = "";
				$concepto->cantidadIVA = $conceptoHomogeneo->cantidadIVA;
				$concepto->subtotal = $conceptoHomogeneo->subtotal;
				$concepto->total = $conceptoHomogeneo->total;

				if($concepto->idEstadoFactura == 22){
					//Cancelado
					//$concepto->estadoFactura = "CANCELADA";
				}else if($concepto->idEstadoFactura == 24){
					//No pagado
					//$concepto->estadoFactura = "NO PAGADO";
				}else{
					//$concepto->estadoFactura = "NO DEFINIDO";
				}

				if($conceptoHomogeneo->moneda == "MXN"){
					$ivaFacturadoPesos += $concepto->cantidadIVA;
					$subtotalFacturadoPesos += $concepto->subtotal;

					$importeFacturadoPesos += $concepto->total;
				}else if($conceptoHomogeneo->moneda == "USD"){
					$ivaFacturadoDolares += $concepto->cantidadIVA;
					$subtotalFacturadoDolares += $concepto->subtotal;

					$importeFacturadoDolares += $concepto->total;
				}else{
					$ivaFacturadoPesos += $concepto->cantidadIVA;
					$subtotalFacturadoPesos += $concepto->subtotal;

					$importeFacturadoPesos += $concepto->total;
				}

				$numeroConceptosFacturados++;
				//Se agrego aquí en vez de abajo, solo cuando entra en esta condición se debe mostrar
				array_push($result_array, $concepto);
			}else{
				$concepto->folio = 'NO DISPONIBLE';
				$concepto->moneda = 'NO DISPONIBLE';
				$concepto->fechaFactura = 'NO DISPONIBLE';
				$concepto->ordenCompra = 'NO DISPONIBLE';
				$concepto->fechaCancelacion = 'NO DISPONIBLE';
				$concepto->idConceptoFactura = "NO DISPONIBLE";

				if($concepto->idEstadoFactura == 22){
					//Cancelado
					//$concepto->estadoFactura = "CANCELADA";
					// $concepto->subtotal = $concepto->montoFechaFactura;
					// $concepto->total = $concepto->totalConceptoCotizacion;
					// $concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);

					$iva = $concepto->tasa;
					$iva = $iva/100;
					$iva = $iva+1;
					$concepto->total = ($concepto->subtotal)*($iva);
					$concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);

					array_push($result_array, $concepto);

					$ivaNoFacturadoPesos += $concepto->cantidadIVA;
					$subtotalNoFacturadoPesos += $concepto->subtotal;
					$importeNoFacturadoPesos += $concepto->total;
					$numeroConceptosSinFacturar++;

				}else if($concepto->idEstadoFactura == 23){
					//Por facturar
					//$concepto->estadoFactura = "POR FACTURAR";
					// $concepto->subtotal = $concepto->montoFechaFactura;
					// $concepto->total = $concepto->totalConceptoCotizacion;
					// $concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);
					$iva = $concepto->tasa;
					$iva = $iva/100;
					$iva = $iva+1;
					$concepto->total = ($concepto->subtotal)*($iva);
					$concepto->cantidadIVA = ($concepto->total) - ($concepto->subtotal);

					array_push($result_array, $concepto);

					$ivaNoFacturadoPesos += $concepto->cantidadIVA;
					$subtotalNoFacturadoPesos += $concepto->subtotal;
					$importeNoFacturadoPesos += $concepto->total;
					$numeroConceptosSinFacturar++;
				}else{
					//$concepto->estadoFactura = "NO DEFINIDO";
					$concepto->cantidadIVA = 0;
					$concepto->subtotal = 0;
					$concepto->total = 0;
				}

				//$importeNoFacturadoPesos += $concepto->total;
				//$numeroConceptosSinFacturar++;
			}

			// Se quito de aqui el array push, pues no se deben meter todos los conceptos, sino únicamente los que estén SI DISPONIBLES
			//array_push($result_array, $concepto);
		}

		//Almacenar datos para su posible exportación en excel
		$this->session->set_userdata("last_query_result", $result_array);

		$data['mainData'] = $result_array;
		
		$data['analytics'] = array();
		$data['analytics']['numeroCotizaciones'] = 0;
		$data['analytics']['numeroConceptosFacturados'] = $numeroConceptosFacturados;
		$data['analytics']['numeroConceptosSinFacturar'] = $numeroConceptosSinFacturar;

		$data['analytics']['importeNoFacturadoPesos'] = round($importeNoFacturadoPesos,2);
		$data['analytics']['importeNoFacturadoDolares'] = round($importeNoFacturadoDolares,2);
		$data['analytics']['subtotalNoFacturadoPesos'] = round($subtotalNoFacturadoPesos,2);
		$data['analytics']['subtotalNoFacturadoDolares'] = round($subtotalNoFacturadoDolares,2);
		$data['analytics']['ivaNoFacturadoPesos'] = round($ivaNoFacturadoPesos,2);
		$data['analytics']['ivaNoFacturadoDolares'] = round($ivaNoFacturadoDolares,2);

		$data['analytics']['importeFacturadoPesos'] = round($importeFacturadoPesos,2);
		$data['analytics']['importeFacturadoDolares'] = round($importeFacturadoDolares,2);
		$data['analytics']['subtotalFacturadoPesos'] = round($subtotalFacturadoPesos,2);
		$data['analytics']['subtotalFacturadoDolares'] = round($subtotalFacturadoDolares,2);
		$data['analytics']['ivaFacturadoPesos'] = round($ivaFacturadoPesos,2);
		$data['analytics']['ivaFacturadoDolares'] = round($ivaFacturadoDolares,2);

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

		$bancos = $this->db->query("SELECT * FROM catbanco ORDER BY nombre")->result();

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
		$data["bancos"]= $bancos;

		echo json_encode($data);
	}

	public function getRazonesSociales(){
		$idCliente = $this->input->post("idCliente");

		echo json_encode($this->DireccionFiscal->traerAsociadas($idCliente));
	}

	public function getCotizaciones(){
		$idCliente = $this->input->post("idCliente");

		$queryCotizaciones = "select * 
							from cotizacion c1 
							where 
								exists(
									select 1 
									from 
										concepto_cotizacion cc 
										join fecha_factura ff on ff.idConceptoCotizacion = cc.id
										join concepto_factura_cotizacion cfc on cfc.idFechaFactura = ff.id
									where c1.id = cc.idCotizacion
								) and idCliente = ".$idCliente;

		echo json_encode($this->db->query($queryCotizaciones)->result());
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

	public function saveFechaPago(){
		$idConceptoCotizacion = $this->input->post("idConceptoCotizacion");
		$fechaPago = $this->input->post("fechaPago");

		$idConceptoCotizacion = htmlentities($idConceptoCotizacion, ENT_QUOTES, 'UTF-8');
		$nota = htmlentities($nota, ENT_QUOTES, 'UTF-8');

		$data = array(
					"fechaPago" => $fechaPago
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
		//print_r($data);

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
		$xls->setCellValue("Banco"); $xls->nextCol(); //Nueva Columna
		$xls->setCellValue("Orden de compra"); $xls->nextCol();
		$xls->setCellValue("Servicio"); $xls->nextCol(); //Nueva columna
		$xls->setCellValue("Clasificación"); $xls->nextCol();
		$xls->setCellValue("Referencia"); $xls->nextCol();
		$xls->setCellValue("Descripción"); $xls->nextCol();
		$xls->setCellValue("Proyecto"); $xls->nextCol();
		$xls->setCellValue("Inicio de proyecto"); $xls->nextCol();
		$xls->setCellValue("Fin de proyecto"); $xls->nextCol();
		// $xls->setCellValue("Razón social"); $xls->nextCol();
		$xls->setCellValue("Fecha de venta"); $xls->nextCol();
		$xls->setCellValue("Junta de arranque"); $xls->nextCol();
		$xls->setCellValue("Cerrador"); $xls->nextCol();
		// $xls->setCellValue("Responsable"); $xls->nextCol();
		$xls->setCellValue("Account Manager"); $xls->nextCol();
		$xls->setCellValue("IVA"); $xls->nextCol();
		$xls->setCellValue("Monto de IVA"); $xls->nextCol();
		$xls->setCellValue("Fecha de cancelación"); $xls->nextCol();
		$xls->setCellValue("Contrato"); $xls->nextCol();
		$xls->setCellValue("Nota");

		$xls->setCellBackground("FE9A2E", "A1:".$xls->getPosition());

		for($k=0, $n=count($data); $k<$n; $k++){
			$row = $data[$k];

			//Asigna nombre de banco
			if ($row->idBanco!="NO ASIGNADO") {
				$query_banco="SELECT nombre FROM catbanco WHERE id=".$row->idBanco;
				$banco = $this->db->query($query_banco)->row();
				$banco = $banco->nombre;
			}	

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
			if ($row->idBanco !="NO ASIGNADO") { //Indica en el excel si tiene o no banco asignado
				$xls->setCellValue($banco); $xls->nextCol(); //Nueva Columna
			}else{
				$xls->setCellValue($row->idBanco); $xls->nextCol(); //Nueva Columna
			}
			$xls->setCellValue($row->ordenCompra); $xls->nextCol();
			$xls->setCellValue($row->servicio); $xls->nextCol();
			$xls->setCellValue($row->clasificacion); $xls->nextCol();
			$xls->setCellValue($row->referencia); $xls->nextCol();
			$xls->setCellValue($row->descripcion); $xls->nextCol();
			$xls->setCellValue($row->tituloCotizacion); $xls->nextCol();
			$xls->setCellValue($row->inicioProyecto); $xls->nextCol();
			$xls->setCellValue($row->finProyecto); $xls->nextCol();
			// $xls->setCellValue($row->razonSocial); $xls->nextCol();
			$xls->setCellValue($row->fechaVenta); $xls->nextCol();
			$xls->setCellValue($row->fechaJuntaArranque); $xls->nextCol();
			$xls->setCellValue($row->cerrador); $xls->nextCol();
			// $xls->setCellValue($row->responsable); $xls->nextCol();
			$xls->setCellValue($row->accountManager); $xls->nextCol();
			$xls->setCellValue($row->tasa); $xls->nextCol();
			$xls->setCellValue($row->cantidadIVA); $xls->nextCol();
			$xls->setCellValue($row->fechaCancelacion); $xls->nextCol();
			$xls->setCellValue($row->contrato); $xls->nextCol();
			$xls->setCellValue($row->nota); $xls->nextCol();
		}

		$xls->autosizeColumns();
		$xls->out("Master_CODICE.xls");
	}

	public function actualizaBanco()
	{	
		$idBanco = htmlentities($this->input->post("idBanco"),ENT_QUOTES,'UTF-8');
		$idFechaFactura = htmlentities($this->input->post("idFechaFactura"),ENT_QUOTES,'UTF-8');

		$query_actualiza_banco = "UPDATE fecha_factura SET idBanco=";
		if ($idBanco!= -1) {
			$query_actualiza_banco.=$idBanco;
		}else{
			$query_actualiza_banco.='NULL';
		}
		$query_actualiza_banco.=" WHERE id=".$idFechaFactura;
		for ($i=0, $n=count($_SESSION["last_query_result"]); $i < $n; $i++) { 
			if (($_SESSION["last_query_result"][$i]->id)==$idFechaFactura) {
				if ($idBanco != -1) {
					$_SESSION["last_query_result"][$i]->idBanco = $idBanco;
					break;
				}else{
					$_SESSION["last_query_result"][$i]->idBanco = "NO ASIGNADO";
					break;
				}
				
			}
		}
		if ($this->db->query($query_actualiza_banco)) {
			echo "Banco actualizado";
		}else{
			echo "Ocurrió un error al actualizar el banco";
		}
	}
}
