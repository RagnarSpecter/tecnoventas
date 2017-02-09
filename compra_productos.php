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
			<link rel="stylesheet" type="text/css" media="all" href="jQuery/ui/jquery-ui.css"/>
		
			<!-- Soporte para los efectos hover -->
			<link href="css/hover.css" rel="stylesheet" media="all">

		<!-- Javascript -->
			<!-- Motor de Jquery -->
			<script type="text/javascript" src="jquery.js"></script>

			<!-- Soporte para Jquery UI -->
			<script type="text/javascript" src="jQuery/ui/jquery-ui.js"></script>	

			<!-- Funciones de la pagina -->
			<script type="text/javascript" src="funciones/compra_productos.js"></script>

			<!-- Funciones para cargar un numero con separador de miles -->
			<script>
				function format(input){
			
					var num = input.value.replace(/\./g,'');
					
					if(!isNaN(num)){
						num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
						num = num.split('').reverse().join('').replace(/^[\.]/,'');
						input.value = num;
					}else{ 
						alert('Solo se permiten numeros');
						input.value = input.value.replace(/[^\d\.]*/g,'');
					}
				}
			</script>
	</head>

	<body class="main_background">
		
		<!-- BLOQUE DONDE SE CARGAN LA CABECERA Y EL MENU DE NAVEGACIO -->
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

			<br>

			<!-- BLOQUE QUE SE MUESTRA SI SE SELECCIONA COMPRAR INSUMOS -->
			<div id="contenedor_detalles_compra_insumos"></div>
			
			<!-- BLOQUE QUE SE MUESTRA SI SE SELECCIONA COMPRAR PRODUCTOS -->
			<div id="contenedor_detalles_compra_productos">

				<div id="contenedor_selects_familia_categoria">
					<!-- BLOQUE PARA SELECCIONAR UNA CATEGORIA -->
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
							<option value=""> -Seleccione una Categoria- </option>
						</select>
					</span>	

					<!-- BLOQUE PARA SELECCIONAR UNA MARCA -->
					<span>Seleccione una marca: </span>
					<span id="contenedor_select_marca">
						<select name="marca">
							<option value=""> -Seleccione una Marca- </option>
						</select>
					</span>
				</div>
				
				<div id="contenedor_productos_categoria_otros">
				</div>

				<div id="contenedor_productos_categoria_plataforma">
				</div>
			</div>

			<!-- BLOQUE DONDE SE CARGA LA LISTA DE PRODUCTOS, DEPENDIENDO DE LOS FILTROS SELECCIONADOS -->
			<div id="contenedor_lista_productos"></div>

			<div id="contenedor_cargando">
				<img src="imagenes/cargando.gif" alt="cargando" >
			</div>

			<div id="alerta"></div>
		</div>
	</body>
</html>	