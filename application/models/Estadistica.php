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
		$fieldAlias = explode('.',$field);
		if(count($fieldAlias)>1) $fieldAlias = $fieldAlias[1];

		$this->db->select($field.' AS '.$fieldAlias);
		$query = $this->db->get($tableName)->result();
		$result = 0;

		foreach($query as $current){
			$result += $current->$fieldAlias;
		}

		return $result;
	}

	//Cuenta el valor de una columna con contenido numérico que verifiquen una condición dada y retorna la sumatoria
	//String, String -> Integer
	public function count_field_where($tableName, $field, $condition){
		$fieldAlias = explode('.',$field);
		if(count($fieldAlias)>1) $fieldAlias = $fieldAlias[1];

		$this->db->select($field.' AS '.$fieldAlias);
		$this->db->where($condition);
		$query = $this->db->get($tableName)->result();
		$result = 0;

		foreach($query as $current){
			$result += $current->$fieldAlias;
		}

		return $result;
	}

	//Cuenta el valor de una columna con contenido de tiempo en formato 00:00 y retorna la sumatoria
	//String, String -> Integer
	public function count_time_field($tableName, $field, $condition = '1 = 1'){
		$fieldAlias = explode('.',$field);
		if(count($fieldAlias)>1) $fieldAlias = $fieldAlias[1]; 
		else $fieldAlias = $fieldAlias[0];

		$this->db->select($field.' AS '.$fieldAlias);
		$this->db->where($condition);
		$query = $this->db->get($tableName)->result();
		$resultHours = 0;
		$resultMinutes = 0;

		foreach($query as $current){
			$fieldExplode = explode(":", $current->$fieldAlias);

			if(count($fieldExplode) == 2){
				$hours = explode(":", $current->$fieldAlias)[0];
				$minutes = explode(":", $current->$fieldAlias)[1];
			}else{
				$hours = 0;
				$minutes = 0;				
			}

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

	//Suma un tiempo dado con otro
	// 'hh1:mm1', 'hh2:mm2' => 'hh(1+2):mm(1+2)'
	// String, String => String
	public function addTimes($time1, $time2){
		$time1 = explode(":",$time1);
		$time2 = explode(":",$time2);

		if((count($time1) == 2) && (count($time2) == 2)){
			$time1[0] = (int) $time1[0];
			$time1[1] = (int) $time1[1];
			$time2[0] = (int) $time2[0];
			$time2[1] = (int) $time2[1];

			$time3 = array($time1[0]+$time2[0], $time1[1]+$time2[1]);

			$minutes = (int) $time3[1]%60;
			$hours = ((int) ($time3[1]/60)) + $time3[0];

			$minutes = ($minutes < 10)? "0".$minutes : $minutes;
			$hours = ($hours<10)? "0".$hours : $hours;

			return $hours.':'.$minutes; 
		}else return "00:00";
	}
}

?>