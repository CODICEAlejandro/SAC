<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BancoAsociado extends CI_Model {
	var $table = "bancoasociado";

	public function __construct(){
		parent::__construct();
	}

	public function traerTodo(){
		//$this->db->where("estadoActivo = 1");
		return $this->db->get($this->table)->result();
	}

	public function traerTodo_AI(){
		return $this->db->get($this->table)->result();		
	}

	public function insertar($data){
		$result = 0;

		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		if($this->db->insert($this->table, $data)){
			$queryMaxID = "SELECT max(`id`) maxID
					FROM
						`bancoasociado` ba
					WHERE
						ba.`idDireccionFiscal` = ".$data['idDireccionFiscal']."
					";

			$result = $this->db->query($queryMaxID)->row();
			$result = $result->maxID;
		}

		return $result;
	}

	public function actualizar($id, $data){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->where("id = ", $id);
		return $this->db->update($this->table, $data);
	}

	public function traerAsociados($idPadre){
		$idPadre = htmlentities($idPadre, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					cb.*
				FROM
					".$this->table." cb
					INNER JOIN `direccionfiscal` df ON df.`id` = cb.`idDireccionFiscal`
				WHERE
					df.`idPadre` = ".$idPadre."
				";

		return $this->db->query($query)->result();
	}
}
