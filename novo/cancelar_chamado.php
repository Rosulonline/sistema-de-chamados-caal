<?php
	include("../funcoes.php");
	
	$descricao = $_POST['descricao'];
	$id_chamado = $_POST['id_chamado'];
	if(isset($_SESSION['id_resp'])){
		$id_responsavel = $_SESSION['id_resp'];
	}else{
		$id_filial=NULL;
		$id_setor=NULL;
		$id_usuario=NULL;
		$id_equipamento=NULL;
		$id_chamado=$_POST['id_chamado'];
		$aberto = NULL;
		$inicial = NULL;
		$por_pagina = NULL;
		$chamado = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
		$id_responsavel = $chamado[0]['R_id'];
	}
	
	$data_solucao = NULL;
	$previsao = NULL;
	$solucionado = 1;
	
	incluir_solucao($descricao, $previsao, $id_chamado,$id_responsavel,$solucionado,$data_solucao);
	header("Location: ../chamado.php?id=".$_POST['id_chamado']);
?>