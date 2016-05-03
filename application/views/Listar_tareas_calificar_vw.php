<!DOCTYPE html>
<html>
	<head>
		<title>SAC</title>
	</head>
	<body>
		<?=$menu ?>

		<table border="1">
			<thead>
				<tr>
					<th>Fecha</th>
					<th>ID</th>
					<th>Consultor</th>
					<th>Cliente</th>
					<th>Proyecto</th>
					<th>Descripción</th>
					<th>Tipo</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($tareas as $tarea){ ?>
					<?php if($tarea->idEstado==2){ ?>
					<tr
					onclick="location='<?php echo base_url().'index.php/Marcar_calificado_ctrl/traerTarea/'.($tarea->id); ?>'"
					>
					<?php }else{ ?>
					<tr>
					<?php } ?>
						<td align="center"><?php echo $tarea->creacion; ?></td>
						<td align="center"><?php echo $tarea->id; ?></td>
						<td align="center"><?php echo $tarea->responsable->nombre; ?></td>
						<td align="center"><?php echo $tarea->cliente->nombre; ?></td>
						<td align="center"><?php echo $tarea->proyecto->nombre; ?></td>
						<td align="center"><?php echo $tarea->descripcion; ?></td>
						<td align="center">Tarea</td>
						<td align="center"><?php echo $tarea->estado->nombre; ?></td>
					</tr>
				<?php } ?>
				<?php foreach($retrabajos as $retrabajo){ ?>
					<?php if($retrabajo->idEstado==2){ ?>
					<tr
					onclick="location='<?php echo base_url().'index.php/Marcar_calificado_ctrl/traerRetrabajo/'.($retrabajo->id); ?>'"
					>
					<?php }else{ ?>
					<tr>
					<?php } ?>
						<td align="center"><?php echo $retrabajo->creacion; ?></td>
						<td align="center"><?php echo $retrabajo->id; ?></td>
						<td align="center"><?php echo $retrabajo->responsable->nombre; ?></td>
						<td align="center"><?php echo $retrabajo->cliente->nombre; ?></td>
						<td align="center"><?php echo $retrabajo->proyecto->nombre; ?></td>
						<td align="center"><?php echo $retrabajo->descripcion; ?></td>
						<td align="center">Retrabajo</td>
						<td align="center"><?php echo $retrabajo->estado->nombre; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</body>
</html>