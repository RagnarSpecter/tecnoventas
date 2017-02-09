<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos_por_categoria($_POST["cod_categoria"]);

?>
<?php echo $retorno; ?>
<!-- <?php print_r($retorno); ?> -->