<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarea extends CI_Model {
	public function insertar($data){
		foreach($data as $key => $value){
			$data[$key] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		if($this->db->insert('cattarea',$data)) 
			return true;
		else
			return false;
	}

	public function delete($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');

		$this->db->where('id =',$id);
		if($this->db->delete('cattarea'))
			return true;
		else 
			return false;
	}

	public function update($id,$data){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		foreach($data as $key => $value){
			$data[$key] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		$this->db->where('id =',$id);
		if($this->db->update('cattarea',$data))
			return true;
		else
			return false;
	}

	//Obtiene una objeto Tarea
	public function traer($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->where('id =',$id);
		$tarea = $this->db->get('cattarea')->row();

		$this->parseForeignKeys($tarea);
		return $tarea;
	}

	//Obtiene un arreglo con todas las tareas en el catAlogo de tareas
	public function traerTodo(){
		$this->db->order_by('creacion','asc');
		$tareas = $this->db->get('cattarea')->result();

		foreach($tareas as $tarea){
			$this->parseForeignKeys($tarea);
		}

		return $tareas;		
	}

	public function traerAsociadas($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->order_by('creacion','asc');
		$this->db->where('idResponsable =', $id);
		$tareas = $this->db->get('cattarea')->result();

		foreach($tareas as $tarea){
			$this->parseForeignKeys($tarea);
		}

		return $tareas;
	}

	public function traerAsociadasTerminadas($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->order_by('creacion','asc');
		$this->db->where('idEstado = 2');
		$this->db->where('idResponsable =',$id);
		$tareas = $this->db->get('cattarea')->result();

		foreach ($tareas as $cTarea) {
			$this->parseForeignKeys($cTarea);
		}

		return $tareas;
	}

	public function traerAsociadasPendientes($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->order_by('creacion','asc');
		$this->db->where('idEstado = 1');
		$this->db->where('idResponsable =',$id);
		$tareas = $this->db->get('cattarea')->result();

		foreach($tareas as $tarea){
			$this->parseForeignKeys($tareas);
		}

		return $tareas;
	}

	public function traerPendientes($idArea='', $fechaInicio='', $fechaFin=''){
		return $this->get('PENDIENTES', $idArea, $fechaInicio, $fechaFin);
	}

	public function traerTerminados($idArea='', $fechaInicio='', $fechaFin=''){
		return $this->get('TERMINADOS', $idArea, $fechaInicio, $fechaFin);
	}

	public function traerCalificados($idArea='', $fechaInicio='', $fechaFin='', $itemsPerPage='', $cPage=''){
		return $this->get('CALIFICADOS', $idArea, $fechaInicio, $fechaFin, $itemsPerPage, $cPage, 1);
	}

	public function get($edo='', $idArea = '', $fechaInicio='', $fechaFin='', $itemsPerPage='', $cPage='', $nPages=''){
		$idArea = htmlentities($idArea, ENT_QUOTES, 'UTF-8');
		$fechaInicio = htmlentities($fechaInicio, ENT_QUOTES, 'UTF-8');
		$fechaFin = htmlentities($fechaFin, ENT_QUOTES, 'UTF-8');
		$edo = strtoupper( htmlentities($edo, ENT_QUOTES, 'UTF-8') );

		switch($edo){
			case 'PENDIENTES':
				$edo = 1;
				break;
			case 'TERMINADOS':
				$edo = 2;
				break;
			case 'CALIFICADOS':
				$edo = 3;
				break;
			default:
				$edo = -1;
				break;
		}

		$query = "SELECT ct.*,
				'N' AS esRetrabajo,
				'Tarea' AS tipo
				FROM 
					`cattarea` AS ct
				INNER JOIN
					`catusuario` AS cu
					ON ct.`idResponsable` = cu.`id`
				WHERE
					ct.`idEstado` = ".$edo;

		if( ($idArea != '') && ($idArea != 'ALL') && is_numeric($idArea) ){
			$query .= " AND cu.`idArea` = ".$idArea;
		}

		if( ($fechaInicio != '') && ($fechaFin != '') ) 
			$query .= " AND ct.`creacion` BETWEEN '".$fechaInicio."' AND DATE_ADD('".$fechaFin."', INTERVAL 1 DAY)";

		$query .= ' ORDER BY ct.`creacion` DESC';

		if( ($itemsPerPage!='') && ($cPage!='') && ($nPages!='') )
			$query .= ' LIMIT '.($itemsPerPage+1).' OFFSET '.($cPage * $itemsPerPage);

		return $this->parseForeignKeys($this->db->query($query)->result());
	}

	//Anexa a la estructura de la tarea recibida, los campos obtenidos desde la base de datos:
	// proyecto -> Proyecto
	// cliente -> Cliente
	// estado -> Estado
	// fase -> Fase
	// responsable -> Responsable
	public function parseForeignKeys($tarea){
		if( is_array($tarea) ){
			foreach($tarea as $t){
				$this->db->where('id =',$t->idProyecto);
				$t->proyecto = $this->db->get('catproyecto')->row();

				$this->db->where('id =',$t->proyecto->idCliente);
				$t->cliente = $this->db->get('catcliente')->row();

				$this->db->where('id =',$t->idEstado);
				$t->estado = $this->db->get('catestado')->row();

				$this->db->where('id =',$t->idFase);
				$t->fase = $this->db->get('catfase')->row();

				$this->db->where('id =',$t->idResponsable);
				$t->responsable = $this->db->get('catusuario')->row();	
			}
		}else{
			$this->db->where('id =',$tarea->idProyecto);
			$tarea->proyecto = $this->db->get('catproyecto')->row();

			$this->db->where('id =',$tarea->proyecto->idCliente);
			$tarea->cliente = $this->db->get('catcliente')->row();

			$this->db->where('id =',$tarea->idEstado);
			$tarea->estado = $this->db->get('catestado')->row();

			$this->db->where('id =',$tarea->idFase);
			$tarea->fase = $this->db->get('catfase')->row();

			$this->db->where('id =',$tarea->idResponsable);
			$tarea->responsable = $this->db->get('catusuario')->row();
		}

		return $tarea;
	}
}
?>
