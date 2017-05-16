<?php  
defined('BASEPATH') OR exit('No direct script access allowed'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<style>
		.container{
			width: 100% !important;
			
		}

		.table{
			text-align: center;
			vertical-align: middle;
		}
	</style>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Cotizacion/Listado_cotizacion_JS.js"></script>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<form class="form" action="Cotizacion/Tabla_cotizacion_ctrl.php" id="form_filtrado">
				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 form-group">
					<label>Filtra los resultados por:</label>
					<select class="form-control" name="filtrado" id="filtrado">
						<option value="-1">Selecciona una opción</option>
						<option value="1">Cliente</option>
						<option value="2">Usuario</option>
						<option value="3">Folio</option>
						<option value="4">Status</option>
						<option value="5">Fecha</option>
						<option value="6">Vigentes/Vencidas</option>
					</select>
					<input type="submit" class="form-control btn btn-info" value="Filtrar">
				</div>
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<label>Buscar:</label>
					<div class="form-group">
					<input type="text" class="form-control" id="search" name="search" placeholder="Busca por título o folio">
					<button class="btn btn-info form-control" id="btn-search">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					</div>
				</div>
			</form>
			<table class="table table-bordered table-hover" id="tabla_cotizaciones">
				<thead>
					<th>Folio</th>
					<th>Cliente</th>
					<th>Título</th>
					<th>Contacto</th>
					<th>Importe Total</th>
					<th>Fecha de alta</th>
					<th>Fecha de inicio servicio</th>
					<th>Fecha de término servicio</th>
					<th>Usuario</th>
					<th>Status</th>
					<th>Exportar PDF</th>
					<th>Editar</th>
					<th>Aprobar</th>
					<th>Cancelar</th>
					<th>Duplicar</th>
				</thead>
				<tbody id="cuerpoTabla">
					<?php foreach ($cotizaciones as $c){ ?>
						<tr id="contenidoTabla">
							<td><?php echo $c->folio; ?></td>
							<td><?php echo $c->nombre_cli; ?></td>
							<td><?php echo $c->titulo; ?></td>
							<td><?php echo $c->nombre_cli." ".$c->apellido_cli."<br>".$c->correo; ?></td>
							<td><?php echo $c->importe_total; ?></td>
							<td><?php if($c->fecha_alta == "00-00-0000"){echo "Indefinida";}else{echo $c->fecha_alta;} ?></td>
							<td><?php if($c->fecha_inicio == "00-00-0000"){echo "Indefinida";}else{echo $c->fecha_inicio;} ?></td>
							<td><?php if($c->fecha_fin=="00-00-0000"){echo "Indefinida";}else{echo $c->fecha_fin;} ?></td>
							<td><?php echo $c->nombre_acc; ?></td>
							<td><?php echo $c->clave_status; ?></td>
							<td>
								<button class="btn btn-primary">
									<span class="glyphicon glyphicon-new-window"></span>
								</button>
							</td>
							<td>
								<button class="btn btn-warning">
									<span class="glyphicon glyphicon-edit"></span>
								</button>
							</td>
							<td>
								<button class="btn btn-success">
									<span class="glyphicon glyphicon-ok"></span>
								</button>
							</td>
							<td>
								<button class="btn btn-danger">
									<span class="glyphicon glyphicon-remove"></span>
								</button>
							</td>
							<td>
								<button class="btn btn-primary">
									<span class="glyphicon glyphicon-duplicate"></span>
								</button>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>


	
<tr id="cloneSectionTabla" style="display: none;">
	<td id="folio"></td>
	<td id="nombreCliente"></td>
	<td id="titulo"></td>
	<td id="contacto"></td>
	<td id="importe_total"></td>
	<td id="fecha_alta"></td>
	<td id="fecha_inicio"></td>
	<td id="fecha_fin"></td>
	<td id="nombreAcc"></td>
	<td id="status"></td>
	<td>
		<button class="btn btn-info" style="display: none;">
			<span class="glyphicon glyphicon-new-window"></span>
		</button>
	</td>
	<td>
		<button class="btn btn-warning" style="display: none;">
			<span class="glyphicon glyphicon-edit"></span>
		</button>
	</td>
	<td>
		<button class="btn btn-success" style="display: none;">
			<span class="glyphicon glyphicon-ok"></span>
		</button>
	</td>
	<td>
		<button class="btn btn-danger" style="display: none;">
			<span class="glyphicon glyphicon-remove"></span>
		</button>
	</td>
	<td>
		<button class="btn btn-primary" style="display: none;">
			<span class="glyphicon glyphicon-duplicate"></span>
		</button>
	</td>
</tr>

<div class="filtro-clone xs-12 sm-12 md-12 lg-12">
	
</div>

</body>
</html>