<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos_marca_compra($_POST["cod_categoria"], $_POST["cod_marca"]);

?>
<?php echo $retorno; ?>
