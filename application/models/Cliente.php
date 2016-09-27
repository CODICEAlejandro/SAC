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

	public function traerCotizaciones($idCliente){		
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					tc.`id` id,
					df.`razonSocial` razonSocial,
					date_format(tc.`creacion`, '%d/%m/%Y') creacion,
					tc.`nota` nota
				FROM
					`cotizacion` tc
					INNER JOIN `direccionfiscal` df ON df.`id` = tc.`idRazonSocial`
					INNER JOIN `catcliente` ccli ON ccli.`id` = df.`idPadre`
				WHERE
					ccli.`id` = ".$idCliente."
					AND tc.`estadoActivo` = 1
				";

		return $this->db->query($query)->result();
	}

	public function traerCotizaciones_AI($idCliente){		
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					tc.*
				FROM
					`cotizacion` tc
					INNER JOIN `direccionfiscal` df ON df.`id` = tc.`idDireccionFiscal`
					INNER JOIN `catcliente` ccli ON ccli.`id` = df.`idPadre`
				WHERE
					ccli.`id` = ".$idCliente."
				";

		return $this->db->query($query)->result();	}
	}

?>
