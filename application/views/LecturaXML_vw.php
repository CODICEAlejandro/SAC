<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<style type="text/css">
		.DataRow {
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					class="form"
					id="main-form"
					enctype="multipart/form-data"
					method="post"
					action="<?php echo base_url(); ?>index.php/LecturaXML_ctrl/processXML"
				>
					<div class="form-group">
						<label>Archivo XML</label>
						<input type="file" name="xmlFile" id="xmlFile" class="form-control">
					</div>

					<div class="form-group">
						<input type="submit" class="form-control">
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="container">
	<?php 
	if(isset($conceptos)){
		print_r($conceptos);
	?>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Conceptos</h3>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-bordered">
					<thead>
						<th>Cantidad</th>
						<th>Unidad</th>
						<th>Descripción</th>
						<th>Valor unitario</th>
						<th>Importe</th>
					</thead>
					<tbody>

		<?php foreach($conceptos as $concepto){ ?>
				<tr>
					<td><?php echo $concepto['cantidad']; ?></td>
					<td><?php echo $concepto['unidad']; ?></td>
					<td><?php echo $concepto['descripcion']; ?></td>
					<td>$ <?php echo $concepto['valorUnitario']; ?></td>
					<td>$ <?php echo $concepto['importe']; ?></td>
				</tr>
		<?php } ?>
					</tbody>
				</table>
			</div>
		</div>

	<?php } ?>
	</div>
</body>
</html>