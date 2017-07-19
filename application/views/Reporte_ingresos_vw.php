<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<style type="text/css">
		select {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
		}

	</style>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Reporte_ingresos_JS.js"></script>
	<script type="text/javascript">var baseURL = '<?php echo base_url(); ?>';</script>

	<!--Fancybox-->
	<script type="text/javascript" src="<?php echo base_url();?>includes/fancybox-3.0/dist/jquery.fancybox.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>includes/fancybox-3.0/dist/jquery.fancybox.css" type="text/css" media="screen" />
</head>
<body>
	<?=$menu?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="appendFiltros">
				<label for="filtro">Ingresa el periodo del que quieres generar el reporte de ingresos.</label>
				<select name="filtro" id="filtro" class="form-control">
					<option value="-1" selected>Seleccione una opción</option>
					<option value="1">Mensual</option>
					<option value="2">Trimestral</option>
					<option value="3">Semestral</option>
					<option value="4">Anual</option>
					<option value="5">Fechas específicas</option>
				</select>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group">
				<button class="btn btn-success form-control" id="btn_genera_reporte">Genera reporte de ingresos</button>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 appendMes" style="display: none;" id="appendMesClon">
		<select name="mes" id="mes" class="form-control" style="margin-top: 20px;">
			<option value="-1" selected>Selecciona una opción</option>
			<option value="01">Enero</option>
			<option value="02">Febrero</option>
			<option value="03">Marzo</option>
			<option value="04">Abril</option>
			<option value="05">Mayo</option>
			<option value="06">Junio</option>
			<option value="07">Julio</option>
			<option value="08">Agosto</option>
			<option value="09">Septiembre</option>
			<option value="10">Octubre</option>
			<option value="11">Noviembre</option>
			<option value="12">Diciembre</option>
		</select>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 appendTrimestre" style="display: none;" id="appendTrimestreClon">
		<select name="trimestre" id="trimestre" class="form-control" style="margin-top: 20px;">
			<option value="-1" selected>Selecciona una opción</option>
			<option value="1">Primer trimestre (Enero - Marzo)</option>
			<option value="2">Segundo trimestre (Abril - Junio)</option>
			<option value="3">Tercer trimestre (Julio - Septiembre)</option>
			<option value="4">Cuarto trimestre (Octubre - Diciembre)</option>
		</select>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 appendSemestre" style="display: none;" id="appendSemestreClon">
		<select name="semestre" id="semestre" class="form-control" style="margin-top: 20px;">
			<option value="-1" selected>Selecciona una opción</option>
			<option value="1">Primer semestre (Enero - Junio)</option>
			<option value="2">Segundo semestre (Julio - Diciembre)</option>
		</select>
	</div>

	<div class="appendFechas" style="display:none;" id="appendFechasClon">
		<div id="fechas_busqueda" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px;">
			<div class="col-sm-6 col-md-6 col-lg-6 form-group">
				<div class="well">
					<label for="fecha_inicio">Fecha de inicio: </label>
					<input type="text" id="fecha_inicio" class="form-control datepicker"/>
				</div>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-6 form-group">
				<div class="well">
				<label for="fecha_fin">Fecha de fin: </label>
				<input type="text" id="fecha_fin" class="form-control datepicker"/>
				</div>
			</div>
		</div>
	</div>

	<form
		action="<?php echo base_url(); ?>index.php/Reporte_ingresos_ctrl/generaReporte"
		method="post"
		id="form-excel"
		style="display: none;"
	>
		<input type="text" name="dataXLS" id="dataXLS" value="">
	</form>
</body>
</html>