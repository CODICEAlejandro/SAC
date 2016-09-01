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
			$mes = $row->mes;
			$superior = $dateIntervals[$mes]['superior'];
			$inferior = $dateIntervals[$mes]['inferior'];

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
							AND ct.`creacion` BETWEEN '".$inferior."' AND '".$superior."'
							AND cu.`idArea` = ".$area->id."
					";

				$queryCountTimeConsultor = "
						SELECT 
							TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(ct.`tiempoRealGerente`))), '%H:%i:%s') tiempoArea
						FROM
							`cattarea` ct
							INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
							INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
						WHERE
							ct.`idProyecto` = ".$proyecto."
							AND ct.`creacion` BETWEEN '".$inferior."' AND '".$superior."'
							AND cu.`idArea` = ".$area->id."
							AND cu.`tipo` = 0
					";

				$queryCountTimeGerente = "
						SELECT 
							TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(ct.`tiempoRealGerente`))), '%H:%i:%s') tiempoArea
						FROM
							`cattarea` ct
							INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
							INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
						WHERE
							ct.`idProyecto` = ".$proyecto."
							AND ct.`creacion` BETWEEN '".$inferior."' AND '".$superior."'
							AND cu.`idArea` = ".$area->id."
							AND cu.`tipo` = 1
					";

				//Los mismos interesados de errores deben estar en interesados de tareas

				$cleanField = preg_replace('/\s+/', '', $area->nombre);
				$cleanField = preg_replace('/á+/', 'a', $cleanField);
				$cleanField = preg_replace('/é+/', 'e', $cleanField);
				$cleanField = preg_replace('/í+/', 'i', $cleanField);
				$cleanField = preg_replace('/ó+/', 'o', $cleanField);
				$cleanField = preg_replace('/ú+/', 'u', $cleanField);
				$cleanField = preg_replace('/ñ+/', 'ni', $cleanField);
				$cleanField = preg_replace('/\/+/', '', $cleanField);

				$fieldConsultor = "gerente_".strtolower($cleanField);
				$fieldGerente = "consultor_".strtolower($cleanField);
				$fieldArea = "interesados_".strtolower($cleanField);

				$resultInteresados = $this->db->query($queryCountInteresadosTareas)->row();
				$resultTimeConsultor = $this->db->query($queryCountTimeConsultor)->row();
				$resultTimeGerente = $this->db->query($queryCountTimeGerente)->row();

				$row->$fieldArea = $resultInteresados->interesados;
				$row->$fieldConsultor = (is_null($resultTimeConsultor->tiempoArea))? '00:00:00': $resultTimeConsultor->tiempoArea;
				$row->$fieldGerente = (is_null($resultTimeGerente->tiempoArea))? '00:00:00': $resultTimeGerente->tiempoArea;
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
					ct.`mes` mes,
					ct.`total_de_tareas` total_de_tareas
				FROM
					(
					SELECT
						ccli.`nombre` cliente,
						ct.`idProyecto` idProyecto,
						cpro.`nombre` proyecto,
						CASE 
							WHEN ct.`creacion` BETWEEN '2016-06-01' AND '2016-07-01' THEN 'Junio'
							WHEN ct.`creacion` BETWEEN '2016-07-01' AND '2016-08-01' THEN 'Julio'
							WHEN ct.`creacion` BETWEEN '2016-08-01' AND '2016-09-01' THEN 'Agosto'
							ELSE ct.`creacion`
						END mes,
						COUNT(*) total_de_tareas
					FROM
						`cattarea` ct
						INNER JOIN `catproyecto` cpro ON cpro.`id` = ct.`idProyecto`
						INNER JOIN `catcliente` ccli ON ccli.`id` = cpro.`idCliente`
					WHERE
						ct.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
					GROUP BY
						cliente,
						proyecto,
						mes

					UNION ALL

					SELECT 
						ccli.`nombre` cliente,
						ct.`idProyecto`,
						cpro.`nombre` proyecto,
						CASE 
							WHEN ce.`creacion` BETWEEN '2016-06-01' AND '2016-07-01' THEN 'Junio'
							WHEN ce.`creacion` BETWEEN '2016-07-01' AND '2016-08-01' THEN 'Julio'
							WHEN ce.`creacion` BETWEEN '2016-08-01' AND '2016-09-01' THEN 'Agosto'
							ELSE ce.`creacion`
						END mes,
						COUNT(*) total_de_tareas
					FROM
						`caterror` ce
						INNER JOIN `cattarea` ct ON ct.`id` = ce.`idTareaOrigen`
						INNER JOIN `catproyecto` cpro ON cpro.`id` = ct.`idProyecto`
						INNER JOIN `catcliente` ccli ON ccli.`id` = cpro.`idCliente`
					WHERE
						ce.`creacion` BETWEEN '2016-06-01' AND '2016-09-01'
					GROUP BY
						cliente,
						proyecto,
						mes

					) ct

				GROUP BY
					cliente,
					proyecto,
					mes

				ORDER BY
					proyecto,
					mes
				";

		return $this->db->query($query)->result();
	}
}
?>
