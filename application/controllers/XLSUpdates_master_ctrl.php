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

		$this->updateFacturas($result);
	}

	public function updateFacturas($data){
		for($r = 1, $n = count($data); $r < $n; $r++){
			$folioFactura = htmlentities(trim($data[$r][0][1]), ENT_QUOTES, 'UTF-8');

			//$fechaCancelacion = htmlentities(trim($data[$r][0][1]), ENT_QUOTES, 'UTF-8');
			$fechaPago = htmlentities(trim($data[$r][0][3]), ENT_QUOTES, 'UTF-8');
			$timestamp = PHPExcel_Shared_Date::ExcelToPHP($fechaPago);
			$fechaPago = date("Y-m-d",$timestamp);

			$queryUpdateFactura = "UPDATE `factura` SET `fechaPago`= '".$fechaPago."' WHERE `folio` = '".$folioFactura."' ";
		}
	}
}