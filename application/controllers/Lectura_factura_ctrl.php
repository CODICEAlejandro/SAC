<?php

defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','-1');
ini_set('max_execution_time', 30000);
set_time_limit(30000);
error_reporting(-1);
ini_set('display_errors', 1);

class Lectura_factura_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Concepto');
		$this->load->model('Impuesto');
		$this->load->model('Factura');
		$this->load->model('Cliente');
	}

	public function index(){
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Lectura_factura_vw", $data);
	}

	public function guardarFactura(){
		$data = $this->input->post("mainData");
		$data = json_decode($data, true);

		$factura = Factura::parseFactura($data);
		$factura->save(true);

		echo $factura->saludar();
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

		// if($result) echo "OK => ###:::### ";
		// else print_r($this->upload->display_errors());

		//print_r($file);
		
		// >>>>>>>>>>>>> Fin Subir XML
		// >>>>>>>>>>>>> Abrir XML

		$xml = simplexml_load_file($file["tmp_name"]);

		//Registrar los namespaces en el objeto
		$namespaces = $xml->getNameSpaces(true);
		$xml->registerXPathNamespace("tfd", $namespaces["tfd"]);
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

		//Encabezados de factura
		$objFactura->fechaFactura = explode("T", $xml->attributes()["fecha"]->__toString())[0];

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

		$data['tiposConcepto'] = $this->db->get("catTipoConcepto")->result();
		$data['clientes'] = $this->Cliente->traerTodo();
		$data['menu'] = $this->load->view("Menu_principal", null, true);
		$data['factura'] = $objFactura;
		$data['estadosFactura'] = $this->db->get("catestadofactura")->result();
		$this->load->view("Lectura_factura_vw", $data);
	}

}