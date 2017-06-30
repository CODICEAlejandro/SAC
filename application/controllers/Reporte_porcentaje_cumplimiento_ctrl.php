<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Mexico_City');

class Reporte_porcentaje_cumplimiento_ctrl extends CI_Controller {
	
	public function index(){
		checkSession();
		$data['menu'] = $this->load->view('Menu_principal',null,false);

		$fechaFin = date('Y-m-d');
		$fechaInicio = strtotime ( '-1 day' , strtotime ( $fechaFin ) ) ;
		$fechaInicio = date ( 'Y-m-d' , $fechaInicio );
		$diaInicio = date ('w');
		if($diaInicio == 0){
			$diaInicio = 6;
		}
		else{
			$diaInicio -= 1;
		}
		$fechaInicioDT = new DateTime($fechaInicio);
		$fechaFinDT = new DateTime($fechaFin);

		$diasIntervalo = $fechaInicioDT->diff($fechaFinDT);
		$diasIntervalo = $diasIntervalo->d;

		$diasIntervalo = $this->obtenDiasIntervalo($diaInicio,$diasIntervalo);

		$this->load->model('Estadistica');
		

		////////Trae datos de consultores
		$query_trae_consultores = "SELECT 0 porcentaje, '00:00' tiempoReal, nombre, id idConsultor, (horasLunes-1) horasLunes, (horasMartes-1) horasMartes, (horasMiercoles-1) horasMiercoles, (horasJueves-1) horasJueves, (horasViernes-1) horasViernes  
			FROM catusuario u WHERE u.idArea NOT IN (5,8) AND u.activo = 'S' ORDER BY u.nombre ASC";

		$consultores  = $this->db->query($query_trae_consultores)->result();
			
		$query_trae_horas = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion,t.idEstado idEstadoTarea, et.nombre estadoTarea, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, u.nombre nombre, t.idResponsable idConsultor
			FROM catusuario u JOIN cattarea t ON u.id = t.idResponsable
			JOIN catestado et ON t.idEstado = et.id
			WHERE u.idArea NOT IN (5,8) AND u.activo = 'S'
    		AND (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
    		ORDER BY t.creacion DESC, u.nombre ASC";

    	$horas = $this->db->query($query_trae_horas)->result();

    	for ($i=0, $n= count($consultores); $i < $n; $i++) { 
    		for($j=0, $m=count($horas); $j < $m; $j++){
    			if($horas[$j]->idConsultor == $consultores[$i]->idConsultor){
    				if($horas[$j]->idEstadoTarea==1){
    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoEstimado); 
    				}else{
    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoReal); 
    				}
    			}
    		}

    		$consultores[$i]->horasLunes = ($consultores[$i]->horasLunes)*($diasIntervalo[1]);
    		$consultores[$i]->horasMartes = ($consultores[$i]->horasMartes)*($diasIntervalo[2]);
    		$consultores[$i]->horasMiercoles = ($consultores[$i]->horasMiercoles)*($diasIntervalo[3]);
    		$consultores[$i]->horasJueves = ($consultores[$i]->horasJueves)*($diasIntervalo[4]);
    		$consultores[$i]->horasViernes = ($consultores[$i]->horasViernes)*($diasIntervalo[5]);

    		$horasTotales = $consultores[$i]->horasLunes+$consultores[$i]->horasMartes+$consultores[$i]->horasMiercoles+$consultores[$i]->horasJueves+$consultores[$i]->horasViernes;

    		$horasReales = $this->obtenNumeroHorasyMinutos($consultores[$i]->tiempoReal);

    		$porcentaje = $this->obtenPorcentajeCumplimiento($horasTotales,$horasReales[0],$horasReales[1]);

    		$consultores[$i]->porcentaje = $porcentaje;
    	}
    	$data['users']= array();
    	$data['users'] = $consultores;

    	//////////////Trae datos para Códice
    	$horasTotales= 0;
		$horasReales = 0;
		$tiempoReal = '00:00';

		$codice = new \stdClass;

		$query_trae_consultores = "SELECT 0 porcentaje, '00:00' tiempoReal, nombre, id idConsultor, (horasLunes-1) horasLunes, (horasMartes-1) horasMartes, (horasMiercoles-1) horasMiercoles, (horasJueves-1) horasJueves, (horasViernes-1) horasViernes 
			FROM catusuario u WHERE u.idArea NOT IN (5,8) AND u.activo = 'S' ORDER BY u.nombre ASC";

		$consultores  = $this->db->query($query_trae_consultores)->result();
			
