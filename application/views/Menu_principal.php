<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<html>
	<head>
		<title>SAC</title>
	</head>
	<body>
		<ul>
			<li>
				<a href="<?php echo base_url().'index.php/Listar_proyectos_ctrl'; ?>">Proyectos</a>
			</li>
			<li>
				<a href="<?php echo base_url().'index.php/Listar_tareas_ctrl'; ?>">Tareas</a>
			</li>
			<!-- Actividades disponibles solo para el administrador -->
			<?php if($this->session->userdata('tipo') == 1){ ?>
			<li>
				<a href="<?php echo base_url().'index.php/Listar_tareas_calificar_ctrl'; ?>">Calificar</a>
			</li>
			<?php } ?>

			<li>
				<a href="<?php echo base_url().'index.php/Logout_ctrl'; ?>">Salir</a>
			</li>
		</ul>
	</body>
</html>