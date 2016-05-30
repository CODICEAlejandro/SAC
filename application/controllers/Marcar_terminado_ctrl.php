<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Marcar_terminado_ctrl extends CI_Controller {
	public function traerTarea($idTarea){
		checkSession();

		$this->load->model('Tarea');
		$data['cTarea'] = $this->Tarea->traer($idTarea);

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Marcar_terminado_vw',$data);
	}

	public function traerRetrabajo($idRetrabajo){
		checkSession();

		$this->load->model('Retrabajo');
		$data['cRetrabajo'] = $this->Retrabajo->traer($idRetrabajo);
		$data['historial'] = $this->Retrabajo->traerHistorialAsociado($idRetrabajo);

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view('Marcar_terminado_vw',$data);
	}

	public function actualizarTarea($id){
		checkSession();

		$this->load->model('Tarea');
		$data = $this->input->post();

		if(empty($_FILES['archivo']) || $_FILES['archivo']['size']==0){
			unset($_FILES['archivo']);
		}else{
			$archivoNm = $this->upload('archivo')['uniqueName'];
			$data['archivo'] = $archivoNm;
		}

		$this->Tarea->update($id,$data);
		redirect(base_url().'index.php/Listar_tareas_ctrl');
	}

	public function actualizarRetrabajo($id){
		checkSession();

		$this->load->model('Retrabajo');
		$data = $this->input->post();

		if(empty($_FILES['archivo']) || $_FILES['archivo']['size']==0){
			unset($_FILES['archivo']);
		}else{
			$archivoNm = $this->upload('archivo')['uniqueName'];
			$data['archivo'] = $archivoNm;
		}

		$this->Retrabajo->update($id,$data);
		redirect(base_url().'index.php/Listar_tareas_ctrl');
	}

	//Sube los archivos indicados al servidor 
	//nombre del input con el archivo a subir => boolean
	public function upload($nameField){
		checkSession();

		//Arreglo de retorno
		$result = array();
		
		//Mensajes de estado
		$messFail = '/ERROR_ON_UPLOAD';
		$messSuccess = '/SUCCESS_ON_UPLOAD';

		//Variables de configuración
		$data['upload_path'] = base_url().'img';
		
		//Asignación inteligente de variables de estado
		//$config['file_name'] = microtime();
		//$config['upload_path'] = (empty($data['upload_path']))? base_url(): $data['upload_path'];
		$config['upload_path'] = "./img";
		$config['allowed_types'] = (empty($data['allowed_types']))? '*': $data['allowed_types'];
		$config['max_size'] = (empty($data['max_size']))? 'None': $data['max_size'];
		$config['max_width'] = (empty($data['max_width']))? 'None': $data['max_width'];
		$config['max_height'] = (empty($data['max_height']))? 'None': $data['max_height'];

		//Carga de la libreria con variables de configuración corrrespondientes
		$this->load->library('upload',$config);
		
		//Subida de imagen al servidor y asignación de variables para retorno
		if(!$this->upload->do_upload($nameField)){
			$result['status_message'] = $messFail;
			$result['error_message'] = $this->upload->display_errors();
		}else{
			$result['status_message'] = $messSuccess;
		}

		//Genera un nombre único para el archivo subido y lo renombra
		$parts = explode(".",$this->upload->data()['file_name']);
		$uniqueName = "";
		$uniqueRoute = $this->upload->data()['file_path'];

		for ($k=0, $length = count($parts); $k < $length; $k++) {
			if($k == ($length-1)) 
				$uniqueName .= microtime().'.'.$parts[$k];
			else{
				//$uniqueName .= $parts[$k];
				$uniqueName .= rand(0,9);
			}
		}

		//Suprime espacios en blanco de nombre único
		$uniqueName = preg_replace('/\s+/', '', $uniqueName);

		$uniqueRoute .= $uniqueName;
		
		rename($this->upload->data()['file_path'].$this->upload->data()['file_name'], $uniqueRoute);
		$result['uniqueName'] = $uniqueName;

		return $result;
	}
}
?>