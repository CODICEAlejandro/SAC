<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Factura extends CI_Model {
	var $table = 'factura';
	var $conceptos = array();		//Array of conceptos
	var $moneda = "";
	var $subtotal = 0.0;
	var $total = 0.0;
	var $tipoDeCambioVenta = 0.0;
	var $totalEnLetra = "";
	var $formaDePago = "";
	var $totalTrasladosFederales = 0.0;
	var $totalIVATrasladado = 0.0;
	var $totalIEPSTrasladado = 0.0;
	var $totalRetencionesFederales = 0.0;
	var $totalISRRetenido = 0.0;
	var $totalIVARetenido = 0.0;
	var $totalTrasladosLocales = 0.0;
	var $totalRetencionesLocales = 0.0;
	var $importe = 0.0;
	var $nota = "";
	var $folio = "";
	var $idEstadoFactura = -1;
	var $fechaPago = "";
	var $ordenCompra = "";
	var $iva = 0.0;
	var $idCotizacion = null;	//Es el ID de la cotización relacionada. Se usa para la tabla de relación Cotización con factura
	var $fechaFactura = "";
	var $fechaCancelacion = "";
	var $importeEfectivo = 0.0;

	public function __construct(){
		parent::__construct();
		$this->load->model("Concepto");
	}

	public static function parseFactura($data){
		$result = new Factura();

		$result->moneda = $data['moneda'];
		$result->subtotal = $data['subtotal'];
		$result->total = $data['total'];
		$result->tipoDeCambioVenta = $data['tipoDeCambioVenta'];
		$result->totalEnLetra = $data['totalEnLetra'];
		$result->formaDePago = $data['formaDePago'];
		$result->totalTrasladosFederales = $data['totalTrasladosFederales'];
		$result->totalIVATrasladado = $data['totalIVATrasladado'];
		$result->totalIEPSTrasladado = $data['totalIEPSTrasladado'];
		$result->totalRetencionesFederales = $data['totalRetencionesFederales'];
		$result->totalISRRetenido = $data['totalISRRetenido'];
		$result->totalIVARetenido = $data['totalIVARetenido'];
		$result->totalTrasladosLocales = $data['totalTrasladosLocales'];
		$result->totalRetencionesLocales = $data['totalRetencionesLocales'];
		$result->importe = $data['importe'];
		$result->nota = $data['nota'];
		$result->folio = $data['folio'];
		$result->idEstadoFactura = $data['idEstadoFactura'];
		$result->fechaPago = $data['fechaPago'];
		$result->ordenCompra = $data['ordenCompra'];
		$result->iva = $data['iva'];
		$result->idCotizacion = $data['idCotizacion'];
		$result->fechaFactura = $data['fechaFactura'];
		$result->fechaCancelacion = $data['fechaCancelacion'];
		$result->importeEfectivo = $data['importeEfectivo'];

		for($k=0, $n = count($data['conceptos']); $k<$n; $k++)
			$result->pushConcepto(Concepto::parseConcepto($data['conceptos'][$k]));

		return $result;
	}

	public function save($recursive = true){
		$data = array(
			"moneda" => $this->moneda,
			"tipoDeCambioVenta" => $this->tipoDeCambioVenta,
			"subtotal" => $this->subtotal,
			"total" => $this->total,
			"totalEnLetra" => $this->totalEnLetra,
			"formaDePago" => $this->formaDePago,
			"totalTrasladosFederales" => $this->totalTrasladosFederales,
			"totalIVATrasladado" => $this->totalIVATrasladado,
			"totalIEPSTrasladado" => $this->totalIEPSTrasladado,
			"totalRetencionesFederales" => $this->totalRetencionesFederales,
			"totalISRRetenido" => $this->totalISRRetenido,
			"totalIVARetenido" => $this->totalIVARetenido,
			"totalTrasladosLocales" => $this->totalTrasladosLocales,
			"totalRetencionesLocales" => $this->totalRetencionesLocales,
			"nota" => $this->nota,
			"folio" => $this->folio,
			"idEstadoFactura" => $this->idEstadoFactura,
			"fechaPago" => $this->fechaPago,
			"ordenCompra" => $this->ordenCompra,
			"iva" => $this->iva,
			"importe" => $this->importe,
			"fechaFactura" => $this->fechaFactura,
			"fechaCancelacion" => $this->fechaCancelacion,
			"importeEfectivo" => $this->importeEfectivo
		);

		$idFactura = $this->insertar($data);

		$dataCotizacion_factura = array(
			"idFactura" => $idFactura,
			"idCotizacion" => $this->idCotizacion
		);
		$this->db->insert("cotizacion_factura_rel", $dataCotizacion_factura);

		if($recursive){
			foreach($this->conceptos as $concepto){
				$idConcepto = $concepto->save();

				$dataConcepto_factura = array(
					"idConcepto" => $idConcepto,
					"idFactura" => $idFactura,
					"monto" => $concepto->montoEfectivo
				);
				$this->db->insert("concepto_factura_rel", $dataConcepto_factura);
			}
		}
	}

	public function pushConcepto($concepto){
		array_push($this->conceptos, $concepto);
	}

	public function insertar($data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function traer($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		$this->db->where('id =', $id);

		return $this->db->get($this->table)->row();
	}

	public function saludar(){
		return "HELLO";
	}

	public function traerTodo(){
		$this->db->where('estadoActivo = 1');
		return $this->db->get($this->table)->result();
	}

	public function traerTodo_AI(){
		return $this->db->get($this->table)->result();
	}

	public function actualizar($id, $data){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->where('id =', $id);
		return $this->db->update($this->table, $data);
	}

	public function eliminar($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		$this->db->where('id = ', $id);
		return $this->db->delete($this->table);
	}

	public function traerConceptos($idFactura){
		$id = htmlentities($idFactura, ENT_QUOTES, 'UTF-8');
		$query = "SELECT
					tc.`id` id,
					tc.`descripcion` descripcion,
					ctc.`descripcion` tipoConcepto,
					tc.`monto` monto,
					cfrel.`monto` montoFacturacion,
					if(tc.`recurrencia`=1, 'Sí', 'No') recurrencia,
					tc.`referencia` referencia,
					tc.`nota` nota
				FROM
					`concepto_factura_rel` cfrel
					INNER JOIN `factura` tf ON tf.`id` = cfrel.`idFactura`
					INNER JOIN `concepto` tc ON tc.`id` = cfrel.`idConcepto`
					INNER JOIN `cattipoconcepto` ctc ON ctc.`id` = tc.`idTipoConcepto`
				WHERE
					tf.`id` = ".$idFactura."
				";

		return $this->db->query($query)->result();
	}

	public function calculaSubtotal($idFactura){
		$idFactura = htmlentities($idFactura, ENT_QUOTES, 'UTF-8');

		$query = "SELECT IF(ISNULL(sum(tr.`monto`)), 0, sum(tr.`monto`)) subtotal
				FROM
					`concepto_factura_rel` tr
				WHERE
					tr.`idFactura` = ".$idFactura."
				";

		$result = $this->db->query($this->table)->row();

		return $result->subtotal;
	}

}

?>