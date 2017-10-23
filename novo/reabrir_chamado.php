<?php
	include("../funcoes.php");
	
	$descricao = $_POST['descricao'];
	$id_chamado = $_POST['id_chamado'];
	$id_filial=NULL;
	$id_setor=NULL;
	$id_usuario=NULL;
	$id_equipamento=NULL;
	$aberto = NULL;
	$inicial = NULL;
	$por_pagina = NULL;
	
	reabrir_chamado($id_chamado);
	$chamado = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
	
	/*	VERIFICA SE O USUÁRIO CONECTADO É ADMINISTRADOR E DEFINE A VARIAVEL id_responsavel COM
 	 *	O ID DO USUÁRIO LOGADO, CASO NÃO SEJA ADIMINISTRADOR A VARIÁVEL ASSUME O VALOR DO RESPONSÁVEL
	 *	PELO CHAMADO
	 */
	if(isset($_SESSION['id_resp'])){
		$id_responsavel = $_SESSION['id_resp'];
	}else{
		$id_responsavel = $chamado[0]['R_id'];
	}
	$solucionado = 2;
	$data_solucao = NULL;
	$previsao = NULL;
	
	incluir_solucao($descricao, $previsao, $id_chamado,$id_responsavel,$solucionado,$data_solucao);
	
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
	
	/*ENVIA EMAIL*/
	$m_assunto = "Re: SISTEMA DE CHAMADOS: ".str_pad($chamado[0]['Ch_id'], 4, "0", STR_PAD_LEFT)." - ".$chamado[0]['Ch_assunto'];
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
	$mensagem .= "			#tabela_no_prazo{";
	$mensagem .= "				float:left;";
	$mensagem .= "				padding:5px;";
	$mensagem .= "				margin:5px;";
	$mensagem .= "				background-color:#00895E;";
	$mensagem .= "				width:auto;";
	$mensagem .= "				min-width:150px;";
	$mensagem .= "			}";
	$mensagem .= "			#tabela_no_prazo caption{";
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
	$mensagem .= "			#tabela_no_prazo td{";
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
	$mensagem .= "			#tabela_no_prazo td p{";
	$mensagem .= "				letter-spacing: 0px;";
	$mensagem .= "				color:#000000;";
	$mensagem .= "			}";
	$mensagem .= "			#tabela_dia_prazo{";
	$mensagem .= "				float:left;";
	$mensagem .= "				padding:5px;";
	$mensagem .= "				margin:5px;";
	$mensagem .= "				background-color:#FF8C00;";
	$mensagem .= "				width:auto;";
	$mensagem .= "				min-width:150px;";
	$mensagem .= "			}";
	$mensagem .= "			#tabela_dia_prazo caption{";
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
	$mensagem .= "			#tabela_dia_prazo td{";
	$mensagem .= "				font: bold 12px Arial, Helvetica,sans-serif;";
	$mensagem .= "				color: #FF8C00;";
	$mensagem .= "				border-right: 1px solid #C1DAD7;";
	$mensagem .= "				border-bottom: 1px solid #C1DAD7;";
	$mensagem .= "				border-top: 1px solid #C1DAD7;";
	$mensagem .= "				text-transform: capitalize;";
	$mensagem .= "				text-align: justify;";
	$mensagem .= "				padding: 4px 5px 4px 5px;";
	$mensagem .= "				background: #E5E5E3 repeat-x;";
	$mensagem .= "			}";
	$mensagem .= "			#tabela_dia_prazo td p{";
	$mensagem .= "				letter-spacing: 0px;";
	$mensagem .= "				color:#000000;";
	$mensagem .= "			}";
	$mensagem .= "		</style>";
	/*FIM: ESTILO CSS*/
	
	$mensagem .= "	</head>";
	$mensagem .= "	<body>";
	$mensagem .= "		<table id='tabela_".urgencia($chamado[0]['Ch_prioridade'])."'>";
	$mensagem .= "			<caption>Chamado - Reabertura</caption>";
	$mensagem .= "			<tr>";
	$mensagem .= "				<td colspan='1'>Número do Chamado: </td>";
	$mensagem .= "				<td colspan='3'><p>".str_pad($chamado[0]['Ch_id'], 4, "0", STR_PAD_LEFT)."</p></td>\n";
	$mensagem .= "			</tr>";
	$mensagem .= "			<tr>";
	$mensagem .= "				<td colspan='1'>Motivo Reabertura: </td>";
	$mensagem .= "				<td colspan='3'><p>".nl2br($descricao)."</p></td>";
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
	
	/*INÍCIO: VERIFICA SE CHAMADO POSSUÍ URGÊNCIA*/
	if($chamado[0]['Ch_prioridade']!=NULL){
		$mensagem .= formata_data($chamado[0]['Ch_prioridade']);
	} else {
		$mensagem .= "Sem Urgência";
	}
	/*FIM: VERIFICA SE CHAMADO POSSUÍ URGÊNCIA*/
	
	$mensagem .= "					</p>";
	$mensagem .= "				</td>";
	$mensagem .= "			</tr>";
	$mensagem .= "			<tr>";
	$mensagem .= "				<td>Responsável: </td>";
	$mensagem .= "				<td colspan='3'><p>".$chamado[0]['R_nome']."</p></td>";
	$mensagem .= "			</tr>";
	$mensagem .= "		</table>";
	$mensagem .= "	</body>";
	$mensagem .= "</html>";
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= "From: ".$chamado[0]['U_nome']."<".$chamado[0]['U_email']."> \r\n";
	$headers .=	"Reply-To: ".$chamado[0]['U_email']. "\r\n";
	$headers .= "Cc: ".$chamado[0]['U_nome']."<".$chamado[0]['U_email']."> \r\n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	mail($_server['emailSuporte'], $m_assunto, $mensagem, $headers);
	/*FIM - ENVIA EMAIL*/
	
	
	header("Location: ../chamado.php?id=".$_POST['id_chamado']);
?>