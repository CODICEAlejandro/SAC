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
		$(function(){

			$("input[name='correo']").keyup(function(){
				var correoVal = $(this).val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Alta_usuario_ctrl/isAvailableEmail',
					method: 'post',
					data: { 'correo': correoVal },
					dataType: 'text',
					success: function(response){
						if(response == "OK"){
							$("#messCorreo").html("<span style='color:green;'>Correo válido.</span>");
							$("input[name='action']").removeClass('disabled');
						}else{
							$("#messCorreo").html("<span style='color:red'>El correo indicado ya ha sido usado por otro usuario. Piense en otro disponible.</span>");	
							$("input[name='action']").addClass('disabled');
						}
					},
					error: function(){
						$("#messCorreo").html('Error al intentar conectar con la base de datos.');
					}
				});
			});
		});
	</script>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<!-- Control de Usuarios -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					class="form"
					action="<?php echo base_url(); ?>index.php/Alta_usuario_ctrl/nuevoUsuario"
					method="POST"
					id="form-alta-usuario"
				>
					<div class="form-group">
						<label>Nombre</label>
						<input class="form-control" name="nombre" placeholder="Nombre" type="text"></input>
					</div>

					<div class="form-group">
						<label>Área</label>
						<select class="form-control" name="idArea">
							<?php foreach($areas as $area){ ?>
							<option 
								value="<?php echo $area->id; ?>"
							><?php echo $area->nombre; ?></option>
							<?php } ?>
						</select>						
					</div>

					<div class="form-group">
						<label>Puesto</label>
						<select class="form-control" name="idPuesto">
							<?php foreach($puestos as $puesto){ ?>
							<option 
								value="<?php echo $puesto->id; ?>"
							><?php echo $puesto->nombre; ?></option>
							<?php } ?>
						</select>						
					</div>

					<div class="form-group">
						<label>Contraseña</label>
						<input class="form-control" name="password" placeholder="Contraseña" type="text"></input>						
					</div>

					<div class="form-group">
						<label>Rol</label>
						<select class="form-control" name="tipo">
							<option value="0"
							>Consultor</option>
							<option value="1"
							>Gerente</option>
							<option value="2"
							>Administrador</option>
						</select>						
					</div>
					<div class="form-group">
						<label>Correo</label>
						<input class="form-control" name="correo" placeholder="Correo" type="text"></input>
						<div id="messCorreo"></div>					
					</div>
					<div class="form-group" style="border-top: 2px solid #AAA; padding-top: 10px;">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<label>Horas hombre por día</label>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label>Lunes</label>
								<input type="text" name="horasLunes" placeholder="0" class="form-control">
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label>Martes</label>
								<input type="text" name="horasMartes" placeholder="0" class="form-control">
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label>Miércoles</label>
								<input type="text" name="horasMiercoles" placeholder="0" class="form-control">
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label>Jueves</label>
								<input type="text" name="horasJueves" placeholder="0" class="form-control">
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label>Viernes</label>
								<input type="text" name="horasViernes" placeholder="0" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<input class="form-control btn btn-info" name="action" placeholder="Nombre" type="submit" value="Crear"></input>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>