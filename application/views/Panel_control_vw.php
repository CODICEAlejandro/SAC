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
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<!-- Control de Usuarios -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Control de usuarios</h3>				
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="list-group">
					<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Alta_usuario_ctrl" style="text-align: left;">Agregar</a>
					<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Edit_usuario_ctrl" style="text-align: left;">Editar</a>
				</div>
			</div>
		</div>

		<!-- Control de Clientes -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Control de clientes</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Alta_cliente_ctrl" style="text-align: left;">Ir a administrador</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Control_info_codice_ctrl" style="text-align: left;">Información de facturación</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Alta_conceptos_cotizacion_ctrl" style="text-align: left;">Junta de arranque</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Control_cotizacion_ctrl" style="text-align: left;">Seguimiento de cobranza</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Facturacion/Consulta_conceptos_facturacion_ctrl" style="text-align: left;">Seguimiento de facturación</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Lectura_factura_ctrl" style="text-align: left;">Subir factura</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Cobranza_ctrl" style="text-align: left;">Cobranza</a>
			</div>
		</div>

		<!-- Control de proveedores -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Control de proveedores</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Control_proveedor_ctrl" style="text-align: left;">Ir a administrador</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Proveedor/Alta_conceptos_cotizacion_ctrl" style="text-align: left;">Alta de conceptos</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Lectura_factura_proveedor_ctrl" style="text-align: left;">Subir factura</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Proveedor/Por_pagar_ctrl" style="text-align: left;">Cuentas por pagar</a>
			</div>
		</div>

		<!-- Facturación -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Facturación</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Facturacion/Captura_facturacion_ctrl" style="text-align: left;">Captura de facturación</a>
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Facturacion/Categorizacion_facturacion_ctrl" style="text-align: left;">Categorización de conceptos</a>
			</div>
		</div>


		<!-- Control de Proyectos -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h3>Control de proyectos</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a class="btn list-group-item" href="<?php echo base_url(); ?>index.php/Nuevo_proyecto_ctrl" style="text-align: left;">Ir a administrador</a>
			</div>
		</div>

	</div>
</body>
</html>