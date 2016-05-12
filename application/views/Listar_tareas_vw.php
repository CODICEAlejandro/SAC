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
	<?=$menu ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">

				<table width="100%" border="1" class="table table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Cliente</th>
							<th>Proyecto</th>
							<th>Título</th>
							<th>Fase</th>
							<th>Tipo</th>
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tareas as $tarea){ ?>
						<?php if($tarea->idEstado == 1){ ?>
						<tr 
							onclick="location='<?php echo base_url().'index.php/Marcar_terminado_ctrl/traerTarea/'.($tarea->id); ?>'"
							class="info"
						>
							<td align="center"><?php echo $tarea->id; ?></td>
							<td align="center"><?php echo $tarea->cliente->nombre; ?></td>
							<td align="center"><?php echo $tarea->proyecto->nombre; ?></td>
							<td align="center"><?php echo $tarea->titulo; ?></td>
							<td align="center"><?php echo $tarea->fase->nombre; ?></td>
							<td align="center">Tarea</td>
							<td align="center"><?php echo $tarea->estado->nombre; ?></td>
						</tr>
						<?php } ?>
						<?php } ?>

						<?php foreach($retrabajos as $retrabajo){ ?>
						<tr 
							onclick="location='<?php echo base_url().'index.php/Marcar_terminado_ctrl/traerRetrabajo/'.($retrabajo->id); ?>'"
							class="danger"
						>
							<td align="center"><?php echo $retrabajo->tareaOrigen->id; ?></td>
							<td align="center"><?php echo $retrabajo->cliente->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->proyecto->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->titulo; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->fase->nombre; ?></td>
							<td align="center">Retrabajo</td>
							<td align="center"><?php echo $retrabajo->estado->nombre; ?></td>
						</tr>
						<?php } ?>

						<?php foreach($retrabajosEdit as $retrabajo){ ?>
						<tr 
							onclick="location='<?php echo base_url().'index.php/Alta_tarea_ctrl/editarRetrabajo/'.($retrabajo->id); ?>'"
							class="danger"
						>
							<td align="center"><?php echo $retrabajo->tareaOrigen->id; ?></td>
							<td align="center"><?php echo $retrabajo->cliente->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->proyecto->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->titulo; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->fase->nombre; ?></td>
							<td align="center">Retrabajo</td>
							<td align="center"><?php echo $retrabajo->estado->nombre; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</body>
</html>