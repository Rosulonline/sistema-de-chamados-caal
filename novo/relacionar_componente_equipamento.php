<?php
	include("../funcoes.php");
	$id_computador = $_POST['equipamento'];
	$id_componente = $_POST['componente'];
	
	relaciona_computador_componente($id_computador, $id_componente);
	
	header("Location: /equipamento.php?id_equipamento=".$id_computador);
?>