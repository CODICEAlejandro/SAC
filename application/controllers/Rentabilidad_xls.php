<?php

function sortByUsername($a, $b)
{
    $t1 = strtotime($a->nombre);
    $t2 = strtotime($b->nombre);

    return ($t1 - $t2);
}

class Rentabilidad_xls extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function retrieveData($fechaSup, $fechaInf, $idProyecto, $idConsultor, $idArea, $idCliente){
		$fechaSup = implode('/', explode('_', $fechaSup));
		$fechaInf = implode('/', explode('_', $fechaInf));

		$fechaSup = htmlentities($fechaSup, ENT_QUOTES, 'UTF-8');
		$fechaInf = htmlentities($fechaInf, ENT_QUOTES, 'UTF-8');
		$idProyecto = htmlentities($idProyecto, ENT_QUOTES, 'UTF-8');
		$idConsultor = htmlentities($idConsultor, ENT_QUOTES, 'UTF-8');

		//Comprobar si $idProyecto = -1 o $idConsultor = -1 => Mostrar todos
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
			$querySecondaryTareas = $querySecondaryTareas.' AND cp.`idCliente` ='.$idCliente;
			$querySecondaryErrores = $querySecondaryErrores.' AND cp.`idCliente` ='.$idCliente;
		}

		if($idArea != -1){
			$querySecondaryTareas = $querySecondaryTareas.' AND cu.`idArea` = '.$idArea;
			$querySecondaryErrores = $querySecondaryErrores.' AND cu.`idArea` = '.$idArea;
		}

		if($idConsultor != -1){
			$querySecondaryTareas = $querySecondaryTareas.' AND cu.`id` = '.$idConsultor;
			$querySecondaryErrores = $querySecondaryErrores.' AND cu.`id` = '.$idConsultor;
		}

		if($idProyecto != -1){
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

		$resultSecondaryTareas = $this->db->query($querySecondaryTareas)->result();
		$resultSecondaryErrores = $this->db->query($querySecondaryErrores)->result();

		$result['secondary_table'] = array_merge($resultSecondaryTareas, $resultSecondaryErrores);
		usort($result['secondary_table'], "sortByUsername");

		return $result['secondary_table'];		
	}

	public function setExcel ($fechaSup, $fechaInf, $idProyecto, $idConsultor, $idArea, $idCliente) {
		$this->load->model("XLSSheetDriver");
		$shDv = new $this->XLSSheetDriver();
		$shDv->setTitle("Master - CODICE");
		$data = $this->retrieveData($fechaSup, $fechaInf, $idProyecto, $idConsultor, $idArea, $idCliente);

	    //Agregar encabezados
	    $shDv->setCellValue('Consultor');
	    $shDv->nextCol();
	    $shDv->setCellValue("Fase");
	    $shDv->nextCol();
	    $shDv->setCellValue("Tareas totales");
	    $shDv->nextCol();
	    $shDv->setCellValue("Tiempo real total");
	    $shDv->nextCol();

		$shDv->setCellBackground("FE9A2E", "A1:".$shDv->getPosition());

	    //Agregar contenido
	    for($k=0, $n=count($data); $k<$n; $k++){
	    	$shDv->nextLine();

			$shDv->setCellValue($data[$k]->nombre);
			$shDv->nextCol();
			$shDv->setCellValue($data[$k]->fase);
			$shDv->nextCol();
			$shDv->setCellValue($data[$k]->total);
			$shDv->nextCol();
			$shDv->setCellValue($data[$k]->tiempoReal);
			$shDv->nextCol();
	    }
		
		$shDv->autosizeColumns();
		$shDv->out("Reporte_rentabilidad_CODICE.xls");	     
	}
}

?>