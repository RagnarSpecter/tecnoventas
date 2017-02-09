<?php
require_once("class.php");

if( !isset($_SESSION['usuario']) ){
	echo "<script>
			alert('Usted debe estar logueado para ingresar al sistema');
			location.href = 'index.php';
		  </script>";
}else{
	if ( isset($_POST["cedula"]) && !empty($_POST["cedula"])){
		$cedula = strtoupper($_POST['cedula']);
	       $ruc = ($_POST['ruc']);
	   $nombres = strtoupper($_POST['nombres']);
     $apellidos = strtoupper($_POST['apellidos']);
  $tipo_cliente = ($_POST['tipo_cliente']);
     $direccion = strtoupper($_POST['direccion']);
    $cod_barrio = ($_POST['barrio']);
    $cod_ciudad = ($_POST['ciudad']);
      $tel_part = ($_POST['tel_part']);
       $tel_lab = ($_POST['tel_lab']);
       $cel_nro = ($_POST['cel_nro']);
        $correo = strtoupper($_POST['correo']);
    $fecha_alta = ($_POST['fecha_alta']);
	
	//Se instancia la clase Trabajo y luego se ejecuta el método guardar_cliente()	
	  $obj = new Trabajo();
	  $retorno = 0;
	  $retorno = $obj->guardar_cliente($cedula,$ruc,$nombres,$apellidos,$tipo_cliente,$direccion,$cod_barrio,$cod_ciudad,$tel_part,$tel_lab,$cel_nro,$correo,$fecha_alta);
	} 
}
 
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Nuevo Cliente</title>
		<link href='imagenes/logo_nav.png' rel='shortcut icon' type='image/png'>
		
						  <!--CSS-->
			<!-- Estilo General del Sistema -->
	        <link rel="stylesheet" href="css/general.css">

			<!-- Estilo de la página -->
	        <link rel="stylesheet" href="css/abm.css">
	        
	        <!-- Soporte para Alerta -->
	        <link rel="stylesheet" type="text/css" media="all" href="jQuery/ui/jquery-ui.css"/>
        
						<!--Javascript-->
			<script type="text/javascript" src="jquery.js"></script>
			
			<!-- Soporte para Jquery UI -->
			<script type="text/javascript" src="jQuery/ui/jquery-ui.js"></script>
	        
	        <!-- Funciones de validación de formulario -->
			<script type="text/javascript" src="funciones/nuevo_cliente.js"></script>

        <script>

		function puntos_miles(donde,caracter){
				pat = /[\*,\+,\(,\),\?,\,$,\[,\],\^]/
				valor = donde.value
				largo = valor.length
				crtr = true
				if(isNaN(caracter) || pat.test(caracter) == true)
				{
					if (pat.test(caracter)==true)
					{ 
						caracter = "'\'" + caracter
					}
					caracter = new RegExp(caracter,"g")
					valor = valor.replace(caracter,"")
					donde.value = valor
					crtr = false
				
				}else{
					var nums = new Array()
					cont = 0
					for(m=0;m<largo;m++)
					{
						if(valor.charAt(m) == "." || valor.charAt(m) == " ")
						{
							continue;
						}else{
							nums[cont] = valor.charAt(m)
							cont++
						}
					}
				}
				
				var cad1="",cad2="",tres=0
				if(largo > 3 && crtr == true)
				{
					for (k=nums.length-1;k>=0;k--)
					{
						cad1 = nums[k]
						cad2 = cad1 + cad2
						tres++
						if((tres%3) == 0)
						{
							if(k!=0)
							{
								cad2 = "." + cad2
							}
						}
					}
					donde.value = cad2
				}
		}

		</script>
         
     </head>

	<body class="main_background" onload="doOnLoad()";>
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>

		<div id="cuerpo">
		  <div id="container_form">
           <div id="header">AGREGAR CLIENTE</div>
           <div id="container">	
            <form id="form_cliente" action="nuevo_cliente.php" method="post">
			   <table id = "tabla_abm">	
                 <tr>
                 	<td>&nbsp;</td><td><p>Todos los campos marcados con * son obligatorios</p><td>	 
                 </tr>
                 <tr>
                 	<td><span>Cédula Nº: <span>*</span> </span></td><td><input type="textbox" name="cedula" id="cedula" onkeyup="puntos_miles(this,this.value.charAt(this.value.length-1))"></td>
				 </tr> 	
                 <tr>
                    <td><span>RUC Nº: </span></td><td><input type="textbox" name="ruc" id="ruc"></td>
				 </tr>	
                 <tr>   
                    <td><span>Nombres: <span>*</span> </span></td><td><input type="textbox" name="nombres" id="nombres"></td>
			
                 </tr>   
                 <tr>   
                    <td><span>Apellidos: <span>*</span> </span></td><td><input type="textbox" name="apellidos" id="apellidos"></td>
					
                 </tr>   
                 <tr>   
                    <td><span>Tipo de cliente: </span></td>
                    <td><select name="tipo_cliente" id="tipo_cliente">
				   
                    	<option value="F" selected="selected">Físico</option>
                    	<option value="J">Jurídico</option> 
                    
                    </select></td>
                    
                  </tr>  
                  <tr>  
                    <td><span>Dirección: </span></td><td><input type="textbox" name="direccion" id="direccion" size="50"></td>
                    
                  </tr>  
                  <tr> 
                     <td><span>Barrio: </span></td><td><select style="width:250px" name="barrio" id="barrio">	
                     <?php
						  
						  $obj = new Trabajo();
						  $db = $obj->conexion();
						  
						  $sql = "SELECT cod_barrio,descripcion FROM barrio ORDER BY descripcion ASC";
						     
						  $resultado = $db->prepare($sql); 
					      $resultado->execute();
						  	
				 		  while($registro = $resultado->fetch(PDO::FETCH_ASSOC))
						 {
					  ?>		  
				    <option value="<?php echo $registro['cod_barrio'];?>"> <?php echo $registro['descripcion']; ?></option>			    		 			  
					  <?php	  
						 }
				      ?>
                     </select></td> 
                  </tr> 
                  <tr>  
                    <td><span>Ciudad: </span></td><td><select style="width:250px" name="ciudad" id="ciudad">	
                     <?php
						  
						  $obj = new Trabajo();
						  $db = $obj->conexion();
						  
						  $sql = "SELECT cod_ciudad,descripcion FROM ciudad ORDER BY descripcion ASC";
						     
						  $resultado = $db->prepare($sql); 
					      $resultado->execute();
						  	
				     	  while($registro = $resultado->fetch(PDO::FETCH_ASSOC))
						 {
					  ?>		  
				    <option value="<?php echo $registro['cod_ciudad'];?>"> <?php echo $registro['descripcion']; ?></option>			    		 			  
					  <?php	  
						 }
				      ?>
                     </select></td>
                     
                    </tr>
                  <tr>
                   <td><span>Teléf particular: </span></td><td><input type="textbox" name="tel_part" id="tel_part"></td>
					
				 </tr>
                  <tr>
                   <td><span>Teléf laboral: </span></td><td><input type="textbox" name="tel_lab" id="tel_lab"></td>
					
				 </tr> 
                  <tr> 
                   <td><span>Celular Nº: <span>*</span> </span></td><td><input type="textbox" name="cel_nro" id="cel_nro"><span id="formato">09xx-xxxxxx</span></td>
					
				 </tr>
                  <tr>
                   <td><span>Correo: </span></td><td><input type="textbox" name="correo" id="correo"></td>
					
				 </tr>   
                 <tr>
                   <td><span>Fecha de alta: <span>*</span> </span> </td><td><input type="textbox" name="fecha_alta" id="fecha_alta" value="<?php echo date("Y-m-d");?>"></td>
					
				 </tr>
			  </table>
            
	      
          </div>
         
         <div id="botonera">
          
            <input type="button" value="aceptar" id="btn_aceptar" class="boton">&nbsp&nbsp&nbsp;
            <input type="button" value="cancelar" id="btn_cancelar" class="boton" onClick="cancelar()"> 
         </div>
        </div>
       </form>
      </div>
	  <div id="dialog-success">
       		
      </div>
      <input type="hidden" value="<?php echo @$retorno; ?>" id="hidden">
      
    </body>
</html>	