		$query_trae_horas = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion,t.idEstado idEstadoTarea, et.nombre estadoTarea, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, u.nombre nombre, t.idResponsable idConsultor
			FROM catusuario u JOIN cattarea t ON u.id = t.idResponsable
			JOIN catestado et ON t.idEstado = et.id
			WHERE u.idArea NOT IN (5,8) AND u.activo = 'S'
    		AND (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
    		ORDER BY t.creacion DESC, u.nombre ASC";

    	$horas = $this->db->query($query_trae_horas)->result();

    	for ($i=0, $n= count($consultores); $i < $n; $i++) { 
    		for($j=0, $m=count($horas); $j < $m; $j++){
    			if($horas[$j]->idConsultor == $consultores[$i]->idConsultor){
    				if($horas[$j]->idEstadoTarea==1){
    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoEstimado); 
    				}else{
    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoReal); 
    				}
    			}
    		}

    		$consultores[$i]->horasLunes = ($consultores[$i]->horasLunes)*($diasIntervalo[1]);
    		$consultores[$i]->horasMartes = ($consultores[$i]->horasMartes)*($diasIntervalo[2]);
    		$consultores[$i]->horasMiercoles = ($consultores[$i]->horasMiercoles)*($diasIntervalo[3]);
    		$consultores[$i]->horasJueves = ($consultores[$i]->horasJueves)*($diasIntervalo[4]);
    		$consultores[$i]->horasViernes = ($consultores[$i]->horasViernes)*($diasIntervalo[5]);

    		$horasTotales += $consultores[$i]->horasLunes+$consultores[$i]->horasMartes+$consultores[$i]->horasMiercoles+$consultores[$i]->horasJueves+$consultores[$i]->horasViernes;
    		

