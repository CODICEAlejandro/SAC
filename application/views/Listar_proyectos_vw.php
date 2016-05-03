<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>SAC</title>
</head>
<body>
	<?=$menu ?>
	
	<table border="1">
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
</body>
</html>