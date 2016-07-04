<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>	
</head>
<body>
	<?= $menu; ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h1>Pendientes: <?php echo $totalPendientes; ?></h1>
				<h1>Terminados: <?php echo $totalTerminadas; ?></h1>
				<h1>Calificados: <?php echo $totalCalificadas; ?></h1>
				<h1>Total de tareas: <?php echo $totalTareas; ?></h1>
				<br>
				<h1>Errores: <?php echo $totalErrores; ?></h1>
				<br>
				<h1>Tiempo total real: <?php echo $tiempoTotalReal; ?></h1>
				<h1>Tiempo total estimado: <?php echo $tiempoTotalEstimado; ?></h1>
			</div>
		</div>
	</div>
</body>
</html>