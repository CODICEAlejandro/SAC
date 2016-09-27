<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class XLSReader extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library("XLSSheetDriver");
	}

	public function index(){
		$xls = $this->xlssheetdriver;

		$xls->openFile("./files/RFCClientes.xlsx");
		$result = $xls->readDocument();

		$a = $this->getCats($result);

		$queryPais = "";
		$queryEstado = "";
		$queryCiudad = "";

		//INICIA inserción de los nuevos clientes
		//FIN de inserción de nuevos cliente

		//INICIA insersión de catálogos relacionados con zona
		foreach($a as $keyPais => $estados){
			$queryPais = "INSERT INTO `catpais` (`nombre`) VALUES ('".$keyPais."'); ";
			$idPais = $this->qr($queryPais);

			foreach($estados AS $keyEstado => $ciudades){
				$queryEstado = "INSERT INTO `catestadogeografico` (`nombre`, `idPais`) VALUES ('".$keyEstado."', ".$idPais."); ";
				$idEstado = $this->qr($queryEstado);

				foreach($ciudades AS $key => $ciudad){
					$queryCiudad = "INSERT INTO `catciudad` (`nombre`,`idEstado`) VALUES ('".$ciudad."', ".$idEstado."); ";
					$idCiudad = $this->qr($queryCiudad);
				}
			}
		}
		//FIN de catAlogos por zona

		//INICIA inserción de direcciones fiscales
		//$this->parseRows($result);
		//FIN de inserción de direcciones fiscales
	}

	public function parseRows($data){
		for($r = 1, $n = count($data); $r<$n; $r++){
			$idPadre = trim($data[$r][0][0]);
			$nombreCliente = trim($data[$r][0][1]);
			$calle = trim($data[$r][0][6]);
			$numero = trim($data[$r][0][7]);
			$colonia = trim($data[$r][0][4]);
			$cp = trim($data[$r][0][5]);
			$razonSocial = trim($data[$r][0][2]);
			$rfc = trim($data[$r][0][3]);

			$currentPais = trim($data[$r][0][8]);
			$currentEstado = trim($data[$r][0][10]);
			$currentCiudad = trim($data[$r][0][9]);

			$queryCiudad = "SELECT id, idEstado FROM catciudad WHERE nombre = '".$currentCiudad."'";
			$idCiudad = $this->db->query($queryCiudad)->row()->id;
			$idEstado = $this->db->query($queryCiudad)->row()->idEstado;

			$queryPais = "SELECT idPais FROM catestadogeografico WHERE id = ".$idEstado;
			$idPais = $this->db->query($queryPais)->row()->idPais;

			$queryDireccionFiscal = "INSERT INTO 
										`direccionfiscal`
									(
									`idPadre`, 
									`calle`, 
									`numero`, 
									`idPais`, 
									`idCiudad`, 
									`idEstado`, 
									`colonia`, 
									`cp`, 
									`razonSocial`, 
									`rfc`, 
									`estadoActivo`
									) 
									VALUES 
									(
									".$idPadre.",
									'".$calle."',
									'".$numero."',
									".$idPais.",
									".$idCiudad.",
									".$idEstado.",
									'".$colonia."',
									'".$cp."',
									'".$razonSocial."',
									'".$rfc."',
									1)";

			$idDireccionFiscal = $this->qr($queryDireccionFiscal);
		}
	}

	public function getCats($data){
		$catZona = array();		// array(Pais => array(Estado => array(Ciudad)) )
		$catClienteNuevo = array();
		$catCliente = array();

		for($r = 1, $n = count($data); $r<$n; $r++){
			$currentID = trim($data[$r][0][0]);
			$currentCliente = trim($data[$r][0][1]);

			$currentPais = trim($data[$r][0][8]);
			$currentEstado = trim($data[$r][0][10]);
			$currentCiudad = trim($data[$r][0][9]);

			//CatAlogo de nuevas zonas
			$flagPais = true;
			$flagEstado = true;
			$flagCiudad = true;

			foreach($catZona as $keyPais => $estados){
				if( ($keyPais == $currentPais) || ($currentPais == '') ){
					$flagPais = false;

					foreach($estados as $keyEstado => $ciudades){
						if(($keyEstado == $currentEstado) || ($currentEstado == '')){
							$flagEstado = false;

							foreach($ciudades as $keyCiudad => $ciudad){
								if(($ciudad == $currentCiudad) || ($currentCiudad == '')){
									$flagCiudad = false; 
									break;
								}
							}
							break;
						}
					}
					break;
				}
			}

			if($flagPais){
				$catZona[$currentPais] = array();
			}

			if($flagEstado){
				$catZona[$currentPais][$currentEstado] = array();
			}

			if($flagCiudad){
				array_push($catZona[$currentPais][$currentEstado], $currentCiudad);
			}

			//CatAlogo de nuevos clientes
			$flagClienteNuevo = true;
			if($currentID == "NONE"){
				foreach($catClienteNuevo as $keyCliente => $valCliente){
					if($valCliente[1] == $currentCliente){
						$flagClienteNuevo = false;
						break;
					}
				}

				if($flagClienteNuevo){
					array_push($catClienteNuevo, array($currentID, $currentCliente));
				}
			}

			//CatAlogo de clientes
			$flagCliente = true;
			foreach($catCliente as $keyCliente => $valCliente){
				if($valCliente[1] == $currentCliente){
					$flagCliente = false;
					break;
				}
			}

			if($flagCliente)
				array_push($catCliente, array($currentID, $currentCliente));
		}

		return $catZona;
	}

	public function qr($query){
		$this->db->query($query);
		return $this->db->insert_id();
	}
}