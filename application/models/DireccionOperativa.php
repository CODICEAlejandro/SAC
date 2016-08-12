<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DireccionOperativa extends CI_Model {
	var $table = 'direccionoperativa';

	public function __construct(){
		parent::__construct();
	}

	public function insertar($data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$result = $this->db->insert($this->table, $data);

		if($result){
			$query_max_id = "SELECT
								MAX(do.`id`) id
							FROM
								`direccionoperativa` do
							WHERE
								do.`idPadre` = ".$data['idPadre']."	
							";

			$max_id = $this->db->query($query_max_id)->row();
			$max_id = $max_id->id;
		}else
			$max_id = -1;

		return $max_id;
	}

	public function actualizar($id, $data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->where('id =', $id);
		return $result = $this->db->update($this->table, $data);
	}

	public function traerAsociadas($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					do.`id` id,
					do.`calle` calle,
					do.`numero` numero,
					do.`idPais` pais,
					do.`idCiudad` ciudad,
					do.`idEstado` estado,
					do.`colonia` colonia,
					do.`cp` cp,
					do.`estadoActivo` estadoActivo
				FROM
					`direccionoperativa` do
				WHERE
					do.`idPadre` = ".$id."
 				";
	
 		return $this->db->query($query)->result();
	}

	public function traerTodo(){
		$this->db->where('estadoActivo = 1');
		return $this->db->get($this->table)->result();
	}

	public function traerTodo_AI(){
		return $this->db->get($this->table)->result();		
	}
}

?>