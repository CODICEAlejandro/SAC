<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banco extends CI_Model {
	var $table = "catbanco";

	public function __construct(){
		parent::__construct();
	}

	public function traerTodo(){
		return $this->db->get($this->table)->result();
	}

}