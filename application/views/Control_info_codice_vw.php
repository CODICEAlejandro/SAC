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

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/InfoCodice_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<title>JOBS</title>
</head>
<body>
	<?=$menu; ?>

	<div class="container">
		<div class="row" style="border-bottom: 2px solid #AAA; padding-bottom: 10px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					id="create-form"
					class="form"
				>
					<div class="form-group">
						<label for="razonSocial">Razón social</label>
						<input type="text" name="razonSocial" id="razonSocial" placeholder="Razón social" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="rfc">RFC</label>
						<input type="text" name="rfc" id="rfc" placeholder="RFC" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="domicilioFiscal">Domicilio fiscal</label>
						<input type="text" name="domicilioFiscal" id="domicilioFiscal" placeholder="Domicilio fiscal" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="regimenFiscal">Régimen fiscal</label>
						<input type="text" name="regimenFiscal" id="regimenFiscal" placeholder="Régimen fiscal" class="form-control" required>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary form-control" value="Crear">
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="container" id="appendSection">
	</div>

	<div class="row" id="toCloneSection" style="border-bottom: 2px solid #AAA; padding-bottom: 10px; display:none; margin-top: 15px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<form
				class="form"
			>
				<div class="form-group">
					<label for="razonSocial">Razón social</label>
					<input type="text" name="razonSocial" id="razonSocial" placeholder="Razón social" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="rfc">RFC</label>
					<input type="text" name="rfc" id="rfc" placeholder="RFC" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="domicilioFiscal">Domicilio fiscal</label>
					<input type="text" name="domicilioFiscal" id="domicilioFiscal" placeholder="Domicilio fiscal" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="regimenFiscal">Régimen fiscal</label>
					<input type="text" name="regimenFiscal" id="regimenFiscal" placeholder="Régimen fiscal" class="form-control" required>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-warning form-control" value="Actualizar">
				</div>
			</form>
		</div>
	</div>
</body>