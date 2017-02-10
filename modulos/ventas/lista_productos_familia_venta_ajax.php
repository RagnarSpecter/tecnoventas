<?php

include_once("class_venta_productos.php");
$v = new Ventas;
$retorno = $v->lista_productos_familia_venta($_POST["cod_familia"]);

?>
<?php echo $retorno; ?>
