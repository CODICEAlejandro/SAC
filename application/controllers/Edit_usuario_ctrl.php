<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_usuario_ctrl extends CI_Controller {
	public function index(){
		$data = $this->cargaInicial();
		$data['menu'] = $this->load->view('Menu_principal',null,true);

		$this->load->view('Edit_usuario_vw', $data);
	}

	public function cargaInicial(){
		$this->load->model('Proyecto');
		$this->load->model('Usuario');

		$proyectos = $this->Proyecto->traer_cp();
		$areas = $this->db->get('catarea')->result();
		$puestos = $this->db->get('catpuesto')->result();
		$usuarios = $this->Usuario->traerTodo_AI();

		$data['proyectos'] = $proyectos;
		$data['areas'] = $areas;
		$data['puestos'] = $puestos;
		$data['usuarios'] = $usuarios;

		return $data;
	}

	public function getUserInfo(){
		$userId = $this->input->post('id');
		$userId = htmlentities($userId, ENT_QUOTES, 'UTF-8');

		$this->load->model('Usuario');
		echo json_encode( $this->Usuario->traer($userId) );
	}

	public function updateUser(){
		$userId = $this->input->post('id');
		$data = $this->input->post();
		unset($data['action']);

		$this->load->model('Usuario');
		$this->Usuario->actualizar($userId, $data);
		$this->index();
	}

	public function darDeBaja(){
		$userId = $this->input->post('id');
		$this->load->model('Usuario');
		$this->Usuario->darBaja($userId);
	}

	public function darDeAlta(){
		$userId = $this->input->post('id');
		$this->load->model('Usuario');
		$this->Usuario->darAlta($userId);
	}
}
?>