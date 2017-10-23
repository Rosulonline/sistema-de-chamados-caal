<?php
	require("../funcoes.php");
	
	if($_POST['nome'] == "")
		$nome = "-";
	else
		$nome = $_POST['nome'];
	
	if($_POST['descricao'] == "")
		$descricao = "-";
	else
		$descricao = $_POST['descricao'];
		
	if((!isset($_POST['ns'])) || ($_POST['ns'] == ""))
		$ns = "-";
	else
		$ns = $_POST['ns'];
	
	if((!isset($_POST['mac'])) || ($_POST['mac'] == ""))
		$mac = "-";
	else
		$mac = $_POST['mac'];
	
	if((!isset($_POST['ip'])) || ($_POST['ip'] == ""))
		$ip = "-";
	else
		$ip = $_POST['ip'];
	
	if((!isset($_POST['nf'])) || ($_POST['nf'] == ""))
		$nf = "-";
	else
		$nf = $_POST['nf'];
	
	
	$id_equipamento = $_GET['id_equipamento'];
	$classe = $_POST['classe'];
	$descartado = 0;
	
	if(isset($_POST['descartado']))
		$descartado = 1;
	
	atualiza_equipamento($id_equipamento, $descricao, $nome, $mac, $ip, $nf, $ns, $classe,$descartado);
	if($descartado == 1){
		header("Location: ../formularios/descartar_equipamento.php?id_equipamento=".$id_equipamento);
	}else{
		//header("Location: ../equipamento.php?id_equipamento=".$_GET['id_equipamento']);
		echo"
		<body onload='window.history.back()'>
		</body>
		";
	}
?>