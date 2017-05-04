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

		//Se calculará el importe total de acuerdo al tipo de pago
		if (isset($cotizacion["alcances"])) {
			$alcances = $cotizacion["alcances"];
			$fechasFinPagos = array();//Variable paara calcular la máxima fecha de fin 
			for ($i=0, $n=count($alcances); $i < $n; $i++) { 
				$a = $alcances[$i];
				if ($tipoCotizacion==1) { //Pagos recurrentes
					$importeTotal+=$a["montoParcialidad"] * $a["numeroParcialidades"];
					//$mult = $a["fechaFinServicioMult"];
					$period = $a["periodicidad"];
					$query_trae_meses = "SELECT meses FROM cat_periodicidad_alcance WHERE id=".$period;
					$mult = $this->db->query($query_trae_meses)->row();
					$mult = $mult->meses;
					$mult = $mult*$a["numeroParcialidades"];
					$fechaInicio = $a["fechaInicioServicio"];
					$query_calcula_fecha = "SELECT (DATE_ADD('".$fechaInicio."',INTERVAL ".$mult." MONTH)) fecha FROM dual";
					$fechaCalculada = $this->db->query($query_calcula_fecha)->row();
					$fechaCalculada = $fechaCalculada->fecha;
					array_push($fechasFinPagos, $fechaCalculada);
				}
				elseif ($tipoCotizacion==2) { //Pagos fijos
					$importeTotal+=$a["precioTotal"];
				}
				else{
					$importeTotal = 0;
					//Mete en un arreglo las fechas de los alcances para seleccionar la máxima para pagos indefinidos
					array_push($fechasFinPagos, $a["fechaInicioServicio"]);
				}
			}

		}

		//Calculo de la fecha de Fin servicio de cotizacion a través del cálculo de las fechas de fin para pagos recurrentes
		if (($tipoCotizacion==1 || $tipoCotizacion==3) && isset($cotizacion["alcances"])) {
			$fechaFinServicioCotizacion = max($fechasFinPagos);
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

				if ($tipoCotizacion==1) { //Pagos recurrentes
					$period = $a["periodicidad"];
					$query_trae_meses = "SELECT meses FROM cat_periodicidad_alcance WHERE id=".$period;
					$mult = $this->db->query($query_trae_meses)->row();
					$mult = $mult->meses;
					$mult = $mult * $a["numeroParcialidades"];
					//$mult = $a["fechaFinServicioMult"];
					$fechaCalculada = $this->db->query("SELECT (DATE_ADD('".$fechaInicioAlcance."',INTERVAL ".$mult." MONTH)) fecha FROM dual")->row();
					$fechaCalculada=$fechaCalculada->fecha;
					$fechaFinAlcance = $fechaCalculada;
					$montoParcial = $a["montoParcialidad"];
					$montoTotal = $montoParcial * $a["numeroParcialidades"];

				}else { //Pagos fijos e indefinidos
					if (isset($a["parcialidades"])) {
						$p = $a["parcialidades"];

						if (count($p)>0) {
							$fechasParcialidades = array();
							for ($j=0, $m=count($p); $j < $m ; $j++) {
								$parcialidad = $p[$j]; 
								array_push($fechasParcialidades, $parcialidad["fecha"]);
							}
							$fechaFinAlcance = max($fechasParcialidades);
							$montoTotal= $a["precioTotal"];
						}
					}else{
						//Si se trata de pagos fijos pero no hay parcialidades:
							//La fecha de fin del alcance se toma como la de Inicio del Alcance
							//El monto total es cero
						$fechaFinAlcance = $fechaInicioAlcance;
						$montoTotal= 0;
					}
					
				}
				
				$query_alcance = "insert into alcance_cotizacion(orden,id_clasificacion_servicio, id_cotizacion_account, titulo, entregables, requerimientos, fecha_inicio_servicio, fecha_fin_servicio, monto_total)
					values(".$orden.",".$servicio.",".$idCotizacion.",'".$titulo."','".$entregables."','".$requerimientos."','".$fechaInicioAlcance."','".$fechaFinAlcance."',".$montoTotal.")";



				$this->db->query($query_alcance);

				//Se insertan las descripciones por alcance
				$query_id_alcance = "SELECT max(id) id_alcance FROM alcance_cotizacion";
				$id_alcance = $this->db->query($query_id_alcance)->row();
				$id_alcance = $id_alcance->id_alcance;
				

				//Se insertan los datos en las tablas subtipos
				if ($tipoCotizacion==1) { //Se inserta en subtipo_pago_recurrente
					$numParcialidades= $a["numeroParcialidades"];

					$this->db->query("INSERT INTO subtipo_pago_recurrente (id_alcance,id_periodicidad,numero_parcialidades,monto_parcialidad) VALUES(".$id_alcance.",".$period.",".$numParcialidades.",".$montoParcial.")");
				}
				elseif ($tipoCotizacion==2) { //Se inserta en subtipo_pago_fijo
					if (isset($a["parcialidades"])) {
						$p = $a["parcialidades"];

						//Se inserta la parcialidad del anticipo
						$montoAnticipo = $a["montoAnticipo"];
						$porcentajeAnticipo = $a["porcentajeAnticipo"];

						$this->db->query("INSERT INTO subtipo_pago_fijo (id_alcance, concepto, fecha, porcentaje_monto, monto_parcialidad) VALUES(".$id_alcance.",'Anticipo','',".$porcentajeAnticipo.",".$montoAnticipo.")");

						if (count($p)>0) {
							
							for ($j=0, $m=count($p); $j < $m ; $j++) {
								$parcialidad = $p[$j];
								$fechaParcialidad = $parcialidad["fecha"];
								$porcentajeParcialidad = $parcialidad["porcentaje"];
								$concepto = $parcialidad["concepto"];
								$monto = $parcialidad["monto"]; 

								$this->db->query("INSERT INTO subtipo_pago_fijo (id_alcance,concepto,fecha,porcentaje_monto,monto_parcialidad) VALUES(".$id_alcance.",'".$concepto."','".$fechaParcialidad."',".$porcentajeParcialidad.",".$monto.")");
								
							}
						}
					}
					
				}

				//Se insertan las descripciones por alcance
				if (isset($a["descripciones"])) {
					$descripciones = $a["descripciones"];
					for ($i=0, $m=count($descripciones); $i < $m ; $i++) { 
						$d = $descripciones[$i];
						$titulo_desc = $d["titulo"];
						$desc_alcance = $d["descripcion"];

						$query_descripciones = "INSERT INTO descripcion_alcance(id_alcance,titulo,descripcion) VALUES(".$id_alcance.",'".$titulo_desc."','".$desc_alcance."')";

						$this->db->query($query_descripciones);
					}
				}
			}
		}


		//Elegir subtipo
			//Pagos indefinidos
			//Pagos fijos
				//Insetar parcialidades
			//Pagos recurrentes

		echo json_encode("OK");
	}
} 

?>