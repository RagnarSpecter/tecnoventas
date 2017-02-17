<?php

include_once("class_venta_productos.php");
$v = new Ventas;
$retorno = $v->lista_productos_marca_venta($_POST["cod_familia"], $_POST["cod_categoria"], $_POST["cod_marca"]);

?>
<?php echo $retorno; ?>
