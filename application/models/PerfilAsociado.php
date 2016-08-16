<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PerfilAsociado extends CI_Model {
	var $table = "perfilasociado";

	public function __construct(){
		parent::__construct();
	}

	public function traerTodo(){
		$this->db->where("estadoActivo = 1");
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
						`perfilasociado` co
					WHERE
						co.`idPadre` = ".$data['idPadre']."
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
					co.*
				FROM
					".$this->table." co
				WHERE
					co.`idPadre` = ".$idPadre."
				";

		return $this->db->query($query)->result();
	}
}
