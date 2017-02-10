<?php 
			$raiz = "C:\\xampp\\htdocs\\tecnoventas";
			$directorio = getcwd();

			$atras = "";

			if( strlen($directorio) > strlen($raiz) )
				$atras = "../../";
?>

<nav>
	<ul class="menu">
		<li><a href="<?php echo $atras; ?>inicio.php">Inicio</a></li>
		<li><a href="#">Estado de Cuentas</a>
			<ul class="sub_menu">
				<li><a href="<?php echo $atras; ?>resumen_general.php">Resumen General</a></li>
				<li><a href="<?php echo $atras; ?>modificar_saldos.php">Modificar Saldos</a></li>
			</ul>
		</li>
		<li><a href="#">Movimiento</a>
			<ul class="sub_menu">
				<li><a href="<?php echo $atras; ?>compra_productos.php">Compra</a></li>
				<li><a href="<?php echo $atras; ?>modulos/ventas/venta_productos.php">Venta</a></li>
			</ul>
		</li>
		<li><a href="#">Inventario</a>
			<ul class="sub_menu">
				<li><a href="<?php echo $atras; ?>lista_productos.php">Lista de Productos</a></li>
				<li><a href="#">Combos PC</a></li>
				<li><a href="#">A Renovar</a></li>
				<li><a href="#">En Espera</a></li>
				<li><a href="#">Pedidos Varios</a></li>
				<li><a href="<?php echo $atras; ?>nuevo_producto.php">Carga de Productos</a></li>
				<li><a href="#">Baja de Productos</a></li>
			</ul>
		</li>	
		<li><a href="#">Presupuestar</a></li>
		<li><a href="#">Clientes</a>
			<ul class="sub_menu">
				<li><a href="<?php echo $atras; ?>nuevo_cliente.php">Agregar Cliente</a></li>
				<li><a href="#">Clientes Activos</a></li>
				<li><a href="#">Clientes Anteriores</a></li>
			</ul>
		</li>
		<li><a href="#">Informes</a>
			<ul class="sub_menu">
				<li><a href="#">Operaciones del Dia</a></li>
				<li><a href="#">Operaciones por Fecha</a></li>
				<li><a href="#">Estado de Cuentas por Fecha</a></li>
				<li><a href="#">Compras Realizadas</a></li>
				<li><a href="#">Productos Dados de Baja</a></li>
				<li><a href="#">Productos Vendidos</a></li>
			</ul>
		</li>
		<li><a href="#">Mantenimiento</a>
			<ul class="sub_menu">
				<li><a href="<?php echo $atras; ?>nueva_familia.php">Agregar Familia</a></li>
				<li><a href="<?php echo $atras; ?>nueva_categoria.php">Agregar Categoria</a></li>
				<li><a href="<?php echo $atras; ?>nueva_marca.php">Agregar Marca</a></li>
				<li><a href="<?php echo $atras; ?>nuevo_modelo.php">Agregar Modelo</a></li>
				<li><a href="<?php echo $atras; ?>nueva_plataforma.php">Agregar Plataforma</a></li>
				<li><a href="<?php echo $atras; ?>nuevo_color.php">Agregar Color</a></li>
			</ul>
		</li>
		<li><a href="<?php echo $atras; ?>salir.php">Cerrar Sesion</a></li>					
	</ul>
</nav>