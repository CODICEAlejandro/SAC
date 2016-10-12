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
		//print_r($result[0]);

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
	}
	*/

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
	//Proceso: muchos en cotización y uno en factura
	public function updateFacturas($data){
		$conceptosSinRelacion = 0;
		$conceptosConRelacion = 0;
		echo "(WARNING) Comenzando proceso ... <br>";

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

					//$this->db->query($queryRelacional);
					$conceptosConRelacion++;
					echo "Concepto relacionado (OK) : Factura(".$c->folioFactura."), Concepto_Factura(".$conceptos_factura[0]->id.") -> Cotización(".$c->id.")<br>";
				}else{
					$conceptosSinRelacion++;
					foreach($conceptos_factura as $k)
						echo "CR (FAIL) : Con_Fact(".$k->id.",".$c->folioFactura.",".$k->importe.",".$k->descripcion.") -> Con_Cot(".$c->id.",".$c->monto.",".$c->descripcion.")<br>";
					echo "<br><br>";
				}
			}else
				$conceptosConRelacion++;
		}

		echo "Proceso finalizado: ".$conceptosConRelacion." conceptos con relación, ".$conceptosSinRelacion." conceptos sin relación<br>";
	}
	
	/*
	//Relación entre conceptos que tienen folio de factura en cotización, pero no relación con un concepto de la factura correspondiente
	//Proceso inverso: Muchos en factura y uno en cotización
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

				//Obtener número de conceptos de la cotización actual asociada a la factura
				$queryNumeroConceptos = "SELECT
											conCot.*
										FROM
											`concepto_cotizacion` conCot
											INNER JOIN `cotizacion` cot ON cot.`id` = conCot.`idCotizacion`
										WHERE
											conCot.`folioFactura` = '".$c->folioFactura."'
										";

				//Obtener conceptos de factura asociada
				$queryConceptosFactura = "SELECT
											con.*
										FROM
											`concepto_factura_rel` fc_rel
											INNER JOIN `concepto` con ON con.`id` = fc_rel.`idConcepto`
											INNER JOIN `factura` fact ON fact.`id` = fc_rel.`idFactura`
										WHERE
											fact.`folio` = '".$c->folioFactura."'
										";

				$conceptos_factura = $this->db->query($queryConceptosFactura)->result();
				$conceptos_cotizacion = $this->db->query($queryNumeroConceptos)->result();
				if(count($conceptos_cotizacion) == 1){
					//Relación directa uno a uno (Varios de la factura van a uno mismo de la cotización)
					//Esto sucede porque puede ser que un concepto de la cotización lo desglosen en varios
					//conceptos en la factura
					foreach($conceptos_factura as $cf){
						$queryRelacional = "INSERT INTO 
										`concepto_factura_cotizacion`(`idConceptoFactura`, `idConceptoCotizacion`) 
									VALUES 
										(".$cf->id.", ".$conceptos_cotizacion[0]->id.")
								";

						$this->db->query($queryRelacional);
						$conceptosConRelacion++;
						echo "Concepto relacionado (OK) : Factura(".$c->folioFactura."), Concepto_Factura(".$cf->id.") -> Cotización(".$conceptos_cotizacion[0]->id.")<br>";
					}
				}else{
					$conceptosSinRelacion++;
					foreach($conceptos_factura as $k)
						echo "CR (FAIL) : Con_Fact(".$k->id.",".$c->folioFactura.",".$k->importe.",".$k->descripcion.") -> Con_Cot(".$c->id.",".$c->monto.",".$c->descripcion.")<br>";
					echo "<br><br>";
				}
			}else
				$conceptosConRelacion++;
		}

		echo "Proceso finalizado: ".$conceptosConRelacion." conceptos con relación, ".$conceptosSinRelacion." conceptos sin relación<br>";
	}*/

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

	/*
	//Revisar conceptos relacionados y no relacionados
	public function updateFacturas($data){
		echo "<br><br>... Procesando ...";

		$queryTotalConceptos_cotizacion = "SELECT * FROM concepto_cotizacion";
		$queryTotalConceptos_factura = "SELECT * FROM concepto";

		$conceptos_cotizacion = $this->db->query($queryTotalConceptos_cotizacion)->result();
		$conceptos_factura = $this->db->query($queryTotalConceptos_factura)->result();

		$totalConceptos_cotizacion = count($conceptos_cotizacion);
		$totalConceptos_factura = count($conceptos_factura);

		$diferenciaTotalConceptos = $totalConceptos_cotizacion - $totalConceptos_factura;

		$totalConceptos_sinFactura = 0;
		$totalConceptos_conFactura = 0;

		$totalConceptos_relacionados = 0;
		$totalConceptos_sinRelacion = 0;

		$facturas_noExistentes = array();
		$facturas_hipoteticas = array();
		$facturas_existentesConProblemas = array();

		$totalConceptos_sinRelacion_conFactura = 0;

		for($k = 0; $k < $totalConceptos_cotizacion; $k++){
			$folioFactura = $conceptos_cotizacion[$k]->folioFactura;

			if(($folioFactura == "") || (is_null($folioFactura)) || ($folioFactura == "NULL")){
				$totalConceptos_sinFactura++;
				$totalConceptos_sinRelacion++;
			}else{
				$totalConceptos_conFactura++;

				$queryRelaciones = "
									SELECT count(*) numeroRelaciones
									FROM `concepto_factura_cotizacion` cfc
									WHERE
										cfc.`idConceptoCotizacion` = ".$conceptos_cotizacion[$k]->id."
									";

				$numeroRelaciones = $this->db->query($queryRelaciones)->row();
				$numeroRelaciones = $numeroRelaciones->numeroRelaciones;

				if($numeroRelaciones > 0)
					$totalConceptos_relacionados++;
				else{
					$totalConceptos_sinRelacion++;
					$totalConceptos_sinRelacion_conFactura++;

					if(!in_array($folioFactura, $facturas_hipoteticas))
						array_push($facturas_hipoteticas, $folioFactura);

					$queryGetFactura = "SELECT count(*) numero, fact.`id` id 
										FROM `factura` fact 
										WHERE fact.`folio` = '".$folioFactura."'";

					$factura = $this->db->query($queryGetFactura)->row();
					$numeroFacturas = $factura->numero;

					if(($numeroFacturas < 1) && !in_array($folioFactura, $facturas_noExistentes))
						array_push($facturas_noExistentes, $folioFactura);
					else if(($numeroFacturas > 0) && !in_array($folioFactura, $facturas_existentesConProblemas)){
						$flagExists = true;

						foreach($facturas_existentesConProblemas as $key => $value){
							if($value[0] == $folioFactura){
								$flagExists = false;
								break;
							}
						}

						if($flagExists)
							array_push($facturas_existentesConProblemas, array($folioFactura, $factura->id));
					}

					echo "<br>(WARNING) Concepto sin relación con factura : (".$conceptos_cotizacion[$k]->id.",".$folioFactura.",".$conceptos_cotizacion[$k]->descripcion.")";
				}
			}
		}

		if(count($facturas_hipoteticas)){
			echo "<br><br>(ERROR) Facturas hipotéticas: ";
			foreach($facturas_hipoteticas as $key => $value)
				echo "<br>Factura ".$value;
		}

		if(count($facturas_noExistentes)){
			asort($facturas_noExistentes);
			echo "<br><br>(ERROR) Facturas no existentes: ";
			foreach($facturas_noExistentes as $key => $value)
				echo "<br>Factura ".$value;
		}

		if(count($facturas_existentesConProblemas)){
			echo "<br><br>(WARNING) Facturas existentes y que presentan problemas: ";
			foreach($facturas_existentesConProblemas as $key => $value)
				echo "<br>Factura ".$value[0]." : ".$value[1];

			//Conceptos
			$queryConceptos_factura = "
										SELECT
											con.`id`,
											con.`descripcion`
										FROM
											`concepto` con
											INNER JOIN `concepto_factura_rel` cf ON cf.`idConcepto` = con.`id`
											INNER JOIN `factura` fact ON fact.`id` = cf.`idFactura`
										WHERE
											fact.`folio` = '".$value[0]."'
									";
			$conceptos_asociados = $this->db->query($queryConceptos_factura)->result();

			foreach($conceptos_asociados as $c)
				echo "<br> -> Concepto ".$c->id." - ".$c->descripcion;
		}

		echo "<br><br>Proceso finalizado:";
		echo "<br>Total conceptos cotización: ".$totalConceptos_cotizacion;
		echo "<br>Total conceptos factura: ".$totalConceptos_factura;
		echo "<br>Diferencia conceptos cotizados menos conceptos facturados: ".$diferenciaTotalConceptos;
		echo "<br><br>Conceptos sin factura: ".$totalConceptos_sinFactura;
		echo "<br>Conceptos con factura: ".$totalConceptos_conFactura;
		echo "<br><br>Conceptos relacionados: ".$totalConceptos_relacionados;
		echo "<br>Conceptos sin relación: ".$totalConceptos_sinRelacion;
		echo "<br><br>Conceptos sin relación y con factura asociada: ".$totalConceptos_sinRelacion_conFactura;
	}
	*/
}