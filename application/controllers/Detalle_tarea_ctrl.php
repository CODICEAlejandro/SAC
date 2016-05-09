<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Detalle_tarea_ctrl extends CI_Controller {
	public function traerTarea($idTarea){
		$this->load->model('Tarea');
		$data['cTarea'] = $this->Tarea->traer($idTarea);

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Detalle_tarea_vw',$data);		
	}

	public function traerRetrabajo($idRetrabajo){
		$this->load->model('Retrabajo');
		$data['cRetrabajo'] = $this->Retrabajo->traer($idRetrabajo);

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Detalle_tarea_vw',$data);
	}

}
?>