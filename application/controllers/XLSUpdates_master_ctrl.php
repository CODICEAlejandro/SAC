<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','-1');
ini_set('max_execution_time', 30000);
set_time_limit(30000);

class XLSUpdates_master_ctrl extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library("XLSSheetDriver");
	}

	public function index(){
		$xls = $this->xlssheetdriver;

		$xls->openFile("./files/MASTER_FILE.xlsx");
		$result = $xls->readDocument();
		print_r($result[0]);

		$this->updateFacturas($result);
	}

	/*
	//Actualiza la fecha de pago en la tabla de factura
	public function updateFacturas($data){
		for($r = 1, $n = count($data); $r < $n; $r++){
			$folioFactura = htmlentities(trim($data[$r][0][1]), ENT_QUOTES, 'UTF-8');

			//$fechaCancelacion = htmlentities(trim($data[$r][0][1]), ENT_QUOTES, 'UTF-8');
			$fechaPago = htmlentities(trim($data[$r][0][3]), ENT_QUOTES, 'UTF-8');
			$timestamp = PHPExcel_Shared_Date::ExcelToPHP($fechaPago);
			$fechaPago = date("Y-m-d",$timestamp);

			$queryUpdateFactura = "UPDATE `factura` SET `fechaPago`= '".$fechaPago."' WHERE `folio` = '".$folioFactura."' ";

			$this->db->query($queryUpdateFactura);
		}
	}*/

	/*
	//Actualiza la nota de los conceptos cotización
	public function updateFacturas($data){
		for($r = 1, $n = count($data); $r < $n; $r++){
			$idConceptoCotizacion = htmlentities(trim($data[$r][0][6]), ENT_QUOTES, 'UTF-8');
			$nota = htmlentities(trim($data[$r][0][34]), ENT_QUOTES, 'UTF-8');

			$queryUpdateFactura = "UPDATE `concepto_cotizacion` SET `nota`= '".$nota."' WHERE `id` = '".$idConceptoCotizacion."' ";

			$this->db->query($queryUpdateFactura);
		}
	}
	*/

	/*
	//Actualiza la orden de compra de los conceptos_cotizacion
	public function updateFacturas($data){
		for($r = 1, $n = count($data); $r < $n; $r++){
			$idConceptoCotizacion = htmlentities(trim($data[$r][0][6]), ENT_QUOTES, 'UTF-8');
			$ordenCompra = htmlentities(trim($data[$r][0][11]), ENT_QUOTES, 'UTF-8');
			$folioFactura = htmlentities(trim($data[$r][0][1]), ENT_QUOTES, 'UTF-8');

			$queryUpdateFactura = "UPDATE `factura` SET `ordenCompra`= '".$ordenCompra."' WHERE `folio` = '".$folioFactura."' ";

			$this->db->query($queryUpdateFactura);
		}
	}
	*/

	
	//Relación entre conceptos que tienen folio de factura en cotización, pero no relación con un concepto de la factura correspondiente
	public function updateFacturas($data){
		$conceptosSinRelacion = 0;
		$conceptosConRelacion = 0;

		//Obtener los conceptos de la cotización con folio de factura
		$queryConceptosCotizacion = "SELECT *
									FROM
										`concepto_cotizacion` conCot
									WHERE
										NOT ISNULL(conCot.`folioFactura`)
										AND conCot.`folioFactura` != ''
									";

		$conceptos_cotizacion = $this->db->query($queryConceptosCotizacion)->result();

		foreach($conceptos_cotizacion as $c){
			//Obtener el número de conceptos de factura relacionados con el concepto de cotización actual
			$queryNumeroRelaciones = "SELECT
										count(*) nRelaciones
									FROM
										`concepto_factura_cotizacion` con_rel
									WHERE
										con_rel.`idConceptoCotizacion` = ".$c->id."
									";

			$numeroRelaciones = $this->db->query($queryNumeroRelaciones)->row();
			$numeroRelaciones = $numeroRelaciones->nRelaciones;

			if($numeroRelaciones == 0){
				//Caso de interés: No existen conceptos asociados para el concepto de cotización actual
				//pese a que este tiene un folio de factura asociado

				//Obtener número de conceptos de la factura asociada al concepto de la cotización actual
				$queryNumeroConceptos = "SELECT
											con.*
										FROM
											`concepto_factura_rel` fc_rel
											INNER JOIN `concepto` con ON con.`id` = fc_rel.`idConcepto`
											INNER JOIN `factura` fact ON fact.`id` = fc_rel.`idFactura`
										WHERE
											fact.`folio` = '".$c->folioFactura."'
										";

				$conceptos_factura = $this->db->query($queryNumeroConceptos)->result();
				if(count($conceptos_factura) == 1){
					//Relación directa uno a uno (Varios de la cotización van a uno mismo)
					//Esto sucede porque puede ser que un concepto de la factura lo desglosen en varios
					//conceptos en la cotización

					$queryRelacional = "INSERT INTO 
									`concepto_factura_cotizacion`(`idConceptoFactura`, `idConceptoCotizacion`) 
								VALUES 
									(".$conceptos_factura[0]->id.",".$c->id.")
							";

					$this->db->query($queryRelacional);
					$conceptosConRelacion++;
					echo "Concepto relacionado (OK) : Factura(".$c->folioFactura."), Concepto_Factura(".$conceptos_factura[0]->id.") -> Cotización(".$c->id.")<br>";
				}else{
					$conceptosSinRelacion++;
					echo "Concepto relacionado (FAIL) : Factura(".$c->folioFactura.") -> Cotización(".$c->id.")<br>";
				}
			}else
				$conceptosConRelacion++;
		}

		echo "Proceso finalizado: ".$conceptosConRelacion." conceptos con relación, ".$conceptosSinRelacion." conceptos sin relación<br>";
	}
	

	/*
	//Migra el contenido de la columna idConcepto_cotizacion de concepto a la tabla de relación corresondiente con cotización
	public function updateFacturas($data){
		//Selecciona todos los conceptos relacionados en factura
		$queryConceptosRelacionados = "SELECT
										con.`id` idConceptoFactura,
										con.`idConcepto_cotizacion` idConceptoCotizacion
									FROM
										`concepto` con
									WHERE
										NOT ISNULL(con.`idConcepto_cotizacion`)
									";

		$conceptosRelacionados = $this->db->query($queryConceptosRelacionados)->result();

		//Migrar contenido a tabla de relación
		foreach($conceptosRelacionados as $c){
			$queryInsercion = "INSERT INTO 
									`concepto_factura_cotizacion`(`idConceptoFactura`, `idConceptoCotizacion`) 
								VALUES 
									(".$c->idConceptoFactura.",".$c->idConceptoCotizacion.")
							";

			$this->db->query($queryInsercion);
		}
	}
	*/
}