<?php
    include_once("class.php");
	$t = new Trabajo;
	$retorno = $t->guardar_marca_popup(strtoupper($_POST['desc_marca']),  $_POST['categoria_popup']);
?>

<?php echo $retorno; ?>