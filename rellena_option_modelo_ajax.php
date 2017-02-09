<?php

 require_once("class.php");

	 $t = new Trabajo;
	 $retorno = $t->listar_marca_modelo($_POST["cod_marca"], $_POST["cod_categoria"]);	
	
?>

<?php echo $retorno; ?>	 