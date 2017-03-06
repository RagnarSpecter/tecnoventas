<?php
// require_once( "../../class.php" );
require_once( "class_venta_productos.php" );

if ( !isset($_SESSION['usuario']) ){
	echo "<script>
			alert('Usted debe estar logueado para ingresar al sistema');
			location.href = 'index.php';
		  </script>";
}
else{
	$t = new Trabajo;
	// $retorno = $t->resumen_saldos();
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Ventas</title>
		<link href='../../css/imagenes/favi.png' rel='shortcut icon' type='image/png'>
		
		<!-- CSS -->
			<!-- Estilo General del Sistema -->
		<link rel="stylesheet" href="../../css/general.css">
			
			<!-- Estilo de la pagina -->
		<link rel="stylesheet" href="venta_productos.css">
			
			<!-- Soporte para los efectos hover -->
		<link href="../../css/hover.css" rel="stylesheet" media="all">

		<!-- Javascript -->
			<!-- jQuery -->
		<script type="text/javascript" src="../../jquery.js"></script>
			
			<!-- Funciones de la pagina -->
		<script type="text/javascript" src="venta_productos.js"></script>
	</head>

	<body class="main_background">
		<?php
			include '../../cabecera.php';
			include '../../navegacion_admin.php';
		?>
		<div id="cuerpo">

			<!-- CONTENEDOR TITULO DE PAGINA -->
			<div id="contenedor_cabecera_ventas">
				<h1 class="centrar">Modulo de Ventas</h1>
				<h2>Filtro de Productos</h2>
			</div>

			<!-- CONTENEDOR FILTRO DE PRODUCTOS -->
			<div id="contenedor_filtro_productos">

				<!-- BLOQUE PARA SELECCIONAR UNA FAMILIA -->
				<span>Seleccione una familia: </span>
				<span>
					<?php  
						$lista_familia = $t->listar_familia();
						echo $lista_familia;
					?>
				</span>


				<!-- BLOQUE PARA SELECCIONAR UNA CATEGORIA -->
				<span>Seleccione una categoria: </span>
				<span id="contenedor_select_categoria">
					<select name="categoria">
						<option value=""> -Elegir categoria- </option>
					</select>
				</span>

				<!-- BLOQUE PARA SELECCIONAR UNA MARCA -->
				<span>Seleccione una marca: </span>
				<span id="contenedor_select_marca">
					<select name="marca">
						<option value=""> -Elegir marca- </option>
					</select>
				</span>
								
				<br><br>

			</div>

			<!-- CONTENEDOR DONDE SE CARGA LA LISTA DE PRODUCTOS, DEPENDIENDO DE LOS FILTROS SELECCIONADOS -->
			<div id="contenedor_lista_productos"></div>

			<!-- CONTENEDOR ICONO CARGANDO -->
			<div id="contenedor_cargando">
				<img src="../../imagenes/cargando.gif" alt="cargando" >
			</div>

			<!-- CONTENEDOR CONTADOR CARRITO -->
			<div id="contenedor_contador_carrito">
				<div id="contador_carrito">
					0
				<div>	
			</div>			

			<!-- CONTENEDOR ALERTA -->
			<div id="alerta"></div>
		</div>
	</body>
</html>	