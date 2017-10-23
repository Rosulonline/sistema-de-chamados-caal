<?php 
	include_once('funcoes.php');
	global $_SG;
	$text = mysql_real_escape_string($_GET['term']);
	$query = "SELECT * FROM USUARIO_SETOR_FILIAL WHERE U_nome LIKE '%$text%' OR U_usuario LIKE '%$text%' OR U_email LIKE '%$text%' ORDER BY U_nome ASC;";
	$result = mysql_query($query);
	$json = '[';
	$first = true;
	while($row = mysql_fetch_array($result)){
		if (!$first){
			$json .=  ',';
		} else {
			$first = false;
		}
		$json .= '{"value":"'.utf8_encode($row['U_nome']).'"}';
	}
	$json .= ']';

	echo $json;
?>
