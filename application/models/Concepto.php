<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Concepto extends CI_Model {
	var $table = 'concepto';
	var $cantidad = 0;
	var $unidadDeMedida = '';
	var $descripcion = '';
	var $valorUnitario = 0.0;
	var $importe = 0.0;
	var $precioLista = 0.0;
	var $importeLista = 0.0;
	var $textosDePosicion = "";
	var $idCotizacion = null;				//Required
	var $monto = 0.0;
	var $idTipoConcepto = null;			//Required
	var $nota = "";
	var $recurrencia = 0;
	var $idPeriodoRecurrencia = null;
	var $impuestos = array();			//Array of Impuesto
	var $idMatched = null;
	var $referencia = "";
	var $montoEfectivo = 0.0;	//Es el monto escrito en la tabla de relación entre factura y concepto

	public function __construct(){
		parent::__construct();
		$this->load->model("Impuesto");
	}

	public static function parseConcepto($data){
		$result = new Concepto();

		$result->cantidad = $data["cantidad"];
		$result->unidadDeMedida = $data["unidadDeMedida"];
		$result->descripcion = $data["descripcion"];
		$result->valorUnitario = $data["valorUnitario"];
		$result->importe = $data["importe"];
		$result->precioLista = $data["precioLista"];
		$result->importeLista = $data["importeLista"];
		$result->textosDePosicion = $data["textosDePosicion"];
		$result->idCotizacion = $data["idCotizacion"];
		$result->monto = $data["monto"];
		$result->idTipoConcepto = $data["idTipoConcepto"];
		$result->nota = $data["nota"];
		$result->recurrencia = $data["recurrencia"];
		$result->idPeriodoRecurrencia = $data["idPeriodoRecurrencia"];
		$result->idMatched = $data["idMatched"];
		$result->montoEfectivo = $data["montoEfectivo"];

		for($k=0, $n=count($data['impuestos']); $k<$n; $k++)
			$result->pushImpuesto( Impuesto::parseImpuesto($data['impuestos'][$k]) );

		return $result;
	}

	public function save($recursive = true){
		if($this->table == 'concepto_cotizacion')
			$data = array(
						"monto" => $this->monto,
						"estadoActivo" => 1,
						"descripcion" => $this->descripcion,
						"idTipoConcepto" => $this->idTipoConcepto,
						"referencia" => $this->referencia,
						"idCotizacion" => $this->idCotizacion,
						"recurrencia" => $this->recurrencia,
						"contadorPagos" => 1,
						"nota" => $this->nota,
						"cantidad" => $this->cantidad,
						"unidadDeMedida" => $this->unidadDeMedida,
						"valorUnitario" => $this->valorUnitario,
						"importe" => $this->importe,
						"textosDePosicion" => $this->textosDePosicion,
						"idPeriodoRecurrencia" => $this->idPeriodoRecurrencia
					);
		else{
			$data = array(
					"monto" => $this->monto,
					"estadoActivo" => 1,
					"descripcion" => $this->descripcion,
					"idTipoConcepto" => $this->idTipoConcepto,
					"referencia" => $this->referencia,
					"idConcepto_cotizacion" => $this->idMatched,
					"recurrencia" => $this->recurrencia,
					"contadorPagos" => 1,
					"nota" => $this->nota,
					"cantidad" => $this->cantidad,
					"unidadDeMedida" => $this->unidadDeMedida,
					"valorUnitario" => $this->valorUnitario,
					"importe" => $this->importe,
					"textosDePosicion" => $this->textosDePosicion
				);
		}

		if(is_null($this->idTipoConcepto)) unset($data["idTipoConcepto"]);

		$idConcepto = $this->insertar($data);

		if($recursive){
			foreach($this->impuestos as $impuesto){
				$impuesto->idConcepto = $idConcepto;
				$impuesto->save();
			}
		}

		return $idConcepto;
	}

	public function pushImpuesto($impuesto){
		array_push($this->impuestos, $impuesto);
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

	public function traerTodo(){
		$this->db->where('estadoActivo = 1');
		return $this->db->get($this->table)->result();
	}

	public function traerAsociadosACotizacion($idCotizacion){		
		$idCotizacion = htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');
		$this->db->where('estadoActivo = 1');
		$this->db->where('idCotizacion =', $idCotizacion);

		return $this->db->get($this->table)->result();
	}

	public function traerTodo_AI(){
		return $this->db->get($this->table)->result();
	}

	public function traerAsociadosACotizacion_AI($idCotizacion){
		$idCotizacion = htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');
		$this->db->where('idCotizacion =', $idCotizacion);

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

}

?>