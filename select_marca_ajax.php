<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->listar_marca( $_POST["cod_categoria"] );

?>
<?php echo $retorno; ?>
