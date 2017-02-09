<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos_familia_compra($_POST["cod_familia"]);

?>
<?php echo $retorno; ?>
