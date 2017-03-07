<?php

include_once("class_venta_productos.php");
$v = new Ventas;
$retorno = $v->cargar_carrito( $_POST["cod_producto"], $_POST["precio"], $_POST["cantidad"] );

?>
<?php echo $retorno; ?>
