<?php
	include("../funcoes.php");
	
	function incluir_todos($id_equipamento){
		global $_server;
		$consulta = "
			SELECT 
				id
			FROM
				usuario
			ORDER BY id ASC
		;";
		$query = mysql_query($consulta)or die($consulta.": ERRO funчуo: incluir_todos(); : ".mysql_error());
		while($row = mysql_fetch_array($query)){
			$sql = "
				INSERT INTO USUARIO_has_EQUIPAMENTO (
					USUARIO_id,
					EQUIPAMENTO_id)VALUES(
					".$row['id'].",
					".$id_equipamento."
					)
			;";
			$query_aux = mysql_query($sql)/*or die($sql.": ERRO funчуo: incluir_todos(); : ".mysql_error())*/;
			echo "Consulta: ".$sql."<br/>";
		}
	}
	
	incluir_todos(29);
?>