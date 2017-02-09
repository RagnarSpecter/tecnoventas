<?php

 require_once("class.php");

	 $t = new Trabajo;
	 $retorno = $t->listar_categoria_marca($_POST["cod_categoria"]);	
	
	 echo $retorno;

?>

	 