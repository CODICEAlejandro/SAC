<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarea extends CI_Model {
	public function insertar($data){
		if($this->db->insert('catTarea',$data)) 
			return true;
		else
			return false;
	}

	public function delete($id){
		$this->db->where('id =',$id);
		if($this->db->delete('catTarea'))
			return true;
		else 
			return false;
	}

	public function update($id,$data){
		$this->db->where('id =',$id);
		if($this->db->update('catTarea',$data))
			return true;
		else
			return false;
	}

	//Obtiene una objeto Tarea
	public function traer($id){
		$this->db->where('id =',$id);
		$tarea = $this->db->get('catTarea')->row();

		$this->parseForeignKeys($tarea);
		return $tarea;
	}

	//Obtiene un arreglo con todas las tareas en el catAlogo de tareas
	public function traerTodo(){
		$this->db->order_by('creacion','desc');
		$tareas = $this->db->get('catTarea')->result();

		foreach($tareas as $tarea){
			$this->parseForeignKeys($tarea);
		}

		return $tareas;		
	}

	public function traerAsociadas($id){
		$this->db->order_by('creacion','desc');
		$this->db->where('idResponsable =', $id);
		$tareas = $this->db->get('catTarea')->result();

		foreach($tareas as $tarea){
			$this->parseForeignKeys($tarea);
		}

		return $tareas;
	}


	public function traerAsociadasPendientes($id){
		$this->db->order_by('creacion','desc');
		$this->db->where('idEstado = 1');
		$this->db->where('idResponsable =',$id);
		$tareas = $this->db->get('catTareas')->result();

		foreach($tareas as $tarea){
			$this->parseForeignKeys($tareas);
		}

		return $tareas;
	}

	//Anexa a la estructura de la tarea recibida, los campos obtenidos desde la base de datos:
	// proyecto -> Proyecto
	// cliente -> Cliente
	// estado -> Estado
	// fase -> Fase
	// responsable -> Responsable
	public function parseForeignKeys($tarea){
		$this->db->where('id =',$tarea->idProyecto);
		$tarea->proyecto = $this->db->get('catProyecto')->row();

		$this->db->where('id =',$tarea->proyecto->idCliente);
		$tarea->cliente = $this->db->get('catCliente')->row();

		$this->db->where('id =',$tarea->idEstado);
		$tarea->estado = $this->db->get('catEstado')->row();

		$this->db->where('id =',$tarea->idFase);
		$tarea->fase = $this->db->get('catFase')->row();

		$this->db->where('id =',$tarea->idResponsable);
		$tarea->responsable = $this->db->get('catUsuario')->row();
	}
}
?>