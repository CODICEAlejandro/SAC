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

	public function guardarCotizacion(){
		$cotizacion = $this->input->post("cotizacion");

		$importeTotal = 0;	//Es la suma de los montos totales de todos los alcances
		$fechaInicioServicioCotizacion = $cotizacion["fechaInicioServicio"];	//Es la fecha mínima de todos los alcances
		$fechaFinServicioCotizacion = $cotizacion["fechaFinServicio"];	//Es la fecha máxima de todos los alcances

		//Formar la inserción en cotización_account
		$dataDBLastCotizacion = $this->db->query("select ifnull(max(folio),999) maxFolio, ifnull(max(id),1) maxID from cotizacion_account")->row();
		$idCotizacion = ($dataDBLastCotizacion->maxID)+1;
		$folio = (int) ($dataDBLastCotizacion->maxFolio);
		$folio = $folio+1;
		$contacto = $cotizacion["contacto"];
		$cliente = $cotizacion["cliente"];
		$usuario = $_SESSION['id'];				//Extraer de la tabla de cliente
		$tituloCotizacion = $cotizacion["titulo"];
		$statusCotizacion = 1;
		$tipoCotizacion = $cotizacion["formaDePago"];
		$nombreArchivo = $cotizacion["nombreArchivo"];
		$introduccion = $cotizacion["introduccion"];
		$objetivo = $cotizacion["objetivo"];
		$nota = $cotizacion["notas"];

		if($nombreArchivo != "NULL"){
			$nombreArchivo = "'".$nombreArchivo."'";
		}

		//Una vez que tenemos listos los parámetros calculados: importe total, fecha inicio y fecha fin, podemos
		//construir el query dinámico que inserta en cotización_account
		$query_cotizacion = "insert into cotizacion_account (id, folio, id_contacto, id_cliente, id_usuario, titulo, importe_total, fecha_inicio_servicio, fecha_fin_servicio, status_cotizacion_id, tipo_cotizacion_id, nombre_archivo, introduccion, objetivo, nota) 
			values (".$idCotizacion.",'".$folio."',".$contacto.",".$cliente.",".$usuario.",'".$tituloCotizacion."',".$importeTotal.",'".$fechaInicioServicioCotizacion."','".$fechaFinServicioCotizacion."',".$statusCotizacion.",".$tipoCotizacion.",".$nombreArchivo.",'".$introduccion."','".$objetivo."','".$nota."')";

		$this->db->query($query_cotizacion);

		//Insertar los alcances
		//Formas las sentencias de los alcances
		if(isset($cotizacion["alcances"])){
			$alcances = $cotizacion["alcances"];
			for($k=0, $n=count($alcances); $k<$n; $k++){
				$a = $alcances[$k];
				$orden = $a["orden"];
				$servicio = $a["servicio"];
				$clasificacionServicio = $a["clasificacion"];
				$titulo = $a["titulo"];
				$entregables = $a["entregables"];
				$requerimientos = $a["requerimientos"];
				$fechaInicioAlcance = $a["fechaInicioServicio"];

				$query_alcance = "insert into alcance_cotizacion(orden,id_clasificacion_servicio, id_cotizacion_account, titulo, entregables, requerimientos, fecha_inicio_servicio, fecha_fin_servicio, monto_total)
					values(".$orden.",".$servicio.",".$idCotizacion.",'".$titulo."','".$entregables."','".$requerimientos."',
					'".$fechaInicioAlcance."')";
			}
		}

			//Insertar descripciones de alcance actual

			//Elegir subtipo
				//Pagos indefinidos
				//Pagos fijos
					//Insetar parcialidades
				//Pagos recurrentes

		echo json_encode("OK");
	}
} 

?>