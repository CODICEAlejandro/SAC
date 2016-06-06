<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<script type="text/javascript">
		$(function(){
			//Fecha actual
			var cDate = new Date();
			var formatCDate = (cDate.getMonth()+1)+"/"+cDate.getDate()+"/"+cDate.getFullYear();
			$("#fechaOrigen").val(formatCDate);
			$("#fechaFin").val(formatCDate);

			$(".DiscoverRow").hide();
			$(".DiscoverRow").each(function(i){
				$(this).delay(i*50).fadeIn(200);
			});

			checkIntervalTerminados();
			
			$('#fechaOrigen').datepicker();
			$('#fechaFin').datepicker();
			$( "#fechaOrigen" ).datepicker( "option", "showAnim", "drop");			
			$( "#fechaFin" ).datepicker( "option", "showAnim", "drop");					

			$("#fechaOrigen, #fechaFin").change(function(){
				checkIntervalTerminados();
			});
		});

		function checkIntervalTerminados(){
			//Verificar que fecha origen sea menor o igual que fecha fin
			var origen = $("#fechaOrigen").val().split("/");
			var fin = $("#fechaFin").val().split("/");
			var dateOrigen = new Date(parseInt(origen[2]), parseInt(origen[0])-1, parseInt(origen[1]));
			var dateFin = new Date(parseInt(fin[2]), parseInt(fin[0])-1, parseInt(fin[1]));

			if(dateOrigen > dateFin){
				$("#fechaFin").val($("#fechaOrigen").val());
			}

			$("#tableTerminados tbody tr").each(function(i){
				var cElement = $(this).children().first();
				var elementDate = cElement.html().split(" ")[0].split("-");

				//cDate = Año, Mes, Día
				var date = new Date(elementDate[0], (elementDate[1]-1), elementDate[2]);
				if(!(date >= dateOrigen) || !(date <= dateFin)){
					$(this).fadeOut(200);
				}else{
					$(this).fadeIn(200);						
				}
			});			
		}			
	</script>	
</head>
<body>
	<?=$menu ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<h3>Pendientes</h3>
			</div>
		</div>
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
							class="info DiscoverRow"
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
							class="danger DiscoverRow"
						>
							<td align="center"><?php echo $retrabajo->tareaOrigen->id; ?></td>
							<td align="center"><?php echo $retrabajo->cliente->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->proyecto->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->titulo; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->fase->nombre; ?></td>
							<td align="center">Error</td>
							<td align="center"><?php echo $retrabajo->estado->nombre; ?></td>
						</tr>
						<?php } ?>

						<?php foreach($retrabajosEdit as $retrabajo){ ?>
						<tr 
							onclick="location='<?php echo base_url().'index.php/Alta_tarea_ctrl/editarRetrabajo/'.($retrabajo->id); ?>'"
							class="danger DiscoverRow"
						>
							<td align="center"><?php echo $retrabajo->tareaOrigen->id; ?></td>
							<td align="center"><?php echo $retrabajo->cliente->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->proyecto->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->titulo; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->fase->nombre; ?></td>
							<td align="center">Error</td>
							<td align="center"><?php echo $retrabajo->estado->nombre; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<h3>Terminados</h3>
			</div>
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<!-- Calendario -->
				<div class="form-group">
					<label for="fechaOrigen">Desde</label>
					<div class="input-group">
						<input class="form-control" id="fechaOrigen" type="text" readonly="readonly">
						<div class="input-group-addon">
						    <span class="glyphicon glyphicon-th"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<!-- Calendario -->
				<div class="form-group">
					<label for="fechaFin">Hasta</label>
					<div class="input-group">
						<input class="form-control" id="fechaFin" type="text" readonly="readonly">
						<div class="input-group-addon">
						    <span class="glyphicon glyphicon-th"></span>
						</div>
					</div>
				</div>
			</div>			
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<table width="100%" border="1" class="table table-striped" id="tableTerminados">
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
						<?php if($tarea->idEstado == 2){ ?>
						<tr 
							onclick="location='<?php echo base_url().'index.php/Detalle_tarea_ctrl/traerTarea/'.($tarea->id); ?>'"
							class = "success DiscoverRow"
						>
							<td style="display:none" class="creacion"><?php echo $tarea->creacion; ?></td>
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

						<?php foreach($retrabajosTerminados as $retrabajo){ ?>
						<tr 
							onclick="location='<?php echo base_url().'index.php/Detalle_tarea_ctrl/traerRetrabajo/'.($retrabajo->id); ?>'"
							class = "danger DiscoverRow"
						>
							<td style="display:none" class="creacion"><?php echo $tarea->creacion; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->id; ?></td>
							<td align="center"><?php echo $retrabajo->cliente->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->proyecto->nombre; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->titulo; ?></td>
							<td align="center"><?php echo $retrabajo->tareaOrigen->fase->nombre; ?></td>
							<td align="center">Error</td>
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