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

		.progress {
			border-radius: 0px !important;
			height: 23px !important;
			background: white !important;
			background-color: white !important;
		}
	</style>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row" style="background: #FFAE3C;">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
		<!-- ################################## PORCENTAJES DE AVANCE GENERALES ######################################### -->

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3 style="text-align: center;">Porcentajes de avance generales</h3>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta roja</h5>
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
				<h5>Meta verde</h5>
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
				<h5>Meta superverde</h5>
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

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px;">
				<p style="text-align: center;">
					<strong>Total facturado: </strong> 
				</p>
				<h1 style="text-align: center; font-size: 50px; margin-top: 20px;">
					$ <?php echo $resultados["porcentajes_generales"]["tf"]; ?>
				</h1>
			</div>

		</div>

		<!-- ################################## PORCENTAJES DE AVANCE ######################################### -->
			</div>
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">

		<div class="row" style="background: #eee;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h4 style="text-align: center;">Porcentajes de avance por tipo de cliente</h4>
			</div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h4>Clientes actuales</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta roja</h5>
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
				<h5>Meta verde</h5>
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
				<h5>Meta superverde</h5>
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

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p style="text-align: center;">
					<strong>Monto facturado: </strong> 
				</p>
				<h3 style="text-align: center;">
					$ <?php echo $resultados["porcentajes_por_tipo"]["sa"]; ?>
				</h3>
			</div>


				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h4>Clientes nuevos</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta roja</h5>
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
				<h5>Meta verde</h5>
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
				<h5>Meta superverde</h5>
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

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p style="text-align: center;">
					<strong>Monto facturado: </strong>
				</p>
				<h3  style="text-align: center;">
					$ <?php echo $resultados["porcentajes_por_tipo"]["sn"]; ?>
				</h3>
			</div>
		</div>
		
				</div>
			</div>
		
		<!-- ################################## AVANCE DE FACTURACIÓN ######################################### -->


		<div class="row" style="background: #FFD78A; color: black;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h4 style="text-align: center;">Avance de facturación por tipo de cliente y proyecto</h4>
			</div>

			<!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h3>Clientes actuales</h3>
			</div>-->			
 			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Proyectos nuevos</h5>
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
				<h5>Proyectos ya contemplados</h5>
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
		</div>
	</div>
</body>