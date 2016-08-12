<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ciudad extends CI_Model {
	var $table = "catciudad";
	var $relCol = "idEstado";

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

	public function traerAsociados($idPais){
		$this->db->where($this->relCol." = ",$idPais);
		//$this->db->where("estadoActivo = 1");
		return $this->db->get($this->table)->result();
	}
}