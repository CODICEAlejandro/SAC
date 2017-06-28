<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

class Actualiza_concepto_cotizacion_ctrl extends CI_Controller {
	public function index(){
		set_time_limit(60);
		$this->load->view("Actualiza_concepto_cotizacion_vw");
		
	}

	public function actualizaRegistros()
	{
		$tiposConcepto = $this->db->query("SELECT cc.id,cc.idTipoConcepto,cc.idClasificacion_servicio FROM concepto_cotizacion cc 
			JOIN cattipoconcepto tc ON cc.idTipoConcepto = tc.id
			WHERE tc.tipo=1 ORDER BY cc.id")->result();

		//print_r($tiposConcepto);
		//print_r($clasificaciones);
		
		for ($i=0, $n=count($tiposConcepto); $i < $n ; $i++) {
			if (($tiposConcepto[$i]->idTipoConcepto)=="") {
			 	$tiposConcepto[$i]->idTipoConcepto = "NULL";
			} 
			if (($tiposConcepto[$i]->idClasificacion_servicio)=="") {
				$tiposConcepto[$i]->idClasificacion_servicio = "NULL";
			}
			//print_r($tiposConcepto);
			
			echo "UPDATE concepto_cotizacion SET idTipoConcepto=".$tiposConcepto[$i]->idClasificacion_servicio.", idClasificacion_servicio=".$tiposConcepto[$i]->idTipoConcepto." WHERE id=".$tiposConcepto[$i]->id.";";
			$this->db->query("UPDATE concepto_cotizacion SET idTipoConcepto=".$tiposConcepto[$i]->idClasificacion_servicio.", idClasificacion_servicio=".$tiposConcepto[$i]->idTipoConcepto." WHERE id=".$tiposConcepto[$i]->id.";");
			
		}
		print_r($tiposConcepto);

		//die("SE REALIZÓ LA OPERACIÓN");
	}
}
?>