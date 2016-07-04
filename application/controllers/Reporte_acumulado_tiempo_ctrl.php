<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_acumulado_tiempo_ctrl extends CI_Controller {
	public function __contruct(){
		parent::__contruct();
	}

	public function index(){
		checkSession();
		$data['users'] = array();

		$this->load->model('Proyecto');
		$data['clients'] = $this->Proyecto->traer_cp();

		$data['menu'] = $this->load->view('Menu_principal',null,false);
		$this->load->view('Reporte_acumulado_tiempo_vw',$data);
	}

	public function doResults($condition=''){
		checkSession();
		$this->load->model('Usuario');
		$result['users'] = $this->Usuario->traerTodo();
		echo "POLLO";
		foreach($result['users'] as $user){
			$cTiempo = $this->getTimes($user,$condition);
			$user->sumaTiempoReal = $cTiempo['tiempoReal'];
			$user->sumaTiempoEstimado = $cTiempo['tiempoEstimado'];
		}

		return $result;
	}

	// Obtiene la suma de tiempos de un usuario
	// User, condition{'AND ... } -> tiempo['tiempoReal','tiempoEstimado']
	public function getTimes($user, $condition=''){
		$this->load->model('Estadistica');
		$tiempoEstimadoTarea = $this->Estadistica->count_time_field('cattarea AS ct','ct.tiempoEstimado','ct.idResponsable = '.$user->id.' '.$condition);
		$tiempoRealTarea = $this->Estadistica->count_time_field('cattarea AS ct','ct.tiempoRealGerente','ct.idResponsable = '.$user->id.' '.$condition);

		$tiempoEstimadoError = $this->Estadistica->count_time_field('cattarea AS ct, caterror AS ce',
																	'ce.tiempoEstimado',
																	'ct.idResponsable = '.$user->id.' '.
																	'AND ce.idTareaOrigen = ct.id '
																	.$condition);
		$tiempoRealError = $this->Estadistica->count_time_field('cattarea AS ct, caterror AS ce',
																'ce.tiempoRealGerente',
																'ct.idResponsable = '.$user->id.' '.
																'AND ce.idTareaOrigen = ct.id '
																.$condition);

		$tiempoEstimadoTarea = explode(':',$tiempoEstimadoTarea);
		$tiempoRealTarea = explode(':',$tiempoRealTarea);
		$tiempoEstimadoError = explode(':',$tiempoEstimadoError);
		$tiempoRealError = explode(':',$tiempoRealError);

		$horasEstimadasTarea = ((int) $tiempoEstimadoTarea[0]) + ((int) $tiempoEstimadoError[0]);
		$minutosEstimadosTarea = ((int) $tiempoEstimadoTarea[1]) + ((int) $tiempoEstimadoError[1]);
		
		$horasRealesTarea = ((int) $tiempoRealTarea[0]) + ((int) $tiempoRealError[0]);
		$minutosRealesTarea = ((int) $tiempoRealTarea[1]) + ((int) $tiempoRealError[1]);

		$tiempo['tiempoEstimado'] = (($horasEstimadasTarea<10)? '0'.$horasEstimadasTarea: $horasEstimadasTarea).':'.
									(($minutosEstimadosTarea<10)? '0'.$minutosEstimadosTarea: $minutosEstimadosTarea);
		$tiempo['tiempoReal'] = (($horasRealesTarea<10)? '0'.$horasRealesTarea: $horasRealesTarea).':'.
								(($minutosRealesTarea<10)? '0'.$minutosRealesTarea: $minutosRealesTarea);

		return $tiempo;
	}

	public function formatString($data){
		checkSession();
		$result = '';

		if(is_array($data)){
			foreach($data as $user){
				$result .= '<tr>
								<td>'.$user->nombre.'</td>
								<td>'.$user->sumaTiempoReal.'</td>
								<td>'.$user->sumaTiempoEstimado.'</td>
							</tr>';
			}
		}else{
			$result = '<tr>NO INFO</tr>';
		}

		return $result;
	}

	public function refreshTimes(){
		checkSession();
		$post = $this->input->post();

		$dateDesde = (isset($post['dateDesde']) && !empty($post['dateDesde']))? explode('/', $post['dateDesde']): array('00','00','0000');
		$dateHasta = (isset($post['dateHasta']) && !empty($post['dateHasta']))? explode('/', $post['dateHasta']): array('00','00','0000');

		if(count($dateDesde) != 3) $dateDesde = array('00','00','0000');
		if(count($dateHasta) != 3) $dateHasta = array('00','00','0000');

		$dayDesde = htmlentities($dateDesde[0], ENT_QUOTES, 'UTF-8');
		$monthDesde = htmlentities($dateDesde[1], ENT_QUOTES, 'UTF-8');
		$yearDesde = htmlentities($dateDesde[2], ENT_QUOTES, 'UTF-8');

		$dayHasta = htmlentities($dateHasta[0], ENT_QUOTES, 'UTF-8');
		$monthHasta = htmlentities($dateHasta[1], ENT_QUOTES, 'UTF-8');
		$yearHasta = htmlentities($dateHasta[2], ENT_QUOTES, 'UTF-8');

		$dateDesde = $yearDesde.'-'.$monthDesde.'-'.$dayDesde;
		$dateHasta = $yearHasta.'-'.$monthHasta.'-'.$dayHasta;

		$condition = "AND ct.creacion BETWEEN '".$dateDesde."' AND DATE_ADD('".$dateHasta."', INTERVAL 1 DAY)";

		//Pregunta si se debe filtrar por proyecto
		if(isset($post['isProyectoFilterActive']) && $post['isProyectoFilterActive']=='S'){
			$proyectoFilter = htmlentities($post['proyectoFilter'], ENT_QUOTES, 'UTF-8');
			$condition .= " AND idProyecto = '".$proyectoFilter."'";
		}

		$result = $this->formatString($this->doResults($condition)['users']);

		echo $result;
	}
}

?>