<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos_categoria_compra($_POST["cod_categoria"]);

?>
<?php echo $retorno; ?>
