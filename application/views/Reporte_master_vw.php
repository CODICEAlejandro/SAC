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

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Reporte_master_JS.js"></script>

	<title>JOBS</title>

	<style type="text/css">
		#header-table {
			background-color: #FB8;
			color: black;
		}

		#main-data-tbl, #main-data-tbl th, #main-data-tbl td {
			border: 1px solid #777;
		}
	</style>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div 
				class="col-xs-12 col-sm-12 col-md-12 col-lg-12" 
			>
				<div class="form-group">
					<label for="idCliente">Cliente</label>
					<select id="idCliente" class="form-control">
						<option value="-1">Mostrar todos</option>
						<?php foreach($clientes as $c){ ?>
						<option value="<?php echo $c->id ?>"><?php echo $c->nombre; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="idRazonSocial">Razón social</label>
					<select id="idRazonSocial" class="form-control">
						<option value="-1">Mostrar todas</option>
					</select>
				</div>
				<div class="form-group">
					<label for="idCotizacion">Cotización</label>
					<select id="idCotizacion" class="form-control">
						<option value="-1">Mostrar todas</option>
					</select>
				</div>

				<div style="width: 100%; min-height: 1px; border-bottom: 1px solid gray; margin-bottom: 15px; margin-top: 15px;"></div>

				<div class="form-group">
					<label for="fechaFactura">Fecha de factura</label>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>Desde</label>
							<div class="input-group">
								<span class="input-group-addon">
								    <input type="checkbox" id="filterByFechaFactura">
								</span>
								<input type="text" readonly="true" class="form-control datepicker" id="fechaFacturaDesde">
								<input type="text" readonly="true" class="form-control datepicker" id="fechaFacturaDesdeAlt">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>Hasta</label>
							<input type="text" readonly="true" class="form-control datepicker" id="fechaFacturaHasta">
							<input type="text" readonly="true" class="form-control datepicker" id="fechaFacturaHastaAlt">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="fechaPago">Fecha de pago</label>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>Desde</label>
							<div class="input-group">
								<span class="input-group-addon">
								    <input type="checkbox" id="filterByFechaPago">
								</span>
								<input type="text" readonly="true" class="form-control datepicker" id="fechaPagoDesde">
								<input type="text" readonly="true" class="form-control datepicker" id="fechaPagoDesdeAlt">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>Hasta</label>
							<input type="text" readonly="true" class="form-control datepicker" id="fechaPagoHasta">
							<input type="text" readonly="true" class="form-control datepicker" id="fechaPagoHastaAlt">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="fechaCancelacion">Fecha de cancelación</label>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>Desde</label>
							<div class="input-group">
								<span class="input-group-addon">
								    <input type="checkbox" id="filterByFechaCancelacion">
								</span>
								<input type="text" readonly="true" class="form-control datepicker" id="fechaCancelacionDesde">
								<input type="text" readonly="true" class="form-control datepicker" id="fechaCancelacionDesdeAlt">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>Hasta</label>
							<input type="text" readonly="true" class="form-control datepicker" id="fechaCancelacionHasta">
							<input type="text" readonly="true" class="form-control datepicker" id="fechaCancelacionHastaAlt">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="idEstadoFactura">Estatus</label>
					<select id="idEstadoFactura" class="form-control">
						<option value="-1">Mostrar todos</option>
						<?php foreach($estadosFactura as $e){ ?>
						<option value="<?php echo $e->id; ?>"><?php echo $e->descripcion; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<button class="form-control btn btn-primary" id="btn-consultar">Consultar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div 
				class="col-xs-12 col-sm-12 col-md-12 col-lg-12" 
			>
				<table
					class="table table-hover table-bordered"
				>
					<tbody>
						<tr>
							<td>Número de cotizaciones</td>
							<td id="numeroCotizaciones"></td>
						</tr>
						<tr>
							<td>Conceptos facturados</td>
							<td id="numeroConceptosFacturados"></td>
						</tr>
						<tr>
							<td>Conceptos sin factura</td>
							<td id="numeroConceptosSinFactura"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div 
				class="col-xs-12 col-sm-12 col-md-12 col-lg-12" 
				style="overflow: scroll;"
			>
				<table
					class="table table-bordered table-hover"
					id="main-data-tbl"
					style="margin-bottom: 15px;"
				>
					<thead>
						<tr id="header-table">
							<th>Estatus</th>
							<th>Folio</th>
							<th>Total</th>
							<th>Fecha de pago</th>
							<th>Cliente</th>
							<th>ID</th>
							<th>Subtotal</th>
							<th>Moneda</th>
							<th>Fecha de factura</th>
							<th>Orden de compra</th>
							<th>Tipo</th>
							<th>Referencia</th>
							<th>Concepto</th>
							<th>Proyecto</th>
							<th>Fecha de inicio del proyecto</th>
							<th>Fecha de finalización del proyecto</th>
							<th>Razón social</th>
							<th>Fecha de venta</th>
							<th>Fecha de junta de arranque</th>
							<th>Cerrador</th>
							<th>Responsable</th>
							<th>Account Manager</th>
							<th>%IVA</th>
							<th>IVA</th>
							<th>Importe</th>
							<th>Fecha de cancelación</th>
							<th>Contrato</th>
							<th>Nota</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
