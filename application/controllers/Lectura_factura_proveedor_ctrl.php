<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lectura_factura_proveedor_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Concepto');
		$this->load->model('Impuesto');
		$this->load->model('Factura');
		$this->load->model('Cliente');
	}

	public function index(){
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Lectura_factura_proveedor_vw", $data);
		
	}

	public function guardarFactura(){
		$data = $this->input->post("mainData");
		$data = json_decode($data, true);

		$factura = Factura::parseFactura($data);
		$factura->save(true);

		print_r($factura);
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
		$fechaDesde = $this->input->post("fecha_desde");
		$fechaHasta = $this->input->post("fecha_hasta");
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
							and DATE(f.fecha_final) >= '".$fechaDesde."' and DATE(f.fecha_final) <= '".$fechaHasta."'
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

	public function procesarXML(){
		// >>>>>>>>>> Subir XML
		$file = $_FILES['fileXML'];
		$path = "./files/bills/";
		$fileName = "XML_BILL-";
		$objFactura = new $this->Factura();
		
		for($k = 0; $k < 30; $k++){
			$fileName .= rand(0,9);
		}

		$fileName .= ".xml";	

		$config = array(
			'upload_path' => "./files/bills",
			'allowed_types' => "xml|XML",
			'file_name' => $fileName
		);

		$this->load->library('upload', $config);
		$result = $this->upload->do_upload('fileXML');
		$objFactura->xml = $fileName;

		// if($result) echo "OK => ###:::### ";
		// else print_r($this->upload->display_errors());

		//print_r($file);
		
		// >>>>>>>>>>>>> Fin Subir XML
		// >>>>>>>>>>>>> Abrir XML

		$xml = simplexml_load_file($file["tmp_name"]);
		$atributosPrincipales = $xml->attributes();

		//Registrar los namespaces en el objeto
		$namespaces = $xml->getNameSpaces(true);

		// Total, subtotal e iva de la factura
		$generalFactura = $xml->xpath("//cfdi:Comprobante")[0]->attributes();
		$dataFactura["subtotal"] = (float) $generalFactura->subTotal->__toString();
		$dataFactura["total"] =  (float) $generalFactura->total->__toString();
		$dataFactura["iva"] = 100 * ($dataFactura["total"] - $dataFactura["subtotal"]) / $dataFactura["subtotal"];

		// Datos propios del receptor (Para el caso del cliente)
		// Para el caso del proveedor, se toman los datos del emisor
		$cliente = $xml->xpath("//cfdi:Emisor")[0]->attributes();
		if(isset($cliente->nombre))
			$dataReceptor["razonSocial"] = htmlentities($cliente->nombre->__toString(), ENT_QUOTES, 'UTF-8');
		else
			$dataReceptor["razonSocial"] = "SIN RAZÓN SOCIAL EN XML";
		$dataReceptor["rfc"] = htmlentities($cliente->rfc->__toString(), ENT_QUOTES, 'UTF-8');

		// Obtener el cliente sugerido si es que existe el RFC asociado con alguna de sus
		// direcciones fiscales
		$cliente_sugerido = $this->db->query("select cc.nombre as nombre, cc.id idCliente
											from
												catcliente cc inner join direccionfiscal df on df.idPadre = cc.id
											where rfc = '".$dataReceptor["rfc"]."'")->row();

		if(isset($cliente_sugerido))
			$dataReceptor["cliente_sugerido"] = $cliente_sugerido->nombre;
		else
			$dataReceptor["cliente_sugerido"] = "NO HAY COINCIDENCIAS";

		if(isset($namespaces["tfd"]))
			$xml->registerXPathNamespace("tfd", $namespaces["tfd"]);

		if (isset($namespaces["registrofiscal"])) 
			$xml->registerXPathNamespace("registrofiscal",$namespaces["registrofiscal"]);

		if(isset($namespaces["fx"])){
			$xml->registerXPathNamespace("fx", $namespaces["fx"]);

			//Acceder árbol de conceptos y luego descender a Concepto
			$conceptos = $xml->xpath("//fx:Conceptos/fx:Concepto");

			//Recorrer cada concepto en busca de sus atributos para almacenarlos en una estructura
			foreach($conceptos as $element){
				$objConcepto = new $this->Concepto();

				$objConcepto->cantidad = $element->xpath("fx:Cantidad")[0]->__toString();
				$objConcepto->unidadDeMedida = $element->xpath("fx:UnidadDeMedida")[0]->__toString();
				$objConcepto->descripcion = $element->xpath("fx:Descripcion")[0]->__toString();
				$objConcepto->valorUnitario = $element->xpath("fx:ValorUnitario")[0]->__toString();
				$objConcepto->importe = $element->xpath("fx:Importe")[0]->__toString();
				$objConcepto->monto = $objConcepto->importe;

				//Obtener los extras
				//Asumimos que solo existe un fragmento etiquetado como ConceptoEx
				$conceptoEx = $element->xpath("fx:ConceptoEx")[0];
				$objConcepto->precioLista = $conceptoEx->xpath("fx:PrecioLista")[0]->__toString();
				$objConcepto->importeLista = $conceptoEx->xpath("fx:ImporteLista")[0]->__toString();
				$importeTotal = $conceptoEx->xpath("fx:ImporteTotal")[0]->__toString();

				//Obtener Impuestos
				$impuestos = $conceptoEx->xpath("fx:Impuestos/fx:Impuesto");
				$listaDeImpuestos = array();
				foreach($impuestos as $impuesto){
					$objImpuesto = new $this->Impuesto();

					$objImpuesto->contexto = $impuesto->xpath("fx:Contexto")[0]->__toString();
					$objImpuesto->operacion = $impuesto->xpath("fx:Operacion")[0]->__toString();
					$objImpuesto->codigo = $impuesto->xpath("fx:Codigo")[0]->__toString();
					$objImpuesto->base = $impuesto->xpath("fx:Base")[0]->__toString();
					$objImpuesto->tasa = $impuesto->xpath("fx:Tasa")[0]->__toString();
					$objImpuesto->monto = $impuesto->xpath("fx:Monto")[0]->__toString();
					$objConcepto->monto += $objImpuesto->monto;

					$objConcepto->pushImpuesto($objImpuesto);
				}

				//Obtener textos de posición
				$textosDePosicion = $conceptoEx->xpath("fx:TextosDePosicion/fx:Texto");
				$texto = "";
				foreach($textosDePosicion as $textoPosicion){
					$texto .= $textoPosicion->__toString()."\r\n";
				}

				$objConcepto->textosDePosicion = $texto;
				$objFactura->pushConcepto($objConcepto);
			}

			//Obtener totales
			$totales = $xml->xpath("//fx:Totales")[0];
			$objFactura->moneda = $totales->xpath("fx:Moneda")[0]->__toString();
			$objFactura->tipoDeCambioVenta = $totales->xpath("fx:TipoDeCambioVenta")[0]->__toString();
			$objFactura->subtotalBruto = $totales->xpath("fx:SubTotalBruto")[0]->__toString();
			$objFactura->subtotal = $totales->xpath("fx:SubTotal")[0]->__toString();
			$objFactura->total = $totales->xpath("fx:Total")[0]->__toString();
			$objFactura->totalEnLetra = $totales->xpath("fx:TotalEnLetra")[0]->__toString();
			$objFactura->formaDePago = $totales->xpath("fx:FormaDePago")[0]->__toString();

			//Resumen de impuestos
			$resImpuestos = $totales->xpath("fx:ResumenDeImpuestos")[0];
			$objFactura->totalTrasladosFederales = $resImpuestos->xpath("fx:TotalTrasladosFederales")[0]->__toString();
			$objFactura->totalIVATrasladado = $resImpuestos->xpath("fx:TotalIVATrasladado")[0]->__toString();
			$objFactura->totalIEPSTrasladado = $resImpuestos->xpath("fx:TotalIEPSTrasladado")[0]->__toString();
			$objFactura->totalRetencionesFederales = $resImpuestos->xpath("fx:TotalRetencionesFederales")[0]->__toString();
			$objFactura->totalISRRetenido = $resImpuestos->xpath("fx:TotalISRRetenido")[0]->__toString();
			$objFactura->totalIVARetenido = $resImpuestos->xpath("fx:TotalIVARetenido")[0]->__toString();
			$objFactura->totalTrasladosLocales = $resImpuestos->xpath("fx:TotalTrasladosLocales")[0]->__toString();
			$objFactura->totalRetencionesLocales = $resImpuestos->xpath("fx:TotalRetencionesLocales")[0]->__toString();

		}else{
			$conceptos = $xml->xpath("//cfdi:Conceptos/cfdi:Concepto");

			//En este caso el mismo impouesto se aplica a todos los conceptos
			$impuestos = $xml->xpath("//cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado");

			foreach($conceptos as $concepto){
				$objConcepto = new $this->Concepto();
				$c = $concepto->attributes();

				$objConcepto->cantidad = $c->cantidad->__toString();
				$objConcepto->unidadDeMedida = $c->unidad->__toString();
				$objConcepto->descripcion = $c->descripcion->__toString();
				$objConcepto->valorUnitario = $c->valorUnitario->__toString();
				$objConcepto->importe = $c->importe->__toString();
				$objConcepto->monto = $objConcepto->importe;
				
				foreach($impuestos as $impuesto){
					$objImpuesto = new $this->Impuesto();
					$i = $impuesto->attributes();

					$objImpuesto->contexto = "SIN INFO EN XML";
					$objImpuesto->operacion = "SIN INFO EN XML";
					$objImpuesto->codigo = $i["impuesto"]->__toString();
					$objImpuesto->base = $objConcepto->importe;
					$objImpuesto->tasa = (float) $i["tasa"];
					$objImpuesto->monto = (($objImpuesto->tasa)/100) * ($objImpuesto->base);

					$objConcepto->monto += $objImpuesto->monto;

					$objConcepto->pushImpuesto($objImpuesto);
				}

				//Obtener los extras
				$objConcepto->precioLista = $c->valorUnitario->__toString();
				$objConcepto->importeLista = $c->importe->__toString();
				$importeTotal = $c->importe->__toString();

				$objFactura->pushConcepto($objConcepto);


				//Resumen de impuestos
				$resImpuestos = $xml->xpath("//cfdi:Impuestos")[0];
				//print_r($resImpuestos["totalImpuestosTrasladados"]->__toString());
				if(isset($resImpuestos["totalImpuestosTrasladados"]))
					$objFactura->totalTrasladosFederales = $resImpuestos["totalImpuestosTrasladados"]->__toString();
				else
					$totalImpuestosTrasladados = 0;
				$traslados = $resImpuestos->xpath("cfdi:Traslados");
				if(isset($traslados[0])){
					for ($i=0,$n=count($traslados[0]->xpath("cfdi:Traslado"));$i<$n;$i++) {
						$t = $traslados[0]->xpath("cfdi:Traslado")[$i]->attributes();
						
						if($t["impuesto"]=="IVA"){
							$objFactura->totalIVATrasladado = $t["importe"]->__toString();
						}elseif($t["impuesto"]=="IEPS"){
							$objFactura->totalIEPSTrasladado = $t["importe"]->__toString();
						}

						if(!isset($resImpuestos["totalImpuestosTrasladados"]))
							$totalImpuestosTrasladados += $t["importe"]->__toString();
					}

					if(isset($totalImpuestosTrasladados)&&$totalImpuestosTrasladados!=0)
						$objFactura->totalTrasladosFederales = $totalImpuestosTrasladados;
				}
				//$objFactura->totalIVATrasladado = $resImpuestos->xpath("cfdi:Traslados")[0]->__toString();
				//$objFactura->totalIEPSTrasladado = $resImpuestos->xpath("fx:TotalIEPSTrasladado")[0]->__toString();
				if(isset($resImpuestos["totalImpuestosRetenidos"]))
					$objFactura->totalRetencionesFederales = $resImpuestos["totalImpuestosRetenidos"]->__toString();
				else
					$totalImpuestosRetenidos = 0;
				$retenciones = $resImpuestos->xpath("cfdi:Retenciones");
				if(isset($retenciones[0])){
					for ($i=0,$n=count($retenciones[0]->xpath("cfdi:Retencion"));$i<$n;$i++) {
						$r = $retenciones[0]->xpath("cfdi:Retencion")[$i]->attributes();
						
						if($r["impuesto"]=="IVA"){
							$objFactura->totalIVARetenido = $r["importe"]->__toString();
						}elseif($r["impuesto"]=="ISR"){
							$objFactura->totalISRRetenido = $r["importe"]->__toString();
						}

						if(!isset($resImpuestos["totalImpuestosRetenidos"]))
							$totalImpuestosRetenidos += $r["importe"]->__toString();
					}

					if(isset($totalImpuestosRetenidos)&&$totalImpuestosRetenidos!=0)
						$objFactura->totalRetencionesFederales = $totalImpuestosRetenidos;
				}
				//$objFactura->totalISRRetenido = $resImpuestos->xpath("fx:TotalISRRetenido")[0]->__toString();
				//$objFactura->totalIVARetenido = $resImpuestos->xpath("fx:TotalIVARetenido")[0]->__toString();
				//$objFactura->totalTrasladosLocales = $resImpuestos->xpath("cfdi:TotalTrasladosLocales")[0]->__toString();
				//$objFactura->totalRetencionesLocales = $resImpuestos->xpath("cfdi:TotalRetencionesLocales")[0]->__toString();				
			}

			if (isset($atributosPrincipales["TotalEnLetra"])) 
				$objFactura->totalEnLetra = $atributosPrincipales["TotalEnLetra"]->__toString();
			else
				$objFactura->totalEnLetra = "SIN INFO EN XML";

			if(isset($atributosPrincipales["Moneda"]))
				$objFactura->moneda = $atributosPrincipales["Moneda"]->__toString();
			else
				$objFactura->moneda = "SIN INFO EN XML";				

			if(isset($atributosPrincipales["TipoCambio"]))
				$objFactura->tipoDeCambioVenta = $atributosPrincipales["TipoCambio"]->__toString();
			else
				$objFactura->tipoDeCambioVenta = "SIN INFO EN XML";				

			if(isset($atributosPrincipales["subTotal"])){
				$objFactura->subtotalBruto = $atributosPrincipales["subTotal"]->__toString();
				$objFactura->subtotal = $atributosPrincipales["subTotal"]->__toString();
			}else
				$objFactura->tipoDeCambioVenta = "SIN INFO EN XML";				

			if(isset($atributosPrincipales["total"]))
				$objFactura->total = $atributosPrincipales["total"]->__toString();
			else
				$objFactura->total = "SIN INFO EN XML";				

			if(isset($atributosPrincipales["formaDePago"]))
				$objFactura->formaDePago = $atributosPrincipales["formaDePago"]->__toString();
			else
				$objFactura->formaDePago = "SIN INFO EN XML";				
		}

		//Encabezados de factura
		$objFactura->fechaFactura = explode("T", $atributosPrincipales["fecha"]->__toString())[0];
		$objFactura->folio = $atributosPrincipales["serie"].$atributosPrincipales["folio"];
		if (empty($objFactura->folio)) {
			$folio = $xml->xpath("//cfdi:Complemento/registrofiscal:CFDIRegistroFiscal");
			$objFactura->folio = $folio[0]["Folio"];
		}
		//$estadoFolio = $this->db->query("select count(*) existencias from factura where folio = '".($objFactura->folio)."'")->row();
		$query_trae_exist_folio = "SELECT COUNT(*) existencias FROM factura f
		JOIN concepto_factura_rel fr ON f.id = fr.idFactura
		JOIN concepto c ON fr.idConcepto = c.id
		JOIN concepto_factura_cotizacion fc ON c.id = fc.idConceptoFactura
		JOIN fecha_factura ff ON fc.idFechaFactura = ff.id
		JOIN concepto_cotizacion cc ON ff.idConceptoCotizacion = cc.id
		JOIN cotizacion cot ON cc.idCotizacion = cot.id
		JOIN catcliente cli ON cot.idCliente = cli.id
		WHERE f.folio = '".($objFactura->folio)."'";

		if (isset($cliente_sugerido->idCliente)) {
			$query_trae_exist_folio.= " AND cli.id = ".$cliente_sugerido->idCliente."";
		}
		
		$estadoFolio = $this->db->query($query_trae_exist_folio)->row();
		
		if($estadoFolio->existencias == 0){
			$data["generalFactura"] = $dataFactura;
			$data["receptor"] = $dataReceptor;
			$data['tiposConcepto'] = $this->db->get("cattipoconcepto")->result();
			$data['clientes'] = $this->db->query("select * from catcliente where tipo = 1 and estadoActivo = 1 order by nombre asc")->result();
			$data['menu'] = $this->load->view("Menu_principal", null, true);
			$data['factura'] = $objFactura;
			$data['estadosFactura'] = $this->db->query("select * from catestadofactura where id = 24")->result();
			$this->load->view("Lectura_factura_proveedor_vw", $data);
		}else{
			$data['menu'] = $this->load->view("Menu_principal", null, true);
			$data['status'] = "El folio ".($objFactura->folio)." ya existe en la base de datos para ese proveedor. Contacte al administrador si desea removerla.";
			$this->load->view("Lectura_factura_proveedor_vw", $data);	
		}

	}

}