<?php
function includeJQuery(){
	echo '<script src="'.base_url().'/includes/js/jQuery.js"></script>';
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
?>
