<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LecturaXML_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Factura");
		$this->load->model("Concepto");

		$fact = new $this->Factura();
	}


	public function index(){
		$this->load->view("LecturaXML_vw");
		phpinfo();
	}

	public function processXML(){
		$files = $_FILES;

		$xml = simplexml_load_file($files["xmlFile"]["tmp_name"]);

		//Registrar los namespaces en el objeto
		$namespaces = $xml->getNameSpaces(true);
		$xml->registerXPathNamespace("tfd", $namespaces["tfd"]);
		$xml->registerXPathNamespace("fx", $namespaces["fx"]);

		//Acceder árbol de conceptos y luego descender a Concepto
		$conceptos = $xml->xpath("//fx:Conceptos/fx:Concepto");

		//Recorrer cada concepto en busca de sus atributos para almacenarlos en una estructura
		foreach($conceptos as $element){
			$cantidad = $element->xpath("fx:Cantidad")[0]->__toString();
			$unidadDeMedida = $element->xpath("fx:UnidadDeMedida")[0]->__toString();
			$descripcion = $element->xpath("fx:Descripcion")[0]->__toString();
			$valorUnitario = $element->xpath("fx:ValorUnitario")[0]->__toString();
			$importe = $element->xpath("fx:Importe")[0]->__toString();

			//Obtener los extras
			//Asumimos que solo existe un fragmento etiquetado como ConceptoEx
			$conceptoEx = $element->xpath("fx:ConceptoEx")[0];
			$precioLista = $conceptoEx->xpath("fx:PrecioLista")[0];
			$importeLista = $conceptoEx->xpath("fx:ImporteLista")[0];
			$importeTotal = $conceptoEx->xpath("fx:ImporteTotal")[0];

			//Obtener Impuestos
			$impuestos = $conceptoEx->xpath("fx:Impuestos/fx:Impuesto");
			$listaDeImpuestos = array();
			foreach($impuestos as $impuesto){
				$contexto = $impuesto->xpath("fx:Contexto")[0]->__toString();
				$operacion = $impuesto->xpath("fx:Operacion")[0]->__toString();
				$codigo = $impuesto->xpath("fx:Codigo")[0]->__toString();
				$base = $impuesto->xpath("fx:Base")[0]->__toString();
				$tasa = $impuesto->xpath("fx:Tasa")[0]->__toString();
				$monto = $impuesto->xpath("fx:Monto")[0]->__toString();

				array_push($listaDeImpuestos,
						array(
							"contexto" => $contexto,
							"operacion" => $operacion,
							"codigo" => $codigo,
							"base" => $base,
							"tasa" => $tasa,
							"monto" => $monto
						)
					);
			}

			//Obtener textos de posición
			$textosDePosicion = $conceptoEx->xpath("fx:TextosDePosicion/fx:Texto");
			$texto = "";
			foreach($textosDePosicion as $textoPosicion){
				$texto .= $textoPosicion->__toString()."\r\n";
			}
		}

		//Obtener totales
		$totales = $xml->xpath("//fx:Totales")[0];
		$moneda = $totales->xpath("fx:Moneda")[0]->__toString();
		$tipoDeCambioVenta = $totales->xpath("fx:TipoDeCambioVenta")[0]->__toString();
		$subtotalBruto = $totales->xpath("fx:SubTotalBruto")[0]->__toString();
		$subtotal = $totales->xpath("fx:SubTotal")[0]->__toString();
		$total = $totales->xpath("fx:Total")[0]->__toString();
		$totalEnLetra = $totales->xpath("fx:TotalEnLetra")[0]->__toString();
		$formaDePago = $totales->xpath("fx:FormaDePago")[0]->__toString();

		//Resumen de impuestos
		$resImpuestos = $totales->xpath("fx:ResumenDeImpuestos")[0];
		$totalTrasladosFederales = $resImpuestos->xpath("fx:TotalTrasladosFederales")[0]->__toString();
		$totalIVATrasladado = $resImpuestos->xpath("fx:TotalIVATrasladado")[0]->__toString();
		$totalIEPSTrasladado = $resImpuestos->xpath("fx:TotalIEPSTrasladado")[0]->__toString();
		$totalRetencionesFederales = $resImpuestos->xpath("fx:TotalRetencionesFederales")[0]->__toString();
		$totalISRRetenido = $resImpuestos->xpath("fx:TotalISRRetenido")[0]->__toString();
		$totalIVARetenido = $resImpuestos->xpath("fx:TotalIVARetenido")[0]->__toString();
		$totalTrasladosLocales = $resImpuestos->xpath("fx:TotalTrasladosLocales")[0]->__toString();
		$totalRetencionesLocales = $resImpuestos->xpath("fx:TotalRetencionesLocales")[0]->__toString();

		$data = array();

		$this->load->view("LecturaXML_vw", $data);
	}
}