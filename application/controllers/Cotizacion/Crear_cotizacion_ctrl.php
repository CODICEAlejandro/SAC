<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crear_cotizacion_ctrl extends CI_Controller {

	public function index(){
		$data["menu"] = $this->load->view("Menu_principal", null, true);
		$data["clientes"] = $this->db->query("select * from catcliente where estadoActivo=1 and tipo=0 order by nombre asc")->result();
		$data["forma_pago"] = $this->db->query("select * from cat_tipo_cotizacion order by clave asc")->result();
		$data["servicio_alcance"] = $this->db->query("select * from cattipoconcepto where tipo = 0 order by descripcion asc")->result();
		$data["periodicidad"] = $this->db->query("select id, clave from cat_periodicidad_alcance order by clave asc")->result();
		$this->load->view("Cotizacion/Crear_cotizacion_vw", $data); 
	}

	public function traerContactos(){
		$idCliente = $this->input->post("idCliente");
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');
		$query = "select contacto.*, UCASE(t.descripcion) tipo from contacto join cattipocontacto t on t.id = contacto.idTipoContacto where idPadre = ".$idCliente." order by nombre, apellido";

		echo json_encode($this->db->query($query)->result());
	}

	public function traerClasificaciones(){
		$idServicio = $this->input->post("idServicio");
		$idServicio = htmlentities($idServicio, ENT_QUOTES, 'UTF-8');
		$query = "select * from catclasificacion_servicio where id_servicio = ".$idServicio;

		echo json_encode($this->db->query($query)->result());
	}

	public function subirArchivoAdjunto(){
		if(isset($_FILES) && isset($_FILES["archivo-adjunto"])){
			$folder = "img/";

			$extension = substr($_FILES["archivo-adjunto"]["name"], strrpos($_FILES["archivo-adjunto"]["name"], ".")+1);
			$fileName = $folder."COTADJ_".rand(0, 9).".";

			while(file_exists($fileName)){
				$fileName.rand(0, 9);
			}

			$fileName .= $extension;

			if($fileName && copy($_FILES["archivo-adjunto"]["tmp_name"], $fileName)){
				$type = pathinfo($fileName, PATHINFO_EXTENSION);
				$data = file_get_contents($fileName);
				echo $fileName;
			}else{
				echo "ERROR";
			}
		}else echo "ERROR";
	}
} 

?>