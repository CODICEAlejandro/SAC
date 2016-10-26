<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Control_cotizacion_JS.js"></script>
	<title>JOBS</title>
</head>
<body>
	<?=$menu; ?>

	<div class="container">
		<div class="row" style="border-bottom: 2px solid #AAA; padding-bottom: 10px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form class="form" id="form-query">
					<div class="form-group">
						<label for="currentCliente">Cliente</label>
						<select class="form-control" id="currentCliente">
							<option value="-1">Todos</option>
							<?php foreach($clientes as $cliente){ ?>
							<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label for="currentRazon">Razón social</label>
						<select class="form-control" id="currentRazon">
							<option value="-1">Todas</option>
						</select>
					</div>
					<div class="form-group">
						<input type="submit" value="Consultar cotizaciones asociadas" class="btn btn-primary form-control">
					</div>
				</form>
			</div>
		</div>

		<div class="row" style="margin-top: 15px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table" id="tbl-razon-social">
					<thead>
						<th>Folio de cotización</th>
						<th>Razón social asociada</th>
						<th style="width: 60%;">Nota</th>
						<th>Creación</th>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>			
		</div>
	</div>
</body>
</html>