<?php
function includeJQuery(){
	echo '<script src="'.base_url().'/includes/js/jQuery.js"></script>';
	echo '<script src="'.base_url().'/includes/jquery-ui/jquery-ui.js'.'"></script>';
	echo '<link rel="stylesheet" href="'.base_url().'/includes/jquery-ui/jquery-ui.css">';
}

function includeBootstrap(){
	echo '<link rel="stylesheet" href="'.base_url().'includes/css/bootstrap.min.css">';
	echo '<link rel="stylesheet" href="'.base_url().'includes/css/bootstrap-theme.min.css">';

	echo '<script src="'.base_url().'includes/js/npm.js"></script>';
	echo '<script src="'.base_url().'includes/js/bootstrap.min.js"></script>';
}

function includeMetaInformation(){
	echo '<meta charset="utf-8">';
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}


function includeAuxiliars(){
	echo '<script type="text/javascript" src="'.base_url().'includes/js/mainFunctions.js"></script>';
}

function checkSession(){
	if(!empty($_SESSION) && isset($_SESSION['user_active']) && $_SESSION['user_active']==1){
		return true;
	}else{
		redirect(base_url().'index.php/Login_ctrl/load_vw');
	}
}

//#########################  Funciones de manipulación de fechas ############################
// Obtiene la diferencia en el formato indicado (por default días) entre dos fechas
// $date_1 = yy-mm-dd
// $date_2 = yy-mm-dd
// $differenceFormat = mask
// string, string, string -> integer
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ){
    $datetime1 = new DateTime($date_1);
    $datetime2 = new DateTime($date_2);
   
    $interval = $datetime1->diff($datetime2)->days;
   
    return $interval->format($differenceFormat);
}

// Verifica si una fecha es el día indicado
// $date = yy-mm-dd
// $dayNumber = [0,6]
// string, integer -> boolean
function isDay($date, $dayNumber){
	$date = new DateTime($date);
	$dateDay = $date->format('w');

	if( is_array($dayNumber) ){
		foreach($dayNumber as $key => $number)
			if($dateDay == $number) return true;
		return false;
	}else
		return $dateDay == $dayNumber;
}

?>
