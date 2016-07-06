<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retrabajo extends CI_Model {
	public function insert($data){
		foreach($data as $key => $value){
			$data[$key] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		if($this->db->insert('caterror',$data))
			return true;
		else return false;
	}

	public function delete($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');

		$this->db->where('id =',$id);
		if($this->db->delete('caterror'))
			return true;
		else return false;
	}

	public function update($id, $data){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		foreach($data as $key => $value){
			$data[$key] = htmlentities($value,ENT_QUOTES,'UTF-8');
		}

		$this->db->where('id =',$id);
		return $this->db->update('caterror',$data);
	}

	public function traer($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->where('id =',$id);
		$retrabajo = $this->db->get('caterror')->row();

		$this->parseForeignKeys($retrabajo);
		return $retrabajo;
	}

	public function traerTodo(){
		$this->db->order_by('creacion','asc');
		$retrabajos = $this->db->get('caterror')->result();

		foreach($retrabajos as $retrabajo){
			$this->parseForeignKeys($retrabajo);
		}

		return $retrabajos;		
	}

	public function traerAsociados($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->order_by('creacion','asc');
		$retrabajos = $this->db->get('caterror')->result();

		foreach($retrabajos as $key=>$retrabajo){
			$this->parseForeignKeys($retrabajo);

			if(($retrabajo->responsable->id) != $id){
				unset($retrabajos[$key]);
			}
		}

		return $retrabajo;
	}

	//En este caso el estado para editar es el estado 4 = Retrabajo
	//El ciclo de vida de un retrabajo se describe a continuación:
	//Generación del retrabajo con estado 4 -> Completar los datos del retrabajo para pasar al estado 1 (Pendiente)
	//-> Marcar el retrabajo como completado (Estado 2) -> Marcar el retrabajo como calificado (Estado 3) 
	public function traerAsociadosParaEditar($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->order_by('creacion','asc');		
		$this->db->where('idEstado = 4');
		$retrabajos = $this->db->get('caterror')->result();

		foreach($retrabajos as $key=>$retrabajo){
			$this->parseForeignKeys($retrabajo);
		
			if(($retrabajo->responsable->id) != $id){
				unset($retrabajos[$key]);
			}
		}


		return $retrabajos;
	}

	public function traerAsociadosPendientes($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->order_by('creacion','asc');		
		$this->db->where('idEstado = 1');
		$retrabajos = $this->db->get('caterror')->result();

		foreach($retrabajos as $key=>$retrabajo){
			$this->parseForeignKeys($retrabajo);

			if(($retrabajo->responsable->id) != $id){
				unset($retrabajos[$key]);
			}
		}

		return $retrabajos;
	}

	public function traerAsociadosTerminados($id){
		$id = htmlentities($id,ENT_QUOTES,'UTF-8');
		$this->db->order_by('creacion','asc');		
		$this->db->where('idEstado = 2');
		$retrabajos = $this->db->get('caterror')->result();

		foreach($retrabajos as $key=>$retrabajo){
			$this->parseForeignKeys($retrabajo);

			if(($retrabajo->responsable->id) != $id){
				unset($retrabajos[$key]);
			}
		}

		return $retrabajos;
	}

	public function traerParaEditar(){
		$this->db->order_by('creacion','asc');		
		$this->db->where('idEstado =',4);
		return $this->db->get('caterror')->result();
	}

	public function traerParaTerminar(){
		$this->db->order_by('creacion','asc');		
		$this->db->where('idEstado =',1);
		return $this->db->get('caterror')->result();
	}

	//Obtener lista enlazada de retrabajos (Más reciente -> ... -> Origen)
	public function traerHistorialAsociado($idRetrabajo){
		$idRetrabajo = htmlentities($idRetrabajo,ENT_QUOTES,'UTF-8');
		$this->load->model('Tarea');

		//Obtener retrabajo actual
		$this->db->where('id =',$idRetrabajo);
		$retrabajo = $this->traer($idRetrabajo);

		//Obtener tarea origen
		$tareaOrigen = $this->Tarea->traer($retrabajo->idTareaOrigen);

		//Obtener retrabajos asociados con fecha ascendente
		$this->db->order_by('creacion','asc');
		$this->db->where('idTareaOrigen =',$retrabajo->idTareaOrigen);
		$this->db->where('id !=',$idRetrabajo);
		$retrabajosAsociados = $this->db->get('caterror')->result();

		foreach($retrabajosAsociados as $retrabajo){
			$this->parseForeignKeys($retrabajo);
		}

		//Adjuntar en orden: $retrabajosAsociados,$tareaOrigen
		array_push($retrabajosAsociados,$tareaOrigen);

		return $retrabajosAsociados;
	}

	public function parseForeignKeys($retrabajo){
		$this->load->model('Tarea');
		$tareaOrigen = $this->Tarea->traer($retrabajo->idTareaOrigen);
		$retrabajo->responsable = $tareaOrigen->responsable;
		$retrabajo->cliente = $tareaOrigen->cliente;
		$retrabajo->proyecto = $tareaOrigen->proyecto;
		$retrabajo->tareaOrigen = $tareaOrigen;

		$this->db->where('id =',$retrabajo->idEstado);
		$retrabajo->estado = $this->db->get('catestado')->row();
	}
}

?>
