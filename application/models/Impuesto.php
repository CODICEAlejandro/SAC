<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Impuesto extends CI_Model {
	var $table = "Impuesto";
	var $contexto = "";
	var $operacion = "";
	var $codigo = "";
	var $base = 0.0;
	var $tasa = 0.0;
	var $monto = 0.0;
	var $idConcepto = -1; 		//Required

	public function __construct(){
		parent::__construct();
	}

	public static function parseImpuesto($data){
		$result = new Impuesto();

		$result->contexto = $data["contexto"];
		$result->operacion = $data["operacion"];
		$result->codigo = $data["codigo"];
		$result->base = $data["base"];
		$result->tasa = $data["tasa"];
		$result->monto = $data["monto"];
		$result->idConcepto = $data["idConcepto"];

		return $result;
	}

	public function save(){
		$data = array(
					"idConcepto" => $this->idConcepto,
					"contexto" => $this->contexto,
					"operacion" => $this->operacion,
					"codigo" => $this->codigo,
					"base" => $this->base,
					"tasa" => $this->tasa,
					"monto" => $this->monto
				);

		return $this->db->insert($this->table, $data);
	}

	public function insertar($data){
		foreach($data as $key => $value)
			$data[$key] = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function actualizar($id, $data){
		foreach($data as $key => $value)
			$data[$key] = html_entity_decode($value);

		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');

		$this->db->where("id =", $id);
		return $this->db->update($this->table, $data);
	}

	public function traer($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');		

		$this->db->where("id =", $id);
		return $this->db->get($this->table)->row();
	}

	public function eliminar($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');

		$this->db->where("id =", $id);
		return $this->db->delete($this->table);
	}
}
