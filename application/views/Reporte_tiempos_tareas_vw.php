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
					url: '<?php echo base_url()."index.php/Reporte_tiempos_tareas_ctrl/refreshData" ?>',
					method: 'POST',
					dataType: 'text',
					data: $('#form-dates').serialize(),
					success: function(response){
						$("#mainDataContainer").html(response);
					}
				});
			});

			$("#form-dates").submit();
			$(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
		});
	</script>
	<style type="text/css">
		.APanelTitle {
			background-color: black;
			color: white;
			padding-left: 20px;
			padding-top: 1px;
			padding-bottom: 1px;
		}

		.APanel {
			border-bottom: 1px solid gray;			
		}

		.APanelBody {
			padding-top: 10px;
			padding: bottom: 10px;
			background: gray;
		}

		.APanelBody div.APanelRight, .APanelBody div.APanelLeft {
			padding-bottom: 10px;
			padding-top: 10px;
		}

		.APanelBody div.APanelLeft {
			background: #DDD;
			border-right: 3px solid orange;
		}

		.APanelBody div + div {
			border-right: solid 1px gray;
			border-bottom: 1px solid gray;
		}
	</style>	
</head>
<body>
	<?= $menu; ?>

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
	</div>

	<div class="container" id="mainDataContainer">
	<!-- Información proveniente de AJAX -->
	</div>
</body>
</html>