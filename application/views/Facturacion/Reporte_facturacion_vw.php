<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Facturacion/Control_facturacion_JS.js"></script>

	<style type="text/css">
		.progress-bar-supergreen {
			background: #2D6 !important;
		}

		.p-right {
			text-align: right;
		}

		.p-center {
			text-align: center;
		}
	</style>
</head>
<body>
	<?=$menu ?>

	<div class="container">

		<!-- ################################## PORCENTAJES DE AVANCE GENERALES ######################################### -->

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Porcentajes de avance generales</h3>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p>
					<strong>Total facturado: </strong> <?php echo $resultados["porcentajes_generales"]["tf"]; ?>
				</p>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta roja</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-danger" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_generales']['par']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_generales']['par'] > 100)? 100: $resultados['porcentajes_generales']['par']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_generales']['par']; ?>%
				  	</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta verde</h4>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-success" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_generales']['pav']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_generales']['pav'] > 100)? 100: $resultados['porcentajes_generales']['pav']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_generales']['pav']; ?>%
				  	</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta superverde</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-supergreen" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_generales']['pasv']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_generales']['pasv'] > 100)? 100: $resultados['porcentajes_generales']['pasv']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_generales']['pasv']; ?>%
				  	</div>
				</div>
			</div>
		</div>

		<!-- ################################## PORCENTAJES DE AVANCE ######################################### -->


		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Porcentajes de avance por tipo de cliente</h3>
			</div>


			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h3>Clientes actuales</h3>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p>
					<strong>Monto facturado: </strong> <?php echo $resultados["porcentajes_por_tipo"]["sa"]; ?>
				</p>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta roja</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-danger" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['par_t1']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['par_t1'] > 100)? 100: $resultados['porcentajes_por_tipo']['par_t1']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_por_tipo']['par_t1']; ?>%
				  	</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta verde</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-success" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pav_t1']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pav_t1'] > 100)? 100: $resultados['porcentajes_por_tipo']['pav_t1']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_por_tipo']['pav_t1']; ?>%
				  	</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta superverde</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-supergreen" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pasv_t1']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pasv_t1'] > 100)? 100: $resultados['porcentajes_por_tipo']['pasv_t1']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_por_tipo']['pasv_t1']; ?>%
				  	</div>
				</div>
			</div>


			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h3>Clientes nuevos</h3>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p>
					<strong>Monto facturado: </strong> <?php echo $resultados["porcentajes_por_tipo"]["sn"]; ?>
				</p>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta roja</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-danger" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['par_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['par_t2'] > 100)? 100: $resultados['porcentajes_por_tipo']['par_t2']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_por_tipo']['par_t2']; ?>%
				  	</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta verde</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-success" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pav_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pav_t2'] > 100)? 100: $resultados['porcentajes_por_tipo']['pav_t2']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_por_tipo']['pav_t2']; ?>%
				  	</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Meta superverde</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-supergreen" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pasv_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pasv_t2'] > 100)? 100: $resultados['porcentajes_por_tipo']['pasv_t2']; ?>%"
					>
				    	<?php echo $resultados['porcentajes_por_tipo']['pasv_t2']; ?>%
				  	</div>
				</div>
			</div>
		</div>

		
		<!-- ################################## AVANCE DE FACTURACIÓN ######################################### -->


		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Avance de facturación por tipo de cliente y proyecto</h3>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h3>Clientes actuales</h3>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Proyectos nuevos</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-danger" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['avance_de_facturacion']['afn_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['avance_de_facturacion']['afn_t2'] > 100)? 100: $resultados['avance_de_facturacion']['afn_t2']; ?>%"
					>
				    	<?php echo $resultados['avance_de_facturacion']['afn_t2']; ?>%
				  	</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h4>Proyectos ya contemplados</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar progress-bar-success" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['avance_de_facturacion']['afa_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['avance_de_facturacion']['afa_t2'] > 100)? 100: $resultados['avance_de_facturacion']['afa_t2']; ?>%"
					>
				    	<?php echo $resultados['avance_de_facturacion']['afa_t2']; ?>%
				  	</div>
				</div>
			</div>
		</div>
	</div>
</body>