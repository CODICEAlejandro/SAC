<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class XLSSheetDriver extends CI_Model {
	private $cCol;
	private $cRow;
	private $mark;
	private $creator = "DEFAULT";
	private $lastModifiedBy = "DEFAULT";
	private $title = "DEFAULT";
	private $subject = "CODICE";
	private $description = "DEFAULT";
	private $sheet = 0;

	public function __construct($cCol='A', $cRow=1){
		$this->cCol = $cCol;
		$this->cRow = $cRow;
		$this->mark = array();

		$this->load->library('PHPExcel.php');
		$this->mark['DOCUMENT_BEGIN'] = array("col" => ($this->cCol), "row" => ($this->cRow));
		$this->mark['DOCUMENT_END'] = array("col" => ($this->cCol), "row" => ($this->cRow));

	    $this->phpexcel->getProperties()->setCreator($this->creator)
	                                 ->setLastModifiedBy($this->lastModifiedBy)
	                                 ->setTitle($this->title)
	                                 ->setSubject($this->subject)
	                                 ->setDescription($this->description);

        $this->sheet = $this->phpexcel->setActiveSheetIndex($this->sheet);
	}

	public function setSheet($number){
		$this->sheet = $this->phpexcel->setActiveSheetIndex($number);
	}

	public function setCellValue($value, $position="NO_GOT"){
		if($position == "NO_GOT")
			$this->sheet->setCellValue($this->getPosition(),utf8_decode(($value)));		
		else
			$this->sheet->setCellValue($position,utf8_decode(($value)));		
	}

	public function setTitle($title){
		$this->title = $title;
	    $this->phpexcel->getProperties()->setTitle($this->title);
	}

	public function setCellBackground($color, $cell="NO_GOT"){
		if($cell == "NO_GOT")
			$cell = $this->getPosition();

		$cellStyle = $this->sheet->getStyle($cell);
		$cellStyle->applyFromArray(
						array(
							'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $color)
									)
							)
					);
	}

	public function setCellBorders($color, $cell = "NO_GOT"){
		if($cell == "NO_GOT")
			$cell = $this->getPosition();

		$this->sheet->getStyle($cell)->applyFromArray(
		    array(
		        'borders' => array(
		            'allborders' => array(
		                'style' => PHPExcel_Style_Border::BORDER_THIN,
		                'color' => array('rgb' => $color)
		            )
		        )
		    )
		);
	}

	public function getMark($markName){
		if( isset($this->mark[$markName]) )
			return $this->mark[$markName];
		return false;
	}

	public function getIntegerFromCol($col){
		$col = str_split($col);
		$col = array_reverse($col);
		$result = 0;

		for($k=0, $n=count($col); $k<$n; $k++)
			$result += ( (ord($col[$k])-64) * pow(26,$k) );

		return $result;
	}

	public function getPosition(){
		return ($this->cCol).($this->cRow);
	}	

	public function getCol(){
		return $this->cCol;
	}

	public function getRow(){
		return $this->cRow;
	}

	public function colToNumber(){
		return ord($this->cCol)-65;
	}

	public function putMark($markName){
		$this->mark[$markName] = array(
									"col" => ($this->cCol),
									"row" => ($this->cRow)
								);
	}

	public function gotoMark($markName){
		if( isset($this->mark[$markName]) ){
			$this->cCol = $this->mark[$markName]['col'];
			$this->cRow = $this->mark[$markName]['row'];

			return $this->getPosition();
		}

		return false;		
	}

	public function carriageReturn(){
		$this->cCol = "A";
	}

	private function moveRight($colPosition){
		if(count($colPosition) == 0)
			return array('A');
		else{
			if($colPosition[0] == 'Z'){
				$colResult = array('A');
				array_shift($colPosition);
				return array_merge($colResult, $this->moveRight($colPosition) );
			}else{
				$colPosition[0] = ++$colPosition[0];
				return $colPosition;
			}
		}
	}

	public function nextCol(){
		$colPosition = str_split($this->cCol);
		$colPosition = array_reverse($colPosition);
		$result = $this->moveRight($colPosition);
		$result = array_reverse($result);
		$colRes = "";

		for($k=0, $n=count($result); $k<$n; $k++)
			$colRes = $colRes.$result[$k];

		$this->cCol = $colRes;
		$this->mark['DOCUMENT_END'] = ($this->cCol).($this->cRow);

		return $this->cCol;
	}

	public function nextLine(){
		$this->nextRow();
		$this->carriageReturn();
	}

	public function nextRow(){
		$this->cRow = ($this->cRow) + 1;
		$this->mark['DOCUMENT_END'] = ($this->cCol).($this->cRow);

		return $this->cRow;
	}

	public function addRightSeparatorOnCell($cell,$color){
		$this->sheet->getStyle($cell)->applyFromArray(
			array(
				'borders' => array(
					'right' => array(
						'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
						'color' => array('rgb' => $color)
					)
				)
			)
		);
	}

	public function centerCellContent($cell){
		$style = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		    )
		);

		$this->sheet->getStyle($cell)->applyFromArray($style);
	}

	public function boldCellContent($cell){
		$this->sheet->getStyle($cell)->getFont()->setBold(true);
	}

	public function autosizeColumns(){
		for($col = 'A'; $col !== $this->cCol; $col++) {
		    $this->sheet->getColumnDimension($col)
		          ->setAutoSize(true);
		}
	}

	public function out($fileName){
	    // Renombramos la hoja de trabajo
	    $this->phpexcel->getActiveSheet()->setTitle($this->title);
	    
	    // configuramos el documento para que la hoja
	    // de trabajo número 0 sera la primera en mostrarse
	    // al abrir el documento
	    $this->phpexcel->setActiveSheetIndex(0);
	    
	    // redireccionamos la salida al navegador del cliente (Excel2007)
		//header('Content-Type: application/vnd.ms-excel; encoding: UTF-8');
		header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

	    $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
	    $objWriter->save('php://output');
	}
}
?>