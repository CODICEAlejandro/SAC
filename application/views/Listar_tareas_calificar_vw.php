<!DOCTYPE html>
<html>
	<head>
		<title>JOBS</title>
		<?php includeJQuery(); ?>
		<?php includeBootstrap(); ?>

		<script type="text/javascript">
			$(function(){
				$(".DiscoverRow").hide();

				$(".DiscoverRow").each(function(i){
					$(this).delay(i*50).fadeIn(200);
				});
			});
		</script>		
	</head>
	<body>
		<?=$menu ?>

		<div class="container">
			<!-- Pendientes -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<h3>Pendientes</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<table border="1" class="table table-striped">
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
											<tr class="success DiscoverRow">
										<?php }else{ ?>
											<tr class="danger DiscoverRow">										
										<?php } ?>
									<?php }else{ ?>
										<tr style="background-color: black; color: white;" class="DiscoverRow">
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
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Terminados -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<h3>Terminados</h3>
				</div>
			</div>			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">			
					<table border="1" class="table table-striped">
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
							</tr>
						</thead>
						<tbody>
							<?php foreach($terminados as $tarea){ ?>
								<?php if($tarea->idEstado==2){ ?>
								<?php if(!$tarea->retrabajo){ ?>
									<tr
									onclick="location='<?php echo base_url().'index.php/Marcar_calificado_ctrl/traerTarea/'.($tarea->id); ?>'"
									class = "info DiscoverRow"
									>
								<?php }else{ ?>
									<tr
									onclick="location='<?php echo base_url().'index.php/Marcar_calificado_ctrl/traerRetrabajo/'.($tarea->id); ?>'"
									class = "danger DiscoverRow"
									>
								<?php } ?>
								<?php }else{ ?>
								<tr class="success DiscoverRow">
								<?php } ?>
									<td align="center"><?php echo $tarea->creacion; ?></td>
									<td align="center"><?php echo $tarea->id; ?></td>
									<td align="center"><?php echo $tarea->responsable->nombre; ?></td>
									<td align="center"><?php echo $tarea->cliente->nombre; ?></td>
									<td align="center"><?php echo $tarea->proyecto->nombre; ?></td>
									<td align="center"><?php echo $tarea->titulo; ?></td>
									<td align="center"><?php echo ($tarea->retrabajo)? "Error" : "Tarea"; ?></td>
									<td align="center"><?php echo $tarea->estado->nombre; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Calificados -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<h3>Calificados</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<table border="1" class="table table-striped">
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
							</tr>
						</thead>
						<tbody>
							<?php foreach($calificados as $tarea){ ?>
								<?php if($tarea->idEstado==3){ ?>
								<?php if(!$tarea->retrabajo){ ?>
									<tr
									onclick="location='<?php echo base_url().'index.php/Detalle_tarea_ctrl/traerTarea/'.($tarea->id); ?>'"
									class = "success DiscoverRow"
									>
								<?php }else{ ?>
									<tr
									onclick="location='<?php echo base_url().'index.php/Detalle_tarea_ctrl/traerRetrabajo/'.($tarea->id); ?>'"
									class = "danger DiscoverRow"
									>
								<?php } ?>
								<?php }else{ ?>
								<tr class="success DiscoverRow">
								<?php } ?>
									<td align="center"><?php echo $tarea->creacion; ?></td>
									<td align="center"><?php echo $tarea->id; ?></td>
									<td align="center"><?php echo $tarea->responsable->nombre; ?></td>
									<td align="center"><?php echo $tarea->cliente->nombre; ?></td>
									<td align="center"><?php echo $tarea->proyecto->nombre; ?></td>
									<td align="center"><?php echo $tarea->titulo; ?></td>
									<td align="center"><?php echo ($tarea->retrabajo)? "Retrabajo" : "Tarea"; ?></td>
									<td align="center"><?php echo $tarea->estado->nombre; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>					
				</div>
			</div>
		</div>
	</body>
</html>