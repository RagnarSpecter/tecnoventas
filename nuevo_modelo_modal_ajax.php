<?php
	include_once("class.php");
	$t = new Trabajo;
	$retorno = $t->guardar_modelo_popup(strtoupper($_POST['desc_modelo']), $_POST['marca_popup_modelo'], $_POST['categoria_popup_modelo']);								
?>

<?php echo $retorno; ?>