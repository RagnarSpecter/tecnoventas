<?php

include_once("class.php");
$t = new Trabajo;
$retorno = $t->lista_productos_busqueda($_POST["texto"]);

?>
<?php echo $retorno; ?>
<!-- <?php print_r($retorno); ?> -->