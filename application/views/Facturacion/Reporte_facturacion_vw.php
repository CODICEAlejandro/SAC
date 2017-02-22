<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="es">
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

		.progress-bar-red {
			background: #C50000 !important;
		}

		.progress-bar-green {
			background: #2CA500 !important;
		}

		.p-right {
			text-align: right;
		}

		.p-right-bottom {
			text-align: right;
			margin-top: -19px !important;
		}

		.p-center {
			text-align: center;
		}

		.progress {
			border-radius: 0px !important;
			height: 23px !important;
			background: white !important;
			background: black !important;		
		}

		#section-1 {	
			background: rgba(255,218,145,1);
			background: -moz-linear-gradient(top, rgba(255,218,145,1) 0%, rgba(255,146,10,1) 100%);
			background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,218,145,1)), color-stop(100%, rgba(255,146,10,1)));
			background: -webkit-linear-gradient(top, rgba(255,218,145,1) 0%, rgba(255,146,10,1) 100%);
			background: -o-linear-gradient(top, rgba(255,218,145,1) 0%, rgba(255,146,10,1) 100%);
			background: -ms-linear-gradient(top, rgba(255,218,145,1) 0%, rgba(255,146,10,1) 100%);
			background: linear-gradient(to bottom, rgba(255,218,145,1) 0%, rgba(255,146,10,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffda91', endColorstr='#ff920a', GradientType=0 );
		}

		#section-2 {

			background: rgba(255,255,255,1);
			background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(148,148,148,1) 100%);
			background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,1)), color-stop(47%, rgba(246,246,246,1)), color-stop(100%, rgba(148,148,148,1)));
			background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(148,148,148,1) 100%);
			background: -o-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(148,148,148,1) 100%);
			background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(148,148,148,1) 100%);
			background: linear-gradient(to bottom, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(148,148,148,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#949494', GradientType=0 );

		}
	</style>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row" id="section-1">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
		<!-- ################################## PORCENTAJES DE AVANCE GENERALES ######################################### -->

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3 style="text-align: center;">Porcentajes de avance generales</h3>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta roja ($<?php echo number_format($resultados['metas_totales']['suma_roja'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_generales']['avance_rojo']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_generales']['par']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_generales']['par'] > 100)? 100: $resultados['porcentajes_generales']['par']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_generales']['par'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_generales"]["avance_rojo"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta verde ($<?php echo number_format($resultados['metas_totales']['suma_verde'],2); ?>)</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_generales']['avance_verde']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_generales']['pav']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_generales']['pav'] > 100)? 100: $resultados['porcentajes_generales']['pav']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_generales']['pav'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_generales"]["avance_verde"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta superverde ($<?php echo number_format($resultados['metas_totales']['suma_superverde'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_generales']['avance_superverde']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_generales']['pasv']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_generales']['pasv'] > 100)? 100: $resultados['porcentajes_generales']['pasv']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_generales']['pasv'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_generales"]["avance_superverde"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px;">
				<p style="text-align: center;">
					<strong>Total facturado: </strong> 
				</p>
				<h1 style="text-align: center; font-size: 50px; margin-top: 20px;">
					$ <?php echo number_format($resultados["porcentajes_generales"]["tf"],2); ?>
				</h1>
			</div>

		</div>

		<!-- ################################## PORCENTAJES DE AVANCE ######################################### -->
			</div>
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">

		<div class="row" id="section-2">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h4 style="text-align: center;">Porcentajes de avance por tipo de cliente</h4>
			</div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h4>Clientes actuales</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta roja ($<?php echo number_format($resultados['porcentajes_por_tipo']['meta_actual_roja'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_por_tipo']['avance_rojo_actual']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['par_t1']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['par_t1'] > 100)? 100: $resultados['porcentajes_por_tipo']['par_t1']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_por_tipo']['par_t1'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_por_tipo"]["avance_rojo_actual"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta verde ($<?php echo number_format($resultados['porcentajes_por_tipo']['meta_actual_verde'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_por_tipo']['avance_verde_actual']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pav_t1']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pav_t1'] > 100)? 100: $resultados['porcentajes_por_tipo']['pav_t1']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_por_tipo']['pav_t1'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_por_tipo"]["avance_verde_actual"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta superverde ($<?php echo number_format($resultados['porcentajes_por_tipo']['meta_actual_superverde'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_por_tipo']['avance_superverde_actual']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pasv_t1']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pasv_t1'] > 100)? 100: $resultados['porcentajes_por_tipo']['pasv_t1']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_por_tipo']['pasv_t1'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_por_tipo"]["avance_superverde_actual"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p style="text-align: center;">
					<strong>Monto facturado: </strong> 
				</p>
				<h3 style="text-align: center;">
					$ <?php echo number_format($resultados["porcentajes_por_tipo"]["sa"],2); ?>
				</h3>
			</div>


				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-center">
				<h4>Clientes nuevos</h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta roja ($<?php echo number_format($resultados['porcentajes_por_tipo']['meta_nueva_roja'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_por_tipo']['avance_rojo_nuevo']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['par_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['par_t2'] > 100)? 100: $resultados['porcentajes_por_tipo']['par_t2']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_por_tipo']['par_t2'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_por_tipo"]["avance_rojo_nuevo"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta verde ($<?php echo number_format($resultados['porcentajes_por_tipo']['meta_nueva_verde'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_por_tipo']['avance_verde_nuevo']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pav_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pav_t2'] > 100)? 100: $resultados['porcentajes_por_tipo']['pav_t2']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_por_tipo']['pav_t2'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_por_tipo"]["avance_verde_nuevo"]["diferencia"]; ?>% abajo</h5>
			</div>


			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Meta superverde ($<?php echo number_format($resultados['porcentajes_por_tipo']['meta_nueva_superverde'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['porcentajes_por_tipo']['avance_superverde_nuevo']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['porcentajes_por_tipo']['pasv_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['porcentajes_por_tipo']['pasv_t2'] > 100)? 100: $resultados['porcentajes_por_tipo']['pasv_t2']; ?>%"
					>
				    	<?php echo number_format($resultados['porcentajes_por_tipo']['pasv_t2'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["porcentajes_por_tipo"]["avance_superverde_nuevo"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p style="text-align: center;">
					<strong>Monto facturado: </strong>
				</p>
				<h3  style="text-align: center;">
					$ <?php echo number_format($resultados["porcentajes_por_tipo"]["sn"],2); ?>
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
				<h5>Proyectos nuevos ($<?php echo number_format($resultados['avance_de_facturacion']['pn'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['avance_de_facturacion']['avance_nuevo_t2']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['avance_de_facturacion']['afn_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['avance_de_facturacion']['afn_t2'] > 100)? 100: $resultados['avance_de_facturacion']['afn_t2']; ?>%"
					>
				    	<?php echo number_format($resultados['avance_de_facturacion']['afn_t2'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["avance_de_facturacion"]["avance_nuevo_t2"]["diferencia"]; ?>% abajo</h5>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right">
				<h5>Proyectos ya contemplados ($<?php echo number_format($resultados['avance_de_facturacion']['pn'],2); ?>)</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="progress">
					<div 
						class="progress-bar <?php echo $resultados['avance_de_facturacion']['avance_actual_t2']['color']; ?>" 
						role="progressbar" 
						aria-valuenow="<?php echo $resultados['avance_de_facturacion']['afa_t2']; ?>" 
						aria-valuemin="0" 
						aria-valuemax="100" 
						style="width: <?php echo ($resultados['avance_de_facturacion']['afa_t2'] > 100)? 100: $resultados['avance_de_facturacion']['afa_t2']; ?>%"
					>
				    	<?php echo number_format($resultados['avance_de_facturacion']['afa_t2'],2); ?>%
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-right-bottom">
				<h5><?php echo $resultados["avance_de_facturacion"]["avance_actual_t2"]["diferencia"]; ?>% abajo</h5>
			</div>
		</div>

			</div>
		</div>
	</div>
</body>