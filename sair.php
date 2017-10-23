<?php
	include_once './funcoes.php';
	session_destroy();
	
	if(isset($_POST['erro'])){
		echo "erro: ".$_POST['erro'];
	}
	
	header("Location: ./index.php");
?>