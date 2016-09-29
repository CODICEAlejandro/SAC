<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','-1');
ini_set('max_execution_time', 30000);
set_time_limit(30000);

class XLSReader_Master extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library("XLSSheetDriver");
	}

	public function index(){
		$xls = $this->xlssheetdriver;

		$xls->openFile("./files/MASTER_FILE.xlsx");
		$result = $xls->readDocument();
		print_r($result[0]);

		$this->getCotizaciones($result);
	}

	public function getCotizaciones($data){
		$catTipoConcepto = array();
		$catCot = array();
		$catEstadoFactura = array();

		//for($r = 1, $n = count($data); $r < $n; $r++){
		for($r = 1, $n = 15; $r < $n; $r++){
			$idCotizacion = $data[$r][0][6];

			//Data de concepto
			$estadoFacturaConcepto =  htmlentities(trim($data[$r][0][0]), ENT_QUOTES, 'UTF-8');
			$folioFacturaConcepto = htmlentities(trim($data[$r][0][1]), ENT_QUOTES, 'UTF-8');
			$montoConcepto = htmlentities(trim($data[$r][0][8]), ENT_QUOTES, 'UTF-8');
			$precioUnitario = htmlentities(trim($data[$r][0][8]), ENT_QUOTES, 'UTF-8');
			$descripcionConcepto = htmlentities(trim($data[$r][0][17]), ENT_QUOTES, 'UTF-8');
			$idTipoConcepto = htmlentities(trim($data[$r][0][13]), ENT_QUOTES, 'UTF-8');
			$referenciaConcepto = htmlentities(trim($data[$r][0][16]), ENT_QUOTES, 'UTF-8');
			$idCotizacionConcepto = htmlentities(trim($idCotizacion), ENT_QUOTES, 'UTF-8');
			$recurrenciaConcepto = 0;
			$contadorPagosConcepto = 1;
			$notaConcepto = htmlentities(trim($data[$r][0][35]), ENT_QUOTES, 'UTF-8');
			$cantidadConcepto = 1;
			$unidadDeMedidaConcepto = '';
			$valorUnitarioConcepto = 0.0;
			$importeConcepto = htmlentities(trim($data[$r][0][30]), ENT_QUOTES, 'UTF-8');
			$textosDePosicionConcepto = '';
			$idPeriodoRecurrenciaConcepto = 'NULL';

			$tipoConcepto = htmlentities(trim($data[$r][0][13]), ENT_QUOTES, 'UTF-8');

			if(!is_numeric($montoConcepto)) $montoConcepto = 0.0; 
			if(!is_numeric($precioUnitario)) $precioUnitario = 0.0; 
			if(!is_numeric($importeConcepto)) $importeConcepto = 0.0; 

			//CatAlogo de estados de la factura
			$flagEstadoFactura = true;
			foreach($catEstadoFactura as $key => $estadoFactura){
				if($estadoFactura[0] == $estadoFacturaConcepto){
					$flagEstadoFactura = false;
					break;
				}
			}

			if($flagEstadoFactura){
				$queryEstadoFactura = "INSERT INTO 
										`catestadofactura`(`descripcion`) 
									VALUES (".$estadoFacturaConcepto.")";
				$idEstadoFactura = $this->qr($queryEstadoFactura);

				array_push($catEstadoFactura, array($idEstadoFactura, $queryEstadoFactura));
			}else{
				foreach($catEstadoFactura as $keyEstado => $estado){
					if($estado[1] == $estadoFacturaConcepto){
						$idEstadoFactura = $estado[0];
						break;
					}
				}				
			}


			//CatAlogo de tipos de concepto
			$flagTipoConcepto = true;
			foreach($catTipoConcepto as $keyConcepto => $concepto){
				if($concepto[1] == $tipoConcepto){
					$flagTipoConcepto = false;
					break;
				}
			}

			if($flagTipoConcepto){
				$queryTipoConcepto = "INSERT INTO 
										`cattipoconcepto`(`descripcion`) 
									VALUES 
										('".$tipoConcepto."')
									";
				$idTipoConcepto = $this->qr($queryTipoConcepto);

				array_push($catTipoConcepto, array($idTipoConcepto, $tipoConcepto));
			}else{
				foreach($catTipoConcepto as $keyConcepto => $concepto){
					if($concepto[1] == $tipoConcepto){
						$idTipoConcepto = $concepto[0];
						break;
					}
				}
			}

			$flagCotizacion = true;
			foreach($catCot as $keyCot => $dataQuery){
				if($keyCot == $idCotizacion){
					$flagCotizacion = false;
					break;
				}
			}


			if($flagCotizacion){
				$catCot[$idCotizacion] = array();
				$catCot[$idCotizacion]["query"] = "";
				$catCot[$idCotizacion]["conceptos"] = array();
			}

			$queryConcepto = "INSERT INTO `concepto_cotizacion`(
								`monto`, `estadoActivo`, `descripcion`, 
								`idTipoConcepto`, `referencia`, 
								`idCotizacion`, `recurrencia`, `contadorPagos`, 
								`nota`, `cantidad`, `unidadDeMedida`, 
								`valorUnitario`, `importe`, `textosDePosicion`, 
								`idPeriodoRecurrencia`, `folioFactura`, 
								`idEstadoFactura`
							) 
							VALUES 
								(
									".$montoConcepto.",
									1, 
									'".$descripcionConcepto."', 
									".$idTipoConcepto.", 
									'".$referenciaConcepto."', 
									".$idCotizacionConcepto.", 
									0, 
									0, 
									'".$notaConcepto."', 
									1, 
									1,
									".$precioUnitario.",
									".$importeConcepto.",
									'".$textosDePosicionConcepto."', 
									3,
									'".$folioFacturaConcepto."',
									".$idEstadoFactura."
								)";

			array_push($catCot[$idCotizacion]["conceptos"],
					$queryConcepto
				);

			if(!$flagCotizacion){
				//La cotización la existe por lo tanto es posible asociarla
				$idConceptoResult = $this->qr($queryConcepto);
				continue;
			}

			$idRazonSocial = htmlentities(trim($data[$r][0][22]), ENT_QUOTES, 'UTF-8');

			$fecha = htmlentities(trim($data[$r][0][24]), ENT_QUOTES, 'UTF-8');
			$timestamp = PHPExcel_Shared_Date::ExcelToPHP($fecha);
			$fechaJuntaArranque = date("Y-m-d",$timestamp);

			$inicioProyecto = htmlentities(trim($data[$r][0][19]), ENT_QUOTES, 'UTF-8');
			$timestamp = PHPExcel_Shared_Date::ExcelToPHP($inicioProyecto);
			$inicioProyecto = date("Y-m-d",$timestamp);

			$finProyecto = htmlentities(trim($data[$r][0][20]), ENT_QUOTES, 'UTF-8');
			$timestamp = PHPExcel_Shared_Date::ExcelToPHP($finProyecto);
			$finProyecto = date("Y-m-d",$timestamp);

			$fechaVenta = htmlentities(trim($data[$r][0][23]), ENT_QUOTES, 'UTF-8');
			$timestamp = PHPExcel_Shared_Date::ExcelToPHP($fechaVenta);
			$fechaVenta = date("Y-m-d",$timestamp);

			$idCerrador = 'NULL';
			$idResponsable = 'NULL';
			$folio = htmlentities(trim($idCotizacion), ENT_QUOTES, 'UTF-8');
			$contrado = htmlentities(trim($data[$r][0][33]), ENT_QUOTES, 'UTF-8');
			$accountManager = 'NULL';
			$titulo = htmlentities(trim($data[$r][0][18]), ENT_QUOTES, 'UTF-8');

			$queryCotizacion = "
					INSERT INTO `cotizacion`(
						`id`, 
						`idRazonSocial`, 
						`estadoActivo`, 
						`nota`, 
						`fechaJuntaArranque`, 
						`inicioProyecto`, 
						`finProyecto`, 
						`fechaVenta`, 
						`idCerrador`, 
						`idResponsable`, 
						`folio`, 
						`contrato`, 
						`accountManager`, 
						`titulo`
					) 
					VALUES 
					(
						".$idCotizacion.", 
						".$idRazonSocial.", 
						1,
						'', 
						'".$fechaJuntaArranque."', 
						'".$inicioProyecto."', 
						'".$finProyecto."', 
						'".$fechaVenta."', 
						NULL, 
						NULL, 
						".$folio.", 
						-1, 
						NULL, 
						'".$titulo."'
					)
					";

			$catCot[$idCotizacion]["query"] = $queryCotizacion;

			//Hasta este punto no existe la cotización
			//Crea cotización y luego asocia el concepto actual
			$idCotizacionResult = $this->qr($queryCotizacion);
			$idConceptoResult = $this->qr($queryConcepto);
		}

		print_r($catCot);
	}

	public function parseRows($data){
		for($r = 1, $n = count($data); $r<$n; $r++){
			$idDireccionFiscal = $this->qr($query);
		}
	}

//Cotización
//Concepto_cotización

	public function qr($query){
		$this->db->query($query);
		return $this->db->insert_id();
	}
}