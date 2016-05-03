<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_ctrl extends CI_Controller {
	public function load_vw(){
		$this->load->view('index');	
	}

	public function index(){
		$this->ingresar();
		if($this->session->userdata('user_active')){
			redirect(base_url().'index.php/Listar_proyectos_ctrl');
		}else{
			$this->load->view('index');
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

			return true;
		}else return false;
	}
}
?>