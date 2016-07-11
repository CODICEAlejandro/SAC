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
				$("#statusChargeCalificados").hide();
				$("#messagesCalificados").hide();
				
				$('#fechaOrigen').datepicker();
				$('#fechaFin').datepicker();
				$( "#fechaOrigen" ).datepicker( "option", "showAnim", "drop");			
				$( "#fechaFin" ).datepicker( "option", "showAnim", "drop");					

				$(".DiscoverRow").hide();

				$(".DiscoverRow").each(function(i){
					$(this).delay(i*50).fadeIn(200);
				});

				checkIntervalTerminados();

				$("#fechaOrigen, #fechaFin").change(function(){
					//checkIntervalTerminados();
					var fechaOrigenVal = $("#fechaOrigen").val().split('/');
					var fechaFinVal = $("#fechaFin").val().split('/');

					fechaOrigenVal = fechaOrigenVal[2]+'/'+fechaOrigenVal[0]+'/'+fechaOrigenVal[1];
					fechaFinVal = fechaFinVal[2]+'/'+fechaFinVal[0]+'/'+fechaFinVal[1];

					$.ajax({
						url:"<?php echo base_url(); ?>index.php/Listar_tareas_calificar_ctrl/getCalificadosAJAX",
						method: 'POST',
						data: { fechaOrigen: fechaOrigenVal , fechaFin: fechaFinVal },
						dataType: 'json',
						complete: function(){
							$("#statusChargeCalificados").hide();
						},
						success: function(response){
							var link = '';

							for(var t in response){
								if(response[t].retrabajo == 'S'){
									$("#tableCalificados > tbody:last-child").append('<tr class="danger"></tr>');
									link = '<?php echo base_url()."index.php/Detalle_tarea_ctrl/traerRetrabajo/"; ?>'+response[t].id;
								}
								else{
									$("#tableCalificados > tbody:last-child").append('<tr class="success"></tr>');
									link = '<?php echo base_url()."index.php/Detalle_tarea_ctrl/traerTarea/"; ?>'+response[t].id;
								}

								$("#tableCalificados > tbody:last-child tr:last-child").attr('goto', link);
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].creacion+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].id+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].responsable.nombre+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].cliente.nombre+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].proyecto.nombre+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].titulo+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].tipo+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].estado.nombre+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].tiempoEstimado+'</td>');
								$("#tableCalificados > tbody:last-child tr:last-child").append('<td>'+response[t].tiempoRealGerente+'</td>');
							}

							$("#tableCalificados > tbody tr").click(function(){
								window.location.replace($(this).attr('goto'));
							});
						},
						beforeSend: function(){
							$("#tableCalificados > tbody tr").remove();
							$("#messagesCalificados").hide();
							$("#statusChargeCalificados").show();
						},
						error: function(){
							$("#statusChargeCalificados").hide();
							$("#tableCalificados > tbody tr").remove();
							$("#messagesCalificados").show();
						}
					});
				});

				$("#showPendientesBtn, #showTerminadosBtn, #showCalificadosBtn").click(function(){
					if($(this).hasClass("active")){
						$(this).removeClass("active").addClass("inactive");
						$(this).removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");

						if($(this).attr("id")=="showPendientesBtn")
							$("#tablePendientes tbody tr").hide();
						else if($(this).attr("id")=="showTerminadosBtn")
							$("#tableTerminados tbody tr").hide()
						else if($(this).attr("id")=="showCalificadosBtn"){
							$("#tableCalificados tbody tr").hide();
							//$("#tableCalificados").slideUp("slow");
						}
					}else{
						$(this).removeClass("inactive").addClass("active");
						$(this).removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
				
						if($(this).attr("id")=="showPendientesBtn")
							$("#tablePendientes tbody tr").show();
						else if($(this).attr("id")=="showTerminadosBtn")
							$("#tableTerminados tbody tr").show();
						else if($(this).attr("id")=="showCalificadosBtn"){
							$("#tableCalificados tbody tr").show();
							checkIntervalTerminados();
						}
							//$("#tableCalificados").slideDown("fast");
					}
				});

				$("#fechaOrigen").change();
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

				$("#tableCalificados tbody tr").each(function(i){
					var cElement = $(this).children().first();
					var elementDate = cElement.html().split(" ")[0].split("-");

					//cDate = Año, Mes, Día
					var date = new Date(elementDate[0], (elementDate[1]-1), elementDate[2]);
					if(!(date >= dateOrigen) || !(date <= dateFin)){
						$(this).hide();
					}else{
						$(this).show();						
					}
				});
			}
		</script>		
	</head>
	<body>
		<?=$menu ?>

		<div class="container">
			<!-- Pendientes -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<h3>Pendientes 
						<span id="showPendientesBtn" class="active glyphicon glyphicon-eye-open btn btn-md" style="float: right;"></span>
					</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<table border="1" class="table table-striped" id="tablePendientes">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>ID</th>
								<th>Consultor</th>
								<th>Cliente</th>
								<th>Proyecto</th>
								<th>Título</th>
								<th>Tipo</th>
								<th>Estado</th>
								<th>Tiempo estimado</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($pendientes as $tarea){ ?>
								<?php if($tarea->idEstado==2){ ?>
								<tr
								onclick="location='<?php echo base_url().'index.php/Marcar_calificado_ctrl/traerTarea/'.($tarea->id); ?>'"
								class = "info"
								>
								<?php }else{ ?>
									<?php 
										$taskDay = explode(" ",explode("-",$tarea->creacion)[2])[0];
										$curDay = date("d");
										if($taskDay == $curDay){ 
									?>
										<?php if(!$tarea->retrabajo){ ?>
											<tr class="success">
										<?php }else{ ?>
											<tr class="danger">										
										<?php } ?>
									<?php }else{ ?>
										<tr style="background-color: black; color: white;">
									<?php } ?>
								<?php } ?>
									<td align="center"><?php echo $tarea->creacion; ?></td>
									<td align="center"><?php echo $tarea->id; ?></td>
									<td align="center"><?php echo $tarea->responsable->nombre; ?></td>
									<td align="center"><?php echo $tarea->cliente->nombre; ?></td>
									<td align="center"><?php echo $tarea->proyecto->nombre; ?></td>
									<td align="center"><?php echo $tarea->titulo; ?></td>
									<td align="center"><?php echo ($tarea->retrabajo)? "Error" : "Tarea"; ?></td>
									<td align="center"><?php echo $tarea->estado->nombre; ?></td>
									<td align="center"><?php echo $tarea->tiempoEstimado; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Terminados -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<h3>Terminados
						<span id="showTerminadosBtn" class="active glyphicon glyphicon-eye-open btn btn-md" style="float: right;"></span>
					</h3>
				</div>
			</div>			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">			
					<table border="1" class="table table-striped" id="tableTerminados">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>ID</th>
								<th>Consultor</th>
								<th>Cliente</th>
								<th>Proyecto</th>
								<th>Título</th>
								<th>Tipo</th>
								<th>Estado</th>
								<th>Tiempo estimado</th>
								<th>Tiempo real</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($terminados as $tarea){ ?>
								<?php if($tarea->idEstado==2){ ?>
								<?php if(!$tarea->retrabajo){ ?>
									<tr
									onclick="location='<?php echo base_url().'index.php/Marcar_calificado_ctrl/traerTarea/'.($tarea->id); ?>'"
									class = "info"
									>
								<?php }else{ ?>
									<tr
									onclick="location='<?php echo base_url().'index.php/Marcar_calificado_ctrl/traerRetrabajo/'.($tarea->id); ?>'"
									class = "danger"
									>
								<?php } ?>
								<?php }else{ ?>
								<tr class="success">
								<?php } ?>
									<td align="center"><?php echo $tarea->creacion; ?></td>
									<td align="center"><?php echo $tarea->id; ?></td>
									<td align="center"><?php echo $tarea->responsable->nombre; ?></td>
									<td align="center"><?php echo $tarea->cliente->nombre; ?></td>
									<td align="center"><?php echo $tarea->proyecto->nombre; ?></td>
									<td align="center"><?php echo $tarea->titulo; ?></td>
									<td align="center"><?php echo ($tarea->retrabajo)? "Error" : "Tarea"; ?></td>
									<td align="center"><?php echo $tarea->estado->nombre; ?></td>
									<td align="center"><?php echo $tarea->tiempoEstimado; ?></td>
									<td align="center"><?php echo $tarea->tiempo; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Calificados -->
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
					<h3>Calificados</h3>
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
				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
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
				<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
					<h3>
						<span id="showCalificadosBtn" class="active glyphicon glyphicon-eye-open btn btn-md" style="float: right;"></span>
					</h3>
				</div>	
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<table border="1" class="table table-striped" id="tableCalificados">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>ID</th>
								<th>Consultor</th>
								<th>Cliente</th>
								<th>Proyecto</th>
								<th>Título</th>
								<th>Tipo</th>
								<th>Estado</th>
								<th>Tiempo estimado</th>
								<th>Tiempo real</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<div id="statusChargeCalificados" class="progress-bar progress-bar-success progress-bar-striped active" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">Cargando</div>
					<div id="messagesCalificados">Demasiado información. Intente con un intervalo más pequeño.</div>
				</div>
			</div>
		</div>
	</body>
</html>