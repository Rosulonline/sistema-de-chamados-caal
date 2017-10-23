<?php
	include_once './funcoes.php';
	
	session_start();
	
	global $_server;
	
	$_SESSION['usuario']	= $_POST['usuario'];
	$_SESSION['senha']		= $_POST['senha'];
	
	$aux = verifica_login();

	if($aux==2){
		echo "login com sucesso ADM";
		header("Location: ../index_resp.php");
	}else if($aux==1){
		echo "login com sucesso COMUM";
		header("Location: ../index_comum.php");
	}else{
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}
		
		$cURL = curl_init('http://chamados.caal.com.br/index.php');
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		
		$dados = array(
			'erro' => 'Usuário Incorreto'
		);
		
		curl_setopt($cURL, CURLOPT_POST, true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, $dados);
		
		$resultado = curl_exec($cURL);
		
		curl_close($cURL);
		echo $resultado;
	}
?>