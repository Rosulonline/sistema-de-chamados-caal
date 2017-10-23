<?php
	include("../funcoes.php");
	$login = protegePagina();
	require("../PHPMailer/class.phpmailer.php");
	
	$mail = new PHPmailer();
	$mail->IsSMTP();
	$mail->IsHTML(true); // define como html
	
	
	$assunto		= $_POST['assunto'];
	$descricao		= $_POST['descricao'];
	$id_responsavel = $_POST['responsavel'];
	$id_usuario 	= $_POST['usuario'];
	$id_equipamento = $_POST['equipamento'];
	$prioridade 	= $_POST['prioridade'];
	
	
	function urgencia($data){
		if($data!=NULL){
			$a = explode(" ",$data);
			$data_atual = date("Y-m-d");
			$hora_atual = date("H:i:s");
			$data_informada = $a[0];
			$hora_informada = $a[1];
			
			if($data_atual == $data_informada){
				if($hora_atual > $hora_informada)
					$aux = "fora_prazo";
				else
					$aux = "dia_prazo";
			} else if($data_atual < $data_informada || $data_informada==NULL){
				$aux = "no_prazo";
			} else {
				$aux = "fora_prazo";
			}
		} else{
			$aux = "no_prazo";
		}
		return $aux;
	}
	
	switch ($prioridade){
		case null:
			$data_prioridade = date("Y-m-d"). " 18:00:00";
			echo $data_prioridade;
			break;
		case strlen($prioridade) > 1:
			$data_prioridade = date("Y-m-d",strtotime($prioridade)). " 18:00:00";
			echo $data_prioridade;
			break;
		case 0: 
			$data_prioridade = null;
			break;
		case 1: 
			$data_prioridade = retorna_proximo_dia_util(retorna_soma_data(0));
			break;
		case 2: 
			$data_prioridade = retorna_proximo_dia_util(retorna_soma_data(2));
			break;
		case 3: 
			$data_prioridade = retorna_proximo_dia_util(retorna_soma_data(7));
			break;
		/*
		case 4: 
			$data_prioridade = retorna_proximo_dia_util(retorna_soma_data(6));
			break;
		*/
		default:
			$data_prioridade = NULL;
	}
	
	if($id_responsavel == 0 || $id_usuario == 0 || $id_equipamento == 0 || $assunto == "" || $descricao == ""){
		echo "<form>";
		echo "	<p>Faltando Dados</p>";
		echo "	<input type='button' value='Voltar' onClick='JavaScript: window.history.back();'/>";
		echo "</form>";
	}else{
		$id_chamado = incluir_chamado($assunto, $descricao, $id_responsavel, $id_usuario, $id_equipamento,$data_prioridade);
		
		$chamado = retorna_chamados(NULL,NULL,NULL,NULL,$id_chamado);
		
		$mail->Subject  = "SISTEMA DE CHAMADOS: ".str_pad($id_chamado, 4, "0", STR_PAD_LEFT)." - ".$assunto;
		$mensagem = "";
		$mensagem .= "<html>";
		$mensagem .= "	<head>";
		$mensagem .= "		<title>CAAL - Chamados</title>";
		
		
		/*INÍCIO: ESTILO CSS*/
		$mensagem .= "		<style type='text/css'>";
		$mensagem .= "			body{";
		$mensagem .= "				background:center -80px no-repeat;";
		$mensagem .= "				padding:0;";
		$mensagem .= "				margin:0;";
		$mensagem .= "				font-family:sans-serif;";
		$mensagem .= "				font-size:12px;";
		$mensagem .= "			}";
		$mensagem .= "			#tabela{";
		$mensagem .= "				float:left;";
		$mensagem .= "				padding:5px;";
		$mensagem .= "				margin:5px;";
		$mensagem .= "				background-color:#00895E;";
		$mensagem .= "				width:auto;";
		$mensagem .= "				min-width:150px;";
		$mensagem .= "			}";
		$mensagem .= "			#tabela caption{";
		$mensagem .= "				border-left: 1px solid #C1DAD7;";
		$mensagem .= "				border-top: 1px solid #C1DAD7;";
		$mensagem .= "				border-right: 1px solid #C1DAD7;";
		$mensagem .= "				background: #fff no-repeat;";
		$mensagem .= "				font: bold 15px 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;";
		$mensagem .= "				line-height:30px;";
		$mensagem .= "				text-transform:uppercase;";
		$mensagem .= "				text-align:left;";
		$mensagem .= "				padding-left:10px;";
		$mensagem .= "			}";
		$mensagem .= "			#tabela td{";
		$mensagem .= "				font: bold 12px Arial, Helvetica,sans-serif;";
		$mensagem .= "				color: #00895E;";
		$mensagem .= "				border-right: 1px solid #C1DAD7;";
		$mensagem .= "				border-bottom: 1px solid #C1DAD7;";
		$mensagem .= "				border-top: 1px solid #C1DAD7;";
		$mensagem .= "				text-transform: capitalize;";
		$mensagem .= "				text-align: justify;";
		$mensagem .= "				padding: 4px 5px 4px 5px;";
		$mensagem .= "				background: #E5E5E3 repeat-x;";
		$mensagem .= "			}";
		$mensagem .= "			#tabela td p{";
		$mensagem .= "				letter-spacing: 0px;";
		$mensagem .= "				color:#000000;";
		$mensagem .= "			}";
		$mensagem .= "		</style>";
		/*FIM: ESTILO CSS*/
		
		$mensagem .= "	</head>";
		$mensagem .= "	<body>";
		$mensagem .= "		<table id='tabela'>";
		$mensagem .= "			<caption>Chamado - Abertura</caption>";
		$mensagem .= "			<tr>";
		$mensagem .= "				<td colspan='1'>Número do Chamado: </td>";
		$mensagem .= "				<td colspan='3'><p>".str_pad($id_chamado, 4, "0", STR_PAD_LEFT)."</p></td>";
		$mensagem .= "			</tr>";
		$mensagem .= "			<tr>";
		$mensagem .= "				<td colspan='1'>Assunto: </td>";
		$mensagem .= "				<td colspan='3'><p>".$chamado[0]['Ch_assunto']."</p></td>";
		$mensagem .= "			</tr>";
		$mensagem .= "			<tr>";
		$mensagem .= "				<td colspan='1'>Desrição: </td>";
		$mensagem .= "				<td colspan='3'><p>".nl2br($chamado[0]['Ch_descricao'])."</p></td>";
		$mensagem .= "			</tr>";
		$mensagem .= "			<tr>";
		$mensagem .= "				<td>Usuario: </td><td><p>".$chamado[0]['U_nome']."</p></td>";
		$mensagem .= "				<td>Equipamento: </td><td><p>".$chamado[0]['C_nome']." - ".$chamado[0]['E_nome']."</p></td>";
		$mensagem .= "			</tr>";
		$mensagem .= "			<tr>";
		$mensagem .= "				<td colspan='1'>Urgência: </td>";
		$mensagem .= "				<td colspan='3'>";
		$mensagem .= "					<p>";
	
		if($data_prioridade!=NULL){
			$mensagem .= formata_data($data_prioridade);
		} else {
			$mensagem .= "Sem Urgência";
		}
		
		$mensagem .= "					</p>";
		$mensagem .= "				</td>";
		$mensagem .= "			</tr>";
		$mensagem .= "			<tr>";
		$mensagem .= "				<td>Responsável: </td>";
		$mensagem .= "				<td colspan='3'><p>".$chamado[0]['R_nome']."</p></td>";
		$mensagem .= "			</tr>";
		$mensagem .= "			<tr>";
		$mensagem .= "				<td colspan='4'>";
		$mensagem .= "					Para maiores informações, acesse: <a href='http://chamados.caal.com.br/'>Sistema de Chamados</a>";
		$mensagem .= "				</td>";
		$mensagem .= "			</tr>";
		$mensagem .= "		</table>";
		$mensagem .= "	</body>";
		$mensagem .= "</html>";

		$mail->From		= $chamado[0]['U_email']; // Seu e-mail
		$mail->FromName	= $chamado[0]['U_nome']; // Seu nome
		$mail->CharSet	= 'utf-8'; // Charset da mensagem (opcional)		
		$mail->Body		= $mensagem;
		
		$mail->AddAddress($_server['emailSuporte'], $_server['nomeSuporte']); // adiciona destinatários
		$mail->AddCC($chamado[0]['U_email'],$chamado[0]['U_nome']);
		//$mail->AddAddress($chamado[0]['R_email'], $chamado[0]['R_nome']); // adiciona destinatários
		
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
		
		header("Location: ../index.php");
	}
?>