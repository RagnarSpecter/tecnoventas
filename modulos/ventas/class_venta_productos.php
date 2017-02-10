<?php 
	if( !isset( $_SESSION ) )
		session_start();

	include('../../class.php');

	class Ventas{

		// Variable que contendra la instancia a la clase Trabajo
		private $trabajo;
		
		// Metodo para devolver una tabla con la lista de productos por familia, 
		// exclusivo para la pagina de venta de productos.
		public function lista_productos_familia_venta( $cod_familia ){
			
			// VARIABLES
			$lista = "";

			// CUERPO
			// Instancia a la clase Trabajo
			$this->trabajo = new Trabajo;
			
			// Abro la conexion a la base de datos
			$con = $this->trabajo->conexion();

			// Primero cuento la cantidad de productos que hay con el codigo de familia elegido
			$consulta = $con->prepare( "SELECT COUNT(*) AS cantidad FROM producto WHERE cod_familia = ?" );
			$consulta->bindValue( 1, $cod_familia );
			$consulta->execute();
			$temporal = $consulta->fetch( PDO::FETCH_ASSOC );
			$cantidad = $temporal['cantidad'];

			// Dependiendo del resultado, se dan las siguientes opciones
			if( $cantidad == 0 ){	
				
				$lista .= "No hay productos en existencia de la familia seleccionada";
			}else{

				// Inicializacion de variables
				$productos = array();
				$cantidad = 0;

				$consulta = $con->prepare( 	"
												SELECT 
													p.cod_familia AS codigo_familia,
													p.cod_producto AS codigo_producto,
													p.cod_categoria AS codigo_categoria, 
													procat.descripcion AS categoria,
													promar.descripcion AS marca,
													promod.descripcion AS modelo,
													procol.descripcion AS color,
													propla.descripcion AS plataforma,
													p.precio_venta AS precio_venta, 
													p.descripcion AS descripcion
												FROM producto AS p
												JOIN producto_categoria AS procat on p.cod_categoria = procat.cod_categoria
												JOIN producto_marca AS promar on p.cod_marca = promar.cod_marca
												JOIN producto_modelo AS promod on p.cod_modelo = promod.cod_modelo
												JOIN producto_color AS procol on p.cod_color = procol.cod_color
												JOIN producto_plataforma AS propla on p.cod_plataforma = propla.cod_plataforma
												WHERE p.cod_familia = ".$cod_familia."
											");

				$consulta->execute();


				while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

					$productos[] = $row;
				}

				$cantidad = count( $productos );
				
				$contador = 0;
				for( $i = 0; $i < $cantidad; $i++ ){

					// Se crea el titulo de la categoria por cada primer producto
					if( $contador == 0 ){
						$lista .= 	"
									<br>
									<table cellspacing='0' id='TABLA_lista_productos_familia'>
										<thead>
											<tr>
												<th width='5%'>Seleccion</th>
												<th width='5%'>Codigo</th>
												<th>Marca</th>
												<th>Modelo</th>
												<th>Color</th>
												<th>Plataforma</th>
												<th>Descripcion</th>
											</tr>
										</thead>

										<tbody>
									";
					}

					$lista .= 	"
											<tr>
												<td><input type='checkbox' class='check_vender' name='".$productos[$i]["codigo_producto"]."'></td>
												<td id='cod_producto'>".$productos[$i]["codigo_producto"]."</td>
												<td>".$productos[$i]["marca"]."</td>
												<td>".$productos[$i]["modelo"]."</td>
												<td>".$productos[$i]["color"]."</td>
												<td>".$productos[$i]["plataforma"]."</td>
												<td>".$productos[$i]["descripcion"]."</td>
											</tr>
											<tr id='contenedor_costo_y_cantidad_".$productos[$i]["codigo_producto"]."' class='contenedor_costo_y_cantidad' style='display:none'>
												<td colspan='7'>
													<span>
														
														<!-- CAMPO PARA CARGA DEL PRECIO DE VENTA. -->
														<span>Precio de Venta: </span>
														<span style='margin-right:20px'><input type='text' id='costo_producto_".$productos[$i]["codigo_producto"]."' size='10' onkeyup='format(this)' onchange='format(this)''></span>
														
														<!-- SELECT PARA LA SELECCION DE LA CANTIDAD DE PRODUCTOS A VENDER. -->
														<span style='text-align: center'>Ingrese la cantidad a vender: </span>
														<span style='margin-right:20px'>".  
															$lista_cantidad = $this->trabajo->listar_cantidad( $productos[$i]["codigo_producto"] )
														."</span>
														
														<br><br>

														<!-- BOTON PARA PROCESAR LA VENTA. -->
														<span><input type='button' id='btn_vender' name='".$productos[$i]["codigo_producto"]."' class='boton' value='Agregar a Carrito'></span>
													
													</span>
													<br>
												</td>
											</tr>";
										

					$contador++;
				}

				$lista .= 				"</tbody>
									</table>
									<br><br>";
			}

			return $lista;

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para devolver una tabla con la lista de productos por categoria, 
		// exclusivo para la pagina de venta de productos.
		public function lista_productos_categoria_venta( $cod_categoria ){
			
			// VARIABLES
			$lista = "";

			// CUERPO
			// Instancia a la clase Trabajo
			$this->trabajo = new Trabajo;
			
			// Abro la conexion a la base de datos
			$con = $this->trabajo->conexion();

			// Primero cuento la cantidad de productos que hay con el codigo de familia elegido
			$consulta = $con->prepare( "SELECT COUNT(*) AS cantidad FROM producto WHERE cod_familia = ?" );
			$consulta->bindValue( 1, $cod_familia );
			$consulta->execute();
			$temporal = $consulta->fetch( PDO::FETCH_ASSOC );
			$cantidad = $temporal['cantidad'];

			// Dependiendo del resultado, se dan las siguientes opciones
			if( $cantidad == 0 ){	
				
				$lista .= "No hay productos en existencia de la familia seleccionada";
			}else{

				// Inicializacion de variables
				$productos = array();
				$cantidad = 0;

				$consulta = $con->prepare( 	"
												SELECT 
													p.cod_familia AS codigo_familia,
													p.cod_producto AS codigo_producto,
													p.cod_categoria AS codigo_categoria, 
													procat.descripcion AS categoria,
													promar.descripcion AS marca,
													promod.descripcion AS modelo,
													procol.descripcion AS color,
													propla.descripcion AS plataforma,
													p.precio_venta AS precio_venta, 
													p.descripcion AS descripcion
												FROM producto AS p
												JOIN producto_categoria AS procat on p.cod_categoria = procat.cod_categoria
												JOIN producto_marca AS promar on p.cod_marca = promar.cod_marca
												JOIN producto_modelo AS promod on p.cod_modelo = promod.cod_modelo
												JOIN producto_color AS procol on p.cod_color = procol.cod_color
												JOIN producto_plataforma AS propla on p.cod_plataforma = propla.cod_plataforma
												WHERE p.cod_familia = ".$cod_familia."
											");

				$consulta->execute();


				while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

					$productos[] = $row;
				}

				$cantidad = count( $productos );
				
				$contador = 0;
				for( $i = 0; $i < $cantidad; $i++ ){

					// Se crea el titulo de la categoria por cada primer producto
					if( $contador == 0 ){
						$lista .= 	"
									<br>
									<table cellspacing='0' id='TABLA_lista_productos_familia'>
										<thead>
											<tr>
												<th width='5%'>Seleccion</th>
												<th width='5%'>Codigo</th>
												<th>Marca</th>
												<th>Modelo</th>
												<th>Color</th>
												<th>Plataforma</th>
												<th>Descripcion</th>
											</tr>
										</thead>

										<tbody>
									";
					}

					$lista .= 	"
											<tr>
												<td><input type='checkbox' class='check_vender' name='".$productos[$i]["codigo_producto"]."'></td>
												<td id='cod_producto'>".$productos[$i]["codigo_producto"]."</td>
												<td>".$productos[$i]["marca"]."</td>
												<td>".$productos[$i]["modelo"]."</td>
												<td>".$productos[$i]["color"]."</td>
												<td>".$productos[$i]["plataforma"]."</td>
												<td>".$productos[$i]["descripcion"]."</td>
											</tr>
											<tr id='contenedor_costo_y_cantidad_".$productos[$i]["codigo_producto"]."' class='contenedor_costo_y_cantidad' style='display:none'>
												<td colspan='7'>
													<span>
														
														<!-- CAMPO PARA CARGA DEL PRECIO DE VENTA. -->
														<span>Precio de Venta: </span>
														<span style='margin-right:20px'><input type='text' id='costo_producto_".$productos[$i]["codigo_producto"]."' size='10' onkeyup='format(this)' onchange='format(this)''></span>
														
														<!-- SELECT PARA LA SELECCION DE LA CANTIDAD DE PRODUCTOS A VENDER. -->
														<span style='text-align: center'>Ingrese la cantidad a vender: </span>
														<span style='margin-right:20px'>".  
															$lista_cantidad = $this->trabajo->listar_cantidad( $productos[$i]["codigo_producto"] )
														."</span>
														
														<br><br>

														<!-- BOTON PARA PROCESAR LA VENTA. -->
														<span><input type='button' id='btn_vender' name='".$productos[$i]["codigo_producto"]."' class='boton' value='Agregar a Carrito'></span>
													
													</span>
													<br>
												</td>
											</tr>";
										

					$contador++;
				}

				$lista .= 				"</tbody>
									</table>
									<br><br>";
			}

			return $lista;

			// Cierra la conexion a la base de datos
			$con = null;
		}
	}

?>