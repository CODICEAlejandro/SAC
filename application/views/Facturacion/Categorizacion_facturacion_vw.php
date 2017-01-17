<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Facturacion/Categorizacion_facturacion_JS.js"></script>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php if(count($conceptos) > 0){ ?>
				<table class="table table-stripped table-hover">
					<thead>
						<th>Cliente</th>
						<th>Account Manager</th>
						<th>Concepto</th>
						<th>Monto</th>
						<th>Fecha</th>
						<th>Acción</th>
					</thead>
					<tbody>
						<?php foreach($conceptos as $c){ ?>
						<tr class="concepto-row">
							<td>
								<?php echo $c->cliente; ?>
							</td>
							<td>
								<?php echo $c->account; ?>
							</td>
							<td>
								<?php echo $c->concepto; ?>
							</td>
							<td>
								<?php echo $c->monto; ?>
							</td>
							<td>
								<?php echo $c->fecha; ?>
							</td>
							<td>
								<button class="btn-es-nuevo btn btn-primary" data-id="<?php echo $c->id; ?>">
									Nuevo
								</button>
								<button class="btn-ya-considerado btn btn-info" data-id="<?php echo $c->id; ?>">
									Ya considerado
								</button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php }else{ ?>
					<h3>
						<span class="glyphicon glyphicon-ok"></span>
						No hay conceptos pendientes para categorizar
					</h3>
				<?php } ?>
			</div>
		</div>
	</div>
</div>