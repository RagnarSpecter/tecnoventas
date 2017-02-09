<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos_por_familia($_POST["cod_familia"]);

?>
<?php echo $retorno; ?>
<!-- <?php echo '<pre>', print_r($retorno), '</pre>'; ?> -->
