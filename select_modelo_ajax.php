<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->listar_modelo( $_POST["cod_categoria"], $_POST["cod_marca"] );

?>
<?php echo $retorno; ?>
