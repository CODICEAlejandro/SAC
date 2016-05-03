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
</head>
<body>
	<?=$menu ?>

	<div>
		<label><?php echo $cliente->nombre; ?></label>		
	</div>
	<div>
		<label><?php echo $proyecto->nombre; ?></label>		
	</div>

	<?php if(!isset($retrabajo)){ ?>
	<form
		method = "POST"
		action = "<?php echo base_url().'index.php/Alta_tarea_ctrl/driverActividades' ?>"
		name= "form_alta_tarea"
		id = "form_alta_tarea"
	>
		<div>
			<label for="titulo">Título</label>
			<input type="text" name="titulo" id="titulo" placeholder="Título" required>
		</div>
		<div>
			<label for="descripcion">Descripción</label>
			<input type="text" name="descripcion" id="descripcion" placeholder="Descripción" required>
		</div>
		<div>
			<label for="TiempoEstimadoTarea">Tiempo estimado</label>
			<input 	type="text" 
					name="tiempoEstimado" 
					id="TiempoEstimadoTarea" 
					class="AInputField"
					placeholder="Horas y minutos (hh:mm)" 
					onchange="validarTiempo(this)" required>
			<label id="mTiempoEstimadoTarea"></label>
		</div>
		<div>
			<label for="idFase"></label>
			<select name="idFase" id="idFase">
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

		<div>
			<input type="submit" name="action" value="Alta" id="btnAlta" disabled>
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
			<input type="text" name="descripcion" id="descripcion" placeholder="Descripción" required>
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

</body>
</html>