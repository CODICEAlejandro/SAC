<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alta_conceptos_cotizacion_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->model("Cliente");
		$this->load->model("Usuario");

		$data['clientes'] = $this->db
								 ->query("select * from catcliente where tipo = 1 and estadoActivo = 1 order by nombre asc")
								 ->result();
		$data['usuario'] = $this->db->query("select id, nombre
											from administrador
											order by nombre")->result();
		$data['account'] = $this->db->query("select * from catusuario where idPuesto in (4,5) and activo = 'S' order by nombre asc")->result();
		$data['tipoConcepto'] = $this->db->query("select * from cattipoconcepto")->result();
		$data['unidadMedida'] = $this->db->query("select * from catunidadmedida")->result();
		$data['menu'] = $this->load->view("Menu_principal", null, true);

		$this->load->view("Proveedor/Alta_conceptos_cotizacion_vw", $data);
	}

	public function getRazonesSociales($idCliente){
		$idCliente = htmlentities($idCliente, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					DISTINCT
					df.`id` id,
					df.`razonSocial` razonSocial
				FROM
					`direccionfiscal` df
					INNER JOIN `catcliente` cc ON cc.`id` = df.`idPadre`
				WHERE
					cc.`id` = ".$idCliente."
					AND df.`estadoActivo` = 1
				";

		echo json_encode($this->db->query($query)->result());
	}

	public function getCotizaciones($idRazonSocial){
		$idRazonSocial = html_entity_decode($idRazonSocial, ENT_QUOTES, 'UTF-8');

		$query = "SELECT
					DISTINCT
					co.`id` id,
					co.`folio`
				FROM
					`cotizacion` co
					INNER JOIN `direccionfiscal` df ON df.`id` = co.`idRazonSocial`
				WHERE
					co.`idRazonSocial` = ".$idRazonSocial."
					AND co.`estadoActivo` = 1
				";

		echo json_encode($this->db->query($query)->result());
	}

	public function getClasificaciones(){
		$idServicio = $this->input->post("idServicio");
		$idServicio = htmlentities($idServicio, ENT_QUOTES, 'UTF-8');

		$result = $this->db->query("select * from catclasificacion_servicio where id_servicio =".$idServicio)->result();

		echo json_encode($result);
	}

	public function guardarCotizacion(){
		$post = $this->input->post();

		$id_cliente = htmlentities($post["idCliente"], ENT_QUOTES, 'UTF-8');
		$nota_cotizacion = htmlentities($post["notaCotizacion"], ENT_QUOTES, 'UTF-8');
		$fecha_junta_arranque = htmlentities($post["fechaJuntaArranque"], ENT_QUOTES, 'UTF-8');
		$fecha_inicio_proyecto = htmlentities($post["fechaInicioProyecto"], ENT_QUOTES, 'UTF-8');
		$fecha_fin_proyecto = htmlentities($post["fechaFinProyecto"], ENT_QUOTES, 'UTF-8');
		$fecha_venta = htmlentities($post["fechaVenta"], ENT_QUOTES, 'UTF-8');
		$id_cerrador = htmlentities($post["idCerrador"], ENT_QUOTES, 'UTF-8');
		$account_manager = htmlentities($post["accountManager"], ENT_QUOTES, 'UTF-8');
		$titulo_cotizacion = htmlentities($post["tituloCotizacion"], ENT_QUOTES, 'UTF-8');
		$folio_cotizacion = htmlentities($post["folioCotizacion"], ENT_QUOTES, 'UTF-8');

		$query_cotizacion = "INSERT INTO `cotizacion` 
								(`idCliente`, `folio`, `estadoActivo`, `nota`, 
								`fechaJuntaArranque`, `inicioProyecto`, `finProyecto`, 
								`fechaVenta`, `idCerrador`, `contrato`, 
								`accountManager`, `titulo`)
							VALUES
							(
								".$id_cliente.", 
								'".$folio_cotizacion."',
								1, 
								'".$nota_cotizacion."', 
								'".$fecha_junta_arranque."', 
								'".$fecha_inicio_proyecto."', 
								'".$fecha_fin_proyecto."', 
								'".$fecha_venta."', 
								".$id_cerrador.", 
								1, 
								".$account_manager.", 
								'".$titulo_cotizacion."'
							)";

		$this->db->query($query_cotizacion);
		$id_cotizacion = $this->db->insert_id();

		//Insertar los conceptos con sus fechas de facturación correspondientes
		$conceptos = json_decode($_POST["conceptos"]);

		for($k=0, $n=count($conceptos); $k<$n; $k++){
			$c = $conceptos[$k];

			$monto = $c->importe;
			$total = $c->total;

			$descripcion = $c->descripcion;
			$idTipoConcepto = $c->servicio;
			$idClasificacionConcepto = $c->clasificacion;
			$referencia = $c->referencia;
			$idCotizacion = $id_cotizacion;
			$nota = $c->nota;
			$iva = $c->iva;

			$query_insertar_concepto = "insert into concepto_cotizacion(monto,descripcion,idTipoConcepto,
																		idClasificacion_servicio,referencia,idCotizacion,
																		nota,total,iva)
										values (".$monto.",'".$descripcion."',".$idTipoConcepto.",".$idClasificacionConcepto.",'".$referencia."',
												".$idCotizacion.",'".$nota."',".$total.",".$iva.")";

			$this->db->query($query_insertar_concepto);
			$id_concepto_cotizacion = $this->db->insert_id();

			for($m=0, $numeroFechas = count($c->fechasFactura); $m<$numeroFechas; $m++){
				$f = $c->fechasFactura[$m];

				$importe = $f->importe;
				$referencia = $f->referencia;
				$fecha = $f->fecha;
				$nota = $f->nota;
				$idConceptoCotizacion = $id_concepto_cotizacion;

				$query_insertar_fecha = "insert into fecha_factura (importe, referencia, fecha, nota, idConceptoCotizacion, idEstadoFactura, fecha_final)
										values (".$importe.",'".$referencia."','".$fecha."','".$nota."',".$idConceptoCotizacion.",23, '".$fecha."')";

				$this->db->query($query_insertar_fecha);
			}
		}

		//redirect('/Panel_control_ctrl', 'refresh');
	}
}

?>