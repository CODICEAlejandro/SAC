<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DireccionFiscal extends CI_Model {
	var $table = 'direccionfiscal';

	public function __construct(){
		parent::__construct();
	}

	public function insertar($data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$result = $this->db->insert($this->table, $data);

		if($result){
			$query_max_id = "SELECT
								MAX(df.`id`) id
							FROM
								`direccionfiscal` df
							WHERE
								df.`idPadre` = ".$data['idPadre']."	
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

	public function traerCotizaciones($idDireccionFiscal){		
		$idDireccionFiscal = htmlentities($idDireccionFiscal, ENT_QUOTES, 'UTF-8');
		$query = "SELECT
					tc.`id` id,
					df.`razonSocial` razonSocial,
					date_format(tc.`creacion`, '%d/%m/%Y') creacion,
					tc.`nota` nota,
					tc.`folio` folio
				FROM
					`cotizacion` tc
					INNER JOIN `direccionfiscal` df ON df.`id` = tc.`idRazonSocial`
					INNER JOIN `catcliente` ccli ON ccli.`id` = df.`idPadre`
				WHERE
					tc.`idRazonSocial` = ".$idDireccionFiscal."
					AND tc.`estadoActivo` = 1
				";

		return $this->db->query($query)->result();
	}

	public function traerCotizaciones_AI($idDireccionFiscal){		
		$idDireccionFiscal = htmlentities($idDireccionFiscal, ENT_QUOTES, 'UTF-8');
		$query = "SELECT
					tc.`id` id,
					df.`razonSocial` razonSocial,
					date_format(tc.`creacion`, '%d/%m/%Y') creacion,
					tc.`nota` nota,
					tc.`folio` folio
				FROM
					`cotizacion` tc
					INNER JOIN `direccionfiscal` df ON df.`id` = tc.`idRazonSocial`
					INNER JOIN `catcliente` ccli ON ccli.`id` = df.`idPadre`
				WHERE
					tc.`idRazonSocial` = ".$idDireccionFiscal."
				";

		return $this->db->query($query)->result();	
	}

	public function traerAsociadas($idPadre){
		$id = htmlentities($idPadre, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					df.`id` id,
					df.`calle` calle,
					df.`numero` numero,
					df.`idPais` pais,
					df.`idCiudad` ciudad,
					df.`idEstado` estado,
					df.`colonia` colonia,
					df.`cp` cp,
					df.`razonSocial` razonSocial,
					df.`rfc` rfc,
					df.`estadoActivo` estadoActivo
				FROM
					`direccionfiscal` df
				WHERE
					df.`idPadre` = ".$id."
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