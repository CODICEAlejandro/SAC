<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Model {
	public function traerTodo(){
		$this->db->order_by('nombre');
		$result = $this->db->get('catcliente')->result();

		return $result;
	}
}

?>
