<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','16M');

class XMLReader_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Concepto');
		$this->load->model('Impuesto');
		$this->load->model('Factura');
		$this->load->model('Cliente');
	}

	public function index(){
		//$this->load->view("XMLReader_vw");
		$this->procesarXML();
	}

	public function procesarXML(){
		$path = "./files/bills/tmp/";

		$allowedTypes = array("xml", "XML"); 
		$filesTmp = scandir($path);
		print_r($filesTmp);
		$files = array();

		foreach($filesTmp as $key => $fileName){
			$type = explode(".", $fileName);
			$type = array_reverse($type);

			if(count($type)>0)
				$type = $type[0];
			else $type = "NONE";

			if(in_array($type, $allowedTypes)){
				array_push($files, $fileName);
			}
		}

		foreach($files as $key => $currentFile){
			// >>>>>>>>>> Subir XML
			$file = "./files/bills/".$currentFile;
			$fileName = "XML_BILL-";
			$objFactura = new $this->Factura();
			
			// for($k = 0; $k < 30; $k++){
			// 	$fileName .= rand(0,9);
			// }

			$fileName .= ".xml";	

			// $config = array(
			// 	'upload_path' => "./files/bills",
			// 	'allowed_types' => "xml|XML",
			// 	'file_name' => $fileName
			// );

			// $this->load->library('upload', $config);
			// $result = $this->upload->do_upload('fileXML');

			// if($result) echo "OK => ###:::### ";
			// else print_r($this->upload->display_errors());

			//print_r($file);
			
			// >>>>>>>>>>>>> Fin Subir XML
			// >>>>>>>>>>>>> Abrir XML

			$xml = simplexml_load_file($file);

			$objFactura->xml = $currentFile;

			//Registrar los namespaces en el objeto
			$namespaces = $xml->getNameSpaces(true);
			if(isset($namespaces['tfd']))
				$xml->registerXPathNamespace("tfd", $namespaces["tfd"]);
			if(isset($namespaces['fx']))
				$xml->registerXPathNamespace("fx", $namespaces["fx"]);

			//Acceder árbol de conceptos y luego descender a Concepto
			if(isset($namespaces['fx'])){
				$conceptos = $xml->xpath("//fx:Conceptos/fx:Concepto");
				//Recorrer cada concepto en busca de sus atributos para almacenarlos en una estructura
				foreach($conceptos as $element){
					$objConcepto = new $this->Concepto();

					$objConcepto->cantidad = $element->xpath("fx:Cantidad")[0]->__toString();
					$objConcepto->unidadDeMedida = $element->xpath("fx:UnidadDeMedida")[0]->__toString();
					$objConcepto->descripcion = $element->xpath("fx:Descripcion")[0]->__toString();
					$objConcepto->valorUnitario = $element->xpath("fx:ValorUnitario")[0]->__toString();
					$objConcepto->importe = $element->xpath("fx:Importe")[0]->__toString();
					$objConcepto->idTipoConcepto = NULL;
					$objConcepto->idMatched = NULL;

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
			}else{
				//Namespace FX no encontrado en la jerarquía. Procede a obtener datos de raíz
				print_r($xml);
				$objFactura->total = $xml->attributes()->total->__toString();
				$objFactura->subtotal = $xml->attributes()->subTotal->__toString();
				$objFactura->Moneda = $xml->attributes()->moneda->__toString();

				$objFactura->tipoDeCambioVenta = $xml->attributes()->TipoCambio->__toString();
				$objFactura->formaDePago = $xml->attributes()->metodoDePago->__toString();

				//Accede a conceptos
				$conceptos = $xml->xpath("//cfdi:Conceptos");
				foreach($conceptos as $c){
					$objConcepto = new $this->Concepto();
					$objConcepto->cantidad = $c->attributes()->cantidad->__toString();	
					$objConcepto->unidadDeMedida = $c->attributes()->unidad->__toString();	
					$objConcepto->descripcion = $c->attributes()->descripcion->__toString();	
					$objConcepto->valorUnitario = $c->attributes()->valorUnitario->__toString();	
					$objConcepto->importe = $c->attributes()->importe->__toString();
				}
			}

			$objFactura->idEstadoFactura = NULL;
			$objFactura->idCotizacion = NULL;
			$objFactura->folio = ($xml->attributes()->serie->__toString()).($xml->attributes()->folio->__toString());
			//$objFactura->save(true);
			print_r($objFactura);
		}
	}
}

?>