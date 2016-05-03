<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listar_tareas_ctrl extends CI_Controller {
	public function index(){
		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		$dataHmw = $this->Tarea->traerAsociadas($this->session->userdata('id'));
		$data['tareas'] = $dataHmw;
		$data['retrabajosEdit'] = $this->Retrabajo->traerAsociadosParaEditar($this->session->userdata('id'));
		$data['retrabajos'] = $this->Retrabajo->traerAsociadosPendientes($this->session->userdata('id'));

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Listar_tareas_vw',$data);
	}

	//Obtiene un arreglo con las tareas asociadas a cada proyecto que a su vez 
	//está asociado con el cliente correspondiente
	// . => {cliente1 => {proy1 => {tarea1,tarea2,...}, proy2 => {...}, ...}, cliente2 => {...}, ... }
	/*public function traerTodo(){
		$total = array();
		$clientes = $this->db->get('catCliente')->result();

		foreach($clientes as $cliente){
			$this->db->where('id =', $cliente->idCliente);
			$total[$cliente->nombre] = $this->db->get('catProyecto')->result();

			foreach($total[$cliente->nombre] as $proyecto){
				$this->db->where('id =', $proyecto->id);
				$total[$proyecto['']]
			}
		}

		return $result;
	}*/
}
?>
