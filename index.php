<?php
	require_once("class.php");
	$t = new Trabajo;
	$op = $t->login();
	echo $op;
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link href='css/imagenes/favi.png' rel='shortcut icon' type='image/png'>
		
		<!-- CSS -->
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/index.css">
		<link rel="stylesheet" href="css/animate.css">
		<link rel="stylesheet" type="text/css" media="all" href="jQuery/sexyalertbox.css"/>

		<!-- Javascript -->
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jQuery/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="jQuery/sexyalertbox.v1.2.jquery.js"></script>
		<script type="text/javascript" src="funciones/index.js"></script>
	</head>
	
	<body id="index">
		
		<!-- Logo FIUNA -->
		<div id="container_logo">
			<img src="imagenes/logo_redondo.png"/>
			<img src="imagenes/logo_tecnoventas.png"/>
		</div>

		<br><br>

		<!-- Container Main -->
		<div id="container_main" class="animated fadeInDown">

			<!-- Contenedor del Cuerpo -->
			<div id="container_body">
				<form name="form" id="login" action="index.php" method="post">
					<div id="cedula_password">
					
						<input type="text" id="cedula" name="cedula" id="cedula" placeholder="Usuario">

						<br><br>
						
						<input type="password" id="password" name="password" id="password" placeholder="Password">
						
						<br><br>

						<input type="submit" id="ingresar" value="INGRESAR" class="hvr-shutter-in-horizontal">
					</div>
				</form>				
			</div>

			<!-- Contenedor del Pie -->
			<div id="container_footer" align="center">
				<span>Karaja Society® 2016</span>	
			</div>
		</div>
		<input type="hidden" name="error" id="error" value="<?php echo @$op; ?>">
	</body>
</html>