<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Model {
	public function traerTodo(){
		$this->db->order_by('nombre');
		$this->db->where('estadoActivo = 1');
		$this->db->where('tipo = 0');
		$result = $this->db->get('catcliente')->result();

		return $result;
	}

	public function traer_AI(){
		$this->db->order_by('nombre');
		$this->db->where('tipo = 0');
		$result = $this->db->get('catcliente')->result();

		return $result;
	}

	public function traer($id){
		$this->db->where('id = ',$id);
		$this->db->where('tipo = 0');
		return $this->db->get('catcliente')->row();
	}

	public function insertar($data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		return $this->db->insert('catcliente', $data);
	}
	
	public function actualizar($id, $data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->where('id = ', $id);
		return $this->db->update('catcliente', $data);
	}
}

?>
