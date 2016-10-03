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

				$("#archivo").change(function(){
					validarArchivo();
				});
			});

			function validarArchivo(){
				var fileName = $("#archivo").val();
				var allowedTypes = "jpg|JPG|jpeg|JPEG|gif|GIF|pdf|PDF|png|PNG|zip|ZIP|numbers|NUMBERS|XML|xml".split("|");
				var fileExtension = fileName.split(".").reverse();
				
				if(fileExtension.length > 0)
					fileExtension = fileExtension[0];
				else
					fileExtension = 'NO_ALLOWED';

				if( $.inArray(fileExtension, allowedTypes) == -1 )
					alert("Recuerde, las extensiones permitidas para archivos subidos al servidor son: jpg, gif, pdf, png, zip, numbers y xml");
			}

			function validarTiempo(obj){
				var regExpTiempo = new RegExp("^\\d{2}:\\d{2}$");
				var tiempoEstimado = obj.value;
				var field = "#m"+obj.id;
				var hoursReal, hoursEstimado;
				var minutesReal, minutesEstimado;
				var minutesRealTot, minutesEstimadoTot;

				hoursEstimado = $("#TiempoEstimado").html().split(":")[0];
				minutesEstimado = $("#TiempoEstimado").html().split(":")[1];				
				minutesEstimadoTot = (parseInt(hoursEstimado)*60) + parseInt(minutesEstimado);

				if(!regExpTiempo.test(tiempoEstimado)){
					$(field).html("<p style='color: red;'>Error de formato (hh:mm)</p>");
					$("#"+obj.id).addClass("AInvalidField").removeClass("AValidField");
				}else{
					$(field).html("OK");				
					$(field).html("<p style='color: green;'>OK</p>");
					$("#"+obj.id).addClass("AValidField").removeClass("AInvalidField");

					hoursReal = $("#TiempoReal").val().split(":")[0];
					minutesReal = $("#TiempoReal").val().split(":")[1];
					minutesRealTot = (parseInt(hoursReal)*60) + parseInt(minutesReal);

					if( (((minutesEstimadoTot+(0.2*minutesEstimadoTot)))<minutesRealTot) || (((minutesEstimadoTot-(0.2*minutesEstimadoTot)))>minutesRealTot) )
						alert("El tiempo estimado difiere del valor real indicado en más del 20%.");
					else
						console.log(minutesEstimadoTot+(0.2*minutesEstimadoTot));		
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
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">

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
							<label>Tiempo estimado: <span id="TiempoEstimado"><?php echo $cTarea->tiempoEstimado; ?></span></label>
							<br>
						</div>
						<div class="form-group">
							<label for="TiempoReal">Tiempo real</label>
							<input 	type="text" 
									name="tiempo" 
									id="TiempoReal" 
									placeholder="hh:mm" 
									class="AInputField form-control"
									onchange="validarTiempo(this)"
									required>
							<label id="mTiempoReal"></label>
						</div>
						<div class="form-group">
							<label for="comentarioConsultor">Comentarios</label>
							<textarea type="text" name="comentarioConsultor" id="comentarioConsultor" class="form-control" placeholder="Comentarios" rows="5" required></textarea>
						</div>
						<div class="form-group">
							<label for="archivo">Archivo de evidencia</label>
							<input type="file" name="archivo" id="archivo" class="form-control">
						</div>
						<div>
							<input type="hidden" name="idEstado" value="2">
						</div>
						<div class="form-group">
							<input type="submit" value="Terminar" id="btnTerminar" class="btn btn-primary" disabled>
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
							<label>Tiempo estimado: <span id="TiempoEstimado"><?php echo $cRetrabajo->tiempoEstimado; ?></span></label>
							<br>
						</div>
						<div class="form-group">
							<label for="TiempoReal">Tiempo real</label>
							<input  type="text" 
									name="tiempo" 
									id="TiempoReal" 
									placeholder="hh:mm"
									class="AInputField form-control"
									onchange="validarTiempo(this)" 
									required>
							<label id="mTiempoReal"></label>
						</div>
						<div class="form-group">
							<label for="comentarioConsultor">Comentarios</label>
							<textarea type="text" name="comentarioConsultor" id="comentarioConsultor" placeholder="Comentarios" class="form-control" rows="5" required></textarea>
						</div>
						<div class="form-group">
							<label for="archivo">Archivo de evidencia</label>
							<input type="file" name="archivo" id="archivo" class="form-control">
						</div>
						<div>
							<input type="hidden" name="idEstado" value="2">
						</div>
						<div class="form-group">
							<input type="submit" value="Terminar" id="btnTerminar" class="btn btn-primary" disabled>
						</div>
					</form>
						</div>
					</div>
					<!-- Listado de historial -->
					<div><h4>Historial</h4></div>		
					<?php foreach($historial as $item){ ?>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="alert alert-info">
									<br>
									<label>Tiempo: <?php echo $item->tiempo; ?></label>
									<br>
									<label>Descripción: <?php echo $item->descripcion; ?></label>
									<br>
									<label>Comentario del Gerente: <?php echo $item->comentarioGerente; ?></label>
									<br>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</body>
</html>