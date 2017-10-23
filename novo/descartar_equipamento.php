<?php
	include("../funcoes.php");
	$motivo = $_POST['motivo'];
	$id_equipamento = $_GET['id_equipamento'];
	
	descartar_equipamento($id_equipamento, $motivo);
	atribuir_equipamento_para_usuario(155, $id_equipamento);
	
	$link = "Location: /index.php";
	header($link);
?>