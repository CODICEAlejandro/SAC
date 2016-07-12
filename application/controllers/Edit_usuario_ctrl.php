<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_usuario_ctrl extends CI_Controller {
	public function index(){
		$data = $this->cargaInicial();
		$data['menu'] = $this->load->model('Menu_principal',null,true);

		$this->load->view('Alta_usuario_vw');
	}

	public function cargaInicial(){
		$this->load->model('Proyecto');

		$proyectos = $this->Proyecto->traer_cp();
		$areas = $this->db->get('catarea')->result();
		$puestos = $this->db->get('catpuesto')->result();

		$data['proyectos'] = $proyectos;
		$data['areas'] = $areas;
		$data['puestos'] = $puestos;

		return $data;
	}

	public function getUserInfo(){
		$userId = $this->input->post('id');
		$userId = htmlentities($userId, ENT_QUOTES, 'UTF-8');

		$this->load->model('Usuario');
		$this->Usuario->traer($userId);
	}
}
?>