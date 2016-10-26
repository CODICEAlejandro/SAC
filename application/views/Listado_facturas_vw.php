<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Listado_facturas_JS.js"></script>
	<title>JOBS</title>

	<style type="text/css">
		.ui-datepicker {
			z-index: 10 !important;
		}
	</style>
</head>
<body>
	<?=$menu; ?>

	<input type="hidden" id="currentCotizacion" value="<?php echo $currentCotizacion; ?>">

	<div class="container">
		<div class="row" style="border-bottom: 2px solid #AAA; padding-bottom: 10px;">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<h4>Cotización #<?php echo $currentCotizacion; ?></h4>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<form
					class = "form form-inline"
					id = "query-form"
					style="float: right;"
				>
					<div class="form-group" style="margin-right: 15px;">
						<label style="display: block;">Desde</label>
						<input type="text" class="form-control" id="dateDesde" class="datepicker">
						<input type="hidden" id="dateDesdeAlt">
					</div>
					<div class="form-group">
						<label style="display: block;">Hasta</label>
						<input type="text" class="form-control" id="dateHasta" class="datepicker">
						<input type="hidden" id="dateHastaAlt">
					</div>
					<div class="form-group">
						<input type="submit" class="form-control btn btn-primary" value="Consultar" style="margin-top: 25px;">
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 15px;">
				<button id="btn-prevPage" class="btn btn-primary">Anterior</button>
				<button id="btn-nextPage" class="btn btn-info">Siguiente</button>
			</div>
		</div>

		<div class="row" style="margin-top: 15px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table" id="tbl-factura">
					<thead>
						<th>ID de factura</th>
						<th>Folio</th>
						<th>Orden de compra</th>
						<th>Fecha de pago</th>
						<th style="width: 50%;">Nota</th>
						<th>Estado</th>
						<th>Consultar</th>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>			
		</div>
	</div>
</body>
</html>