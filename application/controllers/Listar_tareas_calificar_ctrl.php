<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listar_tareas_calificar_ctrl extends CI_Controller {
	public function index(){
		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		$pendientes = array();
		$terminados = array();
		$calificados = array();

		//$data['tareas'] = $this->Tarea->traerTodo();
		//$data['retrabajos'] = $this->Retrabajo->traerTodo();

		$tareas = $this->Tarea->traerTodo();
		$retrabajos = $this->Retrabajo->traerTodo();

		foreach($tareas as $tarea){
			$tarea->retrabajo = false;

			if($tarea->idEstado == 1){
				array_push($pendientes, $tarea);
			}else if($tarea->idEstado == 2){
				array_push($terminados, $tarea);
			}else if($tarea->idEstado == 3){
				array_push($calificados, $tarea);
			}
		}

		foreach($retrabajos as $tarea){
			$tarea->titulo = $tarea->tareaOrigen->titulo;
			$tarea->retrabajo = true;

			if($tarea->idEstado == 1){
				array_push($pendientes, $tarea);
			}else if($tarea->idEstado == 2){
				array_push($terminados, $tarea);
			}else if($tarea->idEstado == 3){
				array_push($calificados, $tarea);
			}
		}

		$data['pendientes'] = $pendientes;
		$data['terminados'] = $terminados;
		$data['calificados'] = $calificados;

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Listar_tareas_calificar_vw',$data);
	}
}
?>