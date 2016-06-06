<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="list-group">
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Reporte_tiempos_tareas_ctrl'; ?>" style="text-align: left;">Tiempos y tareas</a>
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Reporte_diario_ctrl'; ?>" style="text-align: left;">Rutinario</a>
				</div>
			</div>
		</div>		
	</div>
</body>
</html>