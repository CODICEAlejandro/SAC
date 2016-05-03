<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyecto extends CI_Model {
	//Obtiene todos los proyectos activos asociados a cada uno de los clientes
	//Retorna un arreglo: {cliente1 => {proyecto1, proyecto2, ...}, cliente2 ..., ...}
	public function traer_cp(){
		$clientes = $this->db->get('catCliente')->result();
		$clienteProyectos = array();

		foreach($clientes as $cCliente){
			$this->db->where('idCliente =',$cCliente->id);
			$this->db->where('estado = 1');
			$clienteProyectos[$cCliente->nombre] = $this->db->get('catProyecto')->result();
		}

		return $clienteProyectos;
	}
}
?>