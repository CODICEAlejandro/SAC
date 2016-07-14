<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Model {
	public function insertar($data){
		foreach($data as $key => $value){
			$data[htmlentities($key,ENT_QUOTES,'UTF-8')] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		if($this->db->insert("catusuario", $data))
			return true;
		else
			return false;
	}

	public function eliminar($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->where("id =",$id);
		if($this->db->delete("catusuario"))
			return true;
		else
			return false;
	}

	public function traer($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->where("id =",$id);
		return $this->parseUsuario( $this->db->get("catusuario")->row() );
	}

	public function traerTodo(){
		$this->db->where("activo = 'S'");
		$this->db->order_by('nombre ASC');
		$result = $this->db->get("catusuario")->result();

		return $this->parseUsuario($result);
	}

	public function actualizar($id, $data){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		foreach($data as $key => $value){
			$data[$key] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		$this->db->where("id =",$id);
		if($this->db->update("catusuario",$data))
			return true;
		else
			return false;
	}

	public function darBaja($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		$this->db->query("UPDATE `catusuario` AS cu
						SET cu.`activo` = 'N'
						WHERE
							cu.`id` = ".$id
					);
	}

	public function traerAsociados_area($idArea){
		$idArea = htmlentities($idArea, ENT_QUOTES, 'UTF-8');

		$result = $this->db->query("SELECT * 
								FROM
									`catusuario` AS cu
								WHERE
									cu.`idArea` = ".$idArea."
									AND cu.`activo` = 'S'
								ORDER BY
									cu.`nombre` ASC
							")->result();

		return $result;
	}

	public function parseUsuario($data){
		if( is_array($data) ){
			foreach ($data as $cUsuario) {
				$this->db->where("id =",$cUsuario->idArea);
				$cUsuario->area = $this->db->get("catarea")->row();

				$this->db->where("id =",$cUsuario->idPuesto);
				$cUsuario->puesto = $this->db->get("catpuesto")->row();
			}
		}else{
			$this->db->where("id =",$data->idArea);
			$data->area = $this->db->get("catarea")->row();

			$this->db->where("id =",$data->idPuesto);
			$data->puesto = $this->db->get("catpuesto")->row();
		}

		return $data;
	}
}
?>