<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
<head>
	<?php includeMetaInformation(); ?>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<?php includeAuxiliars(); ?>
	<script type="text/javascript">
	$(function(){
		$("#curDate").html(parseDate());
		$("#curTime").html(parseTime());
	});		
	</script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'includes/css/AStyles.css'; ?>">
</head>
<body id="body_ctx">
	<div class="row" id="header_ctx">
		<img src="<?php echo base_url().'img/logo_codice.png'; ?>" class="ACenter" style="margin-top: 5px;">
	</div>


	<div class="container">
		<div class="row">
			<div class="col-xs-0 col-sm-3 col-md-3 col-lg-3"></div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 section_ctx" 
				style="margin: auto; ">
				<h3>Login</h3>
				<form
					method="POST"
					action="<?php echo base_url().'index.php/Login_ctrl'; ?>"
					name="form_login"
					id="form_login"
				>
					<div class="form-group">
						<select name="user" id="mail" class="form-control">
							<option value="NoOption">Selecciona una opción</option>
							<?php foreach($correos as $correo){ ?>
								<option><?php echo $correo->correo; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<input type="password" name="password" id="password" placeholder="Contraseña" class="form-control">
					</div>
					<div class="form-group">
						<input type="submit" value="Ingresar" class="btn btn-primary">
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row" id="footer_ctx">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="font-size: 20px;">
			Hoy es:
			<div id="curDate" style="font-size: 35px;"></div>
		</div>
		<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
		</div>
		<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="font-size: 20px;">
			La hora actual:
			<div id="curTime" style="font-size: 40px;"></div>
		</div>
	</div>

</body>
</html>