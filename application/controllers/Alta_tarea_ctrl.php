<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Alta_tarea_ctrl extends CI_Controller{
	public function load_vw($id){
		checkSession();

		$dataCP = $this->cargarInfo($id);
		$data['cliente'] = $dataCP['cliente'];
		$data['proyecto'] = $dataCP['proyecto'];
		$data['fases'] = $dataCP['fases'];

		$data['menu'] = $this->load->view('Menu_principal', null, true);
		$this->load->view('Alta_tarea_vw',$data);
	}

	public function editarRetrabajo($idRetrabajo){
		checkSession();

		$this->load->model('Retrabajo');

		$data['retrabajo'] = $this->Retrabajo->traer($idRetrabajo);
		$data['historial'] = $this->Retrabajo->traerHistorialAsociado($idRetrabajo);

		$data['cliente'] = $data['retrabajo']->cliente;
		$data['proyecto'] = $data['retrabajo']->proyecto;

		$data['menu'] = $this->load->view('Menu_principal', null, true);		
		$this->load->view('Alta_tarea_vw',$data);
	}

	//Realiza la carga inicial esencial para la página de alta
	//Carga toda la información relativa al proyecto seleccionado y al cliente asociado a éste
	//Carga las fases registradas en la base
	//idProyecto => {'cliente'=>StdClass Object, 'proyecto'=>StdClass Object}
	public function cargarInfo($idProyecto){
		$this->db->order_by('nombre','asc');
		$this->db->where('id =', $idProyecto);
		$format['proyecto'] = $this->db->get('catproyecto')->row();

		$this->db->order_by('nombre','asc');
		$this->db->where('id =', $format['proyecto']->idCliente);
		$format['cliente'] = $this->db->get('catcliente')->row();

		$this->db->order_by('nombre','asc');
		$format['fases'] = $this->db->get('catfase')->result();

		return $format;
	}

	public function generarNuevaTarea($data){
		$idCliente = $data['idCliente'];
		$idResponsable = $data['idResponsable'];
		$idProyecto = $data['idProyecto'];

		$data["stamp"] = $idProyecto.':'.$idCliente.':'.$idResponsable;
		unset($data['idCliente']);

		$this->load->model('Tarea');
		$this->Tarea->insertar($data);
		redirect(base_url().'index.php/Listar_tareas_ctrl');
	}

	public function generarRetrabajoPendiente($id,$data){
		$info['tiempoEstimado'] = $data['tiempoEstimado'];
		$info['descripcion'] = $data['descripcion'];
		$info['idEstado'] = 1;

		$this->load->model('Retrabajo');
		$this->Retrabajo->update($id,$info);
		redirect(base_url().'index.php/Listar_tareas_ctrl');
	}

	public function cancelarNuevaTarea(){
		redirect(base_url().'index.php/Listar_proyectos_ctrl');
	}

	public function driverActividades(){		
		$data = $this->input->post();
		$action = $data['action'];
		unset($data['action']);

		if($action == "Alta"){
			$this->generarNuevaTarea($data);	
		}else if($action == "Cancelar"){
			$this->cancelarNuevaTarea();
		}else if($action == "Alta_retrabajo"){
			$idRetrabajo = $data['idRetrabajo'];
			$this->generarRetrabajoPendiente($idRetrabajo, $data);
		}
	}
}
?>