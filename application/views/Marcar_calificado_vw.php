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
				$(".AInputField").addClass("AValidField");
			});

			function validarTiempo(obj){
				var regExpTiempo = new RegExp("^\\d{2}:\\d{2}$");
				var tiempoEstimado = obj.value;
				var field = "#m"+obj.id;
				var valido = true;

				if(!regExpTiempo.test(tiempoEstimado)){
					$(field).html("<p style='color: red;'>Error de formato (hh:mm)</p>");
					$("#"+obj.id).addClass("AInvalidField").removeClass("AValidField");
				}else{
					$(field).html("<p style='color: green;'>OK</p>");
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
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">

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
				<br>
				<?php if($cTarea->archivo!=''){ ?>
				<a 
					class="btn btn-primary"
					href="<?php echo base_url().'index.php/Marcar_calificado_ctrl/downloadFile/'.($cTarea->id).'/false/'.($cTarea->archivo); ?>"
				>
				Archivo adjunto
				</a>
				<?php } ?>
				<br>
				<br>
			</div>
			<div class="form-group">
				<label for="tiempoReal">Tiempo real</label>
				<input 	type="text" 
						name="tiempo" 
						id="TiempoRealTarea" 
						placeholder="hh:mm"
						value="<?php echo $cTarea->tiempo; ?>"
						onchange="validarTiempo(this)"
						class="AInputField form-control"
				>
				<label id="mTiempoRealTarea"></label>
			</div>
			<div class="form-group">
				<label for="comentarioGerente">Comentarios</label>
				<textarea type="text" 
						name="comentarioGerente" 
						id="comentarioConsultor" 
						placeholder="Comentario de quien califica"
						class="form-control"
						rows="5"></textarea> 
			</div>
			<div class="form-group">
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<input type="submit" name="action" class="btnCorrecto btn btn-success form-control" value="Correcto">
				</div>
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<input type="submit" name="action" class="btnIncorrecto btn btn-danger form-control" value="Incorrecto">
				</div>
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
				<br>
				<?php if($cRetrabajo->archivo!=''){ ?>				
				<label>Archivo de evidencia: 
					<a 
						class="btn btn-primary"
						href="<?php echo base_url().'index.php/Marcar_calificado_ctrl/downloadFile/'.($cRetrabajo->id).'/true/'.($cRetrabajo->archivo); ?>"
					>
					Archivo adjunto
					</a>
				</label>
				<?php } ?>
				<br>
				<br>
			</div>
			<div class="form-group">
				<label for="tiempoReal">Tiempo real</label>
				<input 	type="text" 
						name="tiempo" 
						id="TiempoRealRetrabajo" 
						placeholder="hh:mm" 
						value="<?php echo $cRetrabajo->tiempo; ?>"
						class="AInputField form-control"
						onchange="validarTiempo(this)"
				>
				<label id="mTiempoRealRetrabajo"></label>
			</div>
			<div class="form-group">
				<label for="comentarioGerente">Comentarios</label>
				<textarea 
					type="text" 
					name="comentarioGerente" 
					id="comentarioConsultor" 
					placeholder="Comentario de quien califica" 
					class="form-control" 
					rows="5"></textarea>
			</div>
			<div class="form-group">
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<input type="submit" name="action" class="btnCorrecto btn btn-success form-control" value="Correcto">
				</div>
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<input type="submit" name="action" class="btnIncorrecto btn btn-danger form-control" value="Incorrecto">
				</div>
			</div>			
		</form>
		<?php } ?>

				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<a href="<?php echo base_url().'index.php/Listar_tareas_calificar_ctrl' ?>">
					<button class="btn btn-warning form-control">Cancelar</button>
				</a>
				</div>

			</div>
		</div>
	</div>
	</body>
</html>