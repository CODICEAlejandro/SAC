<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript">
	$(function(){
		function isEditing(){
			var currentProyect = $("#cProyecto").val();

			if(currentProyect == -1){
				$("#rowNuevoProyecto").show();
				$("#rowEditaProyecto").hide();
			}else{
				$("#rowNuevoProyecto").hide();
				$("#rowEditaProyecto").show();				
				retrieveProyect();
			}
		}

		function retrieveProyect(){
			var currentProyect = $("#cProyecto").val();

			$.ajax({
				url: '<?php echo base_url(); ?>index.php/Nuevo_proyecto_ctrl/consultarProyecto_AJAX/'+currentProyect,
				method: 'post',
				dataType: 'json',
				success: function(response){
					$("#rowEditaProyecto #nombre").val(response.nombre);
					$("#rowEditaProyecto #idCotizacion").val(response.idCotizacion);
					$("#rowEditaProyecto #idCliente").val(response.idCliente);
					$("#rowEditaProyecto #tipo").val(response.tipo);
					$("#rowEditaProyecto #estado").val(response.estado);

					toogleButtonActive();
				},
				error: function(){
					alert("Ha ocurrido un error al intentar consutar el proyecto seleccionado. Intente de nuevo, por favor.");
				}
			});
		}

		function toogleButtonActive(){
			var isActive = $("#rowEditaProyecto #estado").val();
			var btnActive = $("#btn-estado");
			var labelEstado = $("#labelEstado");

			if(isActive == 1){
				btnActive.removeClass("btn-success").addClass("btn-danger").html("Inactivar");
				labelEstado.html("Estado actual: Activo");
			}else if(isActive == 0){
				btnActive.removeClass("btn-danger").addClass("btn-success").html("Activar");				
				labelEstado.html("Estado actual: Inactivo");
			}
		}

		$("#form_edita_proyecto").submit(function(event){
			event.preventDefault();

			var currentProyect = $("#cProyecto").val();
			var nombre = $("#rowEditaProyecto #nombre").val();
			var idCotizacion = $("#rowEditaProyecto #idCotizacion").val();
			var idCliente = $("#rowEditaProyecto #idCliente").val();
			var tipo = $("#rowEditaProyecto #tipo").val();
			var estadoActivo = $("#rowEditaProyecto #estado").val();

			$.ajax({
				url: '<?php echo base_url(); ?>index.php/Nuevo_proyecto_ctrl/actualizarProyecto_AJAX/'+currentProyect,
				method: 'post',
				data: {'nombre': nombre, 'idCotizacion': idCotizacion, 'idCliente': idCliente, 'tipo':tipo, 'estado': estadoActivo},
				dataType: 'json',
				success: function(response){
					if(response.status == 'OK'){
						$("#cProyecto *").remove();

						var proyectos = $("#cProyecto");

						proyectos.append("<option value='-1'>Ninguno</option>");
						for(var k = 0; k < response.data.length; k++){
							proyectos.append("<option value='"+response.data[k].id+"'>"+response.data[k].nombre+"</option>");
						}

						isEditing();
						alert('Se ha realizado la operación satisfactoriamente.');
					}else
						alert('Ha ocurrido un error al intentar actualizar. Intente de nuevo, por favor.');
				},
				error: function(){
					alert('Ha ocurrido un error al intentar actualizar. Intente de nuevo, por favor.');
				}
			});
		});

		$("#form_alta_proyecto").submit(function(event){
			event.preventDefault();

			var nombre = $("#rowNuevoProyecto #nombre").val();
			var idCotizacion = $("#rowNuevoProyecto #idCotizacion").val();
			var idCliente = $("#rowNuevoProyecto #idCliente").val();
			var tipo = $("#rowNuevoProyecto #tipo").val();
			var estadoActivo = $("#rowNuevoProyecto #estado").val();

			$.ajax({
				url: '<?php echo base_url(); ?>index.php/Nuevo_proyecto_ctrl/nuevoProyecto_AJAX',
				method: 'post',
				data: {'nombre': nombre, 'idCotizacion': idCotizacion, 'idCliente': idCliente, 'tipo':tipo, 'estado': estadoActivo},
				dataType: 'json',
				success: function(response){
					if(response.status == 'OK'){
						$("#rowNuevoProyecto #nombre").val("");
						$("#rowNuevoProyecto #idCotizacion").val("");
						$("#cProyecto *").remove();
						
						var proyectos = $("#cProyecto");

						proyectos.append("<option value='-1'>Ninguno</option>");
						for(var k = 0; k < response.data.length; k++){
							proyectos.append("<option value='"+response.data[k].id+"'>"+response.data[k].nombre+"</option>");
						}

						isEditing();
						alert('Se ha realizado la operación satisfactoriamente.');
					}else
						alert('Ha ocurrido un error al intentar actualizar. Intente de nuevo, por favor.');
				},
				error: function(){
					alert('Ha ocurrido un error al intentar actualizar. Intente de nuevo, por favor.');
				}
			});			
		});

		$("#btn-estado").click(function(event){
			event.preventDefault();

			var estado = $("#rowEditaProyecto #estado");

			if(estado.val() == 0) estado.val(1);
			else estado.val(0);

			toogleButtonActive();
		});

		$("#cProyecto").change(function(){
			isEditing();
		});

		toogleButtonActive();
		isEditing();
	});
	</script>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label for="cProyecto">Proyecto en edición</label>
					<select class="form-control" id="cProyecto">
						<option value="-1">Ninguno</option>
						<?php foreach($proyectos as $proyecto){ ?>
							<option value="<?php echo $proyecto->id; ?>"><?php echo $proyecto->nombre; ?></option>
						<?php } ?>
					</select>					
				</div>
			</div>
		</div>

		<div class="row" id="rowEditaProyecto">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					name= "form_edita_proyecto"
					id = "form_edita_proyecto"
				>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
								<label for="nombre">Nombre del proyecto</label>
								<input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del proyecto">
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
								<label for="nombre" id="labelEstado">Estado actual: </label>
								<button id="btn-estado" class="btn btn-success" style="width: 100%;">Activar</button>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="idCotizacion">ID de la cotización</label>
						<input type="text" class="form-control" id="idCotizacion" name="idCotizacion" placeholder="0000">
					</div>
					<div class="form-group">
						<label>Cliente</label>
						<select name="idCliente" id="idCliente" class="form-control">
							<?php foreach($clientes as $cliente){ ?>
								<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Tipo</label>
						<select name="tipo" id="tipo" class="form-control">
							<option>DESARROLLO</option>
							<option>SERVICIO</option>
							<option>CAMPAÑA</option>
							<option>MANTENIMIENTO</option>
							<option>REDES SOCIALES Y CAMPAÑA</option>
							<option>CONTENIDO</option>
							<option>Tipo 1</option>
							<option>Tipo 2</option>
							<option>Tipo 3</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="estado" id="estado" value="1">
						<input type="submit" class="btn btn-primary" value="Actualizar">
					</div>
				</form>
			</div>
		</div>

		<div class="row" id="rowNuevoProyecto">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
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
						<select name="idCliente" id="idCliente" class="form-control">
							<?php foreach($clientes as $cliente){ ?>
								<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Tipo</label>
						<select name="tipo" id="tipo" class="form-control">
							<option>DESARROLLO</option>
							<option>SERVICIO</option>
							<option>CAMPAÑA</option>
							<option>MANTENIMIENTO</option>
							<option>Tipo 1</option>
							<option>Tipo 2</option>
							<option>Tipo 3</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="estado" id="estado" value="1">
						<input type="submit" class="btn btn-success" value="Crear">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
