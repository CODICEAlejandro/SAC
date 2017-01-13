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
			$(".DiscoverRow").hide();

			$(".DiscoverRow").each(function(i){
				$(this).delay(i*50).fadeIn(200);
			});
		});			
	</script>

	<style>
		#alerta-informativa {
			padding: 15px;
			margin: 12px;

			background: rgba(252,234,187,1);
			background: -moz-linear-gradient(top, rgba(252,234,187,1) 0%, rgba(252,205,77,1) 50%, rgba(248,181,0,1) 51%, rgba(251,223,147,1) 100%);
			background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(252,234,187,1)), color-stop(50%, rgba(252,205,77,1)), color-stop(51%, rgba(248,181,0,1)), color-stop(100%, rgba(251,223,147,1)));
			background: -webkit-linear-gradient(top, rgba(252,234,187,1) 0%, rgba(252,205,77,1) 50%, rgba(248,181,0,1) 51%, rgba(251,223,147,1) 100%);
			background: -o-linear-gradient(top, rgba(252,234,187,1) 0%, rgba(252,205,77,1) 50%, rgba(248,181,0,1) 51%, rgba(251,223,147,1) 100%);
			background: -ms-linear-gradient(top, rgba(252,234,187,1) 0%, rgba(252,205,77,1) 50%, rgba(248,181,0,1) 51%, rgba(251,223,147,1) 100%);
			background: linear-gradient(to bottom, rgba(252,234,187,1) 0%, rgba(252,205,77,1) 50%, rgba(248,181,0,1) 51%, rgba(251,223,147,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fceabb', endColorstr='#fbdf93', GradientType=0 );
		}
	</style>			
</head>
<body>
	<?=$menu ?>
	
	<div class="container">
		<div class="row" id="alerta-informativa">
			<div class="col-xs-12 col-sm-12 col-md-12">
				En el apartado <span class="glyphicon glyphicon-book"></span> pueden consultar la agenda telefónica. Saludos.
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">

				<table border="1" class="table table-striped">
					<thead>
						<tr>
							<th>Proyecto</th>				
							<th>Cliente</th>				
						</tr>
					</thead>
					<tbody>
						<?php foreach ($clientes_proyectos as $cliente => $proyectos){ ?>
							<?php foreach ($proyectos as $cProyecto){ ?>
							<tr>
								<td>
									<a href="<?php echo base_url().'index.php/Alta_tarea_ctrl/load_vw/'.($cProyecto->id); ?>">
										<?php echo $cProyecto->nombre; ?>
									</a>
								</td>
								<td>
									<?php echo $cliente; ?>
								</td>
							</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</body>
</html>