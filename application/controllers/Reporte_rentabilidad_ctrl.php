<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function sortByUsername($a, $b)
{
    $t1 = strtotime($a->nombre);
    $t2 = strtotime($b->nombre);

    return ($t1 - $t2);
}

class Reporte_rentabilidad_ctrl extends CI_Controller {
	public function index(){
		$data = $this->cargaInicial();
		$data['menu'] = $this->load->view('Menu_principal',null,true);

		$this->load->view('Reporte_rentabilidad_vw', $data);
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

		//Comprobar si $idProyecto = -1 o $idConsultor = -1 => Mostrar todos
		$queryTareas = "SELECT cu.*,
						ca.`nombre` nombreArea,
						ccli.`nombre` nombreCliente,
						cp.`nombre` nombreProyecto,
						cf.`nombre` nombreFase,
						ct.`tiempoEstimado` tiempoEstimado,
						ct.`tiempoRealGerente` tiempoReal,
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
						AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
					";

		$queryErrores = "SELECT cu.*,
						ca.`nombre` nombreArea,
						ccli.`nombre` nombreCliente,
						cp.`nombre` nombreProyecto,
						cf.`nombre` nombreFase,
						ce.`tiempoEstimado` tiempoEstimado,
						ce.`tiempoRealGerente` tiempoReal,
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
						AND ce.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
					";


		$querySecondaryTareas = "SELECT 
								    count(*) AS total,
								    cf.`nombre` AS fase,
								    cu.`nombre` AS nombre,
								    time_format(sec_to_time(sum(time_to_sec(ct.`tiempoRealGerente`))), '%H:%i') AS tiempoReal
								FROM
								    `cattarea` AS ct
								    INNER JOIN `catfase` cf ON cf.`id` = ct.`idFase`
								    INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
								    INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
								WHERE
									ct.`idEstado` = 3
									AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
							";


		$querySecondaryErrores = "SELECT 
								    count(*) AS total,
								    cf.`nombre` AS fase,
								    cu.`nombre` AS nombre,
								    time_format(sec_to_time(sum(time_to_sec(ce.`tiempoRealGerente`))), '%H:%i') AS tiempoReal
								FROM
									`caterror` AS ce
								    INNER JOIN `cattarea` AS ct ON ct.`id`=ce.`idTareaOrigen` 
								    INNER JOIN `catfase` cf ON cf.`id` = ct.`idFase`
								    INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
								    INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
								WHERE
									ct.`idEstado` = 3
									AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
							";

		if($idCliente != -1){
			$queryTareas = $queryTareas.' AND cp.`idCliente` = '.$idCliente;
			$queryErrores = $queryErrores.' AND cp.`idCliente` = '.$idCliente;
			$querySecondaryTareas = $querySecondaryTareas.' AND cp.`idCliente` ='.$idCliente;
			$querySecondaryErrores = $querySecondaryErrores.' AND cp.`idCliente` ='.$idCliente;
		}

		if($idArea != -1){
			$queryTareas = $queryTareas.' AND cu.`idArea` = '.$idArea;
			$queryErrores = $queryErrores.' AND cu.`idArea` = '.$idArea;
			$querySecondaryTareas = $querySecondaryTareas.' AND cu.`idArea` = '.$idArea;
			$querySecondaryErrores = $querySecondaryErrores.' AND cu.`idArea` = '.$idArea;
		}

		if($idConsultor != -1){
			$queryTareas = $queryTareas.' AND cu.`id` = '.$idConsultor;
			$queryErrores = $queryErrores.' AND cu.`id` = '.$idConsultor;
			$querySecondaryTareas = $querySecondaryTareas.' AND cu.`id` = '.$idConsultor;
			$querySecondaryErrores = $querySecondaryErrores.' AND cu.`id` = '.$idConsultor;
		}

		if($idProyecto != -1){
			$queryTareas = $queryTareas.' AND cp.`id` = '.$idProyecto;			
			$queryErrores = $queryErrores.' AND cp.`id` = '.$idProyecto;			
			$querySecondaryTareas = $querySecondaryTareas.' AND cp.`id` = '.$idProyecto;			
			$querySecondaryErrores = $querySecondaryErrores.' AND cp.`id` = '.$idProyecto;			
		}

		$querySecondaryTareas = $querySecondaryTareas
								.' GROUP BY
									fase, nombre
								ORDER BY
									nombre';

		$querySecondaryErrores = $querySecondaryErrores
								.' GROUP BY
									fase, nombre
								ORDER BY
									nombre';

		$resultTareas = $this->db->query($queryTareas)->result();
		$resultErrores = $this->db->query($queryErrores)->result();
		$resultSecondaryTareas = $this->db->query($querySecondaryTareas)->result();
		$resultSecondaryErrores = $this->db->query($querySecondaryErrores)->result();

		$result['primary_table'] = array_merge($resultTareas, $resultErrores);
		$result['secondary_table'] = array_merge($resultSecondaryTareas, $resultSecondaryErrores);

		usort($result['primary_table'], "sortByUsername");
		usort($result['secondary_table'], "sortByUsername");

		echo json_encode($result);
	}
}

?>