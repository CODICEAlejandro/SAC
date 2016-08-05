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
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					id = "form-consulta-proyecto"
				>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<select name="proyecto" class="form-control">
									<?php foreach($proyectos as $proyecto){ ?>
										<option value="<?php echo $proyecto->id; ?>"><?php echo $proyecto->nombre; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<select name="proyecto" class="form-control">
									<?php foreach($proyectos as $proyecto){ ?>
										<option value="<?php echo $proyecto->id; ?>"><?php echo $proyecto->nombre; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<input type="submit" class="btn btn-primary form-control" value="Consultar proyecto">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>