<?php
require_once("class.php");

if( !isset($_SESSION['usuario']) ){
	echo "<script>
			alert('Usted debe estar logueado para ingresar al sistema');
			location.href = 'index.php';
		  </script>";
}else{
	 if( isset($_POST["desc_color"]) && !empty($_POST["desc_color"]) ){

	 	$desc_color = strtoupper($_POST['desc_color']);

	 	//Se instancia la clase Trabajo y luego se ejecuta el método guardar_color()
	 	$obj = new Trabajo();
	 	//Se inicializa la variable $retorno
	 	$retorno = 0;
	 	$retorno = $obj->guardar_color($desc_color);
	 }	

}
 
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Nuevo Color</title>
		<link href='imagenes/logo_nav.png' rel='shortcut icon' type='image/png'>
		
					<!--CSS-->
		<!-- Estilo General del Sistema -->
		<link rel="stylesheet" href="css/general.css">
		
		<!-- Estilo de la página -->
		<link rel="stylesheet" href="css/abm.css">
		
		<!-- Soporte para Alerta -->
		<link rel="stylesheet" type="text/css" media="all" href="jQuery/sexyalertbox.css"/>
        <link rel="stylesheet" type="text/css" media="all" href="jQuery/ui/jquery-ui.css"/>
		
				<!--Javascript-->
		<script type="text/javascript" src="jquery.js"></script>
		
		<!-- Soporte para Jquery UI -->
		<script type="text/javascript" src="jQuery/ui/jquery-ui.js"></script>
		
		<!-- Soporte para Alerta -->
		<script type="text/javascript" src="jQuery/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="jQuery/sexyalertbox.v1.2.jquery.js"></script>
		
		<!-- Funciones de validación de formulario -->
		<script type="text/javascript" src="funciones/nuevo_color.js"></script>
	</head>

	<body class="main_background">
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
		<div id="cuerpo">
		   <div id="container_form">
             <div id="header">AGREGAR COLOR</div>
        	 <div id="container">	
        	  <form id="form_color" name="form" action="nuevo_color.php" method="post">
					<table id = "tabla_abm">	
            			<tr>
                			<td><span>Nombre del Color: </span><td><input type="textbox" name="desc_color" id="desc_color"></td>
						</tr> 	
            		</table>
             </div>		

			 <div id="botonera">
             	 <input type="button" value="aceptar" id="btn_aceptar" class="boton">&nbsp&nbsp&nbsp;
          		 <input type="button" value="cancelar" id="btn_cancelar" class="boton"> 
         	 </div>
           </div>
             </form>
	    </div>
	   <div id="dialog-success">

	   </div>
	   <input type="hidden" value="<?php echo $retorno; ?>" id="hidden">	
    
    </body>
</html>