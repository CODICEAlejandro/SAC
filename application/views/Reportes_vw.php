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
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Reporte_tiempos_tareas_ctrl'; ?>" style="text-align: left;">Panorámico de tiempos y tareas</a>
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Reporte_acumulado_tiempo_ctrl'; ?>" style="text-align: left;">Acumulado en tiempo</a>
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Reporte_rentabilidad_ctrl'; ?>" style="text-align: left;">Reporte de rentabilidad</a>
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Reporte_control_tareas_ctrl'; ?>" style="text-align: left;">Control de tareas</a>
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Reporte_master_ctrl'; ?>" style="text-align: left;">Reporte master</a>
					<a class="btn list-group-item" href="<?php echo base_url().'index.php/Facturacion/Reporte_facturacion_ctrl'; ?>" style="text-align: left;">Reporte de facturación</a>
				</div>
			</div>
		</div>		
	</div>
</body>
</html>