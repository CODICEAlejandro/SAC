<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>SAC</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					method = "POST"
					action = "<?php echo base_url().'index.php/Nuevo_proyecto_ctrl/insertar' ?>"
					name= "form_alta_proyecto"
					id = "form_alta_proyecto"
				>
					<div class="form-group">
						<label for="nombre">Nombre del proyecto</label>
						<input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del proyecto">
					</div>
					<div class="form-group">
						<label for="idCotizacion">ID de la cotización</label>
						<input type="text" class="form-control" id="idCotizacion" name="idCotizacion" placeholder="0000">
					</div>
					<div class="form-group">
						<label>Cliente</label>
						<select name="idCliente" class="form-control">
							<?php foreach($clientes as $cliente){ ?>
								<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Tipo</label>
						<select name="tipo" class="form-control">
							<option>Tipo 1</option>
							<option>Tipo 2</option>
							<option>Tipo 3</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="estado" value="1">
						<input type="submit" class="btn btn-success" value="Crear">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
