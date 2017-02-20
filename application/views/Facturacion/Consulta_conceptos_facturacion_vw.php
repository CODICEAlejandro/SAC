
<!DOCTYPE html>
<html>
<head>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<title>JOBS</title>

	<style>
		.btn-destroy-fecha {
			cursor: pointer;
		}

		.btn-destroy-concepto {
			cursor: pointer;
		}
	</style>

</head>
<body>
	<?=$menu; ?>

	<div class="container" style="margin-bottom: 20px; padding-bottom: 10px;">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-stripped table-hover">
					<thead>
						<th>Cliente</th>
						<th>Account Manager</th>
						<th>Concepto</th>
						<th>Monto</th>
						<th>Fecha</th>
						<th>Temporalidad</th>
					</thead>
					<tbody>
						<?php foreach($facturacion as $f){ ?>
						<tr>
							<td><?php echo $f->cliente; ?></td>
							<td><?php echo $f->account; ?></td>
							<td><?php echo $f->concepto; ?></td>
							<td><?php echo $f->monto; ?></td>
							<td><?php echo $f->fecha; ?></td>
							<td><?php echo $f->temporalidad; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>