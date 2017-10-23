<?php
	include("../funcoes.php");
	$nome = $_POST['nome'];
	$id_filial = $_POST['filial'];
	incluir_setor($nome, $id_filial);
	header("Location: /index.php");
?>