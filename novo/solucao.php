<?php

	include '../funcoes.php';
	//include("../funcoes.php");
	
	$login = protegePagina();
	
	require("../PHPMailer/class.phpmailer.php");
	
	$mail = new PHPmailer();
	
	$mail->IsSMTP();
	$mail->Host = "mail.caal.com.br";
	$mail->IsHTML(true); // define como html

	$descricao = $_POST['descricao'];
	$id_chamado = $_POST['id_chamado'];
	if(isset($_POST['id_resp'])){
		$id_responsavel = $_POST['id_resp'];
	}else{
		$id_responsavel = $_SESSION['id_resp'];
	}
	$solucionado = 0;
	$data_solucao = null;
	$id_filial=null;
	$id_setor=null;
	$id_usuario=null;
	$id_equipamento=null;
	$aberto = null;
	$inicial = null;
	$por_pagina = null;
	
	$chamado = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado);
	
	if(isset($_POST['solucao'])){
		$solucionado = 1;
		$previsao = "NOW()";
		incluir_solucao($descricao, $previsao, $id_chamado,$id_responsavel,$solucionado,$data_solucao);
		header("Location: ../index.php");	
	}else{
		$data = $_POST['data'];
		$hora = $_POST['hora'];
		$aux_data = explode("/",$data);
		$previsao = $aux_data[2]."-".$aux_data[1]."-".$aux_data[0]." ".$hora.":00";
		
		incluir_solucao($descricao, $previsao, $id_chamado,$id_responsavel,$solucionado,$data_solucao);
		header("Location: ../chamado.php?id=".$_POST['id_chamado']);
	}
	
	$mail->Subject  = "Re: SISTEMA DE CHAMADOS: ".str_pad($chamado[0]['Ch_id'], 4, "0", STR_PAD_LEFT)." - ".$chamado[0]['Ch_assunto']; //define o assunto da msg
	
	
	$mensagem = "";
	$mensagem .= "<html>\n";
	$mensagem .= "	<head>\n";
	$mensagem .= "		<title>CAAL - Chamados</title>\n";
	
	/*INÍCIO: ESTILO CSS*/
	$mensagem .= "		<style type='text/css'>\n";
	$mensagem .= "			body{\n";
	$mensagem .= "				background:center -80px no-repeat;\n";
	$mensagem .= "				padding:0;\n";
	$mensagem .= "				margin:0;\n";
	$mensagem .= "				font-family:sans-serif;\n";
	$mensagem .= "				font-size:12px;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_no_prazo{\n";
	$mensagem .= "				float:left;\n";
	$mensagem .= "				padding:5px;\n";
	$mensagem .= "				margin:5px;\n";
	$mensagem .= "				background-color:#00895E;\n";
	$mensagem .= "				width:auto;\n";
	$mensagem .= "				min-width:150px;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_no_prazo caption{\n";
	$mensagem .= "				border-left: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-top: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-right: 1px solid #C1DAD7;\n";
	$mensagem .= "				background: #fff no-repeat;\n";
	$mensagem .= "				font: bold 15px 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;\n";
	$mensagem .= "				line-height:30px;\n";
	$mensagem .= "				text-transform:uppercase;\n";
	$mensagem .= "				text-align:left;\n";
	$mensagem .= "				padding-left:10px;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_no_prazo td{\n";
	$mensagem .= "				font: bold 12px Arial, Helvetica,sans-serif;\n";
	$mensagem .= "				color: #00895E;\n";
	$mensagem .= "				border-right: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-bottom: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-top: 1px solid #C1DAD7;\n";
	$mensagem .= "				text-transform: capitalize;\n";
	$mensagem .= "				text-align: justify;\n";
	$mensagem .= "				padding: 4px 5px 4px 5px;\n";
	$mensagem .= "				background: #E5E5E3 repeat-x;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_no_prazo td p{\n";
	$mensagem .= "				letter-spacing: 0px;\n";
	$mensagem .= "				color:#000000;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_dia_prazo{\n";
	$mensagem .= "				float:left;\n";
	$mensagem .= "				padding:5px;\n";
	$mensagem .= "				margin:5px;\n";
	$mensagem .= "				background-color:#FF8C00;\n";
	$mensagem .= "				width:auto;\n";
	$mensagem .= "				min-width:150px;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_dia_prazo caption{\n";
	$mensagem .= "				border-left: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-top: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-right: 1px solid #C1DAD7;\n";
	$mensagem .= "				background: #fff no-repeat;\n";
	$mensagem .= "				font: bold 15px 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;\n";
	$mensagem .= "				line-height:30px;\n";
	$mensagem .= "				text-transform:uppercase;\n";
	$mensagem .= "				text-align:left;\n";
	$mensagem .= "				padding-left:10px;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_dia_prazo td{\n";
	$mensagem .= "				font: bold 12px Arial, Helvetica,sans-serif;\n";
	$mensagem .= "				color: #FF8C00;\n";
	$mensagem .= "				border-right: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-bottom: 1px solid #C1DAD7;\n";
	$mensagem .= "				border-top: 1px solid #C1DAD7;\n";
	$mensagem .= "				text-transform: capitalize;\n";
	$mensagem .= "				text-align: justify;\n";
	$mensagem .= "				padding: 4px 5px 4px 5px;\n";
	$mensagem .= "				background: #E5E5E3 repeat-x;\n";
	$mensagem .= "			}\n";
	$mensagem .= "			#tabela_dia_prazo td p{\n";
	$mensagem .= "				letter-spacing: 0px;\n";
	$mensagem .= "				color:#000000;\n";
	$mensagem .= "			}\n";
	$mensagem .= "		</style>\n";
	/*FIM: ESTILO CSS*/
	
	$mensagem .= "	</head>\n";
	$mensagem .= "	<body>\n";
	$mensagem .= "		<table id='tabela_no_prazo'>\n";
	$mensagem .= "			<caption>Chamado - ";
	if($solucionado == 1){
	$mensagem .= "FINALIZADO";
	}else{
		$mensagem .= "PREVISÃO DE SOLUÇÃO";
	}
	$mensagem .= "</caption>\n";
	$mensagem .= "			<tr>\n";
	if($solucionado == 0){
		$mensagem .= "				<td colspan='4'>Previsão</td>\n";
	}else{
		$previsao = date("Y-m-d H:i:s");
		$mensagem .= "				<td colspan='4'>Solucionado</td>\n";
	}
	$mensagem .= "			</tr>\n";
	$mensagem .= "			<tr>\n";
	$mensagem .= "				<td colspan='1'>Solução: </td>\n";
	$mensagem .= "				<td colspan='3'><p>".nl2br($descricao)."</p></td>\n";
	$mensagem .= "			</tr>\n";
	$mensagem .= "			<tr>\n";
	if($solucionado == 1){
		$mensagem .= "				<td colspan='1'>Data de Finalização: </td>\n";
	}else{
		$mensagem .= "				<td colspan='1'>Data prevista: </td>\n";
	}
	$mensagem .= "				<td colspan='3'><p>".formata_data($previsao)."</p></td>\n";
	$mensagem .= "			</tr>\n";
	$mensagem .= "			<tr>\n";
	$mensagem .= "				<td colspan='1'>Número do Chamado: </td>\n";
	$mensagem .= "				<td colspan='3'><p>".str_pad($chamado[0]['Ch_id'], 4, "0", STR_PAD_LEFT)."</p></td>\n";
	$mensagem .= "			</tr>\n";
	$mensagem .= "			<tr>\n";
	$mensagem .= "				<td colspan='1'>Assunto: </td>\n";
	$mensagem .= "				<td colspan='3'><p>".$chamado[0]['Ch_assunto']."</p></td>\n";
	$mensagem .= "			</tr>\n";
	$mensagem .= "			<tr>\n";
	$mensagem .= "				<td colspan='1'>Descrição: </td>\n";
	$mensagem .= "				<td colspan='3'><p>".nl2br($chamado[0]['Ch_descricao'])."</p></td>\n";
	$mensagem .= "			</tr>\n";
	$mensagem .= "			<tr>\n";
	$mensagem .= "				<td>Usuario: </td><td><p>".$chamado[0]['U_nome']."</p></td>\n";
	$mensagem .= "				<td>Equipamento: </td><td><p>".$chamado[0]['C_nome']." - ".$chamado[0]['E_nome']."</p></td>\n";
	$mensagem .= "			</tr>\n";

	$mensagem .= "			<tr>\n";
	$mensagem .= "				<td colspan='1'>Urgência: </td>\n";
	$mensagem .= "				<td colspan='3'>\n";
	$mensagem .= "					<p>\n";
	
	/*INÍCIO: VERIFICA SE CHAMADO POSSUÍ URGÊNCIA*/
	if($chamado[0]['Ch_prioridade']!=NULL){
		$mensagem .= formata_data($chamado[0]['Ch_prioridade']);
	} else {
		$mensagem .= "Sem Urgência\n";
	}
	/*FIM: VERIFICA SE CHAMADO POSSUÍ URGÊNCIA*/
	
	$mensagem .= "					</p>\n";
	$mensagem .= "				</td>\n";
	$mensagem .= "			</tr>\n";
	$mensagem .= "		</table>\n";
	$mensagem .= "	</body>\n";
	$mensagem .= "</html>\n";
		
	
	$mail->From = $chamado[0]['R_email']; // Seu e-mail
	$mail->FromName = $chamado[0]['R_nome']; // Seu nome
	$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
	
	
	$mail->AddAddress($chamado[0]['U_email'], $chamado[0]['U_nome']); // adiciona destinatários
	$mail->AddCC($_server['emailSuporte'], $_server['nomeSuporte']); // adiciona destinatário como cópia
	
	$mail->Body = $mensagem;
	
	// Envia o e-mail
	$enviado = $mail->Send();

	// Limpa os destinatários e os anexos
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	
	if($enviado){
		echo "E-mail enviado com sucesso!";
	}else{
		echo "Não foi possível enviar o e-mail.<br /><br />";
		echo "<b>Informações do erro:</b> <br />" . $mail->ErrorInfo;
	}
?>