<?php

include_once("class_venta_productos.php");
$v = new Ventas;
$retorno = $v->lista_productos_categoria_venta($_POST["cod_categoria"]);

?>
<?php echo $retorno; ?>
