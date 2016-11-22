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

class Reporte_rentabilidad_ctrl extends CI_Controller {
	public function __construct(){
		parent::__construct();

		if(isset($_SESSION) && isset($_SESSION['user_active']) && isset($_SESSION['tipo']) && isset($_SESSION['puesto'])){
			$tipo = $_SESSION['tipo'];
			$puesto = $_SESSION['puesto'];

			//Accesso para accounts y administradores
			if( ($tipo != 2) && ($puesto != 5) ){
				die("No permitido");
			}
		}else
			die("No permitido. Primero inicie sesión.");
	}

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
		$idArea = htmlentities($idArea, ENT_QUOTES, 'UTF-8');
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');

		$result['primary_table'] = $this->retrievePrimaryTable($fechaSup, $fechaInf, $idCliente, $idArea, $idConsultor, $idProyecto);
		$result['secondary_table'] = $this->retrieveSecondaryTable($fechaSup, $fechaInf, $idCliente, $idArea, $idConsultor, $idProyecto);

		echo json_encode($result);
	}

	function retrieveSecondaryTable($fechaSup, $fechaInf, $idCliente, $idArea, $idConsultor, $idProyecto){
		$querySecondaryTareas = "SELECT 
								    count(*) AS total,
								    R.`fase` AS fase,
								    R.`nombre` AS nombre,
								    concat(time_format(sec_to_time(sum(time_to_sec(R.`tiempoRealGerente`))), '%H:%i'), ':00') AS tiempoReal
								FROM (
									SELECT 
									    cf.`nombre` AS fase,
									    cu.`nombre` AS nombre,
									    ct.`tiempoRealGerente` AS tiempoRealGerente
									FROM
									    `cattarea` AS ct
									    INNER JOIN `catfase` cf ON cf.`id` = ct.`idFase`
									    INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
									    INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
									WHERE
										ct.`idEstado` = 3
										AND ct.`activo` = 1
										AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
									";

		$this->processQueryWithFilters($querySecondaryTareas, $idCliente, $idArea, $idConsultor, $idProyecto);

		$querySecondaryTareas .=" UNION ALL
									SELECT 
									    cf.`nombre` AS fase,
									    cu.`nombre` AS nombre,
									    ce.`tiempoRealGerente` AS tiempoRealGerente
									FROM
										`caterror` AS ce
									    INNER JOIN `cattarea` AS ct ON ct.`id`=ce.`idTareaOrigen` 
									    INNER JOIN `catfase` cf ON cf.`id` = ct.`idFase`
									    INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
									    INNER JOIN `catproyecto` cp ON cp.`id` = ct.`idProyecto`
									WHERE
										ct.`idEstado` = 3
										AND ce.`activo` = 1
										AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
								";

		$this->processQueryWithFilters($querySecondaryTareas, $idCliente, $idArea, $idConsultor, $idProyecto);

		$querySecondaryTareas .=") R
								GROUP BY
									fase, nombre

								ORDER BY
									nombre
								";

		return $this->db->query($querySecondaryTareas)->result();	
	}

	function retrievePrimaryTable($fechaSup, $fechaInf, $idCliente, $idArea, $idConsultor, $idProyecto){
		$queryTareas = "SELECT 
						R.*,
						R.`nombreArea` nombreArea,
						R.`nombreCliente` nombreCliente,
						R.`nombreProyecto` nombreProyecto,
						R.`nombreFase` nombreFase,
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
							AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'";

		$this->processQueryWithFilters($queryTareas, $idCliente, $idArea, $idConsultor, $idProyecto);
		$queryTareas .= " UNION ALL

						SELECT 
							cu.*,
							ca.`nombre` nombreArea,
							ccli.`nombre` nombreCliente,
							cp.`nombre` nombreProyecto,
							cf.`nombre` nombreFase,
							ce.`tiempoEstimado` tiempoEstimado,
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
							AND ce.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'";

		$this->processQueryWithFilters($queryTareas, $idCliente, $idArea, $idConsultor, $idProyecto);
		$queryTareas .= ") R

					ORDER BY
						nombre
					";

		return $this->db->query($queryTareas)->result();
	}

	function onRetrieveGlobalDetail(){
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

		//Cálculos para tabla de espectativas
			//Obteniendo a los consultores correspondientes al área y consultor seleccionado, si los hay
		$queryConsultores = "SELECT 
								cu.*, 
								0 as expectedTime,
								IF(ISNULL(TiempoRealTbl.`tiempoReal`),'00:00:00',TiempoRealTbl.`tiempoReal`) tiempoReal
							FROM 
								`catusuario` cu
								LEFT JOIN (
									SELECT
										TareasTbl.`id` idConsultor,
										TareasTbl.`nombre` nombre,
										concat(time_format(sec_to_time(sum(time_to_sec(TareasTbl.`tiempoRealGerente`))), '%H:%i'),':00') tiempoReal
									FROM 
										(
											SELECT
												cu.`id` AS id,
											    cu.`nombre` AS nombre,
											    ct.`tiempoRealGerente` AS tiempoRealGerente
											FROM
											    `cattarea` AS ct
											    INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
											WHERE
												ct.`idEstado` = 3
												AND ct.`activo` = 1
												AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
											
											UNION ALL

											SELECT
												cu.`id` AS id,
											    cu.`nombre` AS nombre,
											    ce.`tiempoRealGerente` AS tiempoRealGerente
											FROM
												`caterror` AS ce
											    INNER JOIN `cattarea` AS ct ON ct.`id`=ce.`idTareaOrigen` 
											    INNER JOIN `catusuario` cu ON cu.`id` = ct.`idResponsable`
											WHERE
												ct.`idEstado` = 3
												AND ce.`activo` = 1
												AND ct.`creacion` BETWEEN '".$fechaInf."' AND '".$fechaSup."'
										) TareasTbl
									GROUP BY
										idConsultor, nombre
								) TiempoRealTbl ON TiempoRealTbl.`idConsultor` = cu.`id` 
							WHERE
								1 = 1";

		if($idArea != -1)
			$queryConsultores = $queryConsultores.' AND cu.`idArea` = '.$idArea;

		if($idConsultor != -1)
			$queryConsultores = $queryConsultores.' AND cu.`id` = '.$idConsultor;

		$resultConsultores = $this->db->query($queryConsultores)->result();
			//Recorrer días entre fechas indicadas para hacer el recuento de horas esperadas entre semana y el Viernes
		$result['hopeTimeNoWeekend'] = 0;
		$result['hopeTimeWeekend'] = 0;

		$fechaSupObj = new DateTime($fechaSup);
		$fechaInfObj = new DateTime($fechaInf);
		$interval = new DateInterval('P1D');
		$fridayDates = array();
		$noFridayDates = array();

			//Ciclo de comprobación de día de la semana
		do{
			$representation = $fechaInfObj->format('Y-m-d');

			if( isDay($representation, array(1,2,3,4)) ) array_push($noFridayDates, $representation);
			else if( isDay($representation, 5) ) array_push($fridayDates, $representation);

			foreach($resultConsultores as $consultor){
				if( isDay($representation, 1) ){
					$result['hopeTimeNoWeekend'] += $consultor->horasLunes;
					$consultor->expectedTime += $consultor->horasLunes;
				}else if( isDay($representation, 2) ){
					$result['hopeTimeNoWeekend'] += $consultor->horasMartes;
					$consultor->expectedTime += $consultor->horasMartes;
				}else if( isDay($representation, 3) ){
					$result['hopeTimeNoWeekend'] += $consultor->horasMiercoles;
					$consultor->expectedTime += $consultor->horasMiercoles;
				}else if( isDay($representation, 4) ){
					$result['hopeTimeNoWeekend'] += $consultor->horasJueves;
					$consultor->expectedTime += $consultor->horasJueves;
				}else if( isDay($representation, 5) ){
					$result['hopeTimeWeekend'] += $consultor->horasViernes;
					$consultor->expectedTime += $consultor->horasViernes;
				}
			}

			$fechaInfObj->add($interval);
		}while( !$fechaInfObj->diff($fechaSupObj)->invert );

			//Ciclo de generación de tiempo diferencia entre tiempo real y tiempo esperado
		foreach($resultConsultores as $consultor){
			$expectedTime = new DateTime(($consultor->expectedTime).':00:00');
			$tiempoReal = new DateTime($consultor->tiempoReal);
			$dieTime = $expectedTime->diff($tiempoReal);
			$consultor->dieTime = $dieTime->format('%H:%i:%s');
		}

			//Totalizaciones
		$result['consultors'] = $resultConsultores;
		$result['numberConsultors'] = count($resultConsultores);
		$result['numberDaysNoWeekend'] = count($noFridayDates);
		$result['numberDaysWeekend'] = count($fridayDates);
		$result['totalDaysInterval'] = $result['numberDaysNoWeekend'] + $result['numberDaysWeekend'];
		$result['totalHopeTime'] = $result['hopeTimeWeekend'] + $result['hopeTimeNoWeekend'];

		echo json_encode($result);
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