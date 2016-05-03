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
				var valido = true;

				if(!regExpTiempo.test(tiempoEstimado)){
					$(field).html("Error de formato (hh:mm)");
					$("#"+obj.id).addClass("AInvalidField").removeClass("AValidField");
				}else{
					$(field).html("OK");				
					$("#"+obj.id).removeClass("AInvalidField").addClass("AValidField");
				}

				valido = validarFormulario();
				$(".btnCorrecto").prop("disabled",valido);
				$(".btnIncorrecto").prop("disabled",valido);					
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
			action = "<?php echo base_url().'index.php/Marcar_calificado_ctrl/actualizarTarea/'.($cTarea->id).'/Tarea'; ?>"
			method = "POST"
			id = "form_calificado"
			name = "form_calificado"
		>
			<div>
				<label>ID de la tarea: <?php echo $cTarea->id; ?></label>
				<br>
				<label>Creación: <?php echo $cTarea->creacion; ?></label>
				<br>
				<label>Responsable: <?php echo $cTarea->responsable->nombre; ?></label>
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
				<label>Comentario del consultor: <?php echo $cTarea->comentarioConsultor; ?></label>
				<br>
			</div>
			<div>
				<label for="tiempoReal">Tiempo real</label>
				<input 	type="text" 
						name="tiempo" 
						id="TiempoRealTarea" 
						placeholder="hh:mm"
						value="<?php echo $cTarea->tiempo; ?>"
						onchange="validarTiempo(this)"
						class="AInputField"
				>
				<label id="mTiempoRealTarea"></label>
			</div>
			<div>
				<label for="comentarioGerente">Comentarios</label>
				<input 	type="text" 
						name="comentarioGerente" 
						id="comentarioConsultor" 
						placeholder="Comentario de quien califica">
			</div>
			<div>
				<input type="submit" name="action" class="btnCorrecto" value="Correcto">
				<input type="submit" name="action" class="btnIncorrecto" value="Incorrecto">
			</div>
		</form>


		<?php }else if(isset($cRetrabajo)){ ?>
		<form 
			action = "<?php echo base_url().'index.php/Marcar_calificado_ctrl/actualizarTarea/'.($cRetrabajo->id).'/Retrabajo'; ?>"
			method = "POST"
			id = "form_calificado"
			name = "form_calificado"
		>
			<div>
				<label>ID de la tarea: <?php echo $cRetrabajo->tareaOrigen->id; ?></label>
				<br>
				<label>Creación: <?php echo $cRetrabajo->creacion; ?></label>
				<br>
				<label>Responsable: <?php echo $cRetrabajo->responsable->nombre; ?></label>
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
				<label>Comentario del consultor: <?php echo $cRetrabajo->comentarioConsultor; ?></label>
				<br>
			</div>
			<div>
				<label for="tiempoReal">Tiempo real</label>
				<input 	type="text" 
						name="tiempo" 
						id="TiempoRealRetrabajo" 
						placeholder="hh:mm" 
						value="<?php echo $cRetrabajo->tiempo; ?>"
						class="AInputField"
						onchange="validarTiempo(this)"
				>
				<label id="mTiempoRealRetrabajo"></label>
			</div>
			<div>
				<label for="comentarioGerente">Comentarios</label>
				<input type="text" name="comentarioGerente" id="comentarioConsultor" placeholder="Comentario de quien califica">
			</div>
			<div>
				<input type="submit" name="action" class="btnCorrecto" value="Correcto">
				<input type="submit" name="action" class="btnIncorrecto" value="Incorrecto">
			</div>
		</form>
		<?php } ?>

		<div>
			<a href="<?php echo base_url().'index.php/Listar_tareas_calificar_ctrl' ?>">
				<button>Cancelar</button>
			</a>
		</div>
	</body>
</html>