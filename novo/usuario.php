<?php
	include("../funcoes.php");
	$nome = trim($_POST['nome']);
	$ramal = $_POST['ramal'];
	$email = trim($_POST['email']);
	$id_setor = $_POST['setor'];
	$usuario = trim($_POST['usuario']);
	$senha = trim($_POST['senha']);
	$email_msn="-";
	
	$msn = 0;
	if(isset($_POST['msn']))
		$msn = 1;
	
	$internet = 0;
	if(isset($_POST['net']))
		$internet = 1;

	$dispositivos = 0;
	if(isset($_POST['disp']))
		$dispositivos = 1;
	
	$ativo = 0;
	if(isset($_POST['ativo']))
		$ativo = 1;
		
	incluir_usuario($nome,$ramal,$email,$usuario,$senha,$msn,$email_msn,$dispositivos,$internet,$id_setor,$ativo);
	$link = "Location: /index_resp.php#filiais";
	header($link);
?>