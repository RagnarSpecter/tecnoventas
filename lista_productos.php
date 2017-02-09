<?php
require_once( "class.php" );
if ( !isset($_SESSION['usuario']) ){
	echo "<script>
			alert('Usted debe estar logueado para ingresar al sistema');
			location.href = 'index.php';
		  </script>";
}
else{
	$t = new Trabajo;
	$retorno = $t->lista_productos();
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Listado de Productos</title>
		<link href='css/imagenes/favi.png' rel='shortcut icon' type='image/png'>
		
		<!-- CSS -->
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/lista_productos.css">
		<link rel="stylesheet" type="text/css" media="all" href="jQuery/sexyalertbox.css"/>

		<!-- Javascript -->
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jQuery/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="jQuery/sexyalertbox.v1.2.jquery.js"></script>
		<script type="text/javascript" src="funciones/lista_productos.js"></script>
		<script type="text/javascript" src="funciones/jquery.highlight.js"></script>
	</head>

	<body class="main_background">
		
		<!-- LLAMADO DEL CONTENEDOR DE LA CABECERA Y EL PANEL DE NAVEGACION -->
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
		<!-- CONTENEDOR DEL CUERPO DE LA PAGINA -->
		<div id="cuerpo">

			<div id="contenedor_opciones">
				<span class="titulo">Familia: </span>
				<?php  
					$lista_familia = $t->listar_familia();
					echo $lista_familia;
				?>

				<br>


				<span class="titulo">Categoria: </span>
				<span id="contenedor_select_categoria">
					<select name="categoria">
						<option value=""> -Elegir una Categoria- </option>
					</select>
				</span>
				
				<br>

				<span class="titulo">Buscar: </span><input type="textbox" name="buscar" id='buscar'>
				<br>

				<input type="checkbox" id="ver_costo"><span> Extender</span>
			</div>
			

			<div id="contenedor_lista_productos">
				<?php echo $retorno; ?>
				<!-- <?php echo '<pre>', print_r($retorno), '</pre>'; ?> -->
			</div>

			<div id="contenedor_cargando">
				<img src="imagenes/cargando.gif" alt="cargando" >
			</div>
		</div>

		<!-- FONDO TRANSPARENTE DEL POPUP -->
		<div class="popup-overlay"></div>

		<!-- CONTENEDOR DEL POPUP -->
		<div id="popup" style="display:none;">
		</div>
	</body>
</html>	