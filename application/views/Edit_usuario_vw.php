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

			$("input[name='correo']").change(function(){
				var correoVal = $(this).val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Alta_usuario_ctrl/isAvailableEmail',
					method: 'post',
					data: { 'correo': correoVal },
					dataType: 'text',
					success: function(response){
						if(response == "OK"){
							$("#messCorreo").html("Correo válido.");
							$("input[name='action']").removeClass('disabled');
						}else{
							$("#messCorreo").html("El correo indicado ya ha sido usado por otro usuario. Piense en otro disponible.");	
							$("input[name='action']").addClass('disabled');
						}
					},
					error: function(){
						$("#messCorreo").html('Error al intentar conectar con la base de datos.');
					}
				});
			});

			$("#form-consulta-usuario").submit(function(event){
				event.preventDefault();
				var idConsulta = $("select[name='usuario-actual']").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Edit_usuario_ctrl/getUserInfo',
					method: 'post',
					data: { 'id': idConsulta },
					dataType: 'json',
					success: function(response){
						$("input[name='id']").val(response.id);
						$("input[name='nombre']").val(response.nombre);
						$("input[name='password']").val(response.password);
						$("input[name='correo']").val(response.correo);
						$("input[name='horasLunes']").val(response.horasLunes);
						$("input[name='horasMartes']").val(response.horasMartes);
						$("input[name='horasMiercoles']").val(response.horasMiercoles);
						$("input[name='horasJueves']").val(response.horasJueves);
						$("input[name='horasViernes']").val(response.horasViernes);
						$("select[name='idArea']").find($("option[value='"+response.idArea+"']")).attr('selected','selected');
						$("select[name='idPuesto']").find($("option[value='"+response.idPuesto+"']")).attr('selected','selected');
						$("select[name='tipo']").find($("option[value='"+response.tipo+"']")).attr('selected','selected');
					},
					error: function(){
						alert('Error al intentar consultar el usuario indicado.');
					}
				});
			});

			$("#baja").click(function(){
				var idConsulta = $("select[name='usuario-actual']").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Edit_usuario_ctrl/darDeBaja',
					method: 'post',
					data: { 'id': idConsulta },
					success: function(){
						window.location.replace(window.location);
					},
					error: function(){
						alert('Error al intentar consultar el usuario indicado.');
					}
				});
			});
		});
	</script>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row" style="border-bottom: 2px solid #AAA; padding-bottom: 10px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					class="form form-inline"
					id="form-consulta-usuario"
				>
					<div class="form-group" style="float:right">
						<button class="btn btn-danger" id="baja">Dar de baja</button>
					</div>
					<div class="form-group">
						<label>Usuario actual</label>
						<select name="usuario-actual" id="usuario-actual" class="form-control">
							<?php foreach($usuarios as $usuario){ ?>
							<option value="<?php echo $usuario->id; ?>"><?php echo $usuario->nombre; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success" name="consultar" value="Consultar" class="form-control">
					</div>
				</form>
			</div>
		</div>

		<div class="row" style="padding-top: 10px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					class="form"
					action="<?php echo base_url(); ?>index.php/Edit_usuario_ctrl/updateUser"
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
						<input class="form-control" name="password" placeholder="Contraseña" type="password"></input>	
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
						<input class="form-control btn btn-info" name="action" type="submit" value="Guardar"></input>
					</div>
					<input type="hidden" name="id" value="-1">
				</form>
			</div>
		</div>
	</div>
</body>
</html>