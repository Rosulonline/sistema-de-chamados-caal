<?php
	include("../funcoes.php");
	
	if(isset($_POST['id_equipamento'])){
		$restricao = NULL;
		$id_equipamento = $_POST['id_equipamento'];
		$equipamento = retorna_dados_equipamento($id_equipamento,$restricao);
		$usuarios = retorna_usuarios_equipamento($id_equipamento);
		$id_de = $usuarios[0]['U_id'];
	}	
	
	if(isset($_POST['usuario']))
		$id_para = $_POST['usuario'];
		
	if(isset($_POST['id_componente']) && isset($_POST['id_pc_novo'])){
		$id_componente = $_POST['id_componente'];
		$equipamento = retorna_dados_equipamento($id_componente);
		$id_equipamento_novo = $_POST['id_pc_novo'];
		$id_pc_antigo = $_POST['id_pc_antigo'];
		$usuarios_equip = retorna_usuarios_equipamento($id_pc_antigo);
		$id_de = $usuarios_equip[0]['U_id'];
	}

	$motivo = $_POST['motivo'];
	
	if($equipamento['C_id']==2 || $equipamento['C_id']==4 || $equipamento['C_id']==5 || 
		$equipamento['C_id']==8 || $equipamento['C_id']==9 || $equipamento['C_id']==10 ||
		$equipamento['C_id']==11 || $equipamento['C_id']==12 || $equipamento['C_id']==13 ||
		$equipamento['C_id']==14 || $equipamento['C_id']==15 || $equipamento['C_id']==16){
		
		$com_restricao = true;
		redirecionar_componente($id_componente,$id_de, $id_equipamento_novo,$motivo);
		$link = "Location: /equipamento.php?id_equipamento=".$id_equipamento_novo;
	}else{
		redirecionar_equipamento($id_equipamento,$id_de, $id_para,$motivo);
		$link = "Location: /equipamento.php?id_equipamento=".$id_equipamento;
	}
	
	
	header($link);
?>