<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>SAC</title>
		<script src="/SAC/includes/js/jQuery.js"></script>
		<script>
			$(function(){
				$(".AInputField").addClass("AValidField");
			});

			function validarTiempo(obj){
				var regExpTiempo = new RegExp("^\\d{2}:\\d{2}$");
				var tiempoEstimado = obj.value;
				var field = "#m"+obj.id;

				if(!regExpTiempo.test(tiempoEstimado)){
					$(field).html("Error de formato (hh:mm)");
					$("#"+obj.id).addClass("AInvalidField").removeClass("AValidField");
				}else{
					$(field).html("OK");				
					$("#"+obj.id).removeClass("AInvalidField").addClass("AValidField");
				}

				$("#btnTerminar").prop("disabled", validarFormulario());
			}

			function validarFormulario(){
				var estado = true;

				$(".AInputField").each(function(index){
					if(estado && $(this).hasClass("AValidField")){
						estado = false;
					}
				});

				return estado;
			}
		</script>		
	</head>
	<body>
		<?=$menu ?>

		<?php if(isset($cTarea)){ ?>
		<form 
			name = "form_tarea_terminada"
			id = "form_tarea_terminada"
			method = "POST"
			action = "<?php echo base_url().'index.php/Marcar_terminado_ctrl/actualizarTarea/'.$cTarea->id; ?>"
			enctype = "multipart/form-data"
		>
			<div>
				<label>ID de la tarea: <?php echo $cTarea->id; ?></label>
				<br>
				<label>Cliente: <?php echo $cTarea->cliente->nombre; ?></label>
				<br>
				<label>Proyecto: <?php echo $cTarea->proyecto->nombre; ?></label>
				<br>
				<label>Título: <?php echo $cTarea->titulo; ?></label>
				<br>
				<label>Descripción: <?php echo $cTarea->descripcion; ?></label>
				<br>
				<label>Fase: <?php echo $cTarea->fase->nombre; ?></label>
				<br>
				<label>Estado: <?php echo $cTarea->estado->nombre; ?></label>
				<br>
				<label>Tiempo estimado: <?php echo $cTarea->tiempoEstimado; ?></label>
				<br>
			</div>
			<div>
				<label for="TiempoReal">Tiempo real</label>
				<input 	type="text" 
						name="tiempo" 
						id="TiempoReal" 
						placeholder="hh:mm" 
						class="AInputField"
						onchange="validarTiempo(this)"
						required>
				<label id="mTiempoReal"></label>
			</div>
			<div>
				<label for="comentarioConsultor">Comentarios</label>
				<input type="text" name="comentarioConsultor" id="comentarioConsultor" placeholder="Comentarios" required>
			</div>
			<div>
				<label for="archivo">Archivo de evidencia</label>
				<input type="file" name="archivo" id="archivo">
			</div>
			<div>
				<input type="hidden" name="idEstado" value="2">
			</div>
			<div>
				<input type="submit" value="Terminar" id="btnTerminar" disabled>
			</div>
		</form>


		<?php }else if(isset($cRetrabajo)){ ?>
		<!-- Falta cambiar el comportamiento de actualizar Retrabajo y no tarea -->
		<form 
			name = "form_tarea_terminada"
			id = "form_tarea_terminada"
			method = "POST"
			action = "<?php echo base_url().'index.php/Marcar_terminado_ctrl/actualizarRetrabajo/'.$cRetrabajo->id; ?>"
			enctype = "multipart/form-data"
		>
			<div>
				<label>ID de la tarea: <?php echo $cRetrabajo->tareaOrigen->id; ?></label>
				<br>
				<label>Cliente: <?php echo $cRetrabajo->cliente->nombre; ?></label>
				<br>
				<label>Proyecto: <?php echo $cRetrabajo->proyecto->nombre; ?></label>
				<br>
				<label>Título: <?php echo $cRetrabajo->tareaOrigen->titulo; ?></label>
				<br>
				<label>Descripción: <?php echo $cRetrabajo->descripcion; ?></label>
				<br>
				<label>Fase: <?php echo $cRetrabajo->tareaOrigen->fase->nombre; ?></label>
				<br>
				<label>Estado: <?php echo $cRetrabajo->estado->nombre; ?></label>
				<br>
				<label>Tiempo estimado: <?php echo $cRetrabajo->tiempoEstimado; ?></label>
				<br>
			</div>
			<div>
				<label for="TiempoReal">Tiempo real</label>
				<input  type="text" 
						name="tiempo" 
						id="TiempoReal" 
						placeholder="hh:mm"
						class="AInputField"
						onchange="validarTiempo(this)" 
						required>
				<label id="mTiempoReal"></label>
			</div>
			<div>
				<label for="comentarioConsultor">Comentarios</label>
				<input type="text" name="comentarioConsultor" id="comentarioConsultor" placeholder="Comentarios" required>
			</div>
			<div>
				<label for="archivo">Archivo de evidencia</label>
				<input type="file" name="archivo" id="archivo">
			</div>
			<div>
				<input type="hidden" name="idEstado" value="2">
			</div>
			<div>
				<input type="submit" value="Terminar" id="btnTerminar" disabled>
			</div>
		</form>
		<?php } ?>
	</body>
</html>