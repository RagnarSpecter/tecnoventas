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
	// $retorno = $t->lista_productos();
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Compras</title>
		<link href='css/imagenes/favi.png' rel='shortcut icon' type='image/png'>
		
		<!-- CSS -->
			<!-- Estilo General del Sistema -->
		<link rel="stylesheet" href="css/general.css">
			<!-- Estilo de la pagina -->
		<link rel="stylesheet" href="css/compra_productos.css">
			<!-- Soporte para Alerta -->
		<link rel="stylesheet" type="text/css" media="all" href="jQuery/sexyalertbox.css"/>
		<link rel="stylesheet" type="text/css" media="all" href="jQuery/ui/jquery-ui.css"/>
			<!-- Soporte para los efectos hover -->
		<link href="css/hover.css" rel="stylesheet" media="all">

		<!-- Javascript -->
		<script type="text/javascript" src="jquery.js"></script>
			<!-- Soporte para Jquery UI -->
		<script type="text/javascript" src="jQuery/ui/jquery-ui.js"></script>	
			<!-- Soporte para Alerta -->
		<script type="text/javascript" src="jQuery/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="jQuery/sexyalertbox.v1.2.jquery.js"></script>

			<!-- Funciones de la pagina -->
		<script type="text/javascript" src="funciones/compra_productos.js"></script>
	</head>

	<body class="main_background">
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
		<div id="cuerpo">
			<div id="contenedor_opciones_compra">
				<h2>Elegir una opcion de compra</h2>
				<br>
				<span class="hvr-grow" id="btn_compra_insumos">Insumos Basicos</span>
				<span class="hvr-grow" id="btn_compra_productos">Productos</span>
			</div>
			<div id="contenedor_detalles_compra">

				<!-- Bloque para seleccionar una categoria o agregar una nueva -->
				<span>Seleccione una categoria: </span>
				<?php  
					$lista_categoria = $t->listar_categoria();
					echo $lista_categoria;
				?>
				<span id="nueva_categoria">
					<span>o cargar nueva categoria: </span>
					<input type="text" name="nueva_categoria">
				</span>
				
				<br>
				
				<!-- Bloque para seleccionar una marca o agregar una nueva -->
				<span>Seleccione una marca: </span>
				<?php  
					$lista_marca = $t->listar_marca();
					echo $lista_marca;
				?>
				<span id="nueva_marca">
					<span>o cargar nueva marca: </span>
					<input type="text" name="nueva_marca">
				</span>

				<br>

				<!-- Bloque para seleccionar un modelo o agregar uno nuevo -->
				<span>Seleccione una modelo: </span>
				<?php  
					// $lista_modelo = $t->listar_modelo();
					// echo $lista_modelo;
				?>
				<span id="nuevo_modelo">
					<span>o cargar nuevo modelo: </span>
					<input type="text" name="nueva_modelo">
				</span>

				<br>

				<!-- Bloque para seleccionar un color o agregar uno nuevo -->
				<span>Seleccione un color: </span>
				<?php  
					// $lista_color = $t->listar_color();
					// echo $lista_color;
				?>
				<span id="nuevo_color">
					<span>o cargar nuevo color: </span>
					<input type="text" name="nueva_color">
				</span>

				<br>

				<!-- Bloque para seleccionar una plataforma o agregar una nueva -->
				<span>Seleccione una plataforma: </span>
				<?php  
					// $lista_plataforma = $t->listar_plataforma();
					// echo $lista_plataforma;
				?>
				<span id="nueva_plataforma">
					<span>o cargar nueva plataforma: </span>
					<input type="text" name="nueva_plataforma">
				</span>

				<br>

				<!-- Bloque para cargar una descripcion del producto -->
				<span id="descripcion">
					<span>Ingrese una breve descripcion del producto: </span>
					<input type="text" name="descripcion">
				</span>

				<br>

				<span id="tipo_producto">
					<span>Definir si el producto </span>
					<input type="checkbox" name="tipo_producto" value="basico">Basico
					<input type="checkbox" name="tipo_producto" value="casual">Casual
				</span>

				<br>

				<!-- Bloque para ingresar el costo del producto -->
				<span id="costo">
					<span>Ingrese el costo del producto: </span>
					<input type="text" name="costo_producto">
				</span>
				<span>
					<span>Ingrese la cantidad minima de stock para el producto</span>
					<?php  
					// $cantidad_opciones = $t->lista_cantidad();
					// echo $cantidad_opciones;
					?>
					<span>Ingrese la cantidad de productos a comprar</span>
					<?php  
					// $cantidad_productos = $t->lista_cantidad();
					// echo $cantidad_productos;
					?>
				</span>

				<br>

				<!-- Bloque para definir el precio de venta final -->
				<span id="precio_venta">
					<span>El precio de venta sugerido es: </span>
					<input type="text" name="precio_venta_sugerido">
					<span> o ingrese un precio para la venta: </span>
					<input type="text" name="precio_venta_manual">
				</span>

				<br>

				<div id="contenedor_boton_comprar">
					<input type="button" id="comprar" value="Comprar" class="boton">
				</div>
			</div>
			<div id="dialog-confirm">
				
			</div>
			<div id="dialog-confirmed">
				
			</div>
		</div>
	</body>
</html>	