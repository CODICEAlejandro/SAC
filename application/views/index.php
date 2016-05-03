<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
<head>
	<title>SAC</title>
</head>
<body>
	<h3>Login</h3>
	<form
		method="POST"
		action="<?php echo base_url().'index.php/Login_ctrl'; ?>"
		name="form_login"
		id="form_login"
	>
		<input type="mail" name="user" id="mail" placeholder="Correo electrónico">
		<input type="password" name="password" id="password" placeholder="Contraseña">
		<input type="submit" value="Ingresar">
	</form>
</body>
</html>