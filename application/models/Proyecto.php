<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyecto extends CI_Model {
	//Obtiene todos los proyectos activos asociados a cada uno de los clientes
	//Retorna un arreglo: {cliente1 => {proyecto1, proyecto2, ...}, cliente2 ..., ...}
	public function traer_cp(){
		$this->db->order_by("nombre","ASC");
		$clientes = $this->db->get('catcliente')->result();
		$clienteProyectos = array();

		foreach($clientes as $cCliente){
			$this->db->where('idCliente =',$cCliente->id);
			$this->db->where('estado = 1');
			$clienteProyectos[$cCliente->nombre] = $this->db->get('catproyecto')->result();
		}

		return $clienteProyectos;
	}

	public function insertar($data){
		if($this->db->insert('catproyecto',$data))
			return true;
		else return false;
	}

	public function eliminar($id){
		$this->db->where("id =",$id);
		if($this->db->delete('catproyecto'))
			return true;
		else return false;
	}

	public function actualizar($id,$data){
		$this->db->where("id =",$id);
		if($this->db->update('catproyecto',$data))
			return true;
		else return false;
	}
}
?>
