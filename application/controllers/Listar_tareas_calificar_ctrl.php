<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function compareDateTimes($a, $b)
{
    $t1 = strtotime($a->creacion);
    $t2 = strtotime($b->creacion);

    return ($t1 - $t2);
}

class Listar_tareas_calificar_ctrl extends CI_Controller {
	public function index(){
		checkSession();

		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		$pendientes = array();
		$terminados = array();
		$calificados = array();

		if($_SESSION['tipo'] == 1)
			$idArea = $_SESSION['idArea'];
		else
			$idArea = '';

		$pendientes = array_merge($this->Tarea->traerPendientes($idArea), $this->Retrabajo->traerPendientes($idArea));
		$terminados = array_merge($this->Tarea->traerTerminados($idArea), $this->Retrabajo->traerTerminados($idArea));

		usort($pendientes, "compareDateTimes");
		usort($terminados, "compareDateTimes");

		$data['pendientes'] = $pendientes;
		$data['terminados'] = $terminados;
		$data['calificados'] = array();

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Listar_tareas_calificar_vw',$data);
	}

	public function getCalificados($idArea='', $fechaInicio='', $fechaFin=''){
		checkSession();

		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		$idArea = htmlentities($idArea, ENT_QUOTES, 'UTF-8');
		$fechaInicio = htmlentities($fechaInicio, ENT_QUOTES, 'UTF-8');
		$fechaFin = htmlentities($fechaFin, ENT_QUOTES, 'UTF-8');

		$result = array_merge($this->Tarea->traerCalificados($idArea, $fechaInicio, $fechaFin), 
						   $this->Retrabajo->traerCalificados($idArea, $fechaInicio, $fechaFin));

		usort($result, "compareDateTimes");
	}

	public function getCalificadosAJAX(){
		if($_SESSION['tipo'] == 1)
			$idArea = $_SESSION['idArea'];
		else
			$idArea = '';

		$fechaInicio = $this->input->post('fechaOrigen');
		$fechaFin = $this->input->post('fechaFin');

		$response = $this->getCalificados($idArea, $fechaInicio, $fechaFin);

		echo json_encode($response);
	}

	public function listarGerente(){
		checkSession();

		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		if($_SESSION['tipo'] == 1)
			$idArea = $_SESSION['idArea'];
		else
			$idArea = '';

		$pendientes = array();
		$terminados = array();
		$calificados = array();

		$pendientes = array_merge($this->Tarea->traerPendientes($idArea), $this->Retrabajo->traerPendientes($idArea));
		$terminados = array_merge($this->Tarea->traerTerminados($idArea), $this->Retrabajo->traerTerminados($idArea));

		usort($pendientes, "compareDateTimes");
		usort($terminados, "compareDateTimes");

		$data['pendientes'] = $pendientes;
		$data['terminados'] = $terminados;
		$data['calificados'] = array();

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Listar_tareas_calificar_vw',$data);
	}
}
?>