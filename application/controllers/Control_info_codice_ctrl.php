<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control_info_codice_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model("InfoCodice");
	}

	public function index(){
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Control_info_codice_vw", $data);
	}

	public function retrieveAll(){
		echo json_encode($this->InfoCodice->traerTodo_AI());		
	}

	public function update($id){
		$data = $this->input->post();
		$this->InfoCodice->actualizar($id, $data);
	}

	public function create(){
		$data = $this->input->post();

		$this->InfoCodice->insertar($data);
	}

}

?>