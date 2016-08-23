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

		$this->load->library('PHPExcel.php');
		$this->load->library('XLSSheetDriver.php');
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
			$data = $this->retrieveData($fechaSup, $fechaInf, $idProyecto, $idConsultor, $idArea, $idCliente);
			$shDv = $this->xlssheetdriver;

		    // configuramos las propiedades del documento
		    $this->phpexcel->getProperties()->setCreator("Alejandro Segura")
		                                 ->setLastModifiedBy("Alejandro Segura")
		                                 ->setTitle("Reporte de rentabilidad - CODICE")
		                                 ->setSubject("CODICE")
		                                 ->setDescription("Reporte de rentabilidad.");
		     
		    // agregamos información a las celdas
		    $sheet = $this->phpexcel->setActiveSheetIndex(0);

		    //Agregar encabezados
		    $sheet->setCellValue($shDv->getPosition(),'Consultor');
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), utf8_decode("Área"));
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), utf8_decode("Cliente"));
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), "Proyecto");
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), "Fase");
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), "Tiempo Estimado");
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), "Tiempo Real");
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), utf8_decode("Título"));
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), utf8_decode("Fecha"));
		    $shDv->nextCol();
		    $sheet->setCellValue($shDv->getPosition(), utf8_decode("Descripción"));

		    //Agregar contenido
		    $shDv->gotoMark('DOCUMENT_BEGIN');
		    for($row = 2, $k=0, $n=count($data); $k<$n; $k++, $row++){
	    	    $shDv->gotoMark('DOCUMENT_BEGIN');
	    		$shDv->setRow($row);

	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->consultor)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->area)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->cliente)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->proyecto)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->fase)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->tiempoEstimado)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->tiempoReal)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->titulo)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->creacion)));
	    		$shDv->nextCol();
	    		$sheet->setCellValue($shDv->getPosition(), utf8_decode(html_entity_decode($data[$k]->descripcion)));
		    }

	        // Renombramos la hoja de trabajo
	        $this->phpexcel->getActiveSheet()->setTitle('Reporte de control de tareas');
	        
	        // configuramos el documento para que la hoja
	        // de trabajo número 0 sera la primera en mostrarse
	        // al abrir el documento
	        $this->phpexcel->setActiveSheetIndex(0);
	        
	        // redireccionamos la salida al navegador del cliente (Excel2007)
	        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	        // header('Content-Disposition: attachment;filename="reporte_rentabilidad.xls"');
	        // header('Cache-Control: max-age=0');
	    	header('Content-Type: application/vnd.ms-excel; encoding: UTF-8');
	    	header('Content-Disposition: attachment;filename="reporte_control_tareas.xls"');
	    	header('Cache-Control: max-age=0');

	        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
	        $objWriter->save('php://output');
	}
}

?>