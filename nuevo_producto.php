<?php
	
	require_once("class.php");
	if( !isset($_SESSION['usuario']) ){
		echo "<script>
				alert('Usted debe estar logueado para ingresar al Sistema');
				location.href = 'index.php';
			  </script>";
	}else{
		//Condición para verificar si se cargaron los datos del ABM principal
		if( isset($_POST["familia"]) && !empty($_POST["familia"]) ){

		 	//Se captura el archivo de la carpeta temporal y se guarda en la variable '$destino' el lugar del servidor donde se alojarán las fotos. 	 
		 	$archivo = $_FILES["imagen"]["tmp_name"];
		 	$destino = "C:/xampp/htdocs/tecnoventas/imagenes/fotos/".$_FILES["imagen"]["name"];

		 	//Con esta función se sube el archivo al Servidor.
		 	move_uploaded_file($archivo, $destino);    

			   $codigo = $_POST['codigo'];
		 	  $familia = $_POST['familia'];
		 	$categoria = $_POST['categoria'];
		 	    $marca = $_POST['marca'];
		 	   $modelo = $_POST['modelo'];
		 	    $color = $_POST['color'];
		   $plataforma = $_POST['plataforma'];
		      $p_venta = $_POST['p_venta']; 
		  $descripcion = strtoupper($_POST['descripcion']);
		    $stock_min = $_POST['stock_min'];

			//Se instancia la clase Trabajo y luego se ejecuta el método guardar_producto()
		 	$obj = new Trabajo();
		 	//Se inicializa la variable $retorno
		 	$retorno = 0;
		 	$retorno = $obj->guardar_producto($codigo, $familia, $categoria, $marca, $modelo, $color, $plataforma, $p_venta, $descripcion, $stock_min, $destino);
		}	

	}

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Nuevo Producto</title>
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
		<script type="text/javascript" src="funciones/nuevo_producto.js"></script>
		<script type="text/javascript" src="funciones/nuevo_producto_popup.js"></script>
        <script type="text/javascript" src="funciones/nueva_marca_popup.js"></script>
		<script type="text/javascript" src="funciones/nuevo_modelo_popup.js"></script> 
	</head>
    <body class="main_background">
		<?php
			include 'cabecera.php';
			include 'navegacion_admin.php';
		?>
		<div id="cuerpo">
		    <div id="container_form">
             
             <div id="back_ventana"> 
				
					<div id="cuerpo">
						    <div id="container_form">
				             <div id="header">AGREGAR MARCA</div>
				        	 	<div id="container_popup">	
						        	  <form id="form_marca" name="form_marca" action="nuevo_producto.php" method="post">
											<table id = "tabla_abm">	
						            			<tr>
						                			<td><span>Nombre de Marca: </span><td><input type="textbox" name="desc_marca" id="desc_marca" size="34"></td>
												</tr> 	
						            			<tr> 
						                    	<td><span>Categoría: </span></td><td><select style="width:250px" name="categoria_popup" id="categoria_popup" >	
								                    
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
						            		</table>
				            	
				            </div>		
								<div id="botonera">
					             	<input type="button" value="aceptar" id="btn_aceptar_popup" class="boton"> &nbsp&nbsp&nbsp;
					          		<input type="button" value="cancelar" id="btn_cancelar_popup" class="boton"> 
					         	</div>
				           </div>
				             </form>
					</div>
					   
					   <div id="dialog-success-popup">

					   </div>
					   <input type="hidden" value="<?php echo @$retorno_popup; ?>" id="hidden_popup">
				
		     </div>	

			 
			 <div id="back_ventana_modelo"> 
				
					<div id="cuerpo">
						    <div id="container_form">
				             <div id="header">AGREGAR MODELO</div>
				        	 	<div id="container_popup">	
						        	  <form id="form_modelo" name="form_modelo" action="nuevo_producto.php" method="post">
											<table id = "tabla_abm">	
						            	    	
						            			<tr> 
						                    	  <td><span>Categoría: </span></td><td><select style="width:250px" name="categoria_popup_modelo" id="categoria_popup_modelo">	
								                    
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
						                    	  	<td><span>Marca: </span></td><td><select style="width:250px" name="marca_popup_modelo" id="marca_popup_modelo">	
								                    
								                    <?php
														  
														$obj = new Trabajo();
														$db = $obj->conexion();
														  
														$sql = "SELECT cod_marca,descripcion FROM producto_marca ORDER BY descripcion ASC";
														     
														$resultado = $db->prepare($sql); 
													    $resultado->execute();
														  	
												    ?>  
						                	
							                		<option value=""> --Seleccione una Marca-- </option>
								                      <?php
								                         while($registro = $resultado->fetch(PDO::FETCH_ASSOC))
														 {
													  ?>		  
											    	<option value="<?php echo $registro['cod_marca'];?>"> <?php echo $registro['descripcion']; ?></option>			    		 			  
													  <?php	  
														 }
												      ?>
							                     	</select></td> 
						                    	</tr>

						                    	<tr>
                									<td><span>Nombre de Modelo: </span><td><input type="textbox" name="desc_modelo" id="desc_modelo" size="34"></td>
												</tr>


						            		</table>
				            	
				            </div>		
								<div id="botonera">
					             	<input type="button" value="aceptar" id="btn_aceptar_popup_2" class="boton">&nbsp&nbsp&nbsp;
					          		<input type="button" value="cancelar" id="btn_cancelar_popup_2" class="boton"> 
					         	</div>
				           </div>
				             </form>
					</div>
					   
					   <div id="dialog-success-popup-2">

					   </div>
					   <input type="hidden" value="<?php echo @$retorno_popup_2; ?>" id="hidden_popup_2">
				
		     </div>	
				

			 <div id="header">AGREGAR PRODUCTO</div> 
			 <div id="container">	
        	  <form id="form_producto" name="form_producto" action="nuevo_producto.php" method="post" enctype="multipart/form-data">
				<table id = "tabla_abm">	
            		<tr>
                 		<td>&nbsp;</td><td><p>Todos los campos marcados con * son obligatorios</p><td>	 
                 	</tr>
            		<tr>
	            		<td>
	            	  		Tipo de código: 
	            		</td>
	            		<td class="radio1">
	                    	<input type="radio" name="tipo_codigo" id="barras" value="barras" checked="checked"> de barras </input>
	                	</td>
	                	<td class="radio2">
	                    	<input type="radio" name="tipo_codigo" id="generado" value="generado"> generado </input>
	                	</td>
	                </tr>
            		<tr>
					    <td><span id="ingrese">Ingrese aquí el código:<span>*</span></span></td><td><input type="textbox" name="codigo" id="codigo" size="34"></td>  		
					</tr>	
            		
            		<tr> 
                    	<td><span>Familia: <span>*</span> </span></td><td><select style="width:250px" name="familia" id="familia" >	
                     <?php
						  
						  $obj = new Trabajo();
						  $db = $obj->conexion();
						  
						  $sql = "SELECT cod_familia,descripcion FROM producto_familia ORDER BY descripcion ASC";
						     
						  $resultado = $db->prepare($sql); 
					      $resultado->execute();
						  	
				     ?>  
                	
                		<option value=""> --Seleccione una Familia-- </option>
	                      <?php
	                         while($registro = $resultado->fetch(PDO::FETCH_ASSOC))
							 {
						  ?>		  
				    	<option value="<?php echo $registro['cod_familia'];?>"> <?php echo $registro['descripcion']; ?></option>			    		 			  
						  <?php	  
							 }
					      ?>
                     	</select></td> 
                    </tr>

                    <tr> 
                    	<td><span>Categoría: <span>*</span> </span></td><td id="contenedor_categoria"><select style="width:250px" name="categoria" id="categoria">	
       					                
							<option value=""> --Seleccione una Categoría-- </option>	

						</select></td>		
                    </tr>
				
                    <tr> 
                    	<td><span id="marca_label">Marca: <span>*</span> </span></td><td id="contenedor_marca"><select style="width:250px" name="marca" id="marca">	
	                      
                			<option value=""> --Seleccione una Marca-- </option>
	                      			  
				    	</select> 
                    </tr> 
                    
					<tr> 
                    	<td><span id="modelo_label">Modelo: <span>*</span> </span></td><td id="contenedor_modelo"><select style="width:250px" name="modelo" id="modelo">	
	                    
                			<option value=""> --Seleccione un Modelo-- </option>
	                      		  
				    	</select></select></td> 
                    </tr> 

					<tr> 
                        <td><span id="color_label">Color:</span></td><td><select style="width:250px" name="color" id="color">

	                     <?php
							  
							  $obj = new Trabajo();
							  $db = $obj->conexion();
							  
							  $sql = "SELECT cod_color,descripcion FROM producto_color ORDER BY descripcion ASC";
							     
							  $resultado = $db->prepare($sql); 
						      $resultado->execute();
							  	
					       while($registro = $resultado->fetch(PDO::FETCH_ASSOC))
							 {
						  ?>		  
				    	<option value="<?php echo $registro['cod_color'];?>"> <?php echo $registro['descripcion']; ?></option>			    		 			  
						  <?php	  
							 }
					      ?>
                        </select></td> 
                    </tr> 
                    <tr>  
                        <td><span>Descripción: </span></td><td><input type="textbox" name="descripcion" id="descripcion" size="50"></td>
                    </tr>
                    <tr>  
                    	<td><span>Precio de Venta: </span></td><td><input type="textbox" name="p_venta" id="p_venta" onkeyup="puntos_miles(this,this.value.charAt(this.value.length-1))" size="34"></td>
                    </tr>
                    <tr> 
                       <td><span id="plataforma_label">Plataforma:</span></td><td><select style="width:250px" name="plataforma" id="plataforma">	
	                     <?php
							  
							  $obj = new Trabajo();
							  $db = $obj->conexion();
							  
							  $sql = "SELECT cod_plataforma,descripcion FROM producto_plataforma ORDER BY descripcion ASC";
							     
							  $resultado = $db->prepare($sql); 
						      $resultado->execute();
					     
							  while($registro = $resultado->fetch(PDO::FETCH_ASSOC))
							  {
						  ?>		  
				    	<option value="<?php echo $registro['cod_plataforma'];?>"> <?php echo $registro['descripcion']; ?></option>			    		 			  
						 <?php	  
							  }
					     ?>
                        </select></td> 
                    </tr>
                    <tr>
	            		<td>
	            	  		Frecuencia de venta:
	            		</td>
		            	<td class="radio1">
		                    <input type="radio" name="frecuencia_vta" id="diaria" value="diaria" checked="checked"> diaria </input>
		                </td>
		                <td class="radio2">
		                    <input type="radio" name="frecuencia_vta" id="casual" value="casual"> casual </input>
		                </td>
	                </tr>
	                <tr> 
                        <td><span id="stock">Stock mínimo:</span></td><td><select style="width:250px" name="stock_min" id="stock_min">	
	                     
	                     <option value=""> --Seleccione un número-- </option>  
	                       <?php
							 $i=1;
	                         for($i=1; $i<51; $i++){
	                       ?>		  
				         <option value="<?php echo $i; ?>"> <?php echo $i; ?></option>			    		 			  
					       <?php	  
						     }
				           ?>		  
				        </select></td> 
                    </tr>
                    <tr>
						<td><span>Imagen: </span></td><td><input type="file" name="imagen" id="imagen"></td>
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
	   
	   <input type="hidden" value="<?php echo @$retorno; ?>" id="hidden">	
    
    </body>	
</html>