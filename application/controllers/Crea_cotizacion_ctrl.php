<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Crea_cotizacion_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Cotizacion");
		$this->load->model("DireccionFiscal");
		$this->load->model("Concepto");
		$this->load->model("Usuario");
		$this->load->model("Cliente");
	}

	public function index(){
		$data['direccionesFiscales'] = $this->DireccionFiscal->traerTodo();
		$data['usuarios'] = $this->Usuario->traerTodo();
		$data['tiposConcepto'] = $this->db->get("cattipoconcepto")->result();
		$data['periodoRecurrencia'] = $this->db->get("catperiodorecurrencia")->result();
		$data['clientes'] = $this->Cliente->traerTodo();

		$data['menu'] = $this->load->view("Menu_principal", null, true);
		$this->load->view("Crea_cotizacion_vw", $data);
	}

	public function guardarNuevaCotizacion(){
		$response['data'] = array();
		$response['status'] = 'ERROR';
		$valorUnitario = array();
		$importe = array();
		$cantidad = array();
		$unidadMedida = array();
		$tipo = array();
		$recurrencia = array();
		$descripcion = array();
		$referencia = array();
		$nota = array();

		$data = $this->input->post();
		$data['fechaJuntaArranque'] = $data['fechaJuntaArranqueAlt'];
		$data['inicioProyecto'] = $data['inicioProyectoAlt'];
		$data['finProyecto'] = $data['finProyectoAlt'];
		$data['fechaVenta'] = $data['fechaVentaAlt'];

		unset($data['fechaJuntaArranqueAlt']);
		unset($data['inicioProyectoAlt']);
		unset($data['finProyectoAlt']);
		unset($data['fechaVentaAlt']);

		if(
			isset($data['valorUnitarioConcepto'])
			&& isset($data['importeConcepto'])
			&& isset($data['cantidadConcepto'])
			&& isset($data['unidadMedidaConcepto'])
			&& isset($data['tipoConcepto'])
			&& isset($data['recurrenciaConcepto'])
			&& isset($data['descripcionConcepto'])
			&& isset($data['referenciaConcepto'])
			&& isset($data['notaConcepto'])
			&& isset($data['periodo'])
		){

			$valorUnitario = $data['valorUnitarioConcepto'];
			$importe = $data['importeConcepto'];
			$cantidad = $data['cantidadConcepto'];
			$unidadMedida = $data['unidadMedidaConcepto'];
			$tipo = $data['tipoConcepto'];
			$recurrencia = $data['recurrenciaConcepto'];
			$descripcion = $data['descripcionConcepto'];
			$referencia = $data['referenciaConcepto'];
			$nota = $data['notaConcepto'];
			$periodoRecurrencia = $data['periodo'];

			unset($data['valorUnitarioConcepto']);
			unset($data['importeConcepto']);
			unset($data['cantidadConcepto']);
			unset($data['unidadMedidaConcepto']);
			unset($data['tipoConcepto']);
			unset($data['recurrenciaConcepto']);
			unset($data['descripcionConcepto']);
			unset($data['referenciaConcepto']);
			unset($data['notaConcepto']);
			unset($data['periodo']);

		}

		$idCotizacion = $this->Cotizacion->insertar($data);

		//Datos de conceptos
		//Construcción de los conceptos
		$conceptos = array();

		if(
			( count($valorUnitario) == count($importe) )
			&& ( count($importe) == count($cantidad) )
			&& ( count($cantidad) == count($unidadMedida) )
			&& ( count($unidadMedida) == count($tipo) )
			&& ( count($tipo) == count($recurrencia) )
			&& ( count($recurrencia) == count($descripcion) )
			&& ( count($descripcion) == count($referencia) )
			&& ( count($referencia) == count($nota) )
			&& ( count($periodoRecurrencia) == count($nota) )
		){
			$n = count($valorUnitario);

			for($k = 0; $k < $n; $k++){
				$c = new $this->Concepto();

				$c->table = "concepto_cotizacion";
				$c->valorUnitario = $valorUnitario[$k];
				$c->importe = $importe[$k];
				$c->cantidad = $cantidad[$k];
				$c->unidadDeMedida = $unidadMedida[$k];
				$c->idTipoConcepto = $tipo[$k];
				$c->recurrencia = ($recurrencia[$k] == "1")? 1 : 0;
				$c->descripcion = $descripcion[$k];
				$c->referencia = $referencia[$k];
				$c->nota = $nota[$k];
				$c->idCotizacion = $idCotizacion;

				if($periodoRecurrencia[$k] == -1)
					$c->idPeriodoRecurrencia = NULL;
				else
					$c->idPeriodoRecurrencia = $periodoRecurrencia[$k];

				$conceptos[$k] = $c;
				$c->save();
			}
		}

		header("Location: ".base_url().'index.php/Crea_cotizacion_ctrl');
	}

	public function traerDireccionesFiscales($id){
			if($id == -1){
				echo json_encode($this->DireccionFiscal->traerTodo());
			}else{
				echo json_encode($this->DireccionFiscal->traerAsociadas($id));
			}
	}

	public function actualizarCotizacion($id){
		$response['data'] = array();
		$response['status'] = 'ERROR';

		$data = $this->input->post();

		if($this->Cotizacion->actualizar($id, $data)) $response['status'] = "OK";

		echo json_encode($response);		
	}
}