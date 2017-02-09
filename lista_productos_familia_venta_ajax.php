<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos_familia_venta($_POST["cod_familia"]);

?>
<?php echo $retorno; ?>
