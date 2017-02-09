<?php

 require_once("class.php");

	 $t = new Trabajo;
	 $retorno = $t->listar_familia_categoria($_POST["cod_familia"]);	
	
	 echo $retorno;

?>

	 