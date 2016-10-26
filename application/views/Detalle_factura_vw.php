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
	<title>JOBS</title>

	<style type="text/css">
		.ui-datepicker {
			z-index: 10 !important;
		}
	</style>
</head>
<body>
	<?=$menu; ?>

	<input type="hidden" id="currentFactura" value="<?php echo $currentFactura; ?>">

	<div class="container">
		<div class="row" style="border-bottom: 2px solid #AAA; padding-bottom: 10px;">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<h4>Factura #<?php echo $currentFactura; ?></h4>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px; float: right; width: 40%;">
				<div class="form-group">
					<label>Estado de la factura</label>
					<select id="idEstadoFactura" class="form-control">
						<option>Por pagar</option>
					</select>
				</div>
			</div>			
		</div>

		<div class="row" style="margin-top: 15px; overflow: scroll;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table" id="tbl-factura">
					<thead>
						<th style="min-width: 20px;">ID de concepto</th>
						<th style="min-width: 150px;">Tipo</th>
						<th style="min-width: 150px;">Monto</th>
						<th style="min-width: 150px;">Monto de facturación</th>
						<th style="min-width: 150px;">Recurrencia</th>
						<th style="min-width: 400px;">Referencia</th>
						<th style="min-width: 450px;">Descripción</th>
						<th style="min-width: 500px;">Nota</th>
					</thead>
					<tbody>
					<?php foreach($conceptos as $concepto){ ?>
					<tr>
						<td><?php echo $concepto->id; ?></td>
						<td><?php echo $concepto->tipoConcepto; ?></td>
						<td><?php echo $concepto->monto; ?></td>
						<td><?php echo $concepto->montoFacturacion; ?></td>
						<td><?php echo $concepto->recurrencia; ?></td>
						<td><?php echo $concepto->referencia; ?></td>
						<td><?php echo $concepto->descripcion; ?></td>
						<td>
							<div class="input-group">
								<textarea id="nota" rows="2" style="width: 95%" class="form-control"><?php echo $concepto->nota; ?></textarea>
								<span class="input-group-btn">
									<button class="btn btn-default" id="btn-save-note" data-id-factura="<?php echo $currentFactura; ?>" data-id="<?php echo $concepto->id; ?>" type="button">
										<span class="glyphicon glyphicon-floppy-disk"></span>
									</button>
								</span>
							</div>
						</td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>