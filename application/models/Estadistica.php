<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Realiza operaciones estadísticas sobre entidades en la base de datos
class Estadistica extends CI_Model {
	//Cuenta los registros en la tabla pasada por parámetro
	// String -> Integer
	public function count($tableName){
		return $this->db->count_all($tableName);
	}

	//Cuenta los registros en la tabla pasada por parámetro que cumplan con la condición especificada
	//String, String -> Integer
	public function count_where($tableName, $condition){
		$this->db->where($condition);
		$this->db->from($tableName);

		return $this->db->count_all_results();
	}

	//Cuenta el valor de una columna con contenido numérico y retorna la sumatoria
	//String, String -> Integer
	public function count_field($tableName, $field){
		$this->db->select($field);
		$query = $this->db->get($tableName)->result();
		$result = 0;

		foreach($query as $current){
			$result += $current->$field;
		}

		return $result;
	}

	//Cuenta el valor de una columna con contenido numérico que verifiquen una condición dada y retorna la sumatoria
	//String, String -> Integer
	public function count_field_where($tableName, $field, $condition){
		$this->db->select($field);
		$this->db->where($condition);
		$query = $this->db->get($tableName)->result();
		$result = 0;

		foreach($query as $current){
			$result += $current->$field;
		}

		return $result;
	}

	//Cuenta el valor de una columna con contenido de tiempo en formato 00:00 y retorna la sumatoria
	//String, String -> Integer
	public function count_time_field($tableName, $field, $condition = '1'){
		$this->db->select($field);
		$this->db->where($condition);
		$query = $this->db->get($tableName)->result();
		$resultHours = 0;
		$resultMinutes = 0;

		foreach($query as $current){
			echo " -- ".$current->$field." -- ";

			$hours = explode(":", $current->$field)[0];
			$minutes = explode(":", $current->$field)[1];

			$resultMinutes += $minutes;
			$resultHours += $hours;

			while($resultMinutes >= 60){
				$resultHours ++;
				$resultMinutes -= 60;
			}
		}

		$resultHours = (strlen($resultHours) == 1)? "0".$resultHours : $resultHours;
		$resultMinutes = (strlen($resultMinutes) == 1)? "0".$resultMinutes : $resultMinutes;

		$result = $resultHours.':'.$resultMinutes;

		return $result;
	}	
}

?>