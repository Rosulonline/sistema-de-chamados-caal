<?php
	include("../funcoes.php");
	$nome = $_POST['nome'];
	$endereco = $_POST['endereco'];
	$telefone = $_POST['telefone'];
	incluir_filial($nome,$endereco,$telefone);
	header("Location: /index.php");
?>