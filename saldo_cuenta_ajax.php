<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->saldo_cuenta( $_POST["codigo_cuenta"] );

?>
<?php echo $retorno; ?>
