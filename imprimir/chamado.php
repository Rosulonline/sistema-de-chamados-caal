<?php
	include("../funcoes.php");
	
	//DECLARAÇÕES DE VARIÁVEIS
	
	$login = protegePagina();
	
	$id_filial=NULL;
	$id_setor=NULL;
	$id_usuario=NULL;
	$id_equipamento=NULL;
	$id_chamado=$_GET['id'];
	$aberto = NULL;
	$inicial = NULL;
	$por_pagina = NULL;
	$id_responsavel = NULL;
	
	if(isset($_GET['id'])){
		$chamado = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
		$redirecionamentos = retorna_redirecionamentos_chamado($id_chamado);
		$solucoes = retorna_chamado_solucoes($id_chamado);
	}else{
		return FALSE;
	}
	
	//FIM DECLARAÇÕES
	
	
	
	$html = "";
	
	$html .= "<!DOCTYPE html>";
	$html .= "<html lang='pt-br'>";
	$html .= "	<head>";
	$html .= "		<meta charset='utf-8' />";
	$html .= "		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame";
	$html .= "				Remove this if you use the .htaccess -->";
	$html .= "		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />";
	$html .= "		<title>CAAL - Chamados</title>";
	$html .= "		<meta name='description' content='Descrição do chamado preparada para impressão' />";
	$html .= "		<meta name='author' content='CAAL - Setor de Informática' />";
	$html .= "		<meta name='viewport' content='width=device-width; initial-scale=1.0' />";
	$html .= "		<link rel='stylesheet' href='imprimir.css' />";
	$html .= "	</head>";
	$html .= "	<body>";
	$html .= "		<div>";
	$html .= "			<header>";
	$html .= "				<h1>Chamado: ".$chamado[0]['Ch_id']." | ".$chamado[0]['Ch_assunto']." | ".formata_data($chamado[0]['Ch_data_abertura'])."</h1>";
	$html .= "			</header>";
	$html .= "			<table>";
	$html .= "				<tr>";
	$html .= "					<td>";
	$html .= "						<h2>Dados do Chamado</h2>";
	$html .= "						<p>ID: <div class='info'>".$chamado[0]['Ch_id']."</div></p>";
	$html .= "						<p>Assunto: <div class='info'>".$chamado[0]['Ch_assunto']."</div></p>";
	$html .= "						<p>Descrição: <div class='info'>".$chamado[0]['Ch_descricao']."</div></p>";
	$html .= "						<p>Técnico Responsável: <div class='info'>".$chamado[0]['R_nome']."</div></p>";
	$html .= "						<p>Estado: <div class='info'>".retorna_estado_chamado($chamado[0]['Ch_aberto'])."</div></p>";
	$html .= "					</td>";
	$html .= "					<td>";
	$html .= "						<h2>Datas</h2>";
	$html .= "						<p>Data Abertura: <div class='info'>".formata_data($chamado[0]['Ch_data_abertura'])."</div></p>";
	$html .= "						<p>Data Encerramento: <div class='info'>".formata_data($chamado[0]['So_data_solucionado'])."</div></p>";
	$html .= "						<p>Data de Vencimento: <div class='info'>".formata_data($chamado[0]['Ch_prioridade'])."</div></p>";
	$html .= "						<p>Data inicial da solução do Chamado: <div class='info'>".formata_data($chamado[0]['So_data_inicial'])."</div></p>";
	$html .= "						<p>Data prevista para solução do Chamado: <div class='info'>".formata_data($chamado[0]['Ch_previsao'])."</div></p>";
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "				<tr>";
	$html .= "					<td>";
	$html .= "						<h2>Dados do Usuário</h2>";
	$html .= "						<p>Nome do Usuário: <div class='info'>".$chamado[0]['U_nome']."</div></p>";
	$html .= "						<p>E-mail: <div class='info'>".$chamado[0]['U_email']."</div></p>";
	$html .= "						<p>Ramal / Telefone: <div class='info'>".$chamado[0]['U_ramal']."</div></p>";
	$html .= "						<p>Usuário: <div class='info'>".$chamado[0]['U_usuario']."</div></p>";
	$html .= "						<p>Setor: <div class='info'>".$chamado[0]['S_nome']."</div></p>";
	$html .= "						<p>Filial: <div class='info'>".$chamado[0]['F_nome']."</div></p>";
	$html .= "					</td>";
	$html .= "					<td>";
	$html .= "						<h2>Dados do Equipamento</h2>";
	$html .= "						<p>Nome: <div class='info'>".$chamado[0]['E_nome']."</div></p>";
	$html .= "						<p>Descrção: <div class='info'>".nl2br($chamado[0]['E_descricao'])."</div></p>";
	$html .= "						<p>Número de Série: <div class='info'>".$chamado[0]['E_ns']."</div></p>";
	$html .= "						<p>IP: <div class='info'>".$chamado[0]['E_ip']."</div></p>";
	$html .= "						<p>Tipo de Equipamento: <div class='info'>".$chamado[0]['C_nome']."</div></p>";
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "				<tr>";
	$html .= "					<td colspan='2'>";
	$html .= "						<h2>Histórico do Chamado</h2>";
	for($i = 0; $i< count($solucoes); ++$i){
		$html .= "						<p>Status: ".retorna_tipo_solucao($solucoes[$i]['So_solucionado'])." | ".formata_data($solucoes[$i]['So_data_inicio'])." | Responsável: ".$solucoes[$i]['R_nome']." <div class='info'>".$solucoes[$i]['So_descricao']."</div></p>";
	}
	for($i=0; $i < count($redirecionamentos); ++$i){
		$html .= "						<p>Transferência | ".formata_data($redirecionamentos[$i]['RC_data'])."<div class='info'>Transferido de: ".$redirecionamentos[$i]['DE_nome']." para: ".$redirecionamentos[$i]['PARA_nome']."<br/>Motivo: ".nl2br($redirecionamentos[$i]['RC_motivo'])." </div></p>";
	}
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "			</table>";
	$html .= "			<footer>";
	$html .= "				<p>";
	//$html .= "					Informação Gerada: DD de Mês de AAAA as HH:MM:SS";
	$html .= "				</p>";
	$html .= "			</footer>";
	$html .= "		</div>";
	$html .= "	</body>";
	$html .= "</html>";
	
	$html = utf8_decode($html);
	require_once("../dompdf/dompdf_config.inc.php");
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();
	$dompdf->stream("usuario.pdf");
?>