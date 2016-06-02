<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<!-- Control de Usuarios -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Control de usuarios</h3>				
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="list-group">
					<a class="btn list-group-item" href="#" style="text-align: left;">Agregar</a>
					<a class="btn list-group-item" href="#" style="text-align: left;">Editar</a>
					<a class="btn list-group-item" href="#" style="text-align: left;">Dar de baja</a>
				</div>
			</div>
		</div>

		<!-- Control de Clientes -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Control de clientes</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a class="btn list-group-item" href="#" style="text-align: left;">Agregar</a>
				<a class="btn list-group-item" href="#" style="text-align: left;">Editar</a>
				<a class="btn list-group-item" href="#" style="text-align: left;">Dar de baja</a>
			</div>
		</div>

		<!-- Control de Proyectos -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Control de proyectos</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a class="btn list-group-item" href="http://localhost:8888/JOBS/index.php/Nuevo_proyecto_ctrl" style="text-align: left;">Agregar</a>
				<a class="btn list-group-item" href="#" style="text-align: left;">Editar</a>
				<a class="btn list-group-item" href="#" style="text-align: left;">Dar de baja</a>
			</div>
		</div>		
	</div>
</body>
</html>