<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prueba_pdf_ctrl extends CI_Controller {
	public function index(){		
		
		$idCotizacion = 18;
		$data["cotizacion"] = $this->db->query("SELECT ca.*, DATE_FORMAT(ca.fecha_alta,'%d/%m/%Y') fechaCot, u.id idAccount, u.nombre nombreAccount, u.correo correoAccount, u.telefono telAccount, p.nombre puesto, cli.nombre nombreCliente, con.nombre nombreContacto, con.apellido apellidoContacto    
			FROM cotizacion_account ca  JOIN contacto con ON ca.id_contacto = con.id 
			JOIN catcliente cli ON ca.id_cliente = cli.id 
			JOIN catusuario u ON ca.id_usuario = u.id
			JOIN catpuesto p ON u.idPuesto = p.id
			WHERE ca.id=".$idCotizacion)->row();
		
		$data["alcances"] = $this->db->query("SELECT ac.*, cs.clave clasServicio FROM alcance_cotizacion ac
			JOIN catclasificacion_servicio cs ON ac.id_clasificacion_servicio =  cs.id
			WHERE ac.id_cotizacion_account=".$idCotizacion." ORDER BY orden")->result();

		
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

		/*
		$query_trae_proyectos = "SELECT c.nombre cliente,p.nombre proyecto FROM catcliente c
								 JOIN catproyecto p ON c.id = p.idCliente
								 WHERE  c.estadoActivo=1 AND p.estado=1
								 ORDER BY c.nombre";
		
		$data["proyectos"] = $this->db->query($query_trae_proyectos)->result(); 
		*/
		
		$data['menu'] = $this->load->view('Menu_principal', null, true);

		$this->load->view('Prueba_pdf_vw',$data);
		
	}

	public function generaPDF(){
	
		include_once 'includes/tcpdf/tcpdf.php';


		if (isset($_POST)&& isset($_POST["img64"])) {
			$img64 = $_POST["img64"];

			//$tabla = imagecreatefrompng($img64);
			$pdf = new TCPDF();

			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

			$pdf->SetImageScale(2);
			$pdf->AddPage();
			$pdf->WriteHTML($img64,true,false,true,false,'');
			//$pdf->Image($img64);

			$folder = $_SERVER['DOCUMENT_ROOT']."JOBS/img/";

			$nombre = "pdf_prueba";
			
			while (file_exists($folder.$nombre.".pdf")) {
				$nombre .= rand(0,9);			
			}
			
			$nombre .= ".pdf";

			$pdf->Output($folder.$nombre,'F');

			echo $nombre;
		}
		
	}
}
?>