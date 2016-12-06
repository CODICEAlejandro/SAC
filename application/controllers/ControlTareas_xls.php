<?php

function sortByUsername($a, $b)
{
    $t1 = strtotime($a->consultor);
    $t2 = strtotime($b->consultor);

    return ($t1 - $t2);
}

class ControlTareas_xls extends CI_Controller {
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

		//Generar información
		$queryRetrieveData_tareas = "SELECT
										cu.`nombre` consultor,
										ca.`nombre` area,
										ccli.`nombre` cliente,
										cp.`nombre` proyecto,
										cf.`nombre` fase,
										ct.`tiempoEstimado` tiempoEstimado,
										ct.`tiempoRealGerente` tiempoReal,
										ct.`titulo` titulo,
										ct.`descripcion` descripcion,
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
										ce.`descripcion` descripcion,
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

		return $result;
	}

	public function setExcel ($fechaSup, $fechaInf, $idProyecto, $idConsultor, $idArea, $idCliente) {
			$this->load->model("XLSSheetDriver");

			$data = $this->retrieveData($fechaSup, $fechaInf, $idProyecto, $idConsultor, $idArea, $idCliente);
			$xls = new $this->XLSSheetDriver();
			$xls->setTitle("Reporte de control de tareas");
		     
		    //Agregar encabezados
		    $xls->setCellValue('Consultor');
		    $xls->nextCol();
		    $xls->setCellValue("Área");
		    $xls->nextCol();
		    $xls->setCellValue("Cliente");
		    $xls->nextCol();
		    $xls->setCellValue("Proyecto");
		    $xls->nextCol();
		    $xls->setCellValue("Fase");
		    $xls->nextCol();
		    $xls->setCellValue("Tiempo Estimado");
		    $xls->nextCol();
		    $xls->setCellValue("Tiempo Real");
		    $xls->nextCol();
		    $xls->setCellValue("Título");
		    $xls->nextCol();
		    $xls->setCellValue("Fecha");
		    $xls->nextCol();
		    $xls->setCellValue("Descripción");

		    $xls->setCellBackground("FE9A2E", "A1:".$xls->getPosition());

		    //Agregar contenido
		    for($k=0, $n=count($data); $k<$n; $k++){
		    	$xls->nextLine();

	    		$xls->setCellValue($data[$k]->consultor);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->area);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->cliente);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->proyecto);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->fase);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->tiempoEstimado);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->tiempoReal);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->titulo);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->creacion);
	    		$xls->nextCol();
	    		$xls->setCellValue($data[$k]->descripcion);
		    }

		    $xls->autosizeColumns();
		    $xls->out("Reporte_de_control_de_tareas.xls");
	}
}

?>