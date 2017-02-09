<?php

require_once("class.php");

if( !isset($_SESSION['usuario']) ){
	echo "<script>
			alert('Usted debe estar logueado para ingresar al sistema');
			location.href = 'index.php';
		  </script>";
}else{
	 if( isset($_POST["categoria"]) && !empty($_POST["categoria"]) ){

	 	$categoria = $_POST['categoria'];
	 	    $marca = $_POST['marca']; 
	  $desc_modelo = strtoupper($_POST['desc_modelo']);
	 	
	 	
	 	//Se instancia la clase Trabajo y luego se ejecuta el método guardar_modelo()
	 	$obj = new Trabajo();
	 	//Se inicializa la variable $retorno
	 	$retorno = 0;
	 	$retorno = $obj->guardar_modelo($categoria,$marca,$desc_modelo);
	 }	

}
 
?>

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Nuevo Modelo</title>
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
		<script type="text/javascript" src="funciones/nuevo_modelo.js"></script>
	
		<script>

			$(document).ready(function() {
                
				$("#categoria").change(function() {

                    var categoria = $("#categoria").val();				
					$("#contenedor_marca").load("rellena_option_marca_ajax.php",{"cod_categoria":categoria}, function(){

					});
				
				});
				
		    });

		</script>
		
	</head>

	<body class="main_background">
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
		<div id="cuerpo">
		   <div id="container_form">
             <div id="header">AGREGAR MODELO</div>
        	 <div id="container">	
        	  <form id="form_modelo" name="form" action="nuevo_modelo.php" method="post">
					<table id = "tabla_abm">	
            		<tr> 
                    	<td><span>Categoría: </span></td><td><select style="width:250px" name="categoria" id="categoria" >	
		                    
		                    <?php
								  
								$obj = new Trabajo();
								$db = $obj->conexion();
								  
								$sql = "SELECT cod_categoria,descripcion FROM producto_categoria ORDER BY descripcion ASC";
								     
								$resultado = $db->prepare($sql); 
							    $resultado->execute();
								  	
						    ?>  
                	
                		<option value=""> --Seleccione una Categoría-- </option>
	                      <?php
	                         while($registro = $resultado->fetch(PDO::FETCH_ASSOC))
							 {
						  ?>		  
				    	<option value="<?php echo $registro['cod_categoria'];?>"> <?php echo $registro['descripcion']; ?></option>			    		 			  
						  <?php	  
							 }
					      ?>
                     	</select></td> 
                    </tr>
            		

            		<tr> 
                    	<td><span>Marca: </span></td><td id="contenedor_marca"><select style="width:250px" name="marca" id="marca">	
       					                
							<option value=""> --Seleccione una Marca-- </option>	

						</select></td>
                    </tr> 
            		
					<tr>
                		<td><span>Nombre de Modelo: </span><td><input type="textbox" name="desc_modelo" id="desc_modelo" size="34"></td>
					</tr> 	

            		</table>
             </div>		

			 <div id="botonera">
             	 <input type="submit" value="aceptar" id="btn_aceptar" class="boton">&nbsp&nbsp&nbsp;
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