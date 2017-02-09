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
	$retorno = $t->resumen_saldos();
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Resumen General</title>
		<link href='css/imagenes/favi.png' rel='shortcut icon' type='image/png'>
		
		<!-- CSS -->
			<!-- Estilo General del Sistema -->
		<link rel="stylesheet" href="css/general.css">
			<!-- Estilo de la pagina -->
		<link rel="stylesheet" href="css/resumen_general.css">
			<!-- Soporte para los efectos hover -->
		<link href="css/hover.css" rel="stylesheet" media="all">

		<!-- Javascript -->
		<script type="text/javascript" src="jquery.js"></script>
			<!-- Funciones de la pagina -->
		<script type="text/javascript" src="funciones/resumen_general.js"></script>
	</head>

	<body class="main_background">
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
		<div id="cuerpo">
			<div id="renglon1">
				<div id="contenedor_saldo_cuentas">
					<table>
						<thead>
							<tr>
								<th colspan="10">Resumen de Saldos</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Ventas del Dia<span></span></td>
								<td><input type="text" name="ventas_dia"></td>
							</tr>
							<tr>
								<td><span>Dinero en Caja Fuerte</span></td>
								<td><input type="text" name="caja_fuerte" value="<?php echo number_format( $retorno["caja_fuerte"], 0, ' ', '.' ); ?>"></td>
							</tr>
							<tr>
								<td><span>Dinero en Cuenta Corriente Vision Banco</span></td>
								<td><input type="text" name="cta_cte" value="<?php echo number_format( $retorno["cta_cte"], 0, ' ', '.' ); ?>"></td>
							</tr>
							<tr>
								<td><span>Dinero en Caja Chica</span></td>
								<td><input type="text" name="caja_chica" value="<?php echo number_format( $retorno["caja_chica"], 0, ' ', '.' ); ?>"></td>
							</tr>
							<tr>
								<td><span>Linea de Credito de Tarjeta</span></td>
								<td><input type="text" name="tarjeta_linea"></td>
							</tr>
							<tr>
								<td><span>Disponible en Tarjeta de Credito</span></td>
								<td><input type="text" name="tarjeta_disponible" value="<?php echo number_format( $retorno["tarjeta_disponible"], 0, ' ', '.' ); ?>"></td>
							</tr>
							<tr>
								<td><span>Deuda en Tarjeta de Credito</span></td>
								<td><input type="text" name="tarjeta_deuda" value="<?php echo number_format( $retorno["tarjeta_deuda"], 0, ' ', '.' ); ?>"></td>
							</tr>
							<tr>
								<td><span>Capital en Productos</span></td>
								<td><input type="text"></td>
							</tr>
							<tr>
								<td><span>Total de Compras en el Mes</span></td>
								<td><input type="text"></td>
							</tr>
							<tr>
								<td><span>Total de Ventas en el Mes</span></td>
								<td><input type="text"></td>
							</tr>
							<tr>
								<td><span>Activo Total</span></td>
								<td><input type="text"></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="contenedor_saldo_clientes">
					<table>
						<thead>
							<tr>
								<th colspan="2">Resumen de Saldos de Clientes</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Deuda Total de Clientes<span></span></td>
								<td><input type="text" name=""></td>
							</tr>
							<tr>
								<td><span>A Cobrar en el Mes</span></td>
								<td><input type="text" name=""></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="contenedor_comisiones">
					<table>
						<thead>
							<tr>
								<th colspan="2">Total de Comisiones a Pagar</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>I.V.A. a Pagar<span></span></td>
								<td><input type="text" name=""></td>
							</tr>
							<tr>
								<td><span>Comisiones por Pagos con Tarjeta de Credito</span></td>
								<td><input type="text" name=""></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="contenedor_proyeccion_ganancias">
					<table>
						<thead>
							<tr>
								<th colspan="2">Proyeccion de Ganancias</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Ganancia Proyectada del Mes<span></span></td>
								<td><input type="text" name=""></td>
							</tr>
							<tr>
								<td><span>Ganancia a la Fecha</span></td>
								<td><input type="text" name=""></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>	