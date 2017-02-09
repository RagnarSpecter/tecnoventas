<?php

require_once("class.php");
$t = new Trabajo;
$retorno = $t->procesar_compra($_POST["costo"], $_POST["cantidad"], $_POST["forma_pago"], $_POST["cod_producto"]);	

?>

<?php echo $retorno; ?>

	 