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
	    $sheet->setCellValue($shDv->getPosition(), "Fase");
	    $shDv->nextCol();
	    $sheet->setCellValue($shDv->getPosition(), "Tareas totales");
	    $shDv->nextCol();
	    $sheet->setCellValue($shDv->getPosition(), "Tiempo real total");
	    $shDv->nextCol();

	    //Agregar contenido
	    $shDv->gotoMark('DOCUMENT_BEGIN');
	    for($row = 2, $k=0, $n=count($data); $k<$n; $k++, $row++){
		    $shDv->gotoMark('DOCUMENT_BEGIN');
			$shDv->setRow($row);

			$sheet->setCellValue($shDv->getPosition(),utf8_decode(html_entity_decode($data[$k]->nombre)));
			$shDv->nextCol();
			$sheet->setCellValue($shDv->getPosition(),utf8_decode(html_entity_decode($data[$k]->fase)));
			$shDv->nextCol();
			$sheet->setCellValue($shDv->getPosition(),utf8_decode(html_entity_decode($data[$k]->total)));
			$shDv->nextCol();
			$sheet->setCellValue($shDv->getPosition(),utf8_decode(html_entity_decode($data[$k]->tiempoReal)));
	    }

		//####################### Auto size cells
		// for($shDv->gotoMark('DOCUMENT_BEGIN'), $n=getIntegerFromCol($originCol_END_DOC); $cRow<$n; $cRow++, $cCol = getNextCol($cCol)){
		// 	$cSheet->getColumnDimension($cCol)->setAutoSize(true);
		// }

	    // Renombramos la hoja de trabajo
	    $this->phpexcel->getActiveSheet()->setTitle('Reporte de rentabilidad');
	    
	    // configuramos el documento para que la hoja
	    // de trabajo número 0 sera la primera en mostrarse
	    // al abrir el documento
	    $this->phpexcel->setActiveSheetIndex(0);
	    
	    // redireccionamos la salida al navegador del cliente (Excel2007)
	    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    // header('Content-Disposition: attachment;filename="reporte_rentabilidad.xls"');
	    // header('Cache-Control: max-age=0');
		header('Content-Type: application/vnd.ms-excel; encoding: UTF-8');
		header('Content-Disposition: attachment;filename="reporte_rentabilidad.xls"');
		header('Cache-Control: max-age=0');

	    $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
	    $objWriter->save('php://output');
	     
	}
}

?>