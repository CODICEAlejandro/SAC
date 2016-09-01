<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<title>JOBS</title>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow: scroll; border: 1px solid black; margin-bottom: 20px;">

	<table id="main-tbl" class="table table-bordered" style="margin-top: 15px;">
		<thead>
			<th>Cliente</th>
			<th>Proyecto</th>
			<th>Mes</th>
			<th># de tareas</th>

			<th>Diseño (Co)</th>
			<th>Diseño (G)</th>
			<th>Interesados</th>

			<th>Programación (Co)</th>
			<th>Programación (G)</th>
			<th>Interesados</th>

			<th>Pruebas (Co)</th>
			<th>Pruebas (G)</th>
			<th>Interesados</th>

			<th>Management (Co)</th>
			<th>Management (G)</th>
			<th>Interesados</th>

			<th>Administración (Co)</th>
			<th>Administración (G)</th>
			<th>Interesados</th>

			<th>Analytics (Co)</th>
			<th>Analytics (G)</th>
			<th>Interesados</th>

			<th>HTML (Co)</th>
			<th>HTML (G)</th>
			<th>Interesados</th>

			<th>Dirección (Co)</th>
			<th>Dirección (G)</th>
			<th>Interesados</th>

			<th>Redes (Co)</th>
			<th>Redes (G)</th>
			<th>Interesados</th>

			<th>Creatividad (Co)</th>
			<th>Creatividad (G)</th>
			<th>Interesados</th>

			<th>Time Point (Co)</th>
			<th>Time Point (G)</th>
			<th>Interesados</th>

			<th>Soport (Co)</th>
			<th>Soporte (G)</th>
			<th>Interesados</th>

			<th>Santande (Co)</th>
			<th>Santander (G)</th>
			<th>Interesados</th>
		</thead>
		<tbody>
			<?php foreach($firstSection as $row){ ?>
			<tr>
				<td><?php echo $row->cliente ?></td>
				<td><?php echo $row->proyecto ?></td>
				<td><?php echo $row->mes ?></td>
				<td><?php echo $row->total_de_tareas ?></td>

				<td><?php echo $row->consultor_disenio; ?></td>
				<td><?php echo $row->gerente_disenio; ?></td>
				<td><?php echo $row->interesados_disenio; ?></td>

				<td><?php echo $row->consultor_programacion; ?></td>
				<td><?php echo $row->gerente_programacion; ?></td>
				<td><?php echo $row->interesados_programacion; ?></td>

				<td><?php echo $row->consultor_pruebas; ?></td>
				<td><?php echo $row->gerente_pruebas; ?></td>
				<td><?php echo $row->interesados_pruebas; ?></td>

				<td><?php echo $row->consultor_management; ?></td>
				<td><?php echo $row->gerente_management; ?></td>
				<td><?php echo $row->interesados_management; ?></td>

				<td><?php echo $row->consultor_administracion; ?></td>
				<td><?php echo $row->gerente_administracion; ?></td>
				<td><?php echo $row->interesados_administracion; ?></td>

				<td><?php echo $row->consultor_analyticsadwords; ?></td>
				<td><?php echo $row->gerente_analyticsadwords; ?></td>
				<td><?php echo $row->interesados_analyticsadwords; ?></td>

				<td><?php echo $row->consultor_html; ?></td>
				<td><?php echo $row->gerente_html; ?></td>
				<td><?php echo $row->interesados_html; ?></td>

				<td><?php echo $row->consultor_direccion; ?></td>
				<td><?php echo $row->gerente_direccion; ?></td>
				<td><?php echo $row->interesados_direccion; ?></td>

				<td><?php echo $row->consultor_redes; ?></td>
				<td><?php echo $row->gerente_redes; ?></td>
				<td><?php echo $row->interesados_redes; ?></td>

				<td><?php echo $row->consultor_creatividad; ?></td>
				<td><?php echo $row->gerente_creatividad; ?></td>
				<td><?php echo $row->interesados_creatividad; ?></td>

				<td><?php echo $row->consultor_timepoint; ?></td>
				<td><?php echo $row->gerente_timepoint; ?></td>
				<td><?php echo $row->interesados_timepoint; ?></td>

				<td><?php echo $row->consultor_soporte; ?></td>
				<td><?php echo $row->gerente_soporte; ?></td>
				<td><?php echo $row->interesados_soporte; ?></td>

				<td><?php echo $row->consultor_santander; ?></td>
				<td><?php echo $row->gerente_santander; ?></td>
				<td><?php echo $row->interesados_santander; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

			</div>
		</div>
	</div>
</body>