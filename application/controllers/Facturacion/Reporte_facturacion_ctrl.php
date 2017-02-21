<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_facturacion_ctrl extends CI_Controller {
	public $suma_roja;
	public $suma_verde;
	public $suma_superverde;

	public function __construct(){
		parent::__construct();

		$query_suma_metas = "select sum(roja) roja, sum(verde) verde, sum(superverde) superverde
							from meta_facturacion";
		$result_suma_metas = $this->db->query($query_suma_metas)->row();
		$this->suma_roja = $result_suma_metas->roja;
		$this->suma_verde = $result_suma_metas->verde;
		$this->suma_superverde = $result_suma_metas->superverde;
	}

	public function index(){
		$data['menu'] = $this->load->view('Menu_principal', null, true);
		$data["resultados"] = $this->calcular();

		$this->load->view("Facturacion/Reporte_facturacion_vw", $data);
	}

	public function calcular(){
		$r["porcentajes_generales"] = $this->calcularPorcentajesGenerales();
		$r["porcentajes_por_tipo"] = $this->calcularPorcentajesPorTipo();
		$r["avance_de_facturacion"] = $this->calcularAvanceDeFacturacion();
		$r["metas_totales"] = array("suma_roja" => $this->suma_roja, "suma_verde" => $this->suma_verde, "suma_superverde" => $this->suma_superverde);

		return $r;
	}

	public function calcularPorcentajesGenerales(){
		$query_total_facturado = "select ifnull(sum(monto), 0) as monto from historico_facturacion";
		$res_total_facturado = $this->db->query($query_total_facturado)->row();
		$total_facturado = $res_total_facturado->monto;

		if($this->suma_roja == 0) $porcentaje_avance_rojo = 100;
		else $porcentaje_avance_rojo = 100 * $total_facturado / $this->suma_roja;
		
		if($this->suma_verde == 0) $porcentaje_avance_verde = 100;
		else $porcentaje_avance_verde = 100 * $total_facturado / $this->suma_verde;
		
		if($this->suma_superverde == 0) $porcentaje_avance_superverde = 100;
		else $porcentaje_avance_superverde = 100 * $total_facturado / $this->suma_superverde;

		return array("par" => $porcentaje_avance_rojo,
					"pav" => $porcentaje_avance_verde,
					"pasv" => $porcentaje_avance_superverde,
					"tf" => $total_facturado);
	}

	public function calcularPorcentajesPorTipo(){
		$suma_actual = 0;
		$suma_nueva = 0;

		$query_suma_actual = "select
								c.tipo_periodo tipo,
								sum(f.monto) monto
							from 
								historico_facturacion f
								inner join catcliente c on c.id = f.id_cliente 
							group by c.tipo_periodo";

		$sumas = $this->db->query($query_suma_actual)->result();

		foreach($sumas as $c){
			if($c->tipo == 1) $suma_actual = $c->monto;
			elseif($c->tipo == 2) $suma_nueva = $c->monto;
		}


		//Traer metas para clientes actuales y para clientes nuevos, separadas, mas no sumadas
		$metas_nuevas = $this->db->query("select * from meta_facturacion where tipo_periodo = 2")->row();
		$metas_actuales = $this->db->query("select * from meta_facturacion where tipo_periodo = 1")->row();

		// Para clientes actuales (tipo = 1)
		// Para clientes nuevos (tipo = 2)
		if($this->suma_roja == 0){
			$porcentaje_avance_rojo_t1 = 100;
			$porcentaje_avance_rojo_t2 = 100;
		}else{
			$porcentaje_avance_rojo_t1 = 100 * $suma_actual / $metas_actuales->roja;
			$porcentaje_avance_rojo_t2 = 100 * $suma_nueva / $metas_nuevas->roja;
		}
		
		if($this->suma_verde == 0){
			$porcentaje_avance_verde_t1 = 100;
			$porcentaje_avance_verde_t2 = 100;
		}else{
			$porcentaje_avance_verde_t1 = 100 * $suma_actual / $metas_actuales->verde;
			$porcentaje_avance_verde_t2 = 100 * $suma_nueva / $metas_nuevas->verde;
		}

		if($this->suma_superverde == 0){
			$porcentaje_avance_superverde_t1 = 100;
			$porcentaje_avance_superverde_t2 = 100;
		}else{
			$porcentaje_avance_superverde_t1 = 100 * $suma_actual / $metas_actuales->superverde;
			$porcentaje_avance_superverde_t2 = 100 * $suma_nueva / $metas_nuevas->superverde;
		}


		return array(
					"par_t1" => $porcentaje_avance_rojo_t1,
					"pav_t1" => $porcentaje_avance_verde_t1,
					"pasv_t1" => $porcentaje_avance_superverde_t1,
					"sa" => $suma_actual,
					"par_t2" => $porcentaje_avance_rojo_t2,
					"pav_t2" => $porcentaje_avance_verde_t2,
					"pasv_t2" => $porcentaje_avance_superverde_t2,
					"sn" => $suma_nueva
				);
	}

	public function calcularAvanceDeFacturacion(){
		$suma_nueva_t1 = 0;
		$suma_actual_t1 = 0;
		$suma_nueva_t2 = 0;
		$suma_actual_t2 = 0;

		$query_monto_nuevo = "select 
								sum(f.monto) monto,
								c.tipo_periodo tipo,
								f.es_nuevo es_nuevo
							from
								historico_facturacion f
								inner join catcliente c on c.id = f.id_cliente
							group by
								c.tipo_periodo,
								f.es_nuevo
							";

		$montos = $this->db->query($query_monto_nuevo)->result();

		foreach($montos as $c){
			if($c->tipo == 1){ 
				if($c->es_nuevo == 1) $suma_nueva_t1 = $c->monto;
				elseif($c->es_nuevo == 0) $suma_actual_t1 = $c->monto;
			}elseif($c->tipo == 2){
				if($c->es_nuevo == 1) $suma_nueva_t2 = $c->monto;
				elseif($c->es_nuevo == 0) $suma_actual_t2 = $c->monto;
			}
		}

		$query_sum_cliente = "select 
								sum(pago_seguro) pago_seguro, 
								sum(pago_nuevo) pago_nuevo 
							from catcliente 
							where estadoActivo = 1";
		$sumas_cliente = $this->db->query($query_sum_cliente)->row();
		$pago_seguro = $sumas_cliente->pago_seguro;
		$pago_nuevo = $sumas_cliente->pago_nuevo;

		//Para clientes nuevos (tipo = 2)
		if($pago_nuevo == 0){
			$avance_facturacion_nuevo_t2 = 0;
			$avance_facturacion_actual_t2 = 0;
		}else{
			$avance_facturacion_nuevo_t2 = 100 * $suma_nueva_t2 / $pago_nuevo;
			$avance_facturacion_actual_t2 = 100 * $suma_actual_t2 / $pago_nuevo;
		}


		//Para clientes actuales (tipo = 1)
		if($pago_nuevo == 0){
			$avance_facturacion_nuevo_t1 = 0;
			$avance_facturacion_actual_t1 = 0;
		}else{
			$avance_facturacion_nuevo_t1 = 100 * $suma_nueva_t1 / $pago_seguro;
			$avance_facturacion_actual_t1 = 100 * $suma_actual_t1 / $pago_seguro;
		}

		return array(
					"afn_t1" => $avance_facturacion_nuevo_t1,
					"afa_t1" => $avance_facturacion_actual_t1,
					"afn_t2" => $avance_facturacion_nuevo_t2,
					"afa_t2" => $avance_facturacion_actual_t2,
					"ps" => $pago_seguro,
					"pn" => $pago_nuevo
				);
	}
}

?>