<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_mdl extends CI_Model {
	public $tbl_users = 'catusuario';			//Nombre de tabla de usuarios

	function __contruct(){
		parent::__construct();
		$this->load->helper("result_helper");
	}

	//Retorna el número de usuarios con el usuario y password indicado
	//$data["user","password"] -> n
	function count_usuarios_up($data){
		$user = htmlentities($data["user"],ENT_QUOTES,'UTF-8');
		$pass = htmlentities($data["password"],ENT_QUOTES,'UTF-8');

		$this->db->where("correo =",$user);
		$this->db->where("password =",$pass);

		$this->db->from($this->tbl_users);
		$query = $this->db->get();

		$numberOfUsers = $query->num_rows();

		return $numberOfUsers;
	}

	//Retorna el número de usuarios con el nombre indicado
	//$data["user"] -> n
	function count_usuarios_u($data){
		$user = htmlentities($data["user"],ENT_QUOTES,'UTF-8');

		$this->db->where("correo =", $user);
		$this->db->from($this->tbl_users);
		$query = $this->db->get();

		$numberOfUsers = $query->num_rows();

		return $numberOfUsers;
	}

	//Retorna el usuario indicado y mensajes de estado, usando su password como credencial de acceso
	//$data["user","password"] -> $data["data","status_message"]
	function get($data){
		$messFail = "/NO_MATCH_USER";
		$messSuccess = "/OK_MATCH_USER";

		$user = htmlentities($data["user"],ENT_QUOTES,'UTF-8');
		$pass = htmlentities($data["password"],ENT_QUOTES,'UTF-8');

		$resArray = array();
		$resArray["data"] = null;
		$resArray["status_message"] = $messFail;

		if($this->count_usuarios_u($data)==1){
			$this->db->where("correo =",$user);
			$query = $this->db->get($this->tbl_users);

			$possible_user = $query->row();
			
			if($possible_user->password == $pass){
				$resArray["data"] = $possible_user;
				$resArray["status_message"] = $messSuccess;
			}
		}

		return $resArray;
	}

	//Insertar en tbl_users
	function insert($data){
		foreach($data as $key => $value){
			$data[$key] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		if($this->db->insert($this->tbl_users,$data))
			return true;
		else return false;
	}

	//Eliminar de tbl_users
	function delete($id){
		$id = htmlentities($id);

		$this->db->where("id_user =",$id);
		if($this->db->delete($this->tbl_users))
			return true;
		else return false;
	}

	//Actualizar de tbl_users
	function update($id, $data){
		foreach($data as $key => $value){
			$data[$key] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		$this->db->where("id_user =",$id);
		if($this->db->update($this->tbl_users,$data))
			return true;
		else return false;
	}
}
?>
