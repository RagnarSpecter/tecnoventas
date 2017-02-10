<header id="cabecera_1">
	<div id="logo_main">

		<?php 
			$raiz = "C:\\xampp\\htdocs\\tecnoventas";
			$directorio = getcwd();

			$atras = "";

			if( strlen($directorio) > strlen($raiz) )
				$atras = "../../";

		?>

		<img align="left" src="<?php echo $atras; ?>imagenes/logo_redondo.png"/>
		<img align="left" src="<?php echo $atras; ?>imagenes/logo_tecnoventas.png"/>
		
	</div>
	<div id="identificacion">
		<div>
			<span>Usted se ha identificado como: </span><span><?php echo @$_SESSION[ 'usuario' ] ?></span>
		</div>
	</div>
</header>