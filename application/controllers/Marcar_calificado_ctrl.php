<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Marcar_calificado_ctrl extends CI_Controller {
	public function traerTarea($idTarea){
		$this->load->model('Tarea');
		$data['cTarea'] = $this->Tarea->traer($idTarea);

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Marcar_calificado_vw',$data);		
	}

	public function traerRetrabajo($idRetrabajo){
		$this->load->model('Retrabajo');
		$data['cRetrabajo'] = $this->Retrabajo->traer($idRetrabajo);

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Marcar_calificado_vw',$data);
	}

	public function actualizarTarea($id, $tipo){
		$this->load->model('Tarea');
		$this->load->model('Retrabajo');

		if($tipo == 'Tarea'){
			$data = $this->input->post();
			$tarea = $this->Tarea->traer($id);
			$action = $data['action'];

			$info['tiempo'] = $data['tiempo'];							//Es el tiempo real apreciado por el Gerente
			$info['comentarioGerente'] = $data['comentarioGerente'];

			if($action == 'Incorrecto'){
				$info['idEstado'] = 3;
				$info['calificacion'] = 0;
				$this->Tarea->update($id, $info);

				$infoRetrabajo['idEstado'] = 1;
				$infoRetrabajo['idTareaOrigen'] = $id;
				$infoRetrabajo['tiempoEstimado'] = $tarea->tiempoEstimado;
				$infoRetrabajo['descripcion'] = $tarea->descripcion;

				$this->Retrabajo->insert($infoRetrabajo);
			}else if($action == 'Correcto'){
				$info['idEstado'] = 3;
				$info['calificacion'] = 1;
				$this->Tarea->update($id, $info);
			}
		}else if($tipo == 'Retrabajo'){
			$data = $this->input->post();
			$action = $data['action'];

			$info['tiempo'] = $data['tiempo'];
			$info['comentarioGerente'] = $data['comentarioGerente'];

			if($action == 'Incorrecto'){
				$info['idEstado'] = 3;
				$info['calificacion'] = 1;
				$this->Retrabajo->update($id,$info);

				// Obtener la tarea origen
				$retrabajo = $this->Retrabajo->traer($id);
				$tareaOrigen = $this->Tarea->traer($retrabajo->idTareaOrigen);
				$idOrigen = $tareaOrigen->id;

				$infoRetrabajo['idEstado'] = 1;
				$infoRetrabajo['idTareaOrigen'] = $idOrigen;
				$infoRetrabajo['tiempoEstimado'] = $tareaOrigen->tiempoEstimado;
				$infoRetrabajo['descripcion'] = $tareaOrigen->descripcion;

				$this->Retrabajo->insert($infoRetrabajo);
			}else if($action == 'Correcto'){
				$info['idEstado'] = 3;
				$info['calificacion'] = 1;
				$this->Retrabajo->update($id,$info);
			}
		}

		redirect(base_url().'index.php/Listar_tareas_calificar_ctrl');		//Falta la vista y el controlador de esto
	}
}
?>