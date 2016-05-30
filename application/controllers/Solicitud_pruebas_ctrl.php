<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_pruebas_ctrl extends CI_Controller {
	public function generarReporte($id,$table){
		checkSession();

		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		$data['idTarea'] = $id;
		$data['tabla'] = $table;

		if($table == "tarea"){
			$data["cElement"] = $this->Tarea->traer($id);
		}else{
			$data["cElement"] = $this->Retrabajo->traer($id);			
		}

		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$this->load->view('Solicitud_pruebas_vw', $data);
	}

	public function crearReporte(){
		checkSession();

		$data = $this->input->post();
		$this->load->model('Solicitud_pruebas_mdl');

		//Formato de información
		$info["idTarea"] = $data["idTarea"];
		$info["fechaSugerida"] = $data["fechaSugerida"];
		$info["tabla"] = "cat".$data["tabla"];
		$info["comentarioSolicitante"] = $data["comentarioSolicitante"];
		$info["estado"] = 0;

		$this->Solicitud_pruebas_mdl->insertar($info);

		if ($this->session->userdata("tipo")==2) {
			redirect(base_url().'index.php/Listar_tareas_calificar_ctrl');
		}else if($this->session->userdata("tipo")==1){
			redirect(base_url().'index.php/Listar_tareas_calificar_ctrl/listarGerente');			
		}
	}
}

?>