<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alta_usuario_ctrl extends CI_Controller {
	public function index(){
		$data = $this->cargaInicial();
		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$this->load->view('Alta_usuario_vw', $data);
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

	public function isAvailableEmail(){
		$email = $this->input->post('correo');
		$email = htmlentities($email,ENT_QUOTES,'UTF-8');
		$this->load->model('Estadistica');

		$validEmail = $this->Estadistica->count_where('catusuario AS cu', 'cu.correo = "'.$email.'"');
		if(!$validEmail)
			echo "OK";
		else
			echo "FAIL";
	}

	public function nuevoUsuario(){
		$this->load->model('Usuario');
		$data = $this->input->post();
		unset($data['action']);

		$this->Usuario->insertar($data);
		redirect(base_url().'index.php/Panel_control_ctrl');
	}
}