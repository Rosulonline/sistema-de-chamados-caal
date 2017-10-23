<?php
	include("../funcoes.php");

	if(isset($_POST['msn']))
		$msn = $_POST['msn'];
	if(isset($_POST['disp']))
		$disp =$_POST['disp'];
	if(isset($_POST['net']))
		$net=$_POST['net'];
	if(isset($_POST['ativo']))
		$ativo=$_POST['ativo'];
	else
		retirar_vinculos_equipamento(NULL,$_GET['id']);
	
	if(isset($_POST['ramal'])){
		if($_POST['ramal'] == "-" || $_POST['ramal'] == ""){
			$ramal = "-";
		}else{
			$ramal = $_POST['ramal'];
		}
	}
	if(isset($_POST['matricula'])){
		
		if($_POST['matricula'] == "-" || $_POST['matricula'] == ""){
			$matricula = "-";
		}else{
			$matricula = $_POST['matricula'];
		}
	}else{
		$nascimento = null;
	}
	if(isset($_POST['nascimento'])){
		
		if($_POST['nascimento'] == "-" || $_POST['nascimento'] == ""){
			$nascimento = "01/01/2000";
		}else{
			$nascimento = date("Y-m-d",strtotime($_POST['nascimento']));
		}
	}else{
		$nascimento = null;
	}
	/*
 	$dados = "";
	$dados .= "ID= ".$_GET['id'];
	$dados .= " ,NOME= ".$_POST['nome'];
	$dados .= " ,RAMAL= ".$ramal;
	$dados .= " ,eMAIL= ".trim($_POST['email']);
	$dados .= " ,USUARIO= ".trim($_POST['usuario']);
	$dados .= " ,SENHA= ".trim($_POST['senha']);
	$dados .= " ,MSN= ".$msn;
	$dados .= " ,EMAIL_MSN= ".trim($_POST['emailmsn']);
	$dados .= " ,DISP= ".$disp;
	$dados .= " ,NET= ".$net;
	$dados .= " ,ATIVO= ".$ativo;
	$dados .= " ,SETOR= ".$_POST['setor'];
	$dados .= " ,MATRICULA= ".$matricula;
	$dados .= " ,NASCIMENTO= ".$nascimento;
	echo $dados;
	 * 
	 */
	atualiza_usuario(
		$_GET['id'],
		trim($_POST['nome']),
		$ramal,
		trim($_POST['email']),
		trim($_POST['usuario']),
		trim($_POST['senha']),
		$msn,
		trim($_POST['emailmsn']),
		$disp,
		$net,
		$ativo,
		$_POST['setor'],
		$matricula,
		$nascimento
	);
	
	header("Location: ../usuario.php?id=".$_GET['id']);
?>