<?php
	include("../funcoes.php");
	
	/*VARIÁVEIS*/
	$nome			= null;
	$descricao		= null;
	$numero_serie	= null;
	$mac			= null;
	$ip				= null;
	$numero_nota	= null;
	$classe			= null;
	$id_equipamento	= null;
	$id_usuario		= null;
	$id_computador	= null;
	
	if(isset($_POST['nome']) && $_POST['nome'] != ""){
		$nome = $_POST['nome'];
	}else if(isset($_POST['nome']) && $_POST['nome'] == ""){
		$nome = "-";
	}
	
	if(isset($_POST['descricao']) && $_POST['descricao'] != ""){
		$descricao = $_POST['descricao'];
	}else if(isset($_POST['descricao']) && $_POST['descricao'] == ""){
		$descricao = "-";
	}
	
	if(isset($_POST['ns']) && $_POST['ns'] != ""){
		$numero_serie = $_POST['ns'];
	}else if(isset($_POST['ns']) && $_POST['ns'] == ""){
		$numero_serie = "-";
	}
	
	if(isset($_POST['nf']) && $_POST['nf'] != ""){
		$numero_nota = $_POST['nf'];
	}else if(isset($_POST['nf']) && $_POST['nf'] == ""){
		$numero_nota = "-";
	}
	
	if(isset($_POST['mac']) && $_POST['mac'] != ""){
		$mac = $_POST['mac'];
	}else if(isset($_POST['mac']) && $_POST['mac'] == ""){
		$mac = "-";
	}
	
	if(isset($_POST['ip']) && $_POST['ip'] != ""){
		$ip = $_POST['ip'];
	}else if(isset($_POST['ip']) && $_POST['ip'] == ""){
		$ip = "-";
	}
	
	if(isset($_POST['classe']) && $_POST['classe'] != ""){
		$classe = $_POST['classe'];
	}else if(isset($_POST['classe']) && $_POST['classe'] == ""){
		$classe = "-";
	}
	
	echo "Nome: ".$nome;
	echo "Descricao: ".$descricao;
	echo "ns: ".$numero_serie;
	echo "mac: ".$mac;
	echo "ip: ".$ip;
	echo "nf: ".$numero_nota;
	echo "classe: ".$classe;
	
	$id_equipamento = incluir_equipamento($nome,$descricao,$numero_serie,$mac,$ip,$numero_nota,$classe);
	
	if(isset($_GET['id_usuario']) && $_GET['id_usuario'] != 0){
		$id_usuario = $_GET['id_usuario'];
		atribuir_equipamento_para_usuario($id_usuario, $id_equipamento);
		header("Location: /usuario.php?id=".$id_usuario);
	}else if(isset($_GET['id_pc'])){
		$id_computador = $_GET['id_pc'];
		relaciona_computador_componente($id_computador, $id_equipamento);
		header("Location: /equipamento.php?id_equipamento=".$id_computador);
	}else{
		echo "ERRO";
	}
?>