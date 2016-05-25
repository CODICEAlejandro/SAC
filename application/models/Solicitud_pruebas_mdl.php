<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_pruebas_mdl extends CI_Model {
	public function insertar($data){
		if($this->db->insert('solicitudprueba', $data))
			return true;
		else
			return false;
	}

	public function actualizar($id,$data){
		$this->db->where('id =',$id);

		if($this->db->update('solicitudprueba', $data))
			return true;
		else
			return false;
	}

	public function eliminar($id){
		$this->db->where('id =',$id);

		if($this->db->delete('solicitudprueba'))
			return true;
		else
			return false;
	}

	public function traer($id){
		$this->db->where('id =',$id);

		return $this->db->get('solicitudprueba')->row();
	}

	public function traerTodo($id){
		return $this->db->get('solicitudprueba')->result();
	}
}

?>