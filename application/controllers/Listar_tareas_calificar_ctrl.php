<?php
class Listar_tareas_calificar_ctrl extends CI_Controller {
	public function index(){
		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		$data['tareas'] = $this->Tarea->traerTodo();
		$data['retrabajos'] = $this->Retrabajo->traerTodo();

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Listar_tareas_calificar_vw',$data);
	}
}
?>