    		$tiempoReal = $this->Estadistica->addTimes($tiempoReal,$consultores[$i]->tiempoReal);

    
    	}
    	$horasReales = $this->obtenNumeroHorasyMinutos($tiempoReal);
    	$porcentajeCodice = $this->obtenPorcentajeCumplimiento($horasTotales,$horasReales[0],$horasReales[1]);
    	$codice->tiempoReal = $tiempoReal;
    	$codice->tiempoTotal = $this->obtenFormatoHoras($horasTotales);
    	$codice->porcentaje = $porcentajeCodice;

    	$data['codice'] = $codice;

    	////////////Trae datos para clientes
    	$horasTotales = "00:00";

		$query_trae_clientes = "SELECT 0 porcentaje, '00:00' tiempoReal, nombre cliente, id idCliente
			FROM catcliente WHERE tipo=0 AND estadoActivo=1 ORDER BY nombre ASC";

		$clientes= $this->db->query($query_trae_clientes)->result();

		$query_trae_horas = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion, c.nombre cliente, c.id idCliente, p.nombre proyecto, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, t.idEstado idEstadoTarea
			FROM cattarea t JOIN  catproyecto p ON t.idProyecto = p.id
			JOIN catcliente c ON p.idCliente = c.id
			WHERE (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."') 
			ORDER BY c.nombre ASC";
		
		$horas  = $this->db->query($query_trae_horas)->result();

		for($i=0, $n=count($clientes); $i<$n; $i++){
			for($j=0, $m=count($horas); $j < $m; $j++){
    			if($horas[$j]->idCliente == $clientes[$i]->idCliente){
    				if($horas[$j]->idEstadoTarea==1){
    					$clientes[$i]->tiempoReal = $this->Estadistica->addTimes($clientes[$i]->tiempoReal,$horas[$j]->tiempoEstimado); 
    				}else{
    					$clientes[$i]->tiempoReal = $this->Estadistica->addTimes($clientes[$i]->tiempoReal,$horas[$j]->tiempoReal); 
    				}
    			}
    		}

    		$horasTotales = $this->Estadistica->addTimes($horasTotales,$clientes[$i]->tiempoReal);
		}

		$horasTotales = $this->obtenHoraEnDecimal($horasTotales);
		
		for ($i=0, $n=count($clientes); $i < $n; $i++) { 

			$tiempoReal = $this->obtenNumeroHorasyMinutos($clientes[$i]->tiempoReal);
			$clientes[$i]->porcentaje = $this->obtenPorcentajeCumplimiento($horasTotales,$tiempoReal[0],$tiempoReal[1]);

		}

		$data['clientes'] = $clientes;

		
		$this->load->view('Reporte_porcentaje_cumplimiento_vw',$data);
		
	}

	public function traerHorasConsultor(){

		$this->load->model('Estadistica');

		if(isset($_POST)){

			$fechaInicio = $_POST["fechaInicio"];
			$fechaFin = $_POST["fechaFin"];
			$diasIntervalo = $_POST["diasIntervalo"];

			$query_trae_consultores = "SELECT 0 porcentaje, '00:00' tiempoReal, nombre, id idConsultor, (horasLunes-1) horasLunes, (horasMartes-1) horasMartes, (horasMiercoles-1) horasMiercoles, (horasJueves-1) horasJueves, (horasViernes-1) horasViernes  
				FROM catusuario u WHERE u.idArea NOT IN (5,8) AND u.activo = 'S' ORDER BY u.nombre ASC";

			$consultores  = $this->db->query($query_trae_consultores)->result();
				
			$query_trae_horas = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion,t.idEstado idEstadoTarea, et.nombre estadoTarea, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, u.nombre nombre, t.idResponsable idConsultor
				FROM catusuario u JOIN cattarea t ON u.id = t.idResponsable
				JOIN catestado et ON t.idEstado = et.id
				WHERE u.idArea NOT IN (5,8) AND u.activo = 'S'
	    		AND (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
	    		ORDER BY t.creacion DESC, u.nombre ASC";

	    	$horas = $this->db->query($query_trae_horas)->result();

	    	for ($i=0, $n= count($consultores); $i < $n; $i++) { 
	    		for($j=0, $m=count($horas); $j < $m; $j++){
	    			if($horas[$j]->idConsultor == $consultores[$i]->idConsultor){
	    				if($horas[$j]->idEstadoTarea==1){
	    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoEstimado); 
	    				}else{
	    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoReal); 
	    				}
	    			}
	    		}

	    		$consultores[$i]->horasLunes = ($consultores[$i]->horasLunes)*($diasIntervalo[1]);
	    		$consultores[$i]->horasMartes = ($consultores[$i]->horasMartes)*($diasIntervalo[2]);
	    		$consultores[$i]->horasMiercoles = ($consultores[$i]->horasMiercoles)*($diasIntervalo[3]);
	    		$consultores[$i]->horasJueves = ($consultores[$i]->horasJueves)*($diasIntervalo[4]);
	    		$consultores[$i]->horasViernes = ($consultores[$i]->horasViernes)*($diasIntervalo[5]);

	    		$horasTotales = $consultores[$i]->horasLunes+$consultores[$i]->horasMartes+$consultores[$i]->horasMiercoles+$consultores[$i]->horasJueves+$consultores[$i]->horasViernes;

	    		$horasReales = $this->obtenNumeroHorasyMinutos($consultores[$i]->tiempoReal);

	    		$porcentaje = $this->obtenPorcentajeCumplimiento($horasTotales,$horasReales[0],$horasReales[1]);

	    		$consultores[$i]->porcentaje = $porcentaje;
	    	}
			echo json_encode($consultores);
		}
	}


	public function traerHorasCodice(){

		$this->load->model('Estadistica');

		if(isset($_POST)){

			$fechaInicio = $_POST["fechaInicio"];
			$fechaFin = $_POST["fechaFin"];
			$diasIntervalo = $_POST["diasIntervalo"];

			$horasTotales= 0;
			$horasReales = 0;
			$tiempoReal = '00:00';

			$codice = new \stdClass;

			$query_trae_consultores = "SELECT 0 porcentaje, '00:00' tiempoReal, nombre, id idConsultor, (horasLunes-1) horasLunes, (horasMartes-1) horasMartes, (horasMiercoles-1) horasMiercoles, (horasJueves-1) horasJueves, (horasViernes-1) horasViernes  
				FROM catusuario u WHERE u.idArea NOT IN (5,8) AND u.activo = 'S' ORDER BY u.nombre ASC";

			$consultores  = $this->db->query($query_trae_consultores)->result();
				
			$query_trae_horas = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion,t.idEstado idEstadoTarea, et.nombre estadoTarea, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, u.nombre nombre, t.idResponsable idConsultor
				FROM catusuario u JOIN cattarea t ON u.id = t.idResponsable
				JOIN catestado et ON t.idEstado = et.id
				WHERE u.idArea NOT IN (5,8) AND u.activo = 'S'
	    		AND (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
	    		ORDER BY t.creacion DESC, u.nombre ASC";

	    	$horas = $this->db->query($query_trae_horas)->result();

	    	for ($i=0, $n= count($consultores); $i < $n; $i++) { 
	    		for($j=0, $m=count($horas); $j < $m; $j++){
	    			if($horas[$j]->idConsultor == $consultores[$i]->idConsultor){
	    				if($horas[$j]->idEstadoTarea==1){
	    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoEstimado); 
	    				}else{
	    					$consultores[$i]->tiempoReal = $this->Estadistica->addTimes($consultores[$i]->tiempoReal,$horas[$j]->tiempoReal); 
	    				}
	    			}
	    		}

	    		$consultores[$i]->horasLunes = ($consultores[$i]->horasLunes)*($diasIntervalo[1]);
	    		$consultores[$i]->horasMartes = ($consultores[$i]->horasMartes)*($diasIntervalo[2]);
	    		$consultores[$i]->horasMiercoles = ($consultores[$i]->horasMiercoles)*($diasIntervalo[3]);
	    		$consultores[$i]->horasJueves = ($consultores[$i]->horasJueves)*($diasIntervalo[4]);
	    		$consultores[$i]->horasViernes = ($consultores[$i]->horasViernes)*($diasIntervalo[5]);

	    		$horasTotales += $consultores[$i]->horasLunes+$consultores[$i]->horasMartes+$consultores[$i]->horasMiercoles+$consultores[$i]->horasJueves+$consultores[$i]->horasViernes;
	    		

	    		$tiempoReal = $this->Estadistica->addTimes($tiempoReal,$consultores[$i]->tiempoReal);

	    		//$porcentaje = $this->obtenPorcentajeCumplimiento($horasTotales,$horasReales[0],$horasReales[1]);

	    		//$consultores[$i]->porcentaje = $porcentaje;
	    	}
	    	$horasReales = $this->obtenNumeroHorasyMinutos($tiempoReal);
	    	$porcentajeCodice = $this->obtenPorcentajeCumplimiento($horasTotales,$horasReales[0],$horasReales[1]);
	    	$codice->tiempoReal = $tiempoReal;
	    	$codice->tiempoTotal = $this->obtenFormatoHoras($horasTotales);
	    	$codice->porcentaje = $porcentajeCodice;
			echo json_encode($codice);
		}
	}

	public function traerHorasClientes(){

		$this->load->model('Estadistica');

		if(isset($_POST)){

			$fechaInicio = $_POST["fechaInicio"];
			$fechaFin = $_POST["fechaFin"];
			$diasIntervalo = $_POST["diasIntervalo"];

			$horasTotales = "00:00";

			$query_trae_clientes = "SELECT 0 porcentaje, '00:00' tiempoReal, nombre cliente, id idCliente
				FROM catcliente WHERE tipo=0 AND estadoActivo=1 ORDER BY nombre ASC";

			$clientes= $this->db->query($query_trae_clientes)->result();

			$query_trae_horas = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion, c.nombre cliente, c.id idCliente, p.nombre proyecto, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, t.idEstado idEstadoTarea
				FROM cattarea t JOIN  catproyecto p ON t.idProyecto = p.id
				JOIN catcliente c ON p.idCliente = c.id
				WHERE (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."') 
				ORDER BY c.nombre ASC";
			
			$horas  = $this->db->query($query_trae_horas)->result();

			for($i=0, $n=count($clientes); $i<$n; $i++){
				for($j=0, $m=count($horas); $j < $m; $j++){
	    			if($horas[$j]->idCliente == $clientes[$i]->idCliente){
	    				if($horas[$j]->idEstadoTarea==1){
	    					$clientes[$i]->tiempoReal = $this->Estadistica->addTimes($clientes[$i]->tiempoReal,$horas[$j]->tiempoEstimado); 
	    				}else{
	    					$clientes[$i]->tiempoReal = $this->Estadistica->addTimes($clientes[$i]->tiempoReal,$horas[$j]->tiempoReal); 
	    				}
	    			}
	    		}

	    		$horasTotales = $this->Estadistica->addTimes($horasTotales,$clientes[$i]->tiempoReal);
			}

			$horasTotales = $this->obtenHoraEnDecimal($horasTotales);
			
			for ($i=0, $n=count($clientes); $i < $n; $i++) { 

				$tiempoReal = $this->obtenNumeroHorasyMinutos($clientes[$i]->tiempoReal);
				$clientes[$i]->porcentaje = $this->obtenPorcentajeCumplimiento($horasTotales,$tiempoReal[0],$tiempoReal[1]);

			}

			echo json_encode($clientes);
		}
	}

	public function obtenFormatoHoras($horasSinFormato){

		return $horasSinFormato.":00";
	}

	public function obtenNumeroHorasyMinutos($hora)
	{
		$f = explode(":",$hora);

		if($f[0][0] == 0){
			$f[0] = $f[0][1];
		}

		if($f[1][0] == 0){
			$f[1] = $f[1][1];
		}

		return $f;
	}

	public function obtenPorcentajeCumplimiento($horasTotales,$horasReales,$minReales){


		$minReales = $minReales/60;

		$sumahoramin = $horasReales+$minReales;

		if($horasTotales!=0){
			$porcentaje = ($sumahoramin*100)/$horasTotales;
			$porcentaje = round($porcentaje,2);
		}else{
			$porcentaje=0;
		}

		return $porcentaje;
	}


	public function obtenHoraEnDecimal($horaConFormato){
		$horaDecimal = explode(":", $horaConFormato);

		$horas = intval($horaDecimal[0]);
		$minutos = (intval($horaDecimal[1]))/60;
		if($minutos!=0){
			$minutos = explode(".",$minutos);

			return $horas.".".$minutos[1];
		}

		return $horas.".".$minutos;
	}

	public function traeActividadesConsultor(){
		
		if (isset($_POST)) {
			$fechaInicio = $_POST["fechaInicio"];
			$fechaFin = $_POST["fechaFin"];
			$idConsultor = $_POST["idConsultor"];

			$query_trae_act_consultor = "SELECT t.idEstado idEstadoTarea, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, t.titulo titulo, u.nombre nombre, c.nombre cliente, p.nombre proyecto, t.idResponsable idConsultor
			FROM catusuario u JOIN cattarea t ON u.id = t.idResponsable
			JOIN catproyecto p ON t.idProyecto = p.id
            JOIN catcliente c ON p.idCliente = c.id
			WHERE (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
            AND u.id = ".$idConsultor."
    		ORDER BY t.creacion DESC";

    		$act_consultor = $this->db->query($query_trae_act_consultor)->result();

    		echo json_encode($act_consultor);

		}
	}

	public function traeActividadesCliente(){
		
		if (isset($_POST)) {
			$fechaInicio = $_POST["fechaInicio"];
			$fechaFin = $_POST["fechaFin"];
			$idCliente = $_POST["idCliente"];

			$query_trae_act_cliente = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion, c.nombre cliente, c.id idCliente, u.nombre consultor, p.nombre proyecto, t.titulo titulo, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, t.idEstado estadoTarea
				FROM cattarea t JOIN  catproyecto p ON t.idProyecto = p.id
				JOIN catcliente c ON p.idCliente = c.id
				JOIN catusuario u ON t.idResponsable = u.id
				WHERE (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."')
                AND c.id = ".$idCliente."
				ORDER BY t.creacion DESC";

    		$act_cliente = $this->db->query($query_trae_act_cliente)->result();

    		echo json_encode($act_cliente);

		}
	}

	public function traeActividadesCodice(){
		
		if (isset($_POST)) {
			$fechaInicio = $_POST["fechaInicio"];
			$fechaFin = $_POST["fechaFin"];

			$query_trae_act_codice = "SELECT DATE_FORMAT(t.creacion,'%d-%m-%Y') creacion, c.nombre cliente, c.id idCliente, u.nombre consultor, p.nombre proyecto, t.titulo titulo, t.tiempo tiempoReal, t.tiempoEstimado tiempoEstimado, t.idEstado idEstadoTarea
				FROM cattarea t JOIN  catproyecto p ON t.idProyecto = p.id
				JOIN catcliente c ON p.idCliente = c.id
                JOIN catusuario u ON t.idResponsable = u.id
				WHERE (t.creacion BETWEEN '".$fechaInicio."' AND '".$fechaFin."') 
				ORDER BY u.nombre ASC, t.creacion DESC";

    		$act_codice = $this->db->query($query_trae_act_codice)->result();

    		echo json_encode($act_codice);

		}
	}

	public function obtenDiasIntervalo($diaInicio,$diferencia){
		$dom=0;$lun=0;$mar=0;$mie=0;$jue=0;$vie=0;$sab=0;

		for ($i=0; $i < $diferencia; $i++) {
		
			switch($diaInicio){
				case 0:
					$dom++;
					break;
				case 1:
					$lun++;
					break;
				case 2:
					$mar++;
					break;
				case 3:
					$mie++;
					break;
				case 4:
					$jue++;
					break;
				case 5:
					$vie++;
					break;
				case 6:
					$sab++;
					break;
			}

			$diaInicio++;
			if ($diaInicio==7) {$diaInicio=0;}
		}

		$numDias = array($dom,$lun,$mar,$mie,$jue,$vie,$sab);
		return $numDias;
	}
	
}

?>