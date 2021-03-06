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
			$("#btn-retrieve-global-detail").click(function(event){
				event.preventDefault();
				var idCliente = $("#filtroCliente").val();
				var idArea = $("#filtroArea").val();
				var idProyecto = $("#filtroProyecto").val();
				var idConsultor = $("#filtroConsultor").val();
				var fechaInf = $("#dateDesdeAlt").val();
				var fechaSup = $("#dateHastaAlt").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Reporte_rentabilidad_ctrl/onRetrieveGlobalDetail',
					dataType: 'json',
					method: 'post',
					data: {'idProyecto': idProyecto, 'idConsultor': idConsultor, 'fechaSup': fechaSup, 'fechaInf': fechaInf, 'idCliente': idCliente, 'idArea': idArea},
					success: function(response){
						var section = $("#row-global-data");
						var expectedTimes = $("#expected-table");
						var rowLunesJueves = expectedTimes.find("#row-lunes-jueves");
						var rowViernes = expectedTimes.find("#row-viernes");
						var rowTotales = expectedTimes.find("#row-totales");

						var consultorTimesTable = $("#consultor-times-table");
						consultorTimesTable.find("tbody *").remove();

						rowLunesJueves.find('#dias-expected-table').html(response.numberDaysNoWeekend);
						rowLunesJueves.find('#consultores-expected-table').html(response.numberConsultors);
						rowLunesJueves.find("#total-expected-table").html(response.hopeTimeNoWeekend);

						rowViernes.find('#dias-expected-table').html(response.numberDaysWeekend);
						rowViernes.find('#consultores-expected-table').html(response.numberConsultors);
						rowViernes.find("#total-expected-table").html(response.hopeTimeWeekend);

						rowTotales.find('#dias-expected-table').html(response.totalDaysInterval);
						rowTotales.find('#consultores-expected-table').html(response.numberConsultors * 2);
						rowTotales.find("#total-expected-table").html(response.totalHopeTime);

						for(var k=0, n=response.consultors.length; k<n; k++){
							consultorTimesTable.find("tbody").append("<tr></tr>");
							consultorTimesTable.find("tbody tr:last-child").append("<td>"+response.consultors[k].nombre+"</td>");
							consultorTimesTable.find("tbody tr:last-child").append("<td>"+response.consultors[k].tiempoReal+"</td>");
							consultorTimesTable.find("tbody tr:last-child").append("<td>"+response.consultors[k].expectedTime+"</td>");
							consultorTimesTable.find("tbody tr:last-child").append("<td>"+response.consultors[k].dieTime+"</td>");
						}

						section.show();
					},
					error: function(){
						alert("ERROR");
					}
				});
			});

			$("#dateDesde").datepicker({
				dateFormat: 'dd/mm/yy',
				altFormat: 'yy/mm/dd',
				altField: '#dateDesdeAlt'
			}).datepicker('setDate', new Date());

			$("#dateHasta").datepicker({
				dateFormat: 'dd/mm/yy',
				altFormat: 'yy/mm/dd',
				altField: '#dateHastaAlt'
			}).datepicker('setDate', new Date());

			$("#filtroCliente").change(function(){
				var idCliente = $("#filtroCliente").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Reporte_rentabilidad_ctrl/onChangeCliente/'+idCliente,
					method: 'GET',
					dataType: 'json',
					success: function(response){
						var proyectos = response.proyectos;
						var k, n;

						$("#filtroProyecto *").remove();
						$("#filtroProyecto").append("<option value='-1'>Mostrar todos</option>");
						for(k=0, n=proyectos.length; k<n; k++)
							$("#filtroProyecto").append("<option value='"+proyectos[k].id+"'>"+proyectos[k].nombre+"</option>");
					}
				});
			});

			$("#filtroArea").change(function(){
				var idArea = $("#filtroArea").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Reporte_rentabilidad_ctrl/onChangeArea/'+idArea,
					method: 'GET',
					dataType: 'json',
					success: function(response){
						var usuarios = response.usuarios;
						var k, n;

						$("#filtroConsultor *").remove();
						$("#filtroConsultor").append("<option value='-1'>Mostrar todos</option>");
						for(k=0, n=usuarios.length; k<n; k++)
							$("#filtroConsultor").append("<option value='"+usuarios[k].id+"'>"+usuarios[k].nombre+"</option>");
					}
				});
			});

			$("#exportar-XLS").click(function(event){
				event.preventDefault();
				var url = 'Rentabilidad_xls/setExcel';
				var getData = "/";
				var dateInf = $("#dateDesdeAlt").val();
				var dateHasta = $("#dateHastaAlt").val();
				dateInf = dateInf.split("/").join("_");
				dateHasta = dateHasta.split("/").join("_");

				getData += dateHasta+"/";
				getData += dateInf+"/";
				getData += ($("#filtroProyecto").val())+"/";
				getData += ($("#filtroConsultor").val())+"/";
				getData += ($("#filtroArea").val())+"/";
				getData += ($("#filtroCliente").val());

				url += getData;

				window.location.replace(url);
			});

			$("#form-filtros").submit(function(event){
				event.preventDefault();
				var idCliente = $("#filtroCliente").val();
				var idArea = $("#filtroArea").val();
				var idProyecto = $("#filtroProyecto").val();
				var idConsultor = $("#filtroConsultor").val();
				var fechaInf = $("#dateDesdeAlt").val();
				var fechaSup = $("#dateHastaAlt").val();

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Reporte_rentabilidad_ctrl/onRetrieveData',
					method: 'post',
					data: {'idProyecto': idProyecto, 'idConsultor': idConsultor, 'fechaSup': fechaSup, 'fechaInf': fechaInf, 'idCliente': idCliente, 'idArea': idArea},
					dataType: 'json',
					success: function(response){
						var k, n;
						responsePrimary = response.primary_table;
						responseSecondary = response.secondary_table;
						$("#main-table tbody *").remove();
						$("#secondary-table tbody *").remove();

						for(k=0, n=responsePrimary.length; k<n; k++){
							$("#main-table tbody:last-child").append("<tr></tr>");

							$("#main-table tbody:last-child tr:last-child").append("<td>"+responsePrimary[k].nombre+"</td>");
							$("#main-table tbody:last-child tr:last-child").append("<td>"+responsePrimary[k].nombreArea+"</td>");
							$("#main-table tbody:last-child tr:last-child").append("<td>"+responsePrimary[k].nombreCliente+"</td>");
							$("#main-table tbody:last-child tr:last-child").append("<td>"+responsePrimary[k].nombreProyecto+"</td>");
							$("#main-table tbody:last-child tr:last-child").append("<td>"+responsePrimary[k].nombreFase+"</td>");
							$("#main-table tbody:last-child tr:last-child").append("<td>"+responsePrimary[k].tiempoEstimado+"</td>");
							$("#main-table tbody:last-child tr:last-child").append("<td>"+responsePrimary[k].tiempoReal+"</td>");
						}

						for(k=0, n=responseSecondary.length; k<n; k++){
							$("#secondary-table tbody:last-child").append("<tr></tr>");

							$("#secondary-table tbody:last-child tr:last-child").append("<td>"+responseSecondary[k].nombre+"</td>");
							$("#secondary-table tbody:last-child tr:last-child").append("<td>"+responseSecondary[k].fase+"</td>");
							$("#secondary-table tbody:last-child tr:last-child").append("<td>"+responseSecondary[k].total+"</td>");
							$("#secondary-table tbody:last-child tr:last-child").append("<td>"+responseSecondary[k].tiempoReal+"</td>");
						}
					},
					error: function(){
						alert('Ha ocurrido un error al intentar conectarse con la base de datos');
					}
				});
			});
		});
	</script>

	<style type="text/css">
		#expected-table tbody tr td:last-child {
			background-color: rgb(234, 182, 0);
			color: black;
		}

		#expected-table tbody tr:last-child td {
			background-color: rgb(212, 119, 0);
			color: black;
		}

		#expected-table tbody tr td:first-child {
			background-color: rgb(43, 132, 161);
			color: white;
		}

		#expected-table thead th {
			background-color: rgb(0, 86, 114);
			color: white;
		}

		#expected-table td, #expected-table th {
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					id="form-filtros"
					class="form"
				>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label for="filtroFecha">Desde</label>
								<input class="datepicker form-control" id="dateDesde"></input>
								<input type="hidden" id="dateDesdeAlt"></input>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label for="filtroFecha">Hasta</label>
								<input class="datepicker form-control" id="dateHasta"></input>
								<input type ="hidden" id="dateHastaAlt"></input>
							</div>
						</div>
					</div>					

					<div class="form-group">
						<label for="filtroCliente">Cliente</label>
						<select name="filtroCliente" id="filtroCliente" class="form-control">
							<option value="-1">Mostrar todos</option>
							<?php foreach($clientes as $cliente){ ?>
								<option value="<?php echo $cliente->id; ?>">
									<?php echo $cliente->nombre; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					
					<div class="form-group" id="filtroProyectoSection">
						<label for="filtroProyecto">Proyecto</label>
						<select name="filtroProyecto" id="filtroProyecto" class="form-control">
							<option value="-1">Mostrar todos</option>
						</select>
					</div>

					<div class="form-group">
						<label for="filtroArea">Área</label>
						<select name="filtroArea" id="filtroArea" class="form-control">
							<option value="-1">Mostrar todas</option>
							<?php foreach($areas as $area){ ?>
								<option value="<?php echo $area->id; ?>">
									<?php echo $area->nombre; ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group filtroConsultorSection">
						<label for="filtroConsultor">Consultor</label>
						<select name="filtroConsultor" id="filtroConsultor" class="form-control">
							<option value="-1">Mostrar todos</option>
						</select>
					</div>

					<div class="form-group">
						<input type="submit" value="Consultar" class="form-control btn btn-primary">
					</div>
				</form>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-striped" id="main-table">
					<thead>
						<th>Consultor</th>
						<th>Área</th>
						<th>Cliente</th>
						<th>Proyecto</th>
						<th>Fase</th>
						<th>Tiempo estimado</th>
						<th>Tiempo real</th>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-striped" id="secondary-table">
					<thead>
						<th>Consultor</th>
						<th>Fase</th>
						<th>Tareas totales</th>
						<th>Tiempo real total</th>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<button id="exportar-XLS" class="btn btn-success form-control">Exportar en formato XLS</button>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<button id="btn-retrieve-global-detail" class="btn btn-warning form-control" style="display: none;">Consultar detalle global</button>
			</div>
		</div>

		<section style="display: none;" id="row-global-data">
			<div class="row" style="margin-top: 15px;">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<table class="table" id="expected-table">
						<thead>
							<th>Sector semanal</th>
							<th>Días</th>
							<th>Consultores</th>
							<th>Total de horas</th>
						</thead>
						<tbody>
							<tr id="row-lunes-jueves">
								<td>Lunes a Jueves</td>
								<td id="dias-expected-table"></td>
								<td id="consultores-expected-table"></td>
								<td id="total-expected-table"></td>
							</tr>
							<tr id="row-viernes">
								<td>Viernes</td>
								<td id="dias-expected-table"></td>
								<td id="consultores-expected-table"></td>
								<td id="total-expected-table"></td>
							</tr>
							<tr id="row-totales">
								<td>Total</td>
								<td id="dias-expected-table"></td>
								<td id="consultores-expected-table"></td>
								<td id="total-expected-table"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<table class="table" id="consultor-times-table">
						<thead>
							<th>Consultor</th>
							<th>Total (horas)</th>
							<th>Total a cubrir (horas)</th>
							<th>Diferencia (horas)</th>
						</thead>
						<tbody>
						</tbody>
					</table>					
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					
				</div>
			</div>
		</section>
	</div>
</body>
</html>

