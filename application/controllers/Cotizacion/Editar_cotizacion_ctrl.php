<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Editar_cotizacion_ctrl extends CI_Controller{

	public function traeCotizacion($idCotizacion){
		

		$data["cotizacion"] = $this->db->query("SELECT * FROM cotizacion_account WHERE id=".$idCotizacion)->row();
		$data["clientes"] = $this->db->query("SELECT id,nombre FROM catcliente where estadoActivo=1 and tipo=0")->result();
		$data["contactos"] = $this->db->query("SELECT DISTINCT c.id,c.nombre,c.apellido, tcon.descripcion tipo_contacto
			FROM contacto c JOIN cattipocontacto tcon ON c.idTipoContacto = tcon.id
			JOIN cotizacion_account ca ON c.id= ca.id_contacto
			WHERE ca.id_cliente=".$data["cotizacion"]->id_cliente)->result();
		$data["forma_pago"] = $this->db->query("SELECT * FROM cat_tipo_cotizacion")->result();
		$data["servicio_alcance"] = $this->db->query("select * from cattipoconcepto where tipo = 0")->result();
		$data["periodicidad"] =$this->db->query("SELECT * FROM cat_periodicidad_alcance")->result();
		$data["alcances"] = $this->db->query("SELECT * FROM alcance_cotizacion 
												WHERE id_cotizacion_account=".$idCotizacion." ORDER BY orden")->result();

		
		for ($i=0, $n=count($data["alcances"]); $i < $n; $i++) {
			$id_alcance = $data["alcances"][$i]->id;
			$descripciones = $this->db->query("SELECT * FROM descripcion_alcance 
												WHERE id_alcance=".$id_alcance)->result();

			$data["alcances"][$i]->descripciones = $descripciones;

			$clasificaciones =$this->db->query("SELECT * FROM catclasificacion_servicio 
				WHERE id_servicio=	(SELECT cs.id_servicio FROM catclasificacion_servicio cs
									JOIN alcance_cotizacion ac ON cs.id = ac.id_clasificacion_servicio
									WHERE ac.id=".$id_alcance.")")->result();

			$data["alcances"][$i]->clasificaciones = $clasificaciones;
			for($j=0,$k=count($clasificaciones);$j<$k;$j++){
				if($data["alcances"][$i]->id_clasificacion_servicio == $clasificaciones[$j]->id){
					$data["alcances"][$i]->tipo_concepto_sel = $clasificaciones[$j]->id_servicio;
				}
			}
		}
		
		if (($data["cotizacion"]->tipo_cotizacion_id)==1) { //Pagos recurrentes
		
			for ($i=0,$n=count($data["alcances"]); $i < $n ; $i++) {
				$id_alcance = $data["alcances"][$i]->id; 
				$pago_recurrente= $this->db->query("SELECT * FROM subtipo_pago_recurrente 
													WHERE id_alcance =".$id_alcance)->row();
				$data["alcances"][$i]->pago_recurrente = $pago_recurrente;
			}

		}elseif(($data["cotizacion"]->tipo_cotizacion_id)==2){ //Pagos fijos

			for ($i=0, $n=count($data["alcances"]); $i < $n; $i++) { 
				$id_alcance = $data["alcances"][$i]->id;
				$parcialidades = $this->db->query("SELECT * FROM subtipo_pago_fijo WHERE id_alcance=".$id_alcance)->result();
				$data["alcances"][$i]->parcialidades = $parcialidades;
			}
		}

		
		$data['menu'] = $this->load->view('Menu_principal',null,true);
		
		$this->load->view("Cotizacion/Editar_cotizacion_vw", $data);
	}

	public function eliminarCotizacion()
	{
		$cotizacion_id = $this->input->post("cotizacion_id");
		$tipo_cotizacion_id = $this->input->post("tipo_cotizacion_id");


		$id_alcances = $this->db->query("SELECT id FROM alcance_cotizacion 
										WHERE id_cotizacion_account = ".$cotizacion_id)->result();

		for ($i=0, $n = count($id_alcances); $i < $n ; $i++) { 
			
			$this->db->query("DELETE FROM descripcion_alcance WHERE id_alcance=".$id_alcances[$i]->id);

			if ($tipo_cotizacion_id == 1) { //Pagos recurrentes
				$this->db->query("DELETE FROM subtipo_pago_recurrente WHERE id_alcance=".$id_alcances[$i]->id);
			}else if ($tipo_cotizacion_id == 2) { //Pagos fijos
				$this->db->query("DELETE FROM subtipo_pago_fijo WHERE id_alcance=".$id_alcances[$i]->id);
			}

			$this->db->query("DELETE FROM alcance_cotizacion WHERE id=".$id_alcances[$i]->id);
		}

		if ($this->db->query("DELETE FROM cotizacion_account WHERE id=".$cotizacion_id)) {
			echo "ÉXITO";
		}else{
			echo "ERROR";
		}
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