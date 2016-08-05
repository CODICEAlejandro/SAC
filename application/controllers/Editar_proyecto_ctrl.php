<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editar_proyecto_ctrl extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Proyecto');
		$this->load->model('Cliente');
	}

	public function index(){
		$data = $this->cargaInicial();
		$data['menu'] = $this->load->view('Menu_principal', null, true);
		$this->load->view('Editar_proyecto_vw', $data);
	}

	public function cargaInicial(){
		$result['clientes'] = $this->Cliente->traerTodo();
		$result['proyectos'] = $this->Proyecto->traerAsociados_cliente(1);

		return $result;
	}

	public function actualizarProyecto($id, $data){
		foreach($data as $key => $value)
			$data[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');

		if($this->Proyecto->actualizar($data))
			echo "Proyecto actualizado correctamente.";
		else
			echo "Ha ocurrido un error al intentar actualizar el proyecto. Intente de nuevo, por favor.";
	}
}

?>