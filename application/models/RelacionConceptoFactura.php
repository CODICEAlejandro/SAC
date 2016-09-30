<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RelacionConceptoFactura extends CI_Model {
	var $table = 'concepto_factura_rel';
	var $tableFactura = 'factura';
	var $tableConcepto = 'concepto';

	public function __construct(){
		parent::__construct();
	}

	public function insertar($data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		return $this->db->insert($this->table, $data);
	}

	public function traer($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		$this->db->where('id =', $id);

		return $this->db->get($this->table)->row();
	}

	public function traerTodo(){
		$this->db->where('estadoActivo = 1');
		return $this->db->get($this->table)->result();
	}

	public function traerAsociadosAConcepto($idConcepto){		
		$idConcepto = htmlentities($idConcepto, ENT_QUOTES, 'UTF-8');
		$query = "SELECT
						tf.*
					FROM
						`factura` tf
						INNER JOIN ".($this->table)." mt ON mt.`idFactura`  = tf.`id`
					WHERE
						mt.`idConcepto` = ".$idConcepto."
						AND mt.`estadoActivo` = 1
				";

		return $this->db->query($query)->result();
	}

	public function traerAsociadosAFactura($idFactura){		
		$idFactura = htmlentities($idFactura, ENT_QUOTES, 'UTF-8');
		$query = "SELECT
						tc.*
					FROM
						`concepto` tc
						INNER JOIN ".($this->table)." mt ON mt.`idFactura`  = tc.`id`
					WHERE
						mt.`idFactura` = ".$idFactura."
						AND mt.`estadoActivo` = 1
				";

		return $this->db->query($query)->result();
	}

	public function traerTodo_AI(){
		return $this->db->get($this->table)->result();
	}

	public function traerAsociadosAConcepto_AI($idConcepto){		
		$idConcepto = htmlentities($idConcepto, ENT_QUOTES, 'UTF-8');
		$query = "SELECT
						tf.*
					FROM
						`factura` tf
						INNER JOIN ".($this->table)." mt ON mt.`idFactura`  = tf.`id`
					WHERE
						mt.`idConcepto` = ".$idConcepto."
				";

		return $this->db->query($query)->result();
	}

	public function traerAsociadosAFactura_AI($idFactura){		
		$idFactura = htmlentities($idFactura, ENT_QUOTES, 'UTF-8');
		$query = "SELECT
						tc.*
					FROM
						`concepto` tc
						INNER JOIN ".($this->table)." mt ON mt.`idFactura`  = tc.`id`
					WHERE
						mt.`idFactura` = ".$idFactura."
				";

		return $this->db->query($query)->result();
	}

	public function actualizar($idFactura, $idConcepto, $data){
		$idFactura = htmlentities($idFactura, ENT_QUOTES, 'UTF-8');
		$idConcepto = htmlentities($idConcepto, ENT_QUOTES, 'UTF-8');

		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->where('idFactura =', $idFactura);
		$this->db->where('idConcepto =', $idConcepto);
		return $this->db->update($this->table, $data);
	}

	public function eliminar($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		$this->db->where('id = ', $id);
		return $this->db->delete($this->table);
	}

}

?>