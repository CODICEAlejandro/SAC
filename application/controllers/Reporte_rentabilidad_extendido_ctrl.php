<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function sortByUsername($a, $b)
{
    $letra1 = ord( substr($a->nombre, 0, 1) );
	$letra2 = ord( substr($b->nombre, 0, 1) );

    return ($letra1 - $letra2);
}

function sortByPhasename($a, $b)
{
    $letra1 = ord( substr($a->fase, 0, 1) );
	$letra2 = ord( substr($b->fase, 0, 1) );

    return ($letra1 - $letra2);
}

class Reporte_rentabilidad_extendido_ctrl extends CI_Controller {
	public function index(){
		$data = $this->cargaInicial();
		$data['menu'] = $this->load->view('Menu_principal',null,true);

		$this->load->view('Reporte_rentabilidad_extendido_vw', $data);
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
		$idArea = htmlentities($idArea, ENT_QUOTES, 'UTF-8');
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');

		$result['primary_table'] = $this->retrievePrimaryTable($fechaSup, $fechaInf, $idCliente, $idArea, $idConsultor, $idProyecto);

		echo json_encode($result);
	}

	function retrievePrimaryTable($fechaSup, $fechaInf, $idCliente, $idArea, $idConsultor, $idProyecto){
		$queryTareas = "SELECT 
						R.*,
						R.`nombreArea` nombreArea,
						R.`nombreCliente` nombreCliente,
						R.`nombreProyecto` nombreProyecto,
						R.`nombreFase` nombreFase,
						R.`mesTarea` mesTarea,
						R.`descripcionTarea` descripcionTarea,
						R.`tituloTarea` tituloTarea,
						concat(R.`tiempoEstimado`, ':00') tiempoEstimado,
						concat(R.`tiempoRealGerente`, ':00') tiempoReal,
						R.`esError` AS esError
					FROM (
						SELECT 
							cu.*,
							ca.`nombre` nombreArea,
							ccli.`nombre` nombreCliente,
							cp.`nombre` nombreProyecto,
							cf.`nombre` nombreFase,
							ct.`tiempoEstimado` tiempoEstimado,
							ct.`titulo` tituloTarea,
							ct.`descripcion` descripcionTarea,
							DATE_FORMAT(DATE(ct.`creacion`), '%m') mesTarea,
							ct.`tiempoRealGerente` tiempoRealGerente,
							'N' AS esError
						FROM
							catusuario cu
							INNER JOIN cattarea AS ct ON ct.`idResponsable` = cu.`id`
							INNER JOIN catarea ca ON cu.`idArea` = ca.`id`
							INNER JOIN catfase cf ON ct.`idFase` = cf.`id`
							INNER JOIN catproyecto cp ON ct.`idProyecto` = cp.`id`
							INNER JOIN catcliente ccli ON cp.`idCliente` = ccli.`id`
						WHERE
							ct.`idEstado` = 3
							AND ct.`activo` = 1
							AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'

						UNION ALL

						SELECT 
							cu.*,
							ca.`nombre` nombreArea,
							ccli.`nombre` nombreCliente,
							cp.`nombre` nombreProyecto,
							cf.`nombre` nombreFase,
							ce.`tiempoEstimado` tiempoEstimado,
							ct.`titulo` tituloTarea,
							ce.`descripcion` descripcionTarea,
							DATE_FORMAT(DATE(ce.`creacion`), '%m') mesTarea,
							ce.`tiempoRealGerente` tiempoRealGerente,
							'S' AS esError
						FROM
							catusuario cu
							INNER JOIN cattarea AS ct ON ct.`idResponsable` = cu.`id`
							INNER JOIN catarea ca ON cu.`idArea` = ca.`id`
							INNER JOIN catfase cf ON ct.`idFase` = cf.`id`
							INNER JOIN catproyecto cp ON ct.`idProyecto` = cp.`id`
							INNER JOIN catcliente ccli ON cp.`idCliente` = ccli.`id`
							INNER JOIN caterror ce ON ce.`idTareaOrigen` = ct.`id`
						WHERE
							ce.`idEstado` = 3
							AND ce.`activo` = 1
							AND ce.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
					) R

					ORDER BY
						nombre
					";

		$this->processQueryWithFilters($queryTareas, $idCliente, $idArea, $idConsultor, $idProyecto);
		return $this->db->query($queryTareas)->result();
	}

	function processQueryWithFilters(&$query, $idCliente, $idArea, $idConsultor, $idProyecto){
		//Condiciones determinadas por filtros
		if($idCliente != -1)
			$query = $query.' AND cp.`idCliente` = '.$idCliente;

		if($idArea != -1)
			$query = $query.' AND cu.`idArea` = '.$idArea;

		if($idConsultor != -1)
			$query = $query.' AND cu.`id` = '.$idConsultor;
		

		if($idProyecto != -1)
			$query = $query.' AND cp.`id` = '.$idProyecto;

		return $query;		
	}
}

?>