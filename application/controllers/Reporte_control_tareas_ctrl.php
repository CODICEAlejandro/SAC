<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function sortByUsername($a, $b)
{
    $t1 = strtotime($a->consultor);
    $t2 = strtotime($b->consultor);

    return ($t1 - $t2);
}

class Reporte_control_tareas_ctrl extends CI_Controller {
	public function index(){
		$data = $this->cargaInicial();
		$data['menu'] = $this->load->view('Menu_principal', NULL, true);

		$this->load->view('Reporte_control_tareas_vw', $data);
	}

	public function cargaInicial(){
		$this->load->model('Cliente');

		$data['clientes'] = $this->Cliente->traerTodo();
		$data['areas'] = $this->db->query('SELECT * FROM catarea ca ORDER BY ca.`nombre` ASC')->result();

		return $data;
	}

	public function onChangeArea($idArea){
		$this->load->model('Usuario');
		$data['usuarios'] = $this->Usuario->traerAsociados_area($idArea);
		echo json_encode($data);
	}

	public function onChangeCliente($idCliente){
		$this->load->model('Proyecto');
		$data['proyectos'] = $this->Proyecto->traerAsociados_cliente($idCliente);
		echo json_encode($data);
	}

	public function onRetrieveData(){
		$fechaSup = $this->input->post('fechaSup');
		$fechaInf = $this->input->post('fechaInf');
		$idProyecto = $this->input->post('idProyecto');
		$idConsultor = $this->input->post('idConsultor');
		$idArea = $this->input->post('idArea');
		$idCliente = $this->input->post('idCliente');

		$fechaSup = htmlentities($fechaSup, ENT_QUOTES, 'UTF-8');
		$fechaInf = htmlentities($fechaInf, ENT_QUOTES, 'UTF-8');
		$idProyecto = htmlentities($idProyecto, ENT_QUOTES, 'UTF-8');
		$idConsultor = htmlentities($idConsultor, ENT_QUOTES, 'UTF-8');

		$queryRetrieveData_tareas = "SELECT
										cu.`nombre` consultor,
										ca.`nombre` area,
										ccli.`nombre` cliente,
										cp.`nombre` proyecto,
										cf.`nombre` fase,
										ct.`tiempoEstimado` tiempoEstimado,
										ct.`tiempoRealGerente` tiempoReal,
										ct.`titulo` titulo,
										DATE_FORMAT(ct.`creacion`, '%d/%m/%Y') creacion
									FROM
										`cattarea` ct
										INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
										INNER JOIN `catarea` ca ON ca.`id` = cu.`idArea`
										INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
										INNER JOIN `catcliente` ccli ON ccli.`id` = cp.`idCliente`
										INNER JOIN `catfase` cf ON cf.`id` = ct.`idFase`
									WHERE
										ct.`idEstado` = 3
										AND (ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."')";

		$queryRetrieveData_errores = "SELECT
										cu.`nombre` consultor,
										ca.`nombre` area,
										ccli.`nombre` cliente,
										cp.`nombre` proyecto,
										cf.`nombre` fase,
										ce.`tiempoEstimado` tiempoEstimado,
										ce.`tiempoRealGerente` tiempoReal,
										ct.`titulo` titulo,
										DATE_FORMAT(ce.`creacion`, '%d/%m/%Y') creacion
									FROM
										`caterror` ce
										INNER JOIN `cattarea` ct ON ct.`id` = ce.`idTareaOrigen`
										INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
										INNER JOIN `catarea` ca ON ca.`id` = cu.`idArea`
										INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
										INNER JOIN `catcliente` ccli ON ccli.`id` = cp.`idCliente`
										INNER JOIN `catfase` cf ON cf.`id` = ct.`idFase`
									WHERE
										ce.`idEstado` = 3
										AND (ce.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."')";

		if($idCliente != -1){
			$queryRetrieveData_tareas = $queryRetrieveData_tareas.' AND cp.`idCliente` = '.$idCliente;
			$queryRetrieveData_errores = $queryRetrieveData_errores.' AND cp.`idCliente` = '.$idCliente;
		}

		if($idArea != -1){
			$queryRetrieveData_tareas = $queryRetrieveData_tareas.' AND cu.`idArea` = '.$idArea;
			$queryRetrieveData_errores = $queryRetrieveData_errores.' AND cu.`idArea` = '.$idArea;
		}

		if($idConsultor != -1){
			$queryRetrieveData_tareas = $queryRetrieveData_tareas.' AND cu.`id` = '.$idConsultor;
			$queryRetrieveData_errores = $queryRetrieveData_errores.' AND cu.`id` = '.$idConsultor;
		}

		if($idProyecto != -1){
			$queryRetrieveData_tareas = $queryRetrieveData_tareas.' AND cp.`id` = '.$idProyecto;			
			$queryRetrieveData_errores = $queryRetrieveData_errores.' AND cp.`id` = '.$idProyecto;			
		}

		$queryRetrieveData_tareas = $queryRetrieveData_tareas
								.' ORDER BY
									consultor ASC';

		$queryRetrieveData_errores = $queryRetrieveData_errores
								.' ORDER BY
									consultor ASC';

		$result_tareas = $this->db->query($queryRetrieveData_tareas)->result();
		$result_errores = $this->db->query($queryRetrieveData_errores)->result();

		$result = array_merge($result_tareas, $result_errores);
		usort($result, "sortByUsername");

		echo json_encode($result);
	}
}
?>