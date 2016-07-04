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
			var cDate = new Date();
			var cDay = (cDate.getUTCDate().toString().length < 2)? "0"+cDate.getUTCDate(): cDate.getUTCDate();
			var cMonth = (cDate.getUTCMonth().toString().length < 2)? "0"+(cDate.getUTCMonth()+1): cDate.getUTCMonth()+1;
			var cYear = cDate.getUTCFullYear();

			$("#dateDesde").val(cDay+'/'+cMonth+'/'+cYear);
			$("#dateHasta").val(cDay+'/'+cMonth+'/'+cYear);


			$("#dateDesde, #dateHasta").change(function(){
				var dateDesde = $("#dateDesde").val().split('/');
				var dateHasta = $("#dateHasta").val().split('/');
				var objDateDesde = null;
				var objDateHasta = null;

				if( (dateDesde.length == 3) && (dateHasta.length == 3) ){
					objDateDesde = new Date(dateDesde[2], dateDesde[1], dateDesde[0]);
					objDateHasta = new Date(dateHasta[2], dateHasta[1], dateHasta[0]);

					if(objDateDesde > objDateHasta) $("#dateHasta").val($("#dateDesde").val());
				}
			});

			$("#form-dates").submit(function(event){
				event.preventDefault();
				$.ajax({
					url: '<?php echo base_url()."index.php/Reporte_acumulado_tiempo_ctrl/refreshTimes" ?>',
					method: 'POST',
					dataType: 'text',
					data: $('#form-dates').serialize(),
					success: function(response){
						$("#tblUsers tbody").html(response);
					}
				});
			});

			$("#form-dates").submit();
			$(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
		});
	</script>
</head>
<body>
	<? $menu; ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form class="form form-inline" id="form-dates" method="post">
					<div class="input-group">
						<div class="input-group-addon">Desde:</div>
						<input name="dateDesde" id="dateDesde" class="form-control datepicker" readonly="readonly"></input>
					</div>
					<div class="input-group">
						<div class="input-group-addon">Hasta:</div>
						<input name="dateHasta" id="dateHasta" class="form-control datepicker" readonly="readonly"></input>
					</div>
					<div class="form-group">
						<input type="submit" name="recalcular" class="btn btn-success" value="Recalcular ">
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-striped" id="tblUsers">
					<thead>
						<tr>
							<th>
								Consultor
							</th>
							<th>
								Tiempo real
							</th>
							<th>
								Tiempo estimado
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($users as $user){ ?>
						<tr>
							<td class="colName"><?php echo $user->nombre; ?></td>		
							<td class="colRealTime"><?php echo $user->sumaTiempoReal; ?></td>
							<td class="colEstimatedTime"><?php echo $user->sumaTiempoEstimado; ?></td>	
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>