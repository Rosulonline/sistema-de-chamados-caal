<?php
	include("../funcoes.php");
	$usuario = $_SESSION['usuario'];
	$senha = $_SESSION['senha'];
	$url = 'http://sgi.caal.com.br/valida.php';
	
	
	header("Location: ".$url."?usuario=".$usuario."&senha=".$senha);
	
	/*
	 *	ENVIA POR POST USUÁRIO E SENHA
	 *	
	 *	@param string $url - url de login da outra pagina
	 *	@param string $usuario - nome de usuário
	 *	@param string $senha - senha do usuário
	 *
	 *	@return bool - true se conectou, false caso contrário
	 /
	post_login($url, $usuario, $senha);
	
	/*
	//set POST variables
	$url = 'http://sgi.caal.com.br/valida.php';
	$fields = array(
		'usuario' => urlencode($usuario),
		'senha' => urlencode($senha)
	);
	
	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	//open connection
	
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	
	//execute post
	$result = curl_exec($ch);
	
	//close connection
	curl_close($ch);
	*/
?>