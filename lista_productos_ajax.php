<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos();

?>
<?php echo $retorno; ?>
<!-- <?php print_r($retorno); ?> -->