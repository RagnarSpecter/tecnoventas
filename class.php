<?php  
	if( !isset( $_SESSION ) ){

		session_start();

	}

	class Trabajo{

		// Variables
		private $host;
		private $puerto;
		private $db;
		private $user;
		private $password;

		// Metodos
		public function __construct(){
			
			$this->host     = 'localhost';
			$this->puerto   = '5432';
			$this->db       = 'tecnoventas';
			$this->user     = 'postgres';
			$this->password = 'root';
		}

		// Metodo para abrir la conexion a la base de datos
		public function conexion(){
			
			try{
				$db = new PDO( "pgsql:host=".$this->host."; port=".$this->puerto."; dbname=".$this->db."; user=".$this->user."; password=".$this->password."" );
				$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			}
			catch( PDOException $e ){

				// Hay dos opciones, una es mostrar directamente un mensaje que yo quiera mostrar o
				//echo "Ha ocurrido un error, si el problema persiste, favor comunicarse a la Direccion de Tecnologias de la Informacion y Comunicaciones.  Gracias.";

				// Mostrar el error (UNICAMENTE DURANTE FASE DE DESARROLLO)
				echo $e->getMessage();
				die();
			}

			return $db;	
		}

		// Metodo para permitir el ingreso al sistema
		public function login(){
			@$cedula = $_POST[ 'cedula' ];
			@$password = $_POST[ 'password' ];

			if( isset( $cedula ) && isset( $password ) ){
				if( !empty( $cedula ) && !empty( $password )){

					$con = $this->conexion();
					$consulta = $con->prepare( "SELECT COUNT(*) FROM usuario WHERE cedula = ?" );
					$consulta->bindValue( 1, $cedula );
					$consulta->execute();
					$resultado = $consulta->fetch( PDO::FETCH_ASSOC );
					$registros = count( $resultado );

					if( $registros != 0 ){
						$consulta = $con->prepare( "SELECT cedula, nombres, apellidos, tipo_usuario, contrasenha FROM usuario WHERE cedula = ?" );
						$consulta->bindValue( 1, $cedula );
						$consulta->execute();
						$registro = $consulta->fetch( PDO::FETCH_ASSOC );

						if( $registro['contrasenha'] == 'ADMIN' ){
							if( $password == $registro['contrasenha'] ){
								
								$_SESSION[ 'usuario' ] = $this->cadena( $registro );
								$_SESSION[ 'cedula' ] =  $cedula;
								$_SESSION["carrito"] = array();
								header("Location:inicio.php");
								$con->null;
								
							}else{
								
								return 2;
								
							}
						}
						
					}else{
						return 1;
						
					}		
				}
			}

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo auxiliar para mostrar el primer nombre y el primer apellido de un usuario
		public function cadena( $datos ){
			$long_nombre = strpos( $datos[ 'nombres' ], ' ' );
			$long_apellido = strpos( $datos[ 'apellidos'], ' ' );
			
			if( $long_nombre != 0 && $long_apellido != 0 ){
				$primer_nombre = ucfirst( strtolower( substr( $datos[ 'nombres' ], 0 , $long_nombre ) ) );
				$primer_apellido = ucfirst( strtolower( substr( $datos[ 'apellidos' ], 0 , $long_apellido ) ) ); 
			}
			else if( empty( $long_nombre ) && empty( $long_apellido ) ){

				$primer_nombre = ucfirst( strtolower( $datos[ 'nombres' ] ) );
				$primer_apellido = ucfirst( strtolower( $datos[ 'apellidos' ] ) );
				//$primer_apellido = ucfirst( strtolower( substr( $datos[ 'apellidos' ], 0 , $long_apellido ) ) );
			}
			else if( empty( $long_nombre ) && $long_apellido != 0 ){
				$primer_nombre = ucfirst( strtolower( $datos[ 'nombres' ] ) );
				$primer_apellido = ucfirst( strtolower( substr( $datos[ 'apellidos' ], 0 , $long_apellido ) ) );
			}else{

				$primer_nombre = ucfirst( strtolower( substr( $datos[ 'nombres' ], 0 , $long_nombre ) ) );
				$primer_apellido = ucfirst( strtolower( $datos[ 'apellidos' ] ) );
			}

			return $primer_nombre." ".$primer_apellido;
		}

		// Metodo para devolver un combo box con la lista de categorias de productos
		public function listar_familia(){
			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='familia' id='familia'>
							<option value='0'>-Elegir familia-</option>
						";

			// Traigo los valores de la tabla producto_categoria
			$consulta = $con->prepare( "SELECT * FROM producto_familia" );
			$consulta->execute();

			// Guardo el resultado en el array $categorias
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

				$familias[] = $row;
			}

			// Cuento la cantidad de registros traidos
			$cantidad = count($familias);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){ 
				$select .= "<option value='".$familias[$i]['cod_familia']."'>".ucfirst($familias[$i]['descripcion'])."</option>";
			}

			$select .= 	"</select>";

			return $select;

			// Cierra la conexion a la base de datos
			$con = null;
		}	

		// Metodo para devolver un combo box con la lista de categorias de productos
		public function listar_categoria( $cod_familia ){
			
			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			$categorias = array();

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='categoria' id='categoria'>
							<option value='0'>-Elegir Categoria-</option>
						";

			// Traigo los valores de la tablaS producto_familia_categoria y producto_categoria
			$consulta = $con->prepare( 	"
										SELECT pc.cod_categoria, pc.descripcion 
										FROM producto_familia_categoria AS pfc
										JOIN producto_categoria AS pc
										ON pfc.cod_categoria = pc.cod_categoria
										WHERE pfc.cod_familia = ".$cod_familia."
										ORDER BY pc.cod_categoria ASC
										" );
			$consulta->execute();

			// Guardo el resultado en el array $categorias
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

				$categorias[] = $row;
			}

			// Cuento la cantidad de registros traidos
			$cantidad = count($categorias);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){ 
				$select .= "<option value='".$categorias[$i]['cod_categoria']."'>".$categorias[$i]['descripcion']."</option>";
			}

			$select .= 	"</select>";

			return $select;

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para devolver un combo box con la lista de marcas
		public function listar_marca( $cod_categoria ){

			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			$marcas = array();

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='marca' id='marca'>
							<option value='0'>-Elegir marca-</option>
						";

			// Traigo los valores de la tablaS producto_familia_categoria y producto_categoria
			$consulta = $con->prepare( 	"
											SELECT pm.cod_marca, pm.descripcion 
											FROM producto_categoria_marca AS pcm
											JOIN producto_marca AS pm
											ON pcm.cod_marca = pm.cod_marca
											WHERE pcm.cod_categoria = ".$cod_categoria."
											ORDER BY pm.cod_marca ASC
										" );
			$consulta->execute();

			// Guardo el resultado en el array $categorias
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

				$marcas[] = $row;
			}

			// Cuento la cantidad de registros traidos
			$cantidad = count($marcas);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){ 
				$select .= "<option value='".$marcas[$i]['cod_marca']."'>".$marcas[$i]['descripcion']."</option>";
			}

			$select .= 	"</select>";

			return $select;

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para devolver un combo box con la lista de modelos
		public function listar_modelo( $cod_categoria, $cod_marca ){

			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			$modelos = array();

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='modelo' id='modelo'>
							<option value='0'>-Elegir modelo-</option>
						";

			// Traigo los valores de la tablaS producto_familia_categoria y producto_categoria
			$consulta = $con->prepare( 	"
											SELECT pm.cod_modelo, pm.descripcion 
											FROM producto_categoria_marca_modelo AS pcmm
											JOIN producto_modelo AS pm
											ON pcmm.cod_modelo = pm.cod_modelo
											WHERE pcmm.cod_categoria = ".$cod_categoria."
											AND pcmm.cod_marca = ".$cod_marca."
											ORDER BY pm.cod_modelo ASC
										" );
			$consulta->execute();

			// Guardo el resultado en el array $categorias
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) )
				$modelos[] = $row;
			

			// Cuento la cantidad de registros traidos
			$cantidad = count($modelos);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){ 
				$select .= "<option value='".$modelos[$i]['cod_modelo']."'>".$modelos[$i]['descripcion']."</option>";
			}

			$select .= 	"</select>";

			return $select;

			// Cierra la conexion a la base de datos
			$con = null;	
		}

		// Metodo para devolver un combo box con la lista de colores
		public function listar_color(){

			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='color' id='color'>
							<option value='0'>-Elegir Color-</option>
						";

			// Traigo los valores de la tabla producto_categoria
			$consulta = $con->prepare( "SELECT * FROM producto_color" );
			$consulta->execute();

			// Guardo el resultado en el array $categorias
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

				$colores[] = $row;
			}

			// Cuento la cantidad de registros traidos
			$cantidad = count($colores);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){ 
				$select .= "<option value='".$colores[$i]['cod_color']."'>".$colores[$i]['descripcion']."</option>";
			}

			$select .= 	"</select>";

			return $select;

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para devolver un combo box con la lista de plataformas
		public function listar_plataforma(){

			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='plataforma' id='plataforma'>
							<option value='0'>-Elegir plataforma-</option>
						";

			// Traigo los valores de la tabla producto_categoria
			$consulta = $con->prepare( "SELECT * FROM producto_plataforma" );
			$consulta->execute();

			// Guardo el resultado en el array $categorias
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

				$plataformas[] = $row;
			}

			// Cuento la cantidad de registros traidos
			$cantidad = count($plataformas);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){ 
				$select .= "<option value='".$plataformas[$i]['cod_plataforma']."'>".$plataformas[$i]['descripcion']."</option>";
			}

			$select .= 	"</select>";

			return $select;

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para devolver un combo box, donde se pueda seleccionar un valor numerico,
		// que represente la cantidad de productos a comprar
		public function listar_cantidad( $cod_producto ){

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";

			$select .= 	"
						<select name='cantidad' id='cantidad_".$cod_producto."' onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
						";

			for ( $i = 1; $i < 51; $i++ ) { 
				$select .= "<option value='" . $i . "'>" . $i . "</option>";
			}

			$select .= 	"</select>";

			return $select;
		}

		// Metodo para devolver una tabla con la lista de productos en stock
		public function lista_productos(){

			// Variables
			$productos = array();

			// Abro la conexion a la base de datos
			$con = $this->conexion();

			//Consulta para traer todos los productos de la base de datos
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
											p.stock_actual as stock_actual, 
											p.descripcion AS descripcion,
											p.foto AS foto
										FROM producto AS p
										JOIN producto_familia AS profam ON p.cod_familia = profam.cod_familia
										JOIN producto_categoria AS procat ON p.cod_categoria = procat.cod_categoria
										JOIN producto_marca AS promar ON p.cod_marca = promar.cod_marca
										JOIN producto_modelo AS promod ON p.cod_modelo = promod.cod_modelo
										JOIN producto_color AS procol ON p.cod_color = procol.cod_color
										JOIN producto_plataforma AS propla ON p.cod_plataforma = propla.cod_plataforma
										WHERE stock_actual > 0
										ORDER BY codigo_categoria ASC, marca ASC, modelo ASC
										" );
			$consulta->execute();

			// Guardo el resultado en el array $productos
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) )
				$productos[] = $row;
	

			// return "<pre>".var_dump($productos)."</pre>";
			// exit;

			// Creo la variable donde voy a cargar la tabla con los productos
			$lista = "";

			// Cuento la cantidad de productos que trajo la consulta
			$cantidad = count($productos);
			
			// Defino la primera categoria
			$categoria = $productos[0]["codigo_categoria"];
			
			// Ciclo para cargar la variable que retornara la tabla de los productos, ordenados por categoria
			$contador = 0;
			for( $i = 0; $i < $cantidad; $i++ ){

				// SE CREA EL TITULO DE LA CATEGORIA POR CADA PRIMER PRODUCTO
				if( $contador == 0 ){
					$lista .= 	"
								<span class='titulo'>".ucfirst($productos[$i]["categoria"])."</span>
								<br>
								<table cellspacing='0' id='TABLA_lista_productos'>
								";

					// EN EL CASO DE LOS TITULOS, SE CREA UNO ESPECIFICO PARA JUEGOS, POR NO SOMETERSE
					// AL MISMO TRATAMIENTO DE TODOS LOS DEMAS PRODUCTOS.
					if( $productos[$i]["categoria"] != "juego" ){
						$lista .= 	"
									<thead>
										<tr>
											<th width='5%'>Stock</th>
											<th width='5%'>Codigo</th>
											<th width='10%'>Marca</th>
											<th width='15%'>Modelo</th>
											<th width='10%'>Color</th>
											<th class='costo' width='10%'>Precio de Costo</th>
											<th width='10%'>Precio de Venta</th>
											<th width='35%'>Descripcion</th>
										</tr>
									</thead>
									<tbody>
									";
					}else{
						$lista .= 	"
									<thead>
										<tr>
											<th>Numero</th>
											<th>Codigo Producto</th>
											<th>Descripcion</th>
											<th>Plataforma</th>
											<th class='costo'>Precio de Costo</th>
											<th>Precio de Venta</th>
										</tr>
									</thead>
									<tbody>
									";
					}

					$contador++;			
				}

				// LO MISMO SE DA PARA EL CUERPO DE LA LISTA.
				if( $productos[$i]["categoria"] != "juego" ){
					
					// Se imprimen los productos de la categoria
					$lista .= 	"
										<tr>
											<td>".$productos[$i]["stock_actual"]."</td>
											<td>".$productos[$i]["codigo_producto"]."</td>
											<td>".ucfirst($productos[$i]["marca"])."</td>
											<td><span class='mostrar_foto' name='".$productos[$i]["foto"]."'>".$productos[$i]["modelo"]."</span></td>
											<td>".$productos[$i]["color"]."</td>
											<td class='costo'></td>
											<td>".number_format( intval($productos[$i]["precio_venta"]), 0, ' ', '.')."</td>
											<td>".$productos[$i]["descripcion"]."</td>
										</tr>
								
								";	
				}else{
					$lista .= 	"
										<tr>
											<td>".$productos[$i]["stock_actual"]."</td>
											<td>".$productos[$i]["codigo_producto"]."</td>
											<td>".$productos[$i]["descripcion"]."</td>
											<td>".$productos[$i]["plataforma"]."</td>
											<td class='costo'></td>
											<td>".$productos[$i]["precio_venta"]."</td>
										</tr>
								";		
				}

				$contador++;

				if( $i < $cantidad - 1 ){
					if( $productos[$i+1]["codigo_categoria"] != $categoria ){
						$categoria = $productos[$i+1]["codigo_categoria"];
						$contador = 0;
						$lista .= 	"
									</tbody>
								</table>
								<br>
								";
					}
				}else{
					$lista .= 	"
									</tbody>
								</table>
								<br>
								";
				}
			}
		

			return $lista;

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para devolver una tabla con la lista de productos por familia
		public function lista_productos_por_familia( $cod_familia ){
			
			// Variables
			$lista = "";

			// Cuerpo
			// Abro la conexion a la base de datos
			$con = $this->conexion();

			// Declaro el array donde voy a guardar la lista de los productos
			$productos = array();

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
											p.stock_actual as stock_actual, 
											p.descripcion AS descripcion,
											p.foto AS foto
										FROM producto AS p
										JOIN producto_familia AS profam ON p.cod_familia = profam.cod_familia
										JOIN producto_categoria AS procat ON p.cod_categoria = procat.cod_categoria
										JOIN producto_marca AS promar ON p.cod_marca = promar.cod_marca
										JOIN producto_modelo AS promod ON p.cod_modelo = promod.cod_modelo
										JOIN producto_color AS procol ON p.cod_color = procol.cod_color
										JOIN producto_plataforma AS propla ON p.cod_plataforma = propla.cod_plataforma
										WHERE p.cod_familia = ".$cod_familia." AND stock_actual > 0
										ORDER BY codigo_categoria ASC, marca ASC, modelo ASC
										" );
			$consulta->execute();

			// Guardo el resultado del query en el array contenedor
			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) )
				$productos[] = $row;

			// Cuento la cantidad de productos encontrados
			$cantidad = count($productos);
			
			$categoria = $productos[0]["codigo_categoria"];
			
			// Ciclo para cargar la variable que retornara la tabla de los productos, ordenados por categoria
			$contador = 0;
			for( $i = 0; $i < $cantidad; $i++ ){

				// SE CREA EL TITULO DE LA CATEGORIA POR CADA PRIMER PRODUCTO
				if( $contador == 0 ){
					$lista .= 	"
								<span class='titulo'>".ucfirst($productos[$i]["categoria"])."</span>
								<br>
								<table cellspacing='0' id='TABLA_lista_productos'>
								";

					// EN EL CASO DE LOS TITULOS, SE CREA UNO ESPECIFICO PARA JUEGOS, POR NO SOMETERSE
					// AL MISMO TRATAMIENTO DE TODOS LOS DEMAS PRODUCTOS.
					if( $productos[$i]["categoria"] != "juego" ){
						if( $productos[$i]["stock_actual"] > 0 ){
							$lista .= "
									<thead>
										<tr>
											<th width='5%'>Stock</th>
											<th width='5%'>Codigo</th>
											<th width='10%'>Marca</th>
											<th width='15%'>Modelo</th>
											<th width='10%'>Color</th>
											<th class='costo' width='10%'>Precio de Costo</th>
											<th width='10%'>Precio de Venta</th>
											<th width='35%'>Descripcion</th>
										</tr>
									</thead>
									<tbody>
									";
						}else{
							continue;
						}

					}else{
						if( $productos[$i]["stock_actual"] > 0 ){
							$lista .= "
									<thead>
										<tr>
											<th>Numero</th>
											<th>Codigo Producto</th>
											<th>Descripcion</th>
											<th>Plataforma</th>
											<th class='costo'>Precio de Costo</th>
											<th>Precio de Venta</th>
										</tr>
									</thead>
									<tbody>
									";
						}else{
							continue;
						}					
					}	

					$contador++;			
				}

				// LO MISMO SE DA PARA EL CUERPO DE LA LISTA.
				if( $productos[$i]["categoria"] != "juego" ){
					
					// Se imprimen los productos de la categoria
					$lista .= 	"
										<tr>
											<td>".$productos[$i]["stock_actual"]."</td>
											<td>".$productos[$i]["codigo_producto"]."</td>
											<td>".ucfirst($productos[$i]["marca"])."</td>
											<td><span class='mostrar_foto' name='".$productos[$i]["foto"]."'>".$productos[$i]["modelo"]."</span></td>
											<td>".$productos[$i]["color"]."</td>
											<td class='costo'></td>
											<td>".number_format( intval($productos[$i]["precio_venta"]), 0, ' ', '.')."</td>
											<td>".$productos[$i]["descripcion"]."</td>
										</tr>
								
								";	
				}else{
					$lista .= 	"
										<tr>
											<td>".$productos[$i]["stock_actual"]."</td>
											<td>".$productos[$i]["codigo_producto"]."</td>
											<td>".$productos[$i]["descripcion"]."</td>
											<td>".$productos[$i]["plataforma"]."</td>
											<td class='costo'></td>
											<td>".$productos[$i]["precio_venta"]."</td>
										</tr>
								";		
				}

				$contador++;

				if( $i < $cantidad - 1 ){
					if( $productos[$i+1]["codigo_categoria"] != $categoria ){
						$categoria = $productos[$i+1]["codigo_categoria"];
						$contador = 0;
						$lista .= 	"
									</tbody>
								</table>
								<br>
								";
					}
				}else{
					$lista .= 	"
									</tbody>
								</table>
								<br>
								";
				}
			}
		

			return $lista;

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para devolver una tabla con la lista de productos por categoria
		public function lista_productos_por_categoria( $cod_categoria ){
			
			// Variables
			$lista = "";

			// Cuerpo
			// Abro la conexion a la base de datos
			$con = $this->conexion();

			if( $cod_categoria == 0 ){
				return $this->lista_productos();
			}else{

				// Primero cuento la cantidad de elementos que hay con el codigo de categoria
				$consulta = $con->prepare( "SELECT COUNT(*) AS cantidad FROM producto WHERE cod_categoria = ?" );
				$consulta->bindValue( 1, $cod_categoria );
				$consulta->execute();
				$temporal = $consulta->fetch( PDO::FETCH_ASSOC );
				$cantidad = $temporal['cantidad'];

				// Dependiendo del resultado, se dan las siguientes opciones
				if( $cantidad == 0 ){	
					$lista .= "No hay productos en existencia del codigo";
				}else{

					$productos = array();

					$consulta = $con->prepare( "SELECT 
												p.cod_producto AS codigo_producto,
												p.cod_categoria AS codigo_categoria, 
												procat.descripcion AS categoria,
												promar.descripcion AS marca,
												promod.descripcion AS modelo,
												procol.descripcion AS color,
												propla.descripcion AS plataforma,
												p.precio_venta AS precio_venta, 
												p.descripcion AS descripcion,
												p.stock_actual AS stock_actual,
												p.foto AS foto
											FROM producto AS p
											JOIN producto_categoria AS procat on p.cod_categoria = procat.cod_categoria
											JOIN producto_marca AS promar on p.cod_marca = promar.cod_marca
											JOIN producto_modelo AS promod on p.cod_modelo = promod.cod_modelo
											JOIN producto_color AS procol on p.cod_color = procol.cod_color
											JOIN producto_plataforma AS propla on p.cod_plataforma = propla.cod_plataforma
											WHERE p.cod_categoria = ".$cod_categoria."
											ORDER BY promar.descripcion ASC, modelo ASC
											" );
					$consulta->execute();


					while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) )
						$productos[] = $row;
					

					$cantidad = count($productos);
					
					$categoria = $productos[0]["codigo_categoria"];
					
					$contador = 0;
					for( $i = 0; $i < $cantidad; $i++ ){

						// Se crea el titulo de la categoria por cada primer producto
						if( $contador == 0 ){
							$lista .= 	"
										<span class='titulo'>".ucfirst($productos[$i]["categoria"])."</span>
										<br>
										<table cellspacing='0' id='TABLA_lista_productos'>
										";

							if( $productos[$i]["categoria"] != "juego" ){
								$lista .= 	"
											<thead>
												<tr>
													<th width='5%'>Stock</th>
													<th width='5%'>Codigo</th>
													<th width='10%'>Marca</th>
													<th width='15%'>Modelo</th>
													<th width='10%'>Color</th>
													<th class='costo' width='10%'>Precio de Costo</th>
													<th width='10%'>Precio de Venta</th>
													<th width='35%'>Descripcion</th>
												</tr>
											</thead>
											<tbody>
											";

							}else{
								$lista .= 	"
											<thead>
												<tr>
													<th>Stock</th>
													<th>Codigo Producto</th>
													<th>Descripcion</th>
													<th>Plataforma</th>
													<th class='costo'>Precio de Costo</th>
													<th>Precio de Venta</th>
												</tr>
											</thead>
											<tbody>
											";
							}

							$contador++;			
						}

						if( $productos[$i]["categoria"] != "juego" ){
							// Se imprimen los productos de la categoria
							$lista .= 	"
												<tr>
													<td>".$productos[$i]["stock_actual"]."</td>
													<td>".$productos[$i]["codigo_producto"]."</td>
													<td>".ucfirst($productos[$i]["marca"])."</td>
													<td><span class='mostrar_foto' name='".$productos[$i]["foto"]."'>".$productos[$i]["modelo"]."</span></td>
													<td>".$productos[$i]["color"]."</td>
													<td class='costo'></td>
													<td>".number_format( intval($productos[$i]["precio_venta"]), 0, ' ', '.')."</td>
													<td>".$productos[$i]["descripcion"]."</td>
												</tr>
										
										";	
						}else{
							$lista .= 	"
												<tr>
													<td>".$productos[$i]["stock_actual"]."</td>
													<td>".$productos[$i]["codigo_producto"]."</td>
													<td>".$productos[$i]["descripcion"]."</td>
													<td>".$productos[$i]["plataforma"]."</td>
													<td class='costo'></td>
													<td>".$productos[$i]["precio_venta"]."</td>
												</tr>
										";		
						}

						$contador++;

						if( $i < $cantidad - 1 ){
							if( $productos[$i+1]["codigo_categoria"] != $categoria ){
								$categoria = $productos[$i+1]["codigo_categoria"];
								$contador = 0;
								$lista .= 	"
											</tbody>
										</table>
										<br>
										";
							}
						}else{
							$lista .= 	"
											</tbody>
										</table>
										<br>
										";
						}
					}
				}

				return $lista;

			}

			// Cierra la conexion a la base de datos
			$con = null;
		}

		// Metodo para hacer la devolucion de una lista de productos de acuerdo a una busqueda
		public function lista_productos_busqueda( $texto ){

			// Variables
			$productos = array();
			$lista = "";

			// Cuerpo
			// Abro la conexion a la base de datos
			$con = $this->conexion();

			$consulta = $con->prepare( "SELECT COUNT(*) as cantidad FROM 
										(	SELECT 
												p.cod_producto AS codigo_producto,
												p.cod_categoria AS codigo_categoria, 
												procat.descripcion AS categoria,
												promar.descripcion AS marca,
												promod.descripcion AS modelo,
												procol.descripcion AS color,
												propla.descripcion AS plataforma,
												p.precio_venta AS precio_venta, 
												p.descripcion AS descripcion,
												p.foto AS foto
											FROM producto AS p
											JOIN producto_categoria AS procat on p.cod_categoria = procat.cod_categoria
											JOIN producto_marca AS promar on p.cod_marca = promar.cod_marca
											JOIN producto_modelo AS promod on p.cod_modelo = promod.cod_modelo
											JOIN producto_color AS procol on p.cod_color = procol.cod_color
											JOIN producto_plataforma AS propla on p.cod_plataforma = propla.cod_plataforma
											WHERE 	promar.descripcion LIKE '%".$texto."%' OR
													promod.descripcion LIKE '%".$texto."%' OR
													propla.descripcion LIKE '%".$texto."%' OR
													p.descripcion LIKE '%".$texto."%' OR
													p.descripcion LIKE UPPER('%".$texto."%')	
											ORDER BY promar.descripcion ASC) AS tabla
										" );
			$consulta->execute();
			$temporal = $consulta->fetch( PDO::FETCH_ASSOC );
			$cantidad = $temporal['cantidad'];

			if( $cantidad == 0 ){	
				
				$lista .= "No se han encontrado coincidencias";
			}else{

				$consulta = $con->prepare( "SELECT 
												p.cod_producto AS codigo_producto,
												p.cod_categoria AS codigo_categoria, 
												procat.descripcion AS categoria,
												promar.descripcion AS marca,
												promod.descripcion AS modelo,
												procol.descripcion AS color,
												propla.descripcion AS plataforma,
												p.precio_venta AS precio_venta,
												p.stock_actual as stock_actual, 
												p.descripcion AS descripcion,
												p.foto AS foto
											FROM producto AS p
											JOIN producto_categoria AS procat on p.cod_categoria = procat.cod_categoria
											JOIN producto_marca AS promar on p.cod_marca = promar.cod_marca
											JOIN producto_modelo AS promod on p.cod_modelo = promod.cod_modelo
											JOIN producto_color AS procol on p.cod_color = procol.cod_color
											JOIN producto_plataforma AS propla on p.cod_plataforma = propla.cod_plataforma
											WHERE 	promar.descripcion LIKE '%".$texto."%' OR
													promod.descripcion LIKE '%".$texto."%' OR
													propla.descripcion LIKE '%".$texto."%' OR
													p.descripcion LIKE '%".$texto."%' OR
													p.descripcion LIKE UPPER('%".$texto."%')
											ORDER BY p.cod_categoria ASC, marca ASC, modelo ASC
											" );
				$consulta->execute();


				while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) ){

					$productos[] = $row;
				}

				$cantidad = count($productos);
				$categoria = $productos[0]["codigo_categoria"];
				$contador = 0;
				for( $i = 0; $i < $cantidad; $i++ ){

					// Se crea el titulo de la categoria por cada primer producto
					if( $contador == 0 ){
						$lista .= 	"
									<span class='titulo'>".ucfirst($productos[$i]["categoria"])."</span>
									<br>
									<table cellspacing='0' id='TABLA_lista_productos'>
									";

						if( $productos[$i]["categoria"] != "juego" ){
							$lista .= 	"
										<thead>
											<tr>
												<th width='5%'>Stock</th>
												<th width='5%'>Codigo</th>
												<th width='10%'>Marca</th>
												<th width='15%'>Modelo</th>
												<th width='10%'>Color</th>
												<th class='costo' width='10%'>Precio de Costo</th>
												<th width='10%'>Precio de Venta</th>
												<th width='35%'>Descripcion</th>
											</tr>
										</thead>
										<tbody>
										";

						}else{
							$lista .= 	"
										<thead>
											<tr>
												<th>Numero</th>
												<th>Codigo Producto</th>
												<th>Descripcion</th>
												<th>Plataforma</th>
												<th class='costo'>Precio de Costo</th>
												<th>Precio de Venta</th>
											</tr>
										</thead>
										<tbody>
										";
						}

						$contador++;			
					}

					if( $productos[$i]["categoria"] != "juego" ){
						// Se imprimen los productos de la categoria
						$lista .= 	"
											<tr>
											<td>".$productos[$i]["stock_actual"]."</td>
											<td>".$productos[$i]["codigo_producto"]."</td>
											<td>".ucfirst($productos[$i]["marca"])."</td>
											<td><span class='mostrar_foto' name='".$productos[$i]["foto"]."'>".$productos[$i]["modelo"]."</span></td>
											<td>".$productos[$i]["color"]."</td>
											<td class='costo'></td>
											<td>".number_format( intval($productos[$i]["precio_venta"]), 0, ' ', '.')."</td>
											<td>".$productos[$i]["descripcion"]."</td>
										</tr>
									
									";	
					}else{
						$lista .= 	"
											<tr>
											<td>".$productos[$i]["stock_actual"]."</td>
											<td>".$productos[$i]["codigo_producto"]."</td>
											<td>".$productos[$i]["descripcion"]."</td>
											<td>".$productos[$i]["plataforma"]."</td>
											<td class='costo'></td>
											<td>".$productos[$i]["precio_venta"]."</td>
										</tr>
									";		
					}

					$contador++;

					if( $i < $cantidad - 1 ){
						if( $productos[$i+1]["codigo_categoria"] != $categoria ){
							$categoria = $productos[$i+1]["codigo_categoria"];
							$contador = 0;
							$lista .= 	"
										</tbody>
									</table>
									<br>
									";
						}
					}else{
						$lista .= 	"
										</tbody>
									</table>
									<br>
									";
					}
				}
			}

			return $lista;
		}

		// Metodo para crear un select con las posibles forma de pago a la hora de realizar la compra de un producto
		public function lista_formas_pago( $cod_producto ){

			$formas_pago = array( "Caja Fuerte", "Cuenta Corriente", "Tarjeta de Credito" );

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='formas_pago' id='formas_pago_".$cod_producto."'>
						";

			// Cuento la cantidad de registros traidos
			$cantidad = count($formas_pago);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){
				$aux = $i + 1;
				$select .= "<option value='".$aux."'>".$formas_pago[$i]."</option>";
			}

			$select .= 	"</select>";

			return $select;
		}

		// Metodo para devolver una tabla con la lista de productos por categoria, 
		// exclusivo para la pagina de compras de productos.
		public function lista_productos_familia_compra( $cod_familia ){
			
			// VARIABLES
			$lista = "";

			// CUERPO
			// Abro la conexion a la base de datos
			$con = $this->conexion();

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
												ORDER BY codigo_categoria ASC, marca ASC, modelo ASC
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
									<br><br>
									<table cellspacing='0' id='TABLA_lista_productos_familia'>
										<thead>
											<tr>
												<th width='5%'>Seleccion</th>
												<th width='5%'>Codigo</th>
												<th width='5%'>Marca</th>
												<th width='20%'>Modelo</th>
												<th width='15%'>Color</th>
												<th width='10%'>Plataforma</th>
												<th width='40%'>Descripcion</th>
											</tr>
										</thead>

										<tbody>
									";
					}

					$lista .= 	"
											<tr>
												<td><input type='checkbox' class='check_comprar' name='".$productos[$i]["codigo_producto"]."'></td>
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
														
														<!-- CAMPO PARA CARGA DEL COSTO. -->
														<span>Ingrese el costo del producto: </span>
														<span style='margin-right:20px'><input type='text' id='costo_producto_".$productos[$i]["codigo_producto"]."' size='10' value='".number_format($productos[$i]["precio_venta"], 0, ' ', '.')."' onkeyup='format(this)' onchange='format(this)''></span>
														
														<!-- SELECT PARA LA SELECCION DE LA CANTIDAD DE PRODUCTOS A COMPRAR. -->
														<span style='text-align: center'>Ingrese la cantidad a comprar: </span>
														<span style='margin-right:20px'>".  
															$lista_cantidad = $this->listar_cantidad( $productos[$i]["codigo_producto"] )
														."</span>
														
														<br><br>
														
														<!-- SELECT PARA LA SELECCION DE LA FORMA DE PAGO DE LA COMPRA. -->
														<span>Ingrese la forma de Pago</span>
														<span>".
															$lista_formas_pago = $this->lista_formas_pago( $productos[$i]["codigo_producto"] )
														."</span>

														<!-- BOTON PARA PROCESAR LA COMPRA. -->
														<span><input type='button' id='btn_comprar' name='".$productos[$i]["codigo_producto"]."' class='boton' value='Comprar'></span>
													
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
		// exclusivo para la pagina de compras de productos.
		public function lista_productos_categoria_compra( $cod_categoria ){
			
			// VARIABLES
			$lista = "";

			// CUERPO
			// Abro la conexion a la base de datos
			$con = $this->conexion();

			// Primero cuento la cantidad de productos que hay con el codigo de familia elegido
			$consulta = $con->prepare( "SELECT COUNT(*) AS cantidad FROM producto WHERE cod_categoria = ?" );
			$consulta->bindValue( 1, $cod_categoria );
			$consulta->execute();
			$temporal = $consulta->fetch( PDO::FETCH_ASSOC );
			$cantidad = $temporal['cantidad'];

			// Dependiendo del resultado, se dan las siguientes opciones
			if( $cantidad == 0 ){	
				
				$lista .= "No hay productos en existencia de la categoria seleccionada";
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
												WHERE p.cod_categoria = ".$cod_categoria."
												ORDER BY codigo_categoria ASC, marca ASC, modelo ASC
											");

				$consulta->execute();


				while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) )
					$productos[] = $row;
				

				$cantidad = count( $productos );
				
				$contador = 0;
				for( $i = 0; $i < $cantidad; $i++ ){

					// Se crea el titulo de la categoria por cada primer producto
					if( $contador == 0 ){
						$lista .= 	"
									<br><br>
									<table cellspacing='0' id='TABLA_lista_productos_familia'>
										<thead>
											<tr>
												<th width='5%'>Seleccion</th>
												<th width='5%'>Codigo</th>
												<th width='5%'>Marca</th>
												<th width='20%'>Modelo</th>
												<th width='15%'>Color</th>
												<th width='10%'>Plataforma</th>
												<th width='40%'>Descripcion</th>
											</tr>
										</thead>

										<tbody>
									";
					}

					$lista .= 	"
											<tr>
												<td><input type='checkbox' class='check_comprar' name='".$productos[$i]["codigo_producto"]."'></td>
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
														
														<!-- CAMPO PARA CARGA DEL COSTO. -->
														<span>Ingrese el costo del producto: </span>
														<span style='margin-right:20px'><input type='text' id='costo_producto_".$productos[$i]["codigo_producto"]."' size='10' onkeyup='format(this)' onchange='format(this)''></span>
														
														<!-- SELECT PARA LA SELECCION DE LA CANTIDAD DE PRODUCTOS A COMPRAR. -->
														<span style='text-align: center'>Ingrese la cantidad a comprar: </span>
														<span style='margin-right:20px'>".  
															$lista_cantidad = $this->listar_cantidad( $productos[$i]["codigo_producto"] )
														."</span>
														
														<br><br>
														
														<!-- SELECT PARA LA SELECCION DE LA FORMA DE PAGO DE LA COMPRA. -->
														<span>Ingrese la forma de Pago</span>
														<span>".
															$lista_formas_pago = $this->lista_formas_pago( $productos[$i]["codigo_producto"] )
														."</span>

														<!-- BOTON PARA PROCESAR LA COMPRA. -->
														<span><input type='button' id='btn_comprar' name='".$productos[$i]["codigo_producto"]."' class='boton' value='Comprar'></span>
													
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

		public function lista_productos_marca_compra( $cod_categoria, $cod_marca ){
			
			// VARIABLES
			$lista = "";

			// CUERPO
			// Abro la conexion a la base de datos
			$con = $this->conexion();

			// Primero cuento la cantidad de productos que hay con el codigo de familia elegido
			$consulta = $con->prepare( "
										SELECT COUNT(*) AS cantidad 
										FROM producto 
										WHERE cod_categoria = ? 
										AND cod_marca = ?" );
			$consulta->bindValue( 1, $cod_categoria );
			$consulta->bindValue( 2, $cod_marca );
			$consulta->execute();
			$temporal = $consulta->fetch( PDO::FETCH_ASSOC );
			$cantidad = $temporal['cantidad'];

			// Dependiendo del resultado, se dan las siguientes opciones
			if( $cantidad == 0 ){	
				
				$lista .= "<br><br>No hay productos en existencia de la marca seleccionada";
			}else{

				// Inicializacion de variables
				$productos = array();
				$cantidad = 0;

				$consulta = $con->prepare( 	"
												SELECT 
													p.cod_familia AS codigo_familia,
													p.cod_producto AS codigo_producto,
													p.cod_categoria AS codigo_categoria,
													p.cod_marca AS codigo_marca, 
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
												WHERE p.cod_categoria = ".$cod_categoria." 
												AND p.cod_marca = ".$cod_marca."
												ORDER BY modelo ASC
											");

				$consulta->execute();


				while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) )
					$productos[] = $row;
				

				$cantidad = count( $productos );
				
				$contador = 0;
				for( $i = 0; $i < $cantidad; $i++ ){

					// Se crea el titulo de la categoria por cada primer producto
					if( $contador == 0 ){
						$lista .= 	"
									<br><br>
									<table cellspacing='0' id='TABLA_lista_productos_familia'>
										<thead>
											<tr>
												<th width='5%'>Seleccion</th>
												<th width='5%'>Codigo</th>
												<th width='5%'>Marca</th>
												<th width='20%'>Modelo</th>
												<th width='15%'>Color</th>
												<th width='10%'>Plataforma</th>
												<th width='40%'>Descripcion</th>
											</tr>
										</thead>

										<tbody>
									";
					}

					$lista .= 	"
											<tr>
												<td><input type='checkbox' class='check_comprar' name='".$productos[$i]["codigo_producto"]."'></td>
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
														
														<!-- CAMPO PARA CARGA DEL COSTO. -->
														<span>Ingrese el costo del producto: </span>
														<span style='margin-right:20px'><input type='text' id='costo_producto_".$productos[$i]["codigo_producto"]."' size='10' onkeyup='format(this)' onchange='format(this)''></span>
														
														<!-- SELECT PARA LA SELECCION DE LA CANTIDAD DE PRODUCTOS A COMPRAR. -->
														<span style='text-align: center'>Ingrese la cantidad a comprar: </span>
														<span style='margin-right:20px'>".  
															$lista_cantidad = $this->listar_cantidad( $productos[$i]["codigo_producto"] )
														."</span>
														
														<br><br>
														
														<!-- SELECT PARA LA SELECCION DE LA FORMA DE PAGO DE LA COMPRA. -->
														<span>Ingrese la forma de Pago</span>
														<span>".
															$lista_formas_pago = $this->lista_formas_pago( $productos[$i]["codigo_producto"] )
														."</span>

														<!-- BOTON PARA PROCESAR LA COMPRA. -->
														<span><input type='button' id='btn_comprar' name='".$productos[$i]["codigo_producto"]."' class='boton' value='Comprar'></span>
													
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








































		// METODO PARA PROCESAR Y REGISTRAR LA COMPRA DE UN PRODUCTO.
		public function procesar_compra( $costo, $cantidad, $forma_pago, $cod_producto ){

			// ABRO LA CONEXION A LA BASE DE DATOS
			$con = $this->conexion();

			// CALCULO DEL TOTAL DE LA COMPRA
			$total = $costo * $cantidad;

			// PREPARO EL QUERY DE INSERCION
			$insert_compra = $con->prepare(	"
											INSERT INTO compra( 
												fecha_compra, 
												hora_compra,	
												cod_producto, 		
												costo, 	
												cantidad, 
												total, 	
												cod_cuenta )
											VALUES( 
												NOW(), 		
												NOW()::TIME(0), 
												".$cod_producto.", 	
												".$costo.",
												".$cantidad.", 
												".$total.", 
												".$forma_pago."	)
											" );

			// EJECUTO EL QUERY
			$insert_compra->execute();

			// CUENTO Y VERIFICO EL RESULTADO DE LA INSERCION
			$resultado_insert_compra = $insert_compra->rowCount();

			// SI LA INSERCION FUE EXITOSA
			if( $resultado_insert_compra == 1 ){
			

				//TRAIGO DE LA BASE DE DATO LA INFORMACION RECIENTEMENTE INSERTADA 
				//PARA GRABAR EL MOVIMIENTO EN LA CUENTA ELEGIDA
				$consulta_compra = $con->query( "SELECT * FROM compra ORDER BY cod_compra DESC LIMIT 1" );
				$registro_compra = $consulta_compra->fetch( PDO::FETCH_ASSOC );

				$insert_movimiento = $con->prepare( 	"
														INSERT INTO movimiento ( 
															fecha_movimiento, 
															hora_movimiento, 
															caja_fuerte, 
															cta_cte, 
															caja_chica, 
															tarjeta_disponible, 
															tarjeta_deuda, 
															cod_tipo_mov )
														VALUES ( 
															'".$registro_compra['fecha_compra']."', 
															'".$registro_compra['hora_compra']."', 
															:caja_fuerte 		, 
															:cta_cte 			, 
															0 					, 
															:tarjeta_disponible , 
															:tarjeta_deuda 		, 
															2 )
														");

				// DE ACUERDO A LA FORMA DE PAGO QUE SE SELECCIONO, SE DEFINE LA CUENTA 
				// QUE VA A SER AFECTADA
				switch ( $forma_pago ) {
					case 1:
						$insert_movimiento->bindValue( ':caja_fuerte', $total );
						$insert_movimiento->bindValue( ':cta_cte', 0 );
						$insert_movimiento->bindValue( ':tarjeta_disponible', 0 );
						$insert_movimiento->bindValue( ':tarjeta_deuda', 0 );
						break;

					case 2:
						$insert_movimiento->bindValue( ':caja_fuerte', 0 );
						$insert_movimiento->bindValue( ':cta_cte', $total );
						$insert_movimiento->bindValue( ':tarjeta_disponible', 0 );
						$insert_movimiento->bindValue( ':tarjeta_deuda', 0 );
						break;

					case 3:
						$insert_movimiento->bindValue( ':caja_fuerte', 0 );
						$insert_movimiento->bindValue( ':cta_cte', 0 );
						$insert_movimiento->bindValue( ':tarjeta_disponible', $total );
						$insert_movimiento->bindValue( ':tarjeta_deuda', 0 );
						break;
				}

				// EJECUTO EL QUERY
				$insert_movimiento->execute();

				// CUENTO Y VERIFICO EL RESULTADO DE LA INSERCION
				$resultado_insert_movimiento = $insert_movimiento->rowCount();

				// SI SE AFECTO UNA CUENTA CON EXITO
				if( $resultado_insert_movimiento == 1 ){

					// TRAIGO DE LA BASE DE DATOS LA INFORMACION DEL MOVIMIENTO INSERTADO
					$consulta_movimiento = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
					$registro_movimiento = $consulta_movimiento->fetch( PDO::FETCH_ASSOC );

					// TRAIGO DE LA BASE DE DATOS EL RESUMEN DE LAS DISTINTAS CUENTAS
					$consulta_saldos = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
					$registro_saldos = $consulta_saldos->fetch( PDO::FETCH_ASSOC );

					// PREPARO EL QUERY PARA MODIFICAR EL SALDO DE LA CUENTA QUE SE AFECTO EN EL MOVIMIENTO
					$insert_saldo = $con->prepare( 	"
													INSERT INTO saldos ( 
														cod_movimiento,
														fecha_saldo, 
														hora_saldo, 
														caja_fuerte, 
														cta_cte, 
														caja_chica, 
														tarjeta_disponible, 
														tarjeta_deuda
													)
													VALUES ( 
														".$registro_movimiento['cod_movimiento'].",
														'".$registro_movimiento['fecha_movimiento']."', 
														'".$registro_movimiento['hora_movimiento']."',
														:caja_fuerte, 
														:cta_cte, 
														'".$registro_saldos['caja_chica']."', 
														:tarjeta_disponible, 
														:tarjeta_deuda
													)
													");

					// DE ACUERDO A LA FORMA DE PAGO, SE AFECTARA EL VALOR DE LA CUENTA CORRESPONDIENTE.
					switch ( $forma_pago ) {
						case 1:
							$saldo = $registro_saldos['caja_fuerte'] - $total;
							
							$insert_saldo->bindValue( ':caja_fuerte', $saldo );
							$insert_saldo->bindValue( ':cta_cte', $registro_saldos['cta_cte'] );
							$insert_saldo->bindValue( ':tarjeta_disponible', $registro_saldos['tarjeta_disponible'] );
							$insert_saldo->bindValue( ':tarjeta_deuda', $registro_saldos['tarjeta_deuda'] );
							break;

						case 2:
							$saldo = $registro_saldos['cta_cte'] - $total;
							
							$insert_saldo->bindValue( ':caja_fuerte', $registro_saldos['caja_fuerte'] );
							$insert_saldo->bindValue( ':cta_cte', $saldo );
							$insert_saldo->bindValue( ':tarjeta_disponible', $registro_saldos['tarjeta_disponible'] );
							$insert_saldo->bindValue( ':tarjeta_deuda', $registro_saldos['tarjeta_deuda'] );
							break;

						case 3:
							$saldo_disponible = $registro_saldos['tarjeta_disponible'] - $total;
							$saldo_deuda = $registro_saldos['tarjeta_disponible'] + $total;

							$insert_saldo->bindValue( ':caja_fuerte', $registro_saldos['caja_fuerte'] );
							$insert_saldo->bindValue( ':cta_cte', $registro_saldos['cta_cte'] );
							$insert_saldo->bindValue( ':tarjeta_disponible', $saldo_disponible );
							$insert_saldo->bindValue( ':tarjeta_deuda', $saldo_deuda );
							break;
					}

					// SE EJECUTA EL QUERY
					$insert_saldo->execute();

					// CUENTO Y VERIFICO QUE LA INSERCION HAYA SIDO EXITOSA
					$resultado_insert_saldos = $insert_saldo->rowCount();

					// SI SE MODIFICO EL SALDO EXITOSAMENTE
					if( $resultado_insert_saldos == 1 ){

						// &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
						// TENGO QUE AUMENTAR EL STOCK DEL PRODUCTO EN LA CANTIDAD COMPRADA
						// &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
						
						// TRAIGO DE LA BASE DE DATOS EL VALOR DE STOCK ACTUAL DEL PRODUCTO
						$consulta_stock = $con->prepare( "SELECT stock_actual FROM producto WHERE cod_producto = ?" );
						$consulta_stock->bindvalue( 1, $cod_producto );
						$consulta_stock->execute();
						$stock_producto = $consulta_stock->fetch( PDO::FETCH_ASSOC );
						$stock_actualizado = $stock_producto['stock_actual'] + $cantidad;

						$update_stock = $con->prepare( "UPDATE producto SET stock_actual = ? WHERE cod_producto = ?" );
						$update_stock->bindvalue( 1, $stock_actualizado );
						$update_stock->bindvalue( 2, $cod_producto );
						$update_stock->execute();

						$resultado_update_stock = $update_stock->rowCount();

						if( $resultado_update_stock == 1 ){
							return "La compra ha sido procesada exitosamente!";
						}
					}
				}
			}
		}

		// Metodo para devolver un combo box con la lista de cuentas:
		// 1) Caja Fuerte
		// 2) Cuenta Corriente
		// 3) Caja Chica
		// 4) Tarjeta Disponible
		// 4) Tarjeta Deuda
		public function listar_cuentas(){

			$cuentas = array( "Caja Fuerte", "Cuenta Corriente", "Caja Chica", "Tarjeta Disponible", "Tarjeta Deuda" );

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='cuentas' id='cuentas'>
							<option value='0'>-Elegir Cuenta-</option>
						";

			// Cuento la cantidad de registros traidos
			$cantidad = count($cuentas);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){
				$aux = $i + 1;
				$select .= "<option value='".$aux."'>".$cuentas[$i]."</option>";
			}

			$select .= 	"</select>";

			return $select;
		}

		// Metodo para listar las posibles operaciones a la hora de modificar saldos
		// de cuentas:
		// 1) Cargar Cuenta
		// 2) Retirar de Cuenta
		// 3) Trasladar a Cuenta
		public function listar_operaciones(){

			// Creo el array con las opciones de operaciones
			$operaciones = array( "Cargar Cuenta", "Retirar de Cuenta", "Trasladar a otra Cuenta" );

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='operaciones' id='operaciones'>
							<option value='0'>-Elegir Operacion-</option>
						";

			// Cuento la cantidad de registros traidos
			$cantidad = count($operaciones);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){
				$aux = $i + 1;
				$select .= "<option value='".$aux."'>".$operaciones[$i]."</option>";
			}

			$select .= 	"</select>";

			return $select;		
		}

		// Metodo para devolver un select con las cuentas que a las cuales
		// se desea transferir dinero
		public function listar_cuentas_a_transferir(){

			$cuentas = array( "Caja Fuerte", "Cuenta Corriente", "Caja Chica", "Tarjeta Disponible", "Tarjeta Deuda" );

			// Creo la variable que va a contener el select a ser devuelto
			$select = "";
			$select .= 	"
						<select name='cuentas_a_transferir' id='cuentas_a_transferir'>
							<option value='0'>-Elegir Cuenta-</option>
						";

			// Cuento la cantidad de registros traidos
			$cantidad = count($cuentas);

			// Relleno las opciones del select con los valores en el array
			for($i = 0; $i < $cantidad; $i++){
				$aux = $i + 1;
				$select .= "<option value='".$aux."'>".$cuentas[$i]."</option>";
			}

			$select .= 	"</select>";

			return $select;
		}

		// Metodo para para devolver el saldo actualizado de una cuenta
		public function saldo_cuenta( $cod_cuenta ){
			
			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			$saldo = array();

			// Traigo los valores de la tabla saldo
			$consulta = $con->prepare( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
			$consulta->execute();	

			while( $row = $consulta->fetch( PDO::FETCH_ASSOC ) )
				$saldo[] = $row;

			switch ( $cod_cuenta ) {
				case 1:
					return number_format( $saldo[0]["caja_fuerte"], 0, ' ', '.' );
					break;

				case 2:
					return number_format( $saldo[0]["cta_cte"], 0, ' ', '.' );
					break;

				case 3:
					return number_format( $saldo[0]["caja_chica"], 0, ' ', '.' );
					break;

				case 4:
					return number_format( $saldo[0]["tarjeta_disponible"], 0, ' ', '.' );
					break;

				case 5:
					return number_format( $saldo[0]["tarjeta_deuda"], 0, ' ', '.' );
					break;
			}
		}

		// Metodo para modificar el saldo de una cuenta
		public function modificar_saldos( $cod_cuenta, $cod_operacion, $cod_cuenta_a_transferir, $monto ){
			
			// Variables
			$cuentas = array( " ", "caja_fuerte", "cta_cte", "caja_chica", "tarjeta_disponible", "tarjeta_deuda" );
			$saldo = array();

			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			// Si el codigo de operacion NO corresponde al de transferencia
			if( $cod_operacion != 3 ){

				// Si el codigo de operacion corresponde a "Cargar Cuenta"
				if( $cod_operacion == 1 ){
					switch ( $cod_cuenta ) {
						// CAJA_FUERTE
						case 1:

							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo = $saldo[ "caja_fuerte" ] + $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$nuevo_saldo.",
																					".$saldo[ "cta_cte" ].",
																					".$saldo[ "caja_chica" ].",
																					".$saldo[ "tarjeta_disponible" ].",
																					".$saldo[ "tarjeta_deuda" ]." 
																				)
															");


								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
								
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;

						// CTA_CTE
						case 2:
							
							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo = $saldo[ "cta_cte" ] + $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$nuevo_saldo.",
																					".$saldo[ "caja_chica" ].",
																					".$saldo[ "tarjeta_disponible" ].",
																					".$saldo[ "tarjeta_deuda" ]." 
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;

						// CAJA_CHICA
						case 3:
							
							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo = $saldo[ "caja_chica" ] + $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$saldo[ "cta_cte" ].",
																					".$nuevo_saldo.",
																					".$saldo[ "tarjeta_disponible" ].",
																					".$saldo[ "tarjeta_deuda" ]." 
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}
							break;

						// TARJETA_DISPONIBLE
						case 4:
							
							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo_disponible = $saldo[ "tarjeta_disponible" ] + $monto;
								$nuevo_saldo_deuda      = $saldo[ "tarjeta_deuda" ] - $monto;
								
								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$saldo[ "cta_cte" ].",
																					".$saldo[ "caja_chica" ].",
																					".$nuevo_saldo_disponible.",
																					".$nuevo_saldo_deuda."
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;

						// TARJETA_DEUDA
						case 5:
							
							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta TARJETA_DEUDA
								$nuevo_saldo_deuda      = $saldo[ "tarjeta_deuda" ] + $monto;
								$nuevo_saldo_disponible = $saldo[ "tarjeta_disponible" ] - $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$saldo[ "cta_cte" ].",
																					".$saldo[ "caja_chica" ].",
																					".$nuevo_saldo_disponible.",
																					".$nuevo_saldo_deuda."
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
								
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;
					}
				}else{
					switch ( $cod_cuenta ) {
						
						case 1:

							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo = $saldo[ "caja_fuerte" ] - $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$nuevo_saldo.",
																					".$saldo[ "cta_cte" ].",
																					".$saldo[ "caja_chica" ].",
																					".$saldo[ "tarjeta_disponible" ].",
																					".$saldo[ "tarjeta_deuda" ]." 
																				)
															");


								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;

						case 2:
							
							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo = $saldo[ "cta_cte" ] - $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$nuevo_saldo.",
																					".$saldo[ "caja_chica" ].",
																					".$saldo[ "tarjeta_disponible" ].",
																					".$saldo[ "tarjeta_deuda" ]." 
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;

						case 3:

							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo = $saldo[ "caja_chica" ] - $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$saldo[ "cta_cte" ].",
																					".$nuevo_saldo.",
																					".$saldo[ "tarjeta_disponible" ].",
																					".$saldo[ "tarjeta_deuda" ]." 
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;

						case 4:

							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta CAJA_FUERTE
								$nuevo_saldo_disponible = $saldo[ "tarjeta_disponible" ] - $monto;
								$nuevo_saldo_deuda      = $saldo[ "tarjeta_deuda" ] + $monto;
								
								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$saldo[ "cta_cte" ].",
																					".$saldo[ "caja_chica" ].",
																					".$nuevo_saldo_disponible.",
																					".$nuevo_saldo_deuda."
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;

						case 5:

							$cargar_movimiento = $this->modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto );

							// Si el movimiento se cargo exitosamente
							if( $cargar_movimiento ){

								// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
								$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
								$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

								// Traigo el ultimo registro de la tabla saldo y guardo en el array 
								$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
								$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

								// Aumento el valor de cuenta TARJETA_DEUDA
								$nuevo_saldo_deuda      = $saldo[ "tarjeta_deuda" ] - $monto;
								$nuevo_saldo_disponible = $saldo[ "tarjeta_disponible" ] + $monto;

								// Inserto un nuevo registro en la tabla SALDOS, con el nuevo valor de la cuenta CAJA_FUERTE
								$insercion = $con->prepare( "	
															INSERT INTO saldos 	( 
																					cod_movimiento,
															 						fecha_saldo,
															 						hora_saldo,
															 						caja_fuerte,
															 						cta_cte,
															 						caja_chica,
															 						tarjeta_disponible,
															 						tarjeta_deuda
															 					)
															VALUES 				( 			
																					".$movimiento[ "cod_movimiento" ].",	
																					'".$movimiento[ "fecha_movimiento" ]."', 	
																					'".$movimiento[ "hora_movimiento" ]."', 
																					".$saldo[ "caja_fuerte" ].",
																					".$saldo[ "cta_cte" ].",
																					".$saldo[ "caja_chica" ].",
																					".$nuevo_saldo_disponible.",
																					".$nuevo_saldo_deuda."
																				)
															");

								$insercion->execute();

								// Se comprueba que se haya insertado correctamente el registro en la base de datos
								$cantidad = $insercion->rowCount();
								
								if( $cantidad > 0 ){

									return "El saldo ha sido modificado de manera exitosa!";
								}else{

									return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
								}
								
							}else{
								
								return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
							}

							break;
					}
				}
			}else{

				// Primero creo el movimiento de retirar de la cuenta elegida
				$movimiento_retirar = $this->modificar_saldos_cargar_movimiento( 2, $cod_cuenta, $monto );

				// Si el movimiento se cargo exitosamente
				if( $movimiento_retirar ){

					// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
					$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
					$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

					// Traigo el ultimo registro de la tabla saldo y guardo en el array 
					$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
					$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

					// Selecciono la cuenta de la cual voy a retirar los fondos
					$cuenta = $cuentas[ $cod_cuenta ];

					// Se resta a la cuenta seleccionada, el monto ingresado
					$nuevo_saldo = $saldo[ "$cuenta" ] - $monto;

					$sql = 	"INSERT INTO saldos (
													cod_movimiento,
							 						fecha_saldo,
							 						hora_saldo,
							 						caja_fuerte,
							 						cta_cte,
							 						caja_chica,
							 						tarjeta_disponible,
							 						tarjeta_deuda
							 					)
							VALUES 				( 			
													".$movimiento[ "cod_movimiento" ].",	
													'".$movimiento[ "fecha_movimiento" ]."', 	
													'".$movimiento[ "hora_movimiento" ]."',

							";

					for ( $i = 1; $i < 6; $i++ ) { 
						
						if( $i != 5 ){
							
							if( $i == $cod_cuenta )	{
								
								$sql .= " ". $nuevo_saldo .", "; 	
							}else{
								
								$sql .= " ". $saldo[ "$cuentas[$i]" ] .", ";
							}

						}else{
							
							if( $i == $cod_cuenta )	{
								
								$sql .= " ". $nuevo_saldo .") "; 	
							}else{
								
								$sql .= " ". $saldo[ "$cuentas[$i]" ] .") ";
							}
						}
					}
					
					$insercion = $con->prepare( $sql );	
					$insercion->execute();

					// Se comprueba que se haya insertado correctamente el registro en la base de datos
					$cantidad = $insercion->rowCount();
				}else{
					
					return "No se ha podido llevar a cabo la modificacion.  Favor intentelo nuevamente";
				}

				// Luego creo el movimiento de carga a la cuenta elegida
				$movimiento_cargar = $this->modificar_saldos_cargar_movimiento( 1, $cod_cuenta_a_transferir, $monto );
				
				// Si el movimiento se cargo exitosamente
				if( $movimiento_cargar ){

					// Traigo el ultimo movimiento, ya que voy a necesitar el codigo de movimiento, la fecha y la hora para guardar en la modificacion de saldo
					$consulta = $con->query( "SELECT * FROM movimiento ORDER BY cod_movimiento DESC LIMIT 1" );
					$movimiento = $consulta->fetch( PDO::FETCH_ASSOC );

					// Traigo el ultimo registro de la tabla saldo y guardo en el array 
					$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_actualizacion DESC LIMIT 1" );
					$saldo = $consulta->fetch( PDO::FETCH_ASSOC );

					// Selecciono la cuenta de la cual voy a retirar los fondos
					$cuenta = $cuentas[ $cod_cuenta_a_transferir ];

					// Se resta a la cuenta seleccionada, el monto ingresado
					$nuevo_saldo = $saldo[ "$cuenta" ] + $monto;

					$sql = 	"INSERT INTO saldos (
													cod_movimiento,
							 						fecha_saldo,
							 						hora_saldo,
							 						caja_fuerte,
							 						cta_cte,
							 						caja_chica,
							 						tarjeta_disponible,
							 						tarjeta_deuda
							 					)
							VALUES 				( 			
													".$movimiento[ "cod_movimiento" ].",	
													'".$movimiento[ "fecha_movimiento" ]."', 	
													'".$movimiento[ "hora_movimiento" ]."',

							";

					for ( $i = 1; $i < 6; $i++ ) { 
						
						if( $i != 5 ){
							
							if( $i == $cod_cuenta_a_transferir )	{
								
								$sql .= " ". $nuevo_saldo .", "; 	
							}else{
								
								$sql .= " ". $saldo[ "$cuentas[$i]" ] .", ";
							}

						}else{
							
							if( $i == $cod_cuenta_a_transferir )	{
								
								$sql .= " ". $nuevo_saldo .") "; 	
							}else{
								
								$sql .= " ". $saldo[ "$cuentas[$i]" ] .") ";
							}
						}
					}
					
					$insercion = $con->prepare( $sql );	
					$insercion->execute();

					// Se comprueba que se haya insertado correctamente el registro en la base de datos
					$cantidad = $insercion->rowCount();
					
					if( $cantidad > 0 ){

						return "El saldo ha sido trasladado de manera exitosa!";
					}else{

						return "No se ha podido llevar a cabo el traslado.  Favor intentelo nuevamente";
					}
				}else{
					
					return "No se ha podido llevar a cabo el traslado.  Favor intentelo nuevamente";
				}
			}
		}

		// Mis operaciones son CARGAR CUENTA, SACAR DE CUENTA y TRASLADAR A OTRA CUENTA... 1 2 y 3 respectivamente
		// Primero tengo que afectar la tabla movimiento, donde se guarda el monto correspondiente a la cuenta que se afecta
		// Luego tengo que modificar el monto en la tabla saldos
		public function modificar_saldos_cargar_movimiento( $cod_operacion, $cod_cuenta, $monto ){
			
			// Array con las cuentas a las cuales se puede insertar los saldos
			$cuentas = array( " ", "caja_fuerte", "cta_cte", "caja_chica", "tarjeta_disponible", "tarjeta_deuda" );
			$cuenta = $cuentas[ $cod_cuenta  ];

			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			// Se inserta en la tabla movimientos, a la cuenta correspondiente, el monto ingresado 
			$insercion = $con->prepare( "	
											INSERT INTO movimiento
											( 
												fecha_movimiento, 	
												hora_movimiento, 	
												".$cuenta.", 	
												cod_tipo_mov 
											)
											VALUES
											( 				
												NOW(), 				
												NOW()::time(0), 	
												".$monto.", 	
												".$cod_operacion." 
											)
										");

			$insercion->execute();

			// Se comprueba que se haya insertado correctamente el registro en la base de datos
			$cantidad = $insercion->rowCount();
			
			if( $cantidad > 0 ){

				return true;
				
			}else{

				return false;
			}
		}

		// Metodo para devolver los saldos de las cuentas
		public function resumen_saldos(){

			// Habilito la conexion a la base de datos
			$con = $this->conexion();

			// Traigo el ultimo registro de la tabla saldos
			$consulta = $con->query( "SELECT * FROM saldos ORDER BY cod_movimiento DESC LIMIT 1" );
			$saldos = $consulta->fetch( PDO::FETCH_ASSOC );
			//print_r($saldos);

			return $saldos;
		}

		public function guardar_cliente($cedula,$ruc,$nombres,$apellidos,$tipo_cliente,$direccion,$cod_barrio,$cod_ciudad,$tel_part,$tel_lab,$cel_nro,$correo,$fecha_alta){

			$cedula = str_replace ( ".", "", $cedula);

	    	try{ 
				//Se guarda la sentencia SQL en la variable 'consulta'.
			 	$consulta = "INSERT INTO cliente(	cedula,
			 										ruc,
			 										nombres,
			 										apellidos,
			 										tipo_cliente,
			 										direccion,
			 										cod_barrio,
			 										cod_ciudad,
			 										tel_part,
			 										tel_lab,
			 										celular,
			 										correo,
			 										fecha_alta
			 									) 
							VALUES(	:cedula,
									:ruc,
									:nombres,
									:apellidos,
									:tipo_cliente,
									:direccion,
									:cod_barrio,
									:cod_ciudad,
									:tel_part,
									:tel_lab,
									:cel_nro,
									:correo,
									:fecha_alta)";
			 
				// Se abre la conexión a la base de datos
				$db = $this->conexion();
				               
			    //El objeto de conexión $db llama a su método 'prepare' el cual recibe como parámetro 
			    //la variable $cosulta. Todo se aloja en la variable $resultado. 
			    //La variable $resultado guarda ahora un objeto de tipo PDOStatement.    
				$resultado = $db->prepare($consulta);
			    
			    //Se llama al método execute de la clase PDOStatement y se le pasa como parámetro 
			    //un array asociativo con los marcadores y las variables. 	   
				$resultado->execute(array(	":cedula"=>$cedula,
											":ruc"=>$ruc,
											":nombres"=>$nombres,
											":apellidos"=>$apellidos,
											":tipo_cliente"=>$tipo_cliente,
											":direccion"=>$direccion,
											":cod_barrio"=>$cod_barrio,
											":cod_ciudad"=>$cod_ciudad,
											":tel_part"=>$tel_part,
											":tel_lab"=>$tel_lab,
											":cel_nro"=>$cel_nro,
											":correo"=>$correo,
											":fecha_alta"=>$fecha_alta));
				   	
				// Se guarda en la variable $cant la cantidad de filas retornadas
				$cant = $resultado->rowCount();
				
				
				if( (int)$cant > 0){	
				
					return 1;
				}
				     
			    //Se cierra el cursor de nuestra tabla virtual.
				$resultado->closeCursor();
			}catch(Exception $e){
			   	
			   	echo $e->getMessage();
				echo "<br>";
				echo "La linea del error es: " . $e->getLine();
				die();
			   	/*return 2;*/
			}
		}

		public function guardar_familia($desc_familia){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_familia(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe una familia igual a la ingresada en el formulario.
			$registros = $db->query("SELECT COUNT(*) AS cuenta FROM producto_familia WHERE descripcion = '$desc_familia'");
		    $cant = $registros->fetchAll();

		    foreach ($cant as $clave => $valor) {
		    	echo $valor['cuenta'];
		    }
		    
	        if($valor['cuenta'] > 0) 
			{
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Familia ya existe'.
				return 2;

			}else{
			    //Se hace el INSERT en la base de datos
			    $resultado = $db->prepare($consulta);
			    $resultado->execute(array(":descripcion"=>$desc_familia));
				
				//Se retorna 1 al archivo javascript para lanzar el mensaje de 'Familia ingresada exitosamente'.
			    return 1;
			}
			
			//Se cierra el cursor de nuestra tabla virtual.
			$resultado->closeCursor();
		}

		public function listar_familia_categoria($cod_familia){
				//Se abre la conexión a la base de datos
				$db = $this->conexion();

				$consulta = "SELECT pc.cod_categoria, pc.descripcion 
				             FROM producto_categoria pc 
				             JOIN producto_familia_categoria fc 
				             ON pc.cod_categoria = fc.cod_categoria 
				             WHERE fc.cod_familia = ".$cod_familia." ";

				$resultado = $db->prepare($consulta);

				$resultado->execute();	

				while( $row = $resultado->fetch( PDO::FETCH_ASSOC ) ){

					$categoria[] = $row;
				}

				$retorno = '';
				
				$cant = COUNT($categoria);

				    $retorno .= '<select style="width:250px" id="categoria" name="categoria">
				   
				    <option value="">--Seleccione una Categoría--</option>';	
				
				for ($i=0; $i<$cant; $i++) 
				{ 
					
					$aux = $i + 1;
					$retorno .= ' <option value = '.$categoria[$i]["cod_categoria"].' > '.$categoria[$i]["descripcion"].' </option> ';

				}
	                
					$retorno .= '</select>';
					return $retorno;
		}
			
		// UPDATE: 2016-12-13
		public function listar_categoria_marca($cod_categoria){
					//Se abre la conexión a la base de datos
					$db = $this->conexion();

					$consulta = "SELECT pm.cod_marca, pm.descripcion
								 FROM producto_marca pm
								 JOIN producto_categoria_marca cm
								 ON pm.cod_marca = cm.cod_marca
								 WHERE cm.cod_categoria = ".$cod_categoria." ";

					$resultado = $db->prepare($consulta);

					$resultado->execute();	

					while( $row = $resultado->fetch( PDO::FETCH_ASSOC ) ){

						$marca[] = $row;
					}

					$retorno = '';
					
					$cant = COUNT($marca);

					$retorno .= '<select style="width:250px" id="marca" name="marca">';
					
					//Pregunta si categoría es distinta a 'JUEGO' para mostrar la frase --Seleccione una Marca--    
					if ($cod_categoria <> 18){
						$retorno .= '<option value="">--Seleccione una Marca--</option>';	
					}
					
					for ($i=0; $i<$cant; $i++) 
					{ 
						
						$aux = $i + 1;
						$retorno .= ' <option value = '.$marca[$i]["cod_marca"].' > '.$marca[$i]["descripcion"].' </option> ';

					}
		                
						$retorno .= '</select>';
						$retorno .= '<span id="popup">Agregar marca</span>';
						return $retorno;
		}
		
		// UPDATE: 2016-12-13	
		public function listar_marca_modelo($cod_marca, $cod_categoria){
				
			$modelo = array();

			//Se abre la conexión a la base de datos
			$db = $this->conexion();

			$consulta = "SELECT pmo.cod_modelo, pmo.descripcion
						 FROM producto_modelo pmo
						 JOIN producto_categoria_marca_modelo cmm
						 ON pmo.cod_modelo = cmm.cod_modelo
						 WHERE cmm.cod_marca = '$cod_marca' 
						 AND cmm.cod_categoria = '$cod_categoria' ";
					 
		    $resultado = $db->prepare($consulta);

			$resultado->execute();	

			while( $row = $resultado->fetch( PDO::FETCH_ASSOC ) ){

				$modelo[] = $row;
			}

			$retorno = '';
			
			$cant = COUNT($modelo);

			$retorno .= '<select style="width:250px" id="modelo" name="modelo">';
			
			//Pregunta si categoría es distinta a 'JUEGO' para mostrar la frase --Seleccione un Modelo--  
			if ($cod_categoria <> 18){   
				$retorno .= '<option value="">--Seleccione un Modelo--</option>';	
			}
			
			for ($i=0; $i<$cant; $i++) 
			{ 
				
				$aux = $i + 1;
				$retorno .= ' <option value = '.$modelo[$i]["cod_modelo"].' > '.$modelo[$i]["descripcion"].' </option> ';

			}
                
				$retorno .= '</select>';
				$retorno .= '<span id="popup_modelo">Agregar modelo</span>';
				return $retorno;
		}

		public function guardar_categoria($desc_cat, $familia){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_categoria(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe una categoria igual a la ingresada en el formulario.
			$registros = $db->query("SELECT COUNT(*) AS cuenta FROM producto_categoria WHERE descripcion = '$desc_cat'");
		    $cant = $registros->fetchAll();

		    foreach ($cant as $clave => $valor) {
		    	echo $valor['cuenta'];
		    }
		    
	        if($valor['cuenta'] > 0) 
			{
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Categoría ya existe'.
				return 2;

			}else{
				    //Se hace el INSERT de la nueva categoría en la base de datos. 
				    $resultado = $db->prepare($consulta);
				    $resultado->execute(array(":descripcion"=>$desc_cat));
				    
				    //Se guarda en la variable $consulta2 la sentencia que verifica el último cod_categoria ingresado en la tabla Categoría.
				    $consulta2 = " SELECT MAX(cod_categoria) AS max FROM producto_categoria ";
				    $resultado2 = $db->query($consulta2);

					$intermedio = $resultado2->fetch(PDO::FETCH_ASSOC);
		 		    
		 		    $max = $intermedio['max'];
	   			 } 	
				   
				 //Se prepara la inserción en la tabla intermedia 'producto_familia_categoria' con el cod_categoria y cod_familia correspondientes.
				 $consulta3 = "INSERT INTO producto_familia_categoria(cod_categoria,cod_familia) 
				               VALUES(".$max.",".$familia.")";
	        	  
	        	 $resultado3 = $db->query($consulta3);   

	        	 $resultado3->fetch(PDO::FETCH_ASSOC);	
	             
	        	 //Se retorna 1 al archivo javascript para lanzar el mensaje de 'Categoría ingresada exitosamente'.
	        	 return 1;

	             //Se cierra la variable de conexión.
				 $db = NULL;	
		}

		public function guardar_marca($desc_marca, $categoria){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_marca(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe una marca igual dentro de la categoría seleccionada.
			 $registros = $db->query("SELECT COUNT(*) AS cuenta FROM producto_marca pm JOIN producto_categoria_marca pcm ON pm.cod_marca = pcm.cod_marca
				                     WHERE pm.descripcion = '$desc_marca' AND pcm.cod_categoria = '$categoria'");

		    $cant = $registros->fetchAll();

		    foreach ($cant as $clave => $valor) {
		    	echo $valor['cuenta'];
		    }
		    
	        if($valor['cuenta'] > 0) 
			{	
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Marca ya existe'.
				return 2;

			}else{
			    //Se hace el INSERT de la nueva marca en la base de datos.
			    $resultado = $db->prepare($consulta);
			    $resultado->execute(array(":descripcion"=>$desc_marca));
				
				//Se guarda en la variable $consulta2 la sentencia que verifica el último cod_marca ingresado en la tabla Marca.
				    $consulta2 = " SELECT MAX(cod_marca) AS max FROM producto_marca ";
				    $resultado2 = $db->query($consulta2);

					$intermedio = $resultado2->fetch(PDO::FETCH_ASSOC);
		 		    
		 		    $max = $intermedio['max'];
	   			 } 	
				   
				 //Se prepara la inserción en la tabla intermedia 'producto_categoria_marca' con el cod_categoria y cod_marca correspondientes.
				 $consulta3 = "INSERT INTO producto_categoria_marca(cod_categoria,cod_marca) 
				               VALUES(".$categoria.",".$max.")";
	        	  
	        	 $resultado3 = $db->query($consulta3);   

	        	 $resultado3->fetch(PDO::FETCH_ASSOC);	
	             
	        	 //Se retorna 1 al archivo javascript para lanzar el mensaje de 'Marca ingresada exitosamente'.
	        	 return 1;
			    
			     //Se cierra la variable de conexión.
				 $db = null;		
		}
		
		public function guardar_modelo($categoria, $marca, $desc_modelo){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_modelo(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe un modelo igual al ingresado en el formulario.
			
			$registros = $db->query("SELECT COUNT(*) AS cuenta FROM producto_modelo pm JOIN producto_categoria_marca_modelo cmm ON pm.cod_modelo = cmm.cod_modelo
	                                 WHERE pm.descripcion = '$desc_modelo'
					                 AND cmm.cod_marca = '$marca'
	                                 AND cmm.cod_categoria = '$categoria'");

		    $cant = $registros->fetchAll();

		    foreach ($cant as $clave => $valor) {
		    	echo $valor['cuenta'];
		    }
		    
	        if($valor['cuenta'] > 0) 
			{	
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Modelo ya existe'.
				return 2;

			}else{
			    //Se hace el INSERT del nuevo modelo en la base de datos.
			    $resultado = $db->prepare($consulta);
			    $resultado->execute(array(":descripcion"=>$desc_modelo));
				
				//Se guarda en la variable $consulta2 la sentencia que verifica el último cod_modelo ingresado en la tabla Modelo.
				    $consulta2 = " SELECT MAX(cod_modelo) AS max FROM producto_modelo ";
				    $resultado2 = $db->query($consulta2);

					$intermedio = $resultado2->fetch(PDO::FETCH_ASSOC);
		 		    
		 		    $max = $intermedio['max'];
	   			 } 	
				   
				 //Se prepara la inserción en la tabla intermedia 'producto_categoria_marca_modelo' con el cod_categoria, cod_marca y cod_modelo correspondientes.
				 $consulta3 = "INSERT INTO producto_categoria_marca_modelo(cod_categoria,cod_marca,cod_modelo) 
				               VALUES(".$categoria.",".$marca.",".$max.")";
	        	 	
	        	 $resultado3 = $db->query($consulta3);   

	        	 $resultado3->fetch(PDO::FETCH_ASSOC);	
	             
	        	 //Se retorna 1 al archivo javascript para lanzar el mensaje de 'Modelo ingresado exitosamente'.
	        	 return 1;
			    
			     //Se cierra la variable de conexión.
				 $db = null;	
		}

		// UPDATE: 2016-12-13
        public function guardar_producto($codigo, $familia, $categoria, $marca, $modelo, $color, $plataforma, $p_venta, $descripcion, $stock_min, $destino){
				
			$p_venta = str_replace ( ".", "", $p_venta);
			$stock_min = (int)$stock_min;
			
			try{ 
				if($codigo != NULL){
					//Se guarda la sentencia SQL en la variable 'consulta'.
				 	$consulta = "INSERT INTO producto(	
				 										cod_producto,
				 										cod_familia,
				 										cod_categoria,	
				 										cod_marca,
				 										cod_modelo,
				 										cod_color,
				 										cod_plataforma,
				 										precio_venta,
				 										descripcion,
				 										stock_min,
				 									    foto
				 									 ) 
								VALUES(	
										:cod_producto,
				 						:cod_familia,
				 						:cod_categoria,	
				 						:cod_marca,
				 						:cod_modelo,
				 						:cod_color,
				 						:cod_plataforma,
				 						:precio_venta,
				 						:descripcion,
				 						:stock_min,
				 						:foto)";
								
								// Se abre la conexión a la base de datos
								$db = $this->conexion();

							    //El objeto de conexión $db llama a su método 'prepare' el cual recibe como parámetro 
							    //la variable $cosulta. Todo se aloja en la variable $resultado. 
							    //La variable $resultado guarda ahora un objeto de tipo PDOStatement.    
								$resultado = $db->prepare($consulta);
							    
								//Se llama al método execute de la clase PDOStatement y se le pasa como parámetro 
							    //un array asociativo con los marcadores y las variables. 	   
								$resultado->execute(array(	":cod_producto"=>$codigo,
							 								":cod_familia"=>$familia,
							 								":cod_categoria"=>$categoria,	
							 								":cod_marca"=>$marca,
							 								":cod_modelo"=>$modelo,
							 								":cod_color"=>$color,
							 								":cod_plataforma"=>$plataforma,
							 								":precio_venta"=>$p_venta,
							 								":descripcion"=>$descripcion,
							 								":stock_min"=>$stock_min,
							 								":foto"=>$destino));
				}else{

					$consulta = "INSERT INTO producto(	
				 										cod_familia,
				 										cod_categoria,	
				 										cod_marca,
				 										cod_modelo,
				 										cod_color,
				 										cod_plataforma,
				 										precio_venta,
				 										descripcion,
				 										stock_min,
				 										foto
				 									 ) 
								VALUES(	
				 						:cod_familia,
				 						:cod_categoria,	
				 						:cod_marca,
				 						:cod_modelo,
				 						:cod_color,
				 						:cod_plataforma,
				 						:precio_venta,
				 						:descripcion,
				 						:stock_min,
				 						:foto)";
											
								$db = $this->conexion();

							    $resultado = $db->prepare($consulta);
							    
								$resultado->execute(array(	":cod_familia"=>$familia,
							 								":cod_categoria"=>$categoria,	
							 								":cod_marca"=>$marca,
							 								":cod_modelo"=>$modelo,
							 								":cod_color"=>$color,
							 								":cod_plataforma"=>$plataforma,
							 								":precio_venta"=>$p_venta,
							 								":descripcion"=>$descripcion,
							 								":stock_min"=>$stock_min,
															":foto"=>$destino));
				} 
					// Se guarda en la variable $cant la cantidad de filas retornadas
					$cant = $resultado->rowCount();
					
					if( (int)$cant > 0){	
					
						return 1;
					}
					     
				    //Se cierra el cursor de nuestra tabla virtual.
					$resultado->closeCursor();

			}catch(Exception $e){
			   	
			  /*echo $e->getMessage();
				echo "<br>";
				echo "La linea del error es: " . $e->getLine();
				die();*/
				return 2;
			}	
		}

		public function guardar_plataforma($desc_plataforma){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_plataforma(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe una plataforma igual a la ingresada en el formulario.
			$registros = $db->query("SELECT COUNT(*) AS cuenta FROM producto_plataforma WHERE descripcion = '$desc_plataforma'");
		    $cant = $registros->fetchAll();

		    foreach ($cant as $clave => $valor) {
		    	echo $valor['cuenta'];
		    }
		    
	        if($valor['cuenta'] > 0) 
			{
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Plataforma ya existe'.
				return 2;

			}else{
			    //Se hace el INSERT en la base de datos
			    $resultado = $db->prepare($consulta);
			    $resultado->execute(array(":descripcion"=>$desc_plataforma));
				
				//Se retorna 1 al archivo javascript para lanzar el mensaje de 'Plataforma ingresada exitosamente'.
			    return 1;
			}
			
			//Se cierra el cursor de nuestra tabla virtual.
			$resultado->closeCursor();
		}

		public function guardar_color($desc_color){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_color(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe un color igual al ingresado en el formulario.
			$registros = $db->query("SELECT COUNT(*) AS cuenta FROM producto_color WHERE descripcion = '$desc_color'");
		    $cant = $registros->fetchAll();

		    foreach ($cant as $clave => $valor) {
		    	echo $valor['cuenta'];
		    }
		    
	        if($valor['cuenta'] > 0) 
			{
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Color ya existe'.
				return 2;

			}else{
			    //Se hace el INSERT en la base de datos
			    $resultado = $db->prepare($consulta);
			    $resultado->execute(array(":descripcion"=>$desc_color));
				
				//Se retorna 1 al archivo javascript para lanzar el mensaje de 'Color ingresado exitosamente'.
			    return 1;
			}
			
			//Se cierra el cursor de nuestra tabla virtual.
			$resultado->closeCursor();
		}

		// UPDATE: 2016-12-13
        public function guardar_marca_popup($desc_marca, $categoria){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_marca(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe una marca igual dentro de la categoría seleccionada.
			 $registros = $db->query("SELECT COUNT(*) AS cuenta 
			 						  FROM producto_marca pm JOIN producto_categoria_marca pcm 
			 						  ON pm.cod_marca = pcm.cod_marca
				                      WHERE pm.descripcion = '$desc_marca' AND pcm.cod_categoria = '$categoria' ");

		    $c = $registros->fetch();

		    $cant = intval($c['cuenta']);

		    if($cant > 0) 
			{	
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Marca ya existe'.
				return 2;

			}else{
			    
			    //Se hace el INSERT de la nueva marca en la base de datos.
			    $resultado = $db->prepare($consulta);
			    $resultado->execute(array(":descripcion"=>$desc_marca));
				
				//Se guarda en la variable $consulta2 la sentencia que verifica el último cod_marca ingresado en la tabla Marca.
				    $consulta2 = " SELECT MAX(cod_marca) AS max FROM producto_marca ";
				    $resultado2 = $db->query($consulta2);

					$intermedio = $resultado2->fetch(PDO::FETCH_ASSOC);
		 		    
		 		    $max = $intermedio['max'];
	   			 } 	
				   
				 //Se prepara la inserción en la tabla intermedia 'producto_categoria_marca' con el cod_categoria y cod_marca correspondientes.
				 $consulta3 = "INSERT INTO producto_categoria_marca(cod_categoria,cod_marca) 
				               VALUES(".$categoria.",".$max.")";
	        	  
	        	 $resultado3 = $db->query($consulta3);   

	        	 $resultado3->fetch(PDO::FETCH_ASSOC);	
	             
	        	 //Se retorna 1 al archivo javascript para lanzar el mensaje de 'Marca ingresada exitosamente'.
	        	 return 1;
			    
			     //Se cierra la variable de conexión.
				 $db = null;		
		}

		// UPDATE: 2016-12-13
        public function guardar_modelo_popup($desc_modelo, $marca_mod, $categoria_mod){
			
			//Se guarda la sentencia SQL en la variable 'consulta'.
			$consulta = "INSERT INTO producto_modelo(descripcion) VALUES(:descripcion)";	
			
			//Se abre la conexión a la base de datos
			$db = $this->conexion();	
			
			//Se guarda en la variable $registros la sentencia que verifica si ya existe un modelo igual al ingresado en el formulario.
			$registros = $db->query("SELECT COUNT(*) AS cuenta 
									 FROM producto_modelo pm JOIN producto_categoria_marca_modelo cmm 
									 ON pm.cod_modelo = cmm.cod_modelo
	                                 WHERE pm.descripcion = '$desc_modelo'
					                 AND cmm.cod_marca = '$marca_mod'
	                                 AND cmm.cod_categoria = '$categoria_mod' ");

		    $c = $registros->fetch();

		    $cant = intval($c['cuenta']);

		    if($cant > 0) 
			{	
				//Se retorna 2 al archivo javascript para lanzar el mensaje de 'Modelo ya existe'.
				return 2;

			}else{
			    //Se hace el INSERT del nuevo modelo en la base de datos.
			    $resultado = $db->prepare($consulta);
			    $resultado->execute(array(":descripcion"=>$desc_modelo));
				
				//Se guarda en la variable $consulta2 la sentencia que verifica el último cod_modelo ingresado en la tabla Modelo.
				    $consulta2 = " SELECT MAX(cod_modelo) AS max FROM producto_modelo ";
				    $resultado2 = $db->query($consulta2);

					$intermedio = $resultado2->fetch(PDO::FETCH_ASSOC);
		 		    
		 		    $max = $intermedio['max'];
	   			 } 	
				   
				 //Se prepara la inserción en la tabla intermedia 'producto_categoria_marca_modelo' con el cod_categoria, cod_marca y cod_modelo correspondientes.
				 $consulta3 = "INSERT INTO producto_categoria_marca_modelo(cod_categoria,cod_marca,cod_modelo) 
				               VALUES(".$categoria_mod.",".$marca_mod.",".$max.")";
	        	 	
	        	 $resultado3 = $db->query($consulta3);   

	        	 $resultado3->fetch(PDO::FETCH_ASSOC);	
	             
	        	 //Se retorna 1 al archivo javascript para lanzar el mensaje de 'Modelo ingresado exitosamente'.
	        	 return 1;
			    
			     //Se cierra la variable de conexión.
				 $db = null;	
		}	
	}
?>