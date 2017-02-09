<?php
require_once( "class.php" );
if (!isset($_SESSION['usuario']))
{
	echo "<script>
			alert('Usted debe estar logueado para ingresar al sistema');
			location.href = 'index.php';
		  </script>";
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Administrador</title>
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/inicio.css">
		<link href='css/imagenes/favi.png' rel='shortcut icon' type='image/png'>
		
		<!-- CSS -->
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" type="text/css" media="all" href="jQuery/sexyalertbox.css"/>

		<!-- Javascript -->
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jQuery/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="jQuery/sexyalertbox.v1.2.jquery.js"></script>
		<script type="text/javascript" src="funciones.js"></script>
	</head>

	<body class="main_background">
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
	</body>
</html>	