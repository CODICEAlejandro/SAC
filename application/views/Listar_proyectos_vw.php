<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>	
</head>
<body>
	<?=$menu ?>
	
	<div class="container">
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