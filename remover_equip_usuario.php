<?php
	include_once('funcoes.php');
	
	$login = protegePagina();
	
	if(isset($_GET['equipamento']) && isset($_GET['usuario'])){
		$id_equipamento = $_GET['equipamento'];
		$id_usuario = $_GET['usuario'];
		retirar_vinculos_equipamento($id_equipamento,$id_usuario);
	}
	
	header("Location: /usuario.php?id=".$_GET['usuario']);
?> 