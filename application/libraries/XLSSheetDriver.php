<?php

class XLSSheetDriver {
	private $cCol;
	private $cRow;
	private $mark;

	public function __construct($cCol='A', $cRow=1){
		$this->cCol = $cCol;
		$this->cRow = $cRow;
		$this->mark = array();

		$this->mark['DOCUMENT_BEGIN'] = array("col" => ($this->cCol), "row" => ($this->cRow));
		$this->mark['DOCUMENT_END'] = array("col" => ($this->cCol), "row" => ($this->cRow));
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

	public function getMark($markName){
		if( isset($this->mark[$markName]) )
			return $this->mark[$markName];
		return false;
	}

	public function gotoMark($markName){
		if( isset($this->mark[$markName]) ){
			$this->cCol = $this->mark[$markName]['col'];
			$this->cRow = $this->mark[$markName]['row'];

			return $this->getPosition();
		}

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

	public function getPosition(){
		return ($this->cCol).($this->cRow);
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

	public function nextRow(){
		$this->cRow = ($this->cRow) + 1;
		$this->mark['DOCUMENT_END'] = ($this->cCol).($this->cRow);

		return $this->cRow;
	}

	public function setRow($newRow){
		$this->mark['DOCUMENT_END'] = ($this->cCol).($this->cRow);
		$this->cRow = $newRow;
	}

	public function getCol(){
		return $this->cCol;
	}

	public function getRow(){
		return $this->cRow;
	}

	public function setCellBackground($cell,$color,$sheet){
		$cellStyle = $sheet->getStyle($cell);
		$cellStyle->applyFromArray(
						array(
							'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $color)
									)
							)
					);
	}

	public function setCellBorders($cell,$color,$sheet){
		$sheet->getStyle($cell)->applyFromArray(
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

	public function addRightSeparatorOnCell($cell,$color,$sheet){
		$sheet->getStyle($cell)->applyFromArray(
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

	public function centerCellContent($cell,$sheet){
		$style = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		    )
		);

		$sheet->getStyle($cell)->applyFromArray($style);
	}

	public function boldCellContent($cell,$sheet){
		$sheet->getStyle($cell)->getFont()->setBold(true);
	}
}
?>