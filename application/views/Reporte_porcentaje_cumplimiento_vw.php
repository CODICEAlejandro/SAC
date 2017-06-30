<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<style type="text/css">
		select {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
		}
	</style>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Reporte_porcentaje_cumplimiento_JS.js"></script>
	<script type="text/javascript">var baseURL = '<?php echo base_url(); ?>';</script>

	<!--Fancybox-->
	<script type="text/javascript" src="<?php echo base_url();?>includes/fancybox-3.0/dist/jquery.fancybox.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>includes/fancybox-3.0/dist/jquery.fancybox.css" type="text/css" media="screen" />
</head>
<body>
	<? $menu; ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form class="form form-inline" id="form-dates" method="post">
					<div class="input-group">
						<div class="input-group-addon">Desde:</div>
						<input name="dateDesde" id="dateDesde" class="form-control datepicker" readonly="readonly"></input>
					</div>
					<div class="input-group">
						<div class="input-group-addon">Hasta:</div>
						<input name="dateHasta" id="dateHasta" class="form-control datepicker" readonly="readonly"></input>
					</div>
					
					<div class="form-group">
						<input type="submit" name="recalcular" class="btn btn-success" id="btn-recalcular" value="Recalcular ">
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h2 valign="center">Códice</h2>
						<table class="table table-striped" id="tablaCodice">
							<thead>
								<tr>
									<th>
										Tiempo total
									</th>
									<th>
										Tiempo real
									</th>
									<th>
										Porcentaje de cumplimiento
									</th>
								</tr>
							</thead>
							<tbody id="cuerpoTablaCodice">
								<tr class="contenidoTablaCodice">
									<td class="colTiempoTotal"><?php echo $codice->tiempoTotal; ?></td>		
									<td class="colTiempoReal"><?php echo $codice->tiempoReal; ?></td>
									<td class="colPorcentajeCumplimiento"><a href="" class="detallePorcentajeCodice"><?php echo $codice->porcentaje; ?>%</a></td>	
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h2>Consultores</h2>
						<table class="table table-striped" id="tablaConsultores">
							<thead>
								<tr>
									<th>
										Consultor
									</th>
									<th>
										Tiempo real
									</th>
									<th>
										Porcentaje de cumplimiento
									</th>
								</tr>
							</thead>
							<tbody id="cuerpoTabla">
								<?php foreach($users as $user){ ?>
								<tr class="contenidoTabla">
									<td class="colNombre"><?php echo $user->nombre; ?></td>		
									<td class="colTiempoReal"><?php echo $user->tiempoReal; ?></td>
									<td class="colPorcentajeCumplimiento"><a href="" class="detallePorcentaje" usr-id="<?php echo $user->idConsultor; ?>"><?php echo $user->porcentaje; ?>%</a></td>	
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<h2>Clientes</h2>
				<table class="table table-striped" id="tablaClientes">
					<thead>
						<tr>
							<th>
								Cliente
							</th>
							<th>
								Tiempo real
							</th>
							<th>
								Porcentaje de cumplimiento
							</th>
						</tr>
					</thead>
					<tbody id="cuerpoTablaClientes">
						<?php foreach($clientes as $c){ ?>
						<tr class="contenidoTablaClientes">
							<td class="colCliente"><?php echo $c->cliente; ?></td>		
							<td class="colTiempoReal"><?php echo $c->tiempoReal; ?></td>
							<td class="colPorcentajeCumplimiento"><a href="" class="detallePorcentajeCliente" cliente-id="<?php echo $c->idCliente; ?>"><?php echo $c->porcentaje; ?>%</a></td>	
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="container" id="tablaConsultor" style="display: none;">
		<div class="form-group">
			
			<h2 id="consultorResponsable">Responsable:</h2>
			
			<table class="table table-striped">
				<thead>
					<tr>
						<td>Cliente</td>
						<td>Proyecto</td>
						<td>Titulo</td>
						<td>Tiempo Real</td>
					</tr>
				</thead>
				<tbody id="cuerpoTablaConsultor">
					<tr class="contenidoTablaConsultor">
						<td class="cliente"></td>
						<td class="proyecto"></td>
						<td class="titulo"></td>
						<td class="tiempoReal"></td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
	<div class="container" id="tablaCliente" style="display: none;">
		<div class="form-group">
			
			<h2 id="nombreCliente">Cliente:</h2>
			
			<table class="table table-striped">
				<thead>
					<tr>
						<td>Responsable</td>
						<td>Proyecto</td>
						<td>Titulo</td>
						<td>Tiempo Real</td>
					</tr>
				</thead>
				<tbody id="cuerpoTablaCliente">
					<tr class="contenidoTablaCliente">
						<td class="consultor"></td>
						<td class="proyecto"></td>
						<td class="titulo"></td>
						<td class="tiempoReal"></td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
	<div class="container" id="tablaCodiceClon" style="display: none;">
		<div class="form-group">
			
			<h2>Códice</h2>
			
			<table class="table table-striped">
				<thead>
					<tr>
						<td>Responsable</td>
						<td>Cliente</td>
						<td>Proyecto</td>
						<td>Titulo</td>
						<td>Tiempo Real</td>
					</tr>
				</thead>
				<tbody id="cuerpoTablaCodiceClon">
					<tr class="contenidoTablaCodiceClon">
						<td class="consultor"></td>
						<td class="cliente"></td>
						<td class="proyecto"></td>
						<td class="titulo"></td>
						<td class="tiempoReal"></td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
</body>
</html>