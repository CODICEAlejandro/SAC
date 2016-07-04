<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Model {
	public function insertar($data){
		if($this->db->insert("catusuario", $data))
			return true;
		else
			return false;
	}

	public function eliminar($id){
		$this->db->where("id =",$id);
		if($this->db->delete("catusuario"))
			return true;
		else
			return false;
	}

	public function traer($id){
		$this->db->where("id =",$id);
		return $this->db->get("catusuario")->row();
	}

	public function traerTodo(){
		$result = $this->db->get("catusuario")->result();
		
		return $this->parseUsuario($result);
	}

	public function actualizar($id, $data){
		$this->db->where("id =",$id);
		if($this->db->update("catusuario",$data))
			return true;
		else
			return false;
	}

	public function parseUsuario($data){
		foreach ($data as $cUsuario) {
			$this->db->where("id =",$cUsuario->idArea);
			$cUsuario->area = $this->db->get("catarea")->row();

			$this->db->where("id =",$cUsuario->idPuesto);
			$cUsuario->puesto = $this->db->get("catpuesto")->row();
		}

		return $data;
	}
}
?>