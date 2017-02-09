<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->modificar_saldos( 	$_POST["cod_cuenta"],
									$_POST["cod_operacion"],
									$_POST["cod_cuenta_a_transferir"],
									$_POST["monto"]
								);

?>
<?php echo $retorno; ?>