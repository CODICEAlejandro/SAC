<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>	
</head>
<body>
	<h1><?php echo $totalPendientes; ?></h1>
	<h1><?php echo $totalTerminadas; ?></h1>
	<h1><?php echo $totalCalificadas; ?></h1>
	<h1><?php echo $totalTareas; ?></h1>
	<br>
	<h1><?php echo $totalErrores; ?></h1>
	<br>
	<h1><?php echo $tiempoTotalReal; ?></h1>
	<h1><?php echo $tiempoTotalEstimado; ?></h1>
</body>
</html>