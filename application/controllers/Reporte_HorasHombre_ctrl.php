<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_HorasHombre_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}


	public function index(){
		$dateIntervals = array(
							"Junio" => array("superior" => "2016-07-01", "inferior" => "2016-06-01"),
							"Julio" => array("superior" => "2016-08-01", "inferior" => "2016-07-01"),
							"Agosto" => array("superior" => "2016-09-01", "inferior" => "2016-08-01"),
						);

		$areas = $this->db->query("SELECT * FROM `catarea`")->result();

		$firstSection = $this->doFirstSection();

		foreach($firstSection as $row){
			$proyecto = $row->idProyecto;
			// $mes = $row->mes;
			// $superior = $dateIntervals[$mes]['superior'];
			// $inferior = $dateIntervals[$mes]['inferior'];

			$esError = $row->esError;

			foreach($areas as $area){
				$queryCountInteresadosTareas = "
						SELECT 
							count(DISTINCT ct.`idResponsable`) interesados
						FROM
							`cattarea` ct
							INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
							INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
						WHERE
							ct.`idProyecto` = ".$proyecto."
							AND cu.`idArea` = ".$area->id."
							AND ct.`idEstado` = 3
							AND ct.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
					";
				//Línea eliminada de filtrado de mes: AND ct.`creacion` BETWEEN '".$inferior."' AND '".$superior."'
				$queryCountTimeConsultor = "
						SELECT
							TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(rt.`tiempoArea`))), '%H:%i') tiempoArea
						FROM
						(
							SELECT 
								TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(ct.`tiempoRealGerente`))), '%H:%i') tiempoArea
							FROM
								`cattarea` ct
								INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
								INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
							WHERE
								ct.`idProyecto` = ".$proyecto."
								AND cu.`idArea` = ".$area->id."
								AND cu.`tipo` = 0
								AND ct.`idEstado` = 3
								AND ct.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'

							UNION ALL

							SELECT 
								TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(ce.`tiempoRealGerente`))), '%H:%i') tiempoArea
							FROM
								`caterror` ce
								INNER JOIN `cattarea` ct ON ct.`id` = ce.`idTareaOrigen`
								INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
								INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
							WHERE
								ct.`idProyecto` = ".$proyecto."
								AND cu.`idArea` = ".$area->id."
								AND cu.`tipo` = 0
								AND ce.`idEstado` = 3
								AND ct.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
						) rt
					";
				//Línea eliminada de filtrado de mes: AND ct.`creacion` BETWEEN '".$inferior."' AND '".$superior."'

				$queryCountTimeGerente = "
						SELECT
							TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(rt.`tiempoArea`))), '%H:%i') tiempoArea
						FROM
						(
							SELECT 
								TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(ct.`tiempoRealGerente`))), '%H:%i') tiempoArea
							FROM
								`cattarea` ct
								INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
								INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
							WHERE
								ct.`idProyecto` = ".$proyecto."
								AND cu.`idArea` = ".$area->id."
								AND cu.`tipo` = 1
								AND ct.`idEstado` = 3
								AND ct.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
						
							UNION ALL

							SELECT 
								TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(ce.`tiempoRealGerente`))), '%H:%i') tiempoArea
							FROM
								`caterror` ce
								INNER JOIN `cattarea` ct ON ct.`id` = ce.`idTareaOrigen`
								INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
								INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
							WHERE
								ct.`idProyecto` = ".$proyecto."
								AND cu.`idArea` = ".$area->id."
								AND cu.`tipo` = 1
								AND ce.`idEstado` = 3
								AND ct.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
						) rt
					";
				//Línea eliminada de filtrado de mes: AND ct.`creacion` BETWEEN '".$inferior."' AND '".$superior."'
				//Línea eliminada de filtrado de mes: AND ct.`creacion` BETWEEN '".$inferior."' AND '".$superior."'

				//Los mismos interesados de errores deben estar en interesados de tareas

				$cleanField = preg_replace('/\s+/', '', $area->nombre);
				$cleanField = preg_replace('/á+/', 'a', $cleanField);
				$cleanField = preg_replace('/é+/', 'e', $cleanField);
				$cleanField = preg_replace('/í+/', 'i', $cleanField);
				$cleanField = preg_replace('/ó+/', 'o', $cleanField);
				$cleanField = preg_replace('/ú+/', 'u', $cleanField);
				$cleanField = preg_replace('/ñ+/', 'ni', $cleanField);
				$cleanField = preg_replace('/\/+/', '', $cleanField);

				$fieldConsultor = "consultor_".strtolower($cleanField);
				$fieldGerente = "gerente_".strtolower($cleanField);
				$fieldArea = "interesados_".strtolower($cleanField);

				$resultInteresados = $this->db->query($queryCountInteresadosTareas)->row();
				$resultTimeConsultor = $this->db->query($queryCountTimeConsultor)->row();
				$resultTimeGerente = $this->db->query($queryCountTimeGerente)->row();

				$row->$fieldArea = $resultInteresados->interesados;

				if(is_null($resultTimeConsultor->tiempoArea)) 
					$row->$fieldConsultor = '0';
				else{
				 	$parts = explode(":",$resultTimeConsultor->tiempoArea);
				 	$hours = (int) $parts[0];
				 	$minutes = (int) $parts[1];
				 	$minutes = $minutes / 60;
				 	$total = $hours + $minutes;

				 	$row->$fieldConsultor = $total;
				}

				if(is_null($resultTimeGerente->tiempoArea)) 
					$row->$fieldGerente = '0';
				else{
				 	$parts = explode(":",$resultTimeGerente->tiempoArea);
				 	$hours = (int) $parts[0];
				 	$minutes = (int) $parts[1];
				 	$minutes = $minutes / 60;
				 	$total = $hours + $minutes;

				 	$row->$fieldGerente = $total;
				}

			}
		}

		$data['firstSection'] = $firstSection;
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Reporte_HorasHombre_vw", $data);
	}

	public function doFirstSection(){
		$query = "
				SELECT 
					ct.`cliente` cliente,
					ct.`proyecto` proyecto,
					ct.`idProyecto` idProyecto,
					SUM(ct.`total_de_tareas`) total_de_tareas,
					ct.`esError` esError
				FROM
					(
					SELECT
						ccli.`nombre` cliente,
						ct.`idProyecto` idProyecto,
						cpro.`nombre` proyecto,
						0 AS esError,
						COUNT(*) total_de_tareas
					FROM
						`cattarea` ct
						INNER JOIN `catproyecto` cpro ON cpro.`id` = ct.`idProyecto`
						INNER JOIN `catcliente` ccli ON ccli.`id` = cpro.`idCliente`
					WHERE
						ct.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
						AND ct.`idEstado` = 3
					GROUP BY
						idProyecto

					UNION ALL

					SELECT 
						ccli.`nombre` cliente,
						ct.`idProyecto`,
						cpro.`nombre` proyecto,
						1 AS esError,
						COUNT(*) total_de_tareas
					FROM
						`caterror` ce
						INNER JOIN `cattarea` ct ON ct.`id` = ce.`idTareaOrigen`
						INNER JOIN `catproyecto` cpro ON cpro.`id` = ct.`idProyecto`
						INNER JOIN `catcliente` ccli ON ccli.`id` = cpro.`idCliente`
					WHERE
						ce.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
						AND ce.`idEstado` = 3
					GROUP BY
						idProyecto

					) ct

				GROUP BY
					idProyecto

				ORDER BY
					proyecto
				";

		return $this->db->query($query)->result();
	}
}
?>
