<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Cobranza_JS.js"></script>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<form class="form" method="post" action="<?php echo base_url(); ?>index.php/Cobranza_ctrl/getData_AJAX">
				<div class="form-group">
					<label>Mostrar cotizaciones de </label>
					<select class="form-control" name="idCliente">
						<option value="-1">Todos los clientes</option>
						<?php foreach($clientes as $c){ ?>
						<option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<input type="submit" class="form-control btn btn-info" value="Filtrar">
				</div>
			</form>
		</div>
	</div>

	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<table class="table table-bordered table-hover">
				<thead>
					<th style="width: 115px;">Fecha</th>
					<th>Confirmada</th>
					<th>Estimada</th>
					<th>Folio de la factura</th>
					<th>Folio de la cotización</th>
					<th>Cliente</th>
					<th>Pagar</th>
					<th>Cancelar</th>
					<th>Refacturar</th>
					<th>Referencia</th>
					<th>Concepto asociado</th>
				</thead>
				<tbody id="main-content-section">
				<?php foreach($fechas as $f){ ?>
				<?php if($f->idEstadoFactura == 25){ ?>
				<tr class="row-fecha" id="row-fecha" style="outline: 1px solid orange; background: #aaa;">
				<?php }else{ ?>
				<tr class="row-fecha" id="row-fecha">
				<?php } ?>
					<td>
						<input class="form-control datepicker" 
								data-id="<?php echo $f->id; ?>"
								value="<?php echo $f->fecha_final; ?>">
						<input class="form-control datepicker-alt" id="datepicker-alt">
					</td>
					<td>
						<?php if($f->confirmada == 1){ ?>
						<input type="radio" class="radio-confirmada" value="1" 
							data-id="<?php echo $f->id; ?>"
							name="confirmada<?php echo $f->id; ?>" checked>
						<?php }else{ ?>
						<input type="radio" class="radio-confirmada" value="1" 
							data-id="<?php echo $f->id; ?>"
							name="confirmada<?php echo $f->id; ?>">
						<?php } ?>
					</td>
					<td>
						<?php if($f->confirmada == 1){ ?>
						<input type="radio" class="radio-confirmada" value="0" 
							data-id="<?php echo $f->id; ?>"
							name="confirmada<?php echo $f->id; ?>">
						<?php }else{ ?>
						<input type="radio" class="radio-confirmada" value="0" 
							data-id="<?php echo $f->id; ?>"
							name="confirmada<?php echo $f->id; ?>" checked>
						<?php } ?>
					</td>
					<td>
						<?php echo $f->folioFactura; ?>
					</td>
					<td>
						<?php echo $f->folioCotizacion; ?>
					</td>
					<td>
						<?php echo $f->cliente; ?>
					</td>
					<td>
						<button data-id="<?php echo $f->id; ?>" id="btn-pagar"
							class="btn btn-success btn-pagar"
						>
							<span class="glyphicon glyphicon-credit-card"></span>
						</button>
					</td>
					<td>
						<button data-id="<?php echo $f->id; ?>" id="btn-cancelar"
							class="btn btn-danger btn-cancelar"
						>
							<span class="glyphicon glyphicon-ban-circle"></span>
						</button>
					</td>
					<td>
						<button data-id="<?php echo $f->id; ?>" id="btn-refacturar"
							class="btn btn-primary btn-refacturar"
							data-id="<?php echo $f->id; ?>"
						>
							<span class=" glyphicon glyphicon-object-align-vertical"></span>
						</button>
					</td>
					<td><?php echo $f->ref_fecha; ?></td>
					<td><?php echo $f->desc_concepto_asociado; ?></td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>