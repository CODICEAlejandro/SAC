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

}