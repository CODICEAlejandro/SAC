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
			var hours = parseInt(tiempoEstimado.split(":")[0]);
			var minutes = parseInt(tiempoEstimado.split(":")[1]);
			var totalMinutes = (hours*60)+minutes;
			var field = "#m"+obj.id;

			if(!regExpTiempo.test(tiempoEstimado)){
				$(field).html("<p style='color: red;'>Error de formato (hh:mm)</p>");
				$("#"+obj.id).addClass("AInvalidField").removeClass("AValidField");
			}else if((totalMinutes>(8*60)) || (totalMinutes<=0)){
				$(field).html("<p style='color: red;'>El tiempo no debe ser inferior a 0 ni debe exceder 8 horas</p>");
				$("#"+obj.id).addClass("AInvalidField").removeClass("AValidField");
			}else{
				$(field).html("<p style='color: green;'>OK</p>");				
				$("#"+obj.id).removeClass("AInvalidField").addClass("AValidField");
			}

			$("#btnAlta").prop("disabled",validarFormulario());
		}

		function validarFormulario(){
			var estado = true;

			$(".AInputField").each(function(index){
				if(estado && $(this).hasClass("AValidField")){
					estado = false;
				}
			});

			return estado
		}
	</script>

	<style>
		tr td:first-child {
			background-color: #ffdf80;
			text-align: right;
			width: 30%;
		}

		tr td:last-child {
			background-color: #eee;
			text-align: right;
		}

		table, td, th {
			border: 1px solid #aaa !important;
		}
	</style>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">

				<table class="table">
					<tbody>
						<tr>
							<td>Cliente</td>
							<td><?php echo $cliente->nombre; ?></td>
						</tr>
						<tr>
							<td>Proyecto</td>
							<td><?php echo $proyecto->nombre; ?></td>
						</tr>
					</tbody>
				</table>

				<?php if(!isset($retrabajo)){ ?>
				<form
					method = "POST"
					action = "<?php echo base_url().'index.php/Alta_tarea_ctrl/driverActividades' ?>"
					name= "form_alta_tarea"
					id = "form_alta_tarea"
				>
					<div class="form-group">
						<label for="titulo">Título</label>
						<input type="text" name="titulo" id="titulo" placeholder="Título" class="form-control" maxlength="30" required>
					</div>
					<div class="form-group">
						<label for="descripcion">Descripción</label>
						<textarea name="descripcion" id="descripcion" class="form-control" rows="10" required>
						</textarea>
					</div>
					<div class="form-group">
						<label for="TiempoEstimadoTarea">Tiempo estimado</label>
						<input 	type="text" 
								name="tiempoEstimado" 
								id="TiempoEstimadoTarea" 
								class="AInputField form-control"
								placeholder="Horas y minutos (hh:mm)" 
								onchange="validarTiempo(this)" 
								required>
						<label id="mTiempoEstimadoTarea"></label>
					</div>

					<div class="form-group">
						<label for="idFase">Fase</label>
						<select name="idFase" id="idFase" class="form-control">
							<?php foreach($fases as $fase){ ?>
								<option value="<?php echo $fase->id; ?>">
									<?php echo $fase->nombre; ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<input type="hidden" name="idProyecto" value="<?php echo $proyecto->id; ?>">
					<input type="hidden" name="idResponsable" value="<?php echo $this->session->userdata('id'); ?>">
					<input type="hidden" name="idCliente" value="<?php echo $cliente->id; ?>">
					<input type="hidden" name="idEstado" value="1">

					<div class="form-group">
						<input type="submit" name="action" value="Alta" id="btnAlta" class="form-control btn btn-success" disabled>
					</div>
				</form>


				<?php }else{ ?>
				<form
					method = "POST"
					action = "<?php echo base_url().'index.php/Alta_tarea_ctrl/driverActividades' ?>"
					name= "form_alta_tarea"
					id = "form_alta_tarea"
				>
					<div>
						<label>Título: <?php echo $retrabajo->tareaOrigen->titulo; ?></label>
					</div>
					<div>
						<label for="descripcion">Descripción</label>
						<textarea name="descripcion" id="descripcion" class="form-control" rows="10" required>
						</textarea>
					</div>
					<div>
						<label for="tiempoEstimado">Tiempo estimado</label>
						<input type="text" name="tiempoEstimado" id="tiempoEstimado" placeholder="Horas y minutos (hh:mm)" required>
					</div>
					<div>
						<label for="idFase">Fase: <?php echo $retrabajo->tareaOrigen->fase->nombre; ?></label>
					</div>

					<input type="hidden" name="idProyecto" value="<?php echo $proyecto->id; ?>">
					<input type="hidden" name="idResponsable" value="<?php echo $this->session->userdata('id'); ?>">
					<input type="hidden" name="idCliente" value="<?php echo $cliente->id; ?>">
					<input type="hidden" name="idEstado" value="4">
					<input type="hidden" name="idRetrabajo" value="<?php echo $retrabajo->id; ?>">

					<div>
						<button type="submit" name="action" value="Alta_retrabajo" id="btnAlta">Alta</button>
					</div>
				</form>

				<form
					method="POST"
					name="form_action" 
					action="<?php echo base_url().'index.php/Alta_tarea_ctrl/driverActividades' ?>"
				>
					<input type="submit" name="action" value="Cancelar">
				</form>

				<!-- Listado de historial -->
				<div>
					<?php foreach($historial as $item){ ?>
						<div>
							<br>
							<label>Tiempo: <?php echo $item->tiempo; ?></label>
							<br>
							<label>Descripción: <?php echo $item->descripcion; ?></label>
							<br>
							<label>Comentario del Gerente: <?php echo $item->comentarioGerente; ?></label>
							<br>
						</div>
					<?php } ?>
				</div>
				<?php } ?>

			</div>
		</div>
	</div>

</body>
</html>