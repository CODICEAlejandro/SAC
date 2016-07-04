<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_tiempos_tareas_ctrl extends CI_Controller {
	public function index(){
		checkSession();
		$data = $this->doResults();
		
		$data["menu"] = $this->load->view("Menu_principal",null,true);
		$this->load->view("Reporte_tiempos_tareas_vw", $data);
	}


	public function doResults(){
		$this->load->model("Estadistica");

		$result['totalTareas'] = ($this->Estadistica->count('cattarea')) + ($this->Estadistica->count('caterror'));
		
		$result['totalPendientes'] = ($this->Estadistica->count_where('cattarea','idEstado = 1')) + ($this->Estadistica->count_where('caterror','idEstado = 1'));
		$result['totalTerminadas'] = ($this->Estadistica->count_where('cattarea','idEstado = 2')) + ($this->Estadistica->count_where('caterror','idEstado = 1'));
		$result['totalCalificadas'] = ($this->Estadistica->count_where('cattarea','idEstado = 3')) + ($this->Estadistica->count_where('caterror','idEstado = 1'));

		$resultAreas = $this->db->get('catarea')->result();
		foreach($resultAreas as $area){
			$area->totalTareas = '';
		}

		$result['totalErrores'] = $this->Estadistica->count('caterror');

		$result['tiempoTotalEstimado'] = $this->Estadistica->count_time_field('cattarea AS ct','ct.tiempoEstimado');
		$result['tiempoTotalReal'] = $this->Estadistica->count_time_field('cattarea AS ct','ct.tiempoRealGerente');

		return $result;
	}
}

?>