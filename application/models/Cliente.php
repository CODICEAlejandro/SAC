<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Model {
	public function traerTodo(){
		$result = $this->db->get('catCliente')->result();

		return $result;
	}
}

?>