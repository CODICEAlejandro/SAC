<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<html>
<head>
	<!--<title>JOBS</title>-->
	<?php //includeJQuery(); ?>
	<?php //includeBootstrap(); ?>

	<style type="text/css">
		body {
			padding-top: 70px;
		}
	</style>
</head>
<body>
	<?php $page = $this->uri->segment(1); ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">

				<nav class="navbar navbar-default navbar-fixed-top">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<span class="navbar-brand">Códice | JOBS</span>
						</div>

						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav navbar-left">
								<li <?php ($page=="Listar_proyectos_ctrl")? $text = "class = active" : $text = ""; echo $text;?>>
									<a href="<?php echo base_url().'index.php/Listar_proyectos_ctrl'; ?>">Proyectos</a>
								</li>
								<li <?php ($page=="Listar_tareas_ctrl")? $text = "class = active" : $text = ""; echo $text;?>>
									<a href="<?php echo base_url().'index.php/Listar_tareas_ctrl'; ?>">Tareas</a>
								</li>
								<!-- Actividades disponibles solo para el gerente -->
								<?php if($this->session->userdata('tipo') == 1){ ?>
								<li <?php ($page=="Listar_tareas_calificar_ctrl")? $text = "class = active" : $text = ""; echo $text;?>>
									<a href="<?php echo base_url().'index.php/Listar_tareas_calificar_ctrl/listarGerente'; ?>">Calificar</a>
								</li>
								<li <?php ($page=="Reportes_ctrl")? $text = "class = active" : $text = ""; echo $text;?>>
									<a href="<?php echo base_url().'index.php/Reportes_ctrl'; ?>">Reportes</a>
								</li>
								<?php }else if($this->session->userdata('tipo') == 2){ ?>
								<!-- Actividades disponibles solo para el administrador -->
								<li <?php ($page=="Listar_tareas_calificar_ctrl")? $text = "class = active" : $text = ""; echo $text;?>>
									<a href="<?php echo base_url().'index.php/Listar_tareas_calificar_ctrl'; ?>">Calificar</a>
								</li>
								<li <?php ($page=="Panel_control_ctrl")? $text = "class = active" : $text = ""; echo $text;?>>
									<a href="<?php echo base_url().'index.php/Panel_control_ctrl'; ?>">Panel de control</a>
								</li>
								<li <?php ($page=="Reportes_ctrl")? $text = "class = active" : $text = ""; echo $text;?>>
									<a href="<?php echo base_url().'index.php/Reportes_ctrl'; ?>">Reportes</a>
								</li>
								<?php } ?>
								<li>
									<a href="<?php echo base_url().'index.php/Logout_ctrl'; ?>">Salir</a>
								</li>							
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<p class="navbar-text">
									<a href="<?php echo base_url().'index.php/Agenda_telefonica_ctrl'; ?>"
										style="margin-right: 15px; color: black;">
										<span class="glyphicon glyphicon-book"></span>
									</a>
									<?php echo $this->session->userdata('nombre'); ?>				
								</p>
								<img class="navbar-brand" src="<?php echo base_url().'img/logoPerfil.png'; ?>">							
							</ul>
						</div>
					</div>
				</nav>
			</div>
		</div>
	</div>
</body>
</html>