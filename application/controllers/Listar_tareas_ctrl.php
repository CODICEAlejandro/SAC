<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Listar_tareas_ctrl extends CI_Controller {
	public function index(){
		checkSession();

		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		$dataHmw = $this->Tarea->traerAsociadas($this->session->userdata('id'));
		$data['tareas'] = $dataHmw;
		$data['retrabajosEdit'] = $this->Retrabajo->traerAsociadosParaEditar($this->session->userdata('id'));
		$data['retrabajos'] = $this->Retrabajo->traerAsociadosPendientes($this->session->userdata('id'));
		$data['retrabajosTerminados'] = $this->Retrabajo->traerAsociadosTerminados($this->session->userdata('id'));

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Listar_tareas_vw',$data);
	}
}
?>
