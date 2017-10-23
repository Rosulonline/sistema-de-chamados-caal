<?php
	include("../funcoes.php");
	$id_componente = $_GET['id_componente'];
	if(isset($_GET['id_pc'])){
		$id_pc = $_GET['id_pc'];
	}else{
		$id_pc = NULL;
	}
	
	retirar_vinculos_componentes($id_componente,$id_pc);
	
	echo"
		<body onload='window.history.back()'>
		</body>
		";
?>