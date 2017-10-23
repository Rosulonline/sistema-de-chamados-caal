<?php
	include("../funcoes.php");
	$id_usuario = $_POST['nome'];
	$id_equipamento = $_POST['equipamento'];
	
	atribuir_equipamento_para_usuario($id_usuario, $id_equipamento);
	
	header("Location: /equipamento.php?id_equipamento=".$id_equipamento);
?>