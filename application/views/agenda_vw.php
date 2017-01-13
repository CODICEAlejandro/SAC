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
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Agenda.js"></script>
	<title>JOBS</title>

</head>
<body>
	<?=$menu; ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Buscador por nombre</label>
					<input type="text" placeholder="Nombre" class="form-control" id="buscador">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-bordered table-hover">
					<thead>
						<th>Nombre</th>
						<th>Correo</th>
						<th>Teléfono</th>
					</thead>
					<tbody>
						<?php foreach($contactos as $c){ ?>
						<tr class="row-contacto">
							<td id="col-nombre"><?php echo $c->nombre; ?></td>
							<td><?php echo $c->correo; ?></td>
							<td><?php echo $c->telefono; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>			
		</div>
	</div>
</body>