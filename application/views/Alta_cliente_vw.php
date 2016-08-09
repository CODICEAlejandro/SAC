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
			function checkCCliente(){
				var cCliente = $("#cCliente").val();

				if(cCliente == -1){
					$("#rowNuevoCliente").show();
					$("#rowEdicionCliente").hide();
				}else{
					$.ajax({
						url: '<?php echo base_url(); ?>index.php/Alta_cliente_ctrl/consultarCliente_AJAX/'+cCliente,
						method: 'post',
						dataType: 'json',
						success: function(response){
							$("#nombre:visible").val(response.nombre);
							$("input[name='estadoActivo']").val(response.estadoActivo);
							$("input[name='id']").val(cCliente);

							checkEstadoActivo();
						},
						error: function(){
							alert("Ha ocurrido un error al intentar consultar el cliente seleccionado. Intente de nuevo, por favor.")
						}
					});

					$("#rowNuevoCliente").hide();
					$("#rowEdicionCliente").show();				
				}
			}

			function checkEstadoActivo(){
				var estadoActivo = $("input[name='estadoActivo']").val();

				if(estadoActivo == 1){ 
					$("#btn-estado").removeClass("btn-success").addClass("btn-danger").html("Inactivar");
					$("#labelEstado").html("Estado: Activo");
				}else if(estadoActivo == 0){ 
					$("#btn-estado").removeClass("btn-danger").addClass("btn-success").html("Activar");
					$("#labelEstado").html("Estado: Inactivo");
				}

			}

			$("#cCliente").change(function(){
				checkCCliente();
			});

			$("#btn-estado").click(function(event){
				event.preventDefault();
				var inpEstadoActivo = $("input[name='estadoActivo']");

				if(inpEstadoActivo.val() == 1) inpEstadoActivo.val(0); 
				else if(inpEstadoActivo.val() == 0) inpEstadoActivo.val(1);

				checkEstadoActivo(); 
			});


			$("#form_alta").submit(function(event){
				event.preventDefault();
				var nombreComercial = $("input[name='nombre']:visible").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Alta_cliente_ctrl/nuevoCliente_AJAX',
					method: 'post',
					dataType: 'text',
					data: {'nombre' : nombreComercial},
					success: function(response){
						if(response == "OK") {
							alert("Operación realizada con éxito.");
							window.location.reload();
						}else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
					},
					error: function(){
						alert("Ha ocurrido un error. Intente de nuevo, por favor.");
					}
				});
			});

			$("#form-edita").submit(function(event){
				event.preventDefault();
				var nombreComercial = $("input[name='nombre']:visible").val();
				var estadoActivo = $("input[name='estadoActivo']").val();
				var id = $("input[name='id']").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Alta_cliente_ctrl/editarCliente_AJAX',
					method: 'post',
					dataType: 'text',
					data: {'nombre' : nombreComercial, 'estadoActivo' : estadoActivo, 'id' : id},
					success: function(response){
						if(response == "OK") {
							alert("Operación realizada con éxito.");
							window.location.reload();
						}else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
					},
					error: function(){
						alert("Ha ocurrido un error. Intente de nuevo, por favor.");
					}					
				});
			});

			checkEstadoActivo();
			checkCCliente();
		});
	</script>

</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label for="cCliente">Cliente en edición</label>
					<select id="cCliente" class="form-control">
						<option value="-1">Ninguno</option>
						<?php foreach($clientes as $cliente){ ?>
							<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row" id="rowNuevoCliente">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					action = "Alta_cliente_ctrl/nuevoCliente"
					method = "post"
					id = "form_alta"
				>
					<div class="form-group">
						<label for="nombre">Nombre comercial</label>
						<input type="text" name="nombre" placeholder="Nombre comercial" class="form-control" required>
					</div>
					<div class="form-group">
						<input type="submit" value="Crear" class="btn btn-primary">
					</div>
				</form>
			</div>
		</div>

		<div class="row" id="rowEdicionCliente">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					action = "Alta_cliente_ctrl/editarCliente"
					method = "post"
					id = "form-edita"
				>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
								<label for="nombre">Nombre comercial</label>
								<input type="text" name="nombre" id="nombre" placeholder="Nombre comercial" class="form-control" required>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
								<label id="labelEstado">Estado: Activo</label>
								<button style="width: 100%;" id="btn-estado" class="btn btn-danger">Inactivar</button>
							</div>
						</div>
					</div>
					
					<input type="hidden" name="id" value="-1">
					<input type="hidden" name="estadoActivo" value="1">

					<div class="form-group">
						<input type="submit" value="Actualizar" class="btn btn-info">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>