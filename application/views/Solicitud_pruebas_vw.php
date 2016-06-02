<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>JOBS</title>
		<?php includeJQuery(); ?>
		<?php includeBootstrap(); ?>
		<script>
		$(function(){
			$('#fechaSugerida').datepicker();
			$( "#fechaSugerida" ).datepicker( "option", "showAnim", "drop");			
		});
		</script>
	</head>
	<body>
		<?=$menu ?>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<form
						method="POST"
						action="<?php echo base_url().'index.php/Solicitud_pruebas_ctrl/crearReporte'; ?>"
						name="form-alta-solicitud"
						id="form-alta-solicitud"
					>
						<div class="form-group">
							<input type="hidden" name="tabla" value="<?php echo $tabla; ?>">
							<input type="hidden" name="idTarea" value="<?php echo $idTarea; ?>">
						</div>
						<!-- Calendario -->
						<div class="form-group">
							<label for="fechaSugerida">Fecha sugerida</label>
							<div class="input-group">
								<input class="form-control" name="fechaSugerida" id="fechaSugerida" type="text" readonly="readonly">
								<div class="input-group-addon">
								    <span class="glyphicon glyphicon-th"></span>
								</div>
							</div>
						</div>
						<!--<div class="form-group">
							<label for="fechaSugerida">Fecha sugerida</label>
							<input type="text" class="form-control" name="fechaSugerida" id="fechaSugerida">
						</div>-->
						<div class="form-group">
							<label for="comentarioSolicitante">Comentario</label>
							<textarea id="comentarioSolicitante" class="form-control" name="comentarioSolicitante" rows="5"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success" name="generaSolicitud">
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>		