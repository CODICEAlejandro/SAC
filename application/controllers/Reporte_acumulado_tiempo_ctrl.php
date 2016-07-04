<?php

class Reporte_acumulado_tiempo_ctrl extends CI_Controller {
	public function __contruct(){
		parent::__contruct();
	}

	public function index(){
		checkSession();
		$data['users'] = array();

		$data['menu'] = $this->load->view('Menu_principal',null,false);
		$this->load->view('Reporte_acumulado_tiempo_vw',$data);
	}

	public function doResults($condition=''){
		checkSession();
		$this->load->model('Usuario');
		$result['users'] = $this->Usuario->traerTodo();

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
		$tiempo['tiempoEstimado'] = $this->Estadistica->count_time_field('cattarea','tiempoEstimado','idResponsable = '.$user->id.' '.$condition);
		$tiempo['tiempoReal'] = $this->Estadistica->count_time_field('cattarea','tiempoRealGerente','idResponsable = '.$user->id.' '.$condition);

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

		$dateDesde = (isset($post['dateDesde']))? explode('/', $post['dateDesde']): array('00','00','0000');
		$dateHasta = (isset($post['dateHasta']))? explode('/', $post['dateHasta']): array('00','00','0000');

		$dayDesde = htmlentities($dateDesde[0]);
		$monthDesde = htmlentities($dateDesde[1]);
		$yearDesde = htmlentities($dateDesde[2]);

		$dayHasta = htmlentities($dateHasta[0]);
		$monthHasta = htmlentities($dateHasta[1]);
		$yearHasta = htmlentities($dateHasta[2]);

		$dateDesde = $yearDesde.'-'.$monthDesde.'-'.$dayDesde;
		$dateHasta = $yearHasta.'-'.$monthHasta.'-'.$dayHasta;

		$condition = "AND `creacion` BETWEEN '".$dateDesde."' AND DATE_ADD('".$dateHasta."', INTERVAL 1 DAY)";

		$result = $this->formatString($this->doResults($condition)['users']);

		echo $result;
	}
}

?>