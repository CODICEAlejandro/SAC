<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizacion extends CI_Model {
	var $table = 'cotizacion';
	var $bookPage;

	public function __construct(){
		parent::__construct();
		$this->load->library('BookPage.php', array(0));
		$this->bookPage = $this->bookpage;
	}

	public function insertar($data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function traerFacturas($idCotizacion){		
		$idCotizacion = htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					tf.`id` id,
					cef.`descripcion` estadoFactura,
					tf.`folio` folio,
					tf.`fechaPago`,
					tf.`ordenPago` ordenPago,
					tf.`nota` nota
				FROM
					`factura` tf,
					`catestadofactura` cef
				WHERE
					tf.`idCotizacion` = ".$idCotizacion."
					AND tf.`estadoActivo` = 1
				";

		return $this->db->query($query)->result();
	}


	public function traerFacturas_AI($idCotizacion){		
		$idCotizacion = htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');
		
		$query = "SELECT
					tf.`id` id,
					cef.`descripcion` estadoFactura,
					tf.`folio` folio,
					tf.`fechaPago`,
					tf.`ordenPago` ordenPago,
					tf.`nota` nota
				FROM
					`factura` tf,
					`catestadofactura` cef
				WHERE
					tf.`idCotizacion` = ".$idCotizacion."
				";

		return $this->db->query($query)->result();
	}

	//$cPage = [0, ...] >= 0
	//$count = [1, ...] > 0
	public function traerFacturas_Interval($idCotizacion, $cPage, $count, $dateFrom, $dateTo){
		$this->bookPage->setItemsPerPage($count);
		$idCotizacion = htmlentities($idCotizacion, ENT_QUOTES, 'UTF-8');
		$cPage = htmlentities($cPage, ENT_QUOTES, 'UTF-8');
		$count = htmlentities($count, ENT_QUOTES, 'UTF-8');
		$dateFrom = htmlentities($dateFrom, ENT_QUOTES, 'UTF-8');
		$dateTo = htmlentities($dateTo, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					tf.`id` id,
					cef.`descripcion` estadoFactura,
					tf.`folio` folio,
					tf.`fechaPago`,
					tf.`ordenCompra` ordenCompra,
					tf.`nota` nota
				FROM
					`factura` tf
					INNER JOIN `catestadofactura` cef ON cef.`id` = tf.`idEstadoFactura`
				WHERE
					tf.`idCotizacion` = ".$idCotizacion."
					AND tf.`estadoActivo` = 1
					AND tf.`creacion` BETWEEN '".$dateFrom."' AND DATE_ADD('".$dateTo."', INTERVAL 1 DAY)
				LIMIT
					".($count+1)."
					OFFSET ".($cPage*$count)."
				";

		$result = $this->db->query($query)->result();
		$this->bookPage->pushItems($result);

		return $this->bookPage;
	}

	public function traer($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		$this->db->where('id =', $id);

		return $this->db->get($this->table)->row();
	}

	public function traerTodo(){
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
					tc.`estadoActivo` = 1
				";

		return $this->db->query($query)->result();
	}

	public function traerTodo_AI(){
		return $this->db->get($this->table)->result();
	}

	public function actualizar($id, $data){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$this->db->where('id =', $id);
		return $this->db->update($this->table, $data);
	}

	public function eliminar($id){
		$id = htmlentities($id, ENT_QUOTES, 'UTF-8');
		$this->db->where('id = ', $id);
		return $this->db->delete($this->table);
	}

}

?>