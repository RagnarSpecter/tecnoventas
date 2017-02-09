<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->listar_categoria( $_POST["cod_familia"] );

?>
<?php echo $retorno; ?>
