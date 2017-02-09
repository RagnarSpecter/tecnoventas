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
		<title>Modificar Saldos</title>
		<link href='css/imagenes/favi.png' rel='shortcut icon' type='image/png'>
		
		<!-- CSS -->
			<!-- Estilo General del Sistema -->
			<link rel="stylesheet" href="css/general.css">

			<!-- Estilo de la pagina -->
			<link rel="stylesheet" href="css/modificar_saldos.css">

			<!-- Soporte para Alerta -->
			<link rel="stylesheet" type="text/css" media="all" href="jQuery/ui/jquery-ui.css"/>

			<!-- Soporte para los efectos hover -->
			<link href="css/hover.css" rel="stylesheet" media="all">

		<!-- Javascript -->
			<!-- Libreria Jquery -->
			<script type="text/javascript" src="jquery.js"></script>

			<!-- Soporte para Jquery UI -->
			<script type="text/javascript" src="jQuery/ui/jquery-ui.js"></script>

			<!-- Funciones de la pagina -->
			<script type="text/javascript" src="funciones/modificar_saldos.js"></script>
			
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
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
		<div id="cuerpo">
			<div id="contenedor_opciones">
				<span>Modulo para Modificacion de Saldos</span>
				
				<br>

				<table>
					<tbody>
						<tr>
							<td><span>Elegir una cuenta: </span></td>
							<td>
								<?php  
									$lista_cuentas = $t->listar_cuentas();
									echo $lista_cuentas;
								?>
							</td>
						</tr>

						<tr>
							<td><span>Saldo en Cuenta: </span></td>
							<td><input type="text" id="saldo" name="saldo"></td>
						</tr>

						<tr>
							<td><span>Elegir Operacion</span></td>
							<td>
								<?php  
									$lista_operaciones = $t->listar_operaciones();
									echo $lista_operaciones;
								?>
							</td>
						</tr>

						<tr id="cuenta_a_transferir">
							<td><span>Elegir cuenta a transferir: </span></td>
							<td>
								<?php  
									$lista_cuentas = $t->listar_cuentas_a_transferir();
									echo $lista_cuentas;
								?>
							</td>
						</tr>	
						

						<tr>
							<td><span>Ingrese el monto: </span></td>
							<td><input type="text" id="monto" name="monto" onkeyup="format(this)" onchange="format(this)"></td>
						</tr>
						
						<tr>
							<td><input type="button" class="boton" id="guardar" name="guardar" value="Guardar"></td>	
						</tr>
					</tbody>
				</table>
			</div>

			<div id="alerta"></div>
			<div id="contenedor_cargando">
				<img src="imagenes/cargando.gif" alt="cargando" >
			</div>
		</div>
	</body>
</html>	

<!-- No te olvides de ocultar despues la fila donde elegis la cuenta a transferir -->