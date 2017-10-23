<?php
    include_once '../funcoes.php';
	
	$login		= protegePagina();
	
	
	
	$search		= array("+");
	$replace	= array(" ");;
	$subject	= strtolower(trim($_GET['term']));
	$text		= str_replace($search, $replace, $subject);
		
	$U_consulta = "";
	$U_consulta .= "	SELECT";
	$U_consulta .= "		*";
	$U_consulta .= "	FROM";
	$U_consulta .= "		USUARIO_SETOR_FILIAL";
	$U_consulta .= "	WHERE";
	$U_consulta .= "		(";
	$U_consulta .= "				LCASE(U_nome)		LIKE '%".$text."%'";
	$U_consulta .= "			OR	LCASE(U_ramal)		LIKE '%".$text."%'";
	$U_consulta .= "			OR	LCASE(U_email)		LIKE '%".$text."%'";
	$U_consulta .= "			OR 	LCASE(U_usuario)	LIKE '%".$text."%'";
	$U_consulta .= "			OR	LCASE(U_email_msn)	LIKE '%".$text."%'";
	$U_consulta .= "			OR	LCASE(S_nome)		LIKE '%".$text."%'";
	$U_consulta .= "			OR	LCASE(F_nome)		LIKE '%".$text."%'";
	$U_consulta .= "			OR 	LCASE(F_endereco)	LIKE '%".$text."%'";
	$U_consulta .= "		) AND U_ativo = 1";
	$U_consulta .= "	ORDER BY U_nome ASC";
	$U_consulta .= ";";
		
	$U_query	= mysql_query($U_consulta)or die($U_consulta.": ERRO Usuário: ".mysql_error());
	
	$json		= '[';
	
	$cont		= 0;
	while($row=mysql_fetch_array($U_query)){
		if($cont != 0 ){
			$json .= ", ";
		}
	    $json .= '{"value":"'.$row['U_nome'].'", "id": "'.$row['U_id'].'"}';
		++$cont;
	}
	
	$json .= ']';
	
	echo $json;
?>