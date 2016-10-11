<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','-1');
ini_set('max_execution_time', 30000);
set_time_limit(30000);

class Relacional_FC extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->buildRelationCotizacion_Factura();
		$this->buildRelationConcepto_ConceptoCotizacion();
	}

	public function buildRelationCotizacion_Factura(){
		$queryGet = "SELECT 
					DISTINCT
					fact.`id` idFactura, 
					fact.`folio` folioFactura, 
					con.`idCotizacion` idCotizacion
				FROM 
					`factura` fact
					INNER JOIN `concepto_cotizacion` con ON con.`folioFactura` = fact.`folio`
				";

		$result = $this->db->query($queryGet)->result();

		foreach($result as $rel){
			//Comprobar existencia de relación
			$queryRelExists = "SELECT count(*) numeroRelaciones
							FROM `cotizacion_factura_rel` cfc
							WHERE
								cfc.`idFactura` = ".$rel->idFactura.
								"AND cfc.`idCotizacion` = ".$rel->idCotizacion;

			$numeroRelaciones = $this->db->query($queryRelExists)->row();
			$numeroRelaciones = $numeroRelaciones->numeroRelaciones;

			if($numeroRelaciones == 0){
				$queryRel = "INSERT INTO 
								`cotizacion_factura_rel`(`idFactura`, `idCotizacion`) 
							VALUES (".$rel->idFactura.",".$rel->idCotizacion.")";

				$this->db->query($queryRel);
				echo "(OK) Factura: ".$rel->idFactura." con CotizaciOn: ".$rel->idCotizacion;
			}
		}

		echo "<br><br>Proceso finalizado.<br><br>";
	}

	public function buildRelationConcepto_ConceptoCotizacion(){
		$queryGet = "SELECT
						con.`id` idConceptoFactura,
						con_cot.`id` idConceptoCotizacion
					FROM
						`concepto` con
						INNER JOIN `concepto_factura_rel` rel_con_fact ON rel_con_fact.`idConcepto` = con.`id`
						INNER JOIN `factura` fact ON fact.`id` = rel_con_fact.`idFactura`
						INNER JOIN `concepto_cotizacion` con_cot ON con_cot.`folioFactura` = fact.`folio`
					WHERE
						con.`importe` = con_cot.`monto`
					";

		$result = $this->db->query($queryGet)->result();

		foreach($result as $rel){
			//Comprobar existencia de relación
			$queryRelExists = "SELECT count(*) numeroRelaciones
								FROM
									`concepto_factura_cotizacion` cfc
								WHERE
									cfc.`idConceptoFactura` = ".$rel->idConceptoFactura.
									"AND cfc.`idConceptoCotizacion` = ".$rel->idConceptoCotizacion;
			$numeroRelaciones = $this->db->query($queryRelExists)->row();
			$numeroRelaciones = $numeroRelaciones->numeroRelaciones;

			if($numeroRelaciones == 0){
				$queryRel = "UPDATE 
								`concepto` 
							SET 
								`idConcepto_cotizacion`=".$rel->idConceptoCotizacion." 
							WHERE id = ".$rel->idConceptoFactura;

				$queryRel_inTable = "INSERT INTO 
										`concepto_factura_cotizacion`(`idConceptoFactura`, `idConceptoCotizacion`) 
									VALUES 
										(".$rel->idConceptoFactura.",".$rel->idConceptoCotizacion.")
									";

				$this->db->query($queryRel);

				echo "<br>(OK) Relacionando: Cot(".$rel->idConceptoCotizacion.") : Fact(".$rel->idConceptoFactura.")";
			}
		}

		echo "<br><br>(OK) Proceso finalizado.<br><br>";
	}

	public function qr($query){
		$this->db->query($query);
		return $this->db->insert_id();
	}
}
