<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_ctrl extends CI_Controller {
	public function load_vw(){
		$this->session->sess_destroy();	

		$this->db->order_by("correo", "ASC");
		$correos = $this->db->get('catusuario')->result();
		$data['correos'] = $correos;

		$this->load->view('index',$data);	
	}

	public function index(){
		$this->ingresar();
		if($this->session->userdata('user_active')){
			if ($this->session->userdata('tipo') == 0) {
				redirect(base_url().'index.php/Listar_proyectos_ctrl');
			}else if($this->session->userdata('tipo') == 1){
				redirect(base_url().'index.php/Listar_tareas_calificar_ctrl/listarGerente');				
			}else if($this->session->userdata('tipo') == 2){
				redirect(base_url().'index.php/Listar_proyectos_ctrl');				
			}
		}else{
			//$this->load->view('index');
			$this->load_vw();
		}
	}

	public function ingresar(){
		$this->load->model('login_mdl');
		$data = $this->input->post();

		if(($this->login_mdl->count_usuarios_up($data))==1){
			$result = $this->login_mdl->get($data);
			$user = $result['data'];

			foreach($user as $key=>$value)
				$this->session->set_userdata($key,$value);

			$this->session->set_userdata('user_active',true);

			//Define llave global correspondiente al candado asignado al rol del usuario
			if($this->session->userdata('tipo')==0){
				define('NORMAL_USER',true);
			}else if($this->session->userdata('tipo')==1){
				define('MANAGER_USER',true);
			}else if($this->session->userdata('tipo')==2){
				define('ADMIN_USER',true);
			}

			return true;
		}else return false;
	}
}
?>
