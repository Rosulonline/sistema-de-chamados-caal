<?php
	include("../funcoes.php");
	
	//DECLARAÇÕES DE VARIÁVEIS
	
	$id_filial = NULL;
	$id_setor = NULL;
	$id_classe = NULL;
	$id_usuario = $_GET['usuario'];
	
	//FIM DECLARAÇÕES
	
	$usuario = retorna_usuarios($id_filial,$id_setor,$id_usuario);
	$equipamentos = retorna_equipamentos_usuario($id_usuario,$id_classe);
	$html = "";
	$html .= "<html>";
	$html .= "	<head>";
	$html .= "		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
	$html .= "		<title>CAAL - Chamados</title>";
	$html .= "		<link rel='stylesheet' type='text/css' href='imprimir.css' />";
	$html .= "	</head>";
	$html .= "	<body>";
	$html .= "		<h1>".$usuario[0]['U_nome']."</h1>";
	$html .= "		<h2>".$usuario[0]['U_usuario']." | ".$usuario[0]['U_senha']."</h2>";
	$html .= "		<p class='nome'>E-mail:</p>";
	$html .= "		<p>".$usuario[0]['U_email']."</p>";
	$html .= "		<p class='nome'>Filial:</p>";
	$html .= "		<p>".$usuario[0]['F_nome']."</p>";
	$html .= "		<p class='nome'>Setor:</p>";
	$html .= "		<p>".$usuario[0]['S_nome']."</p>";
	$html .= "		<p class='nome'>Liberações:</p>";
	//liberações
	$html .= "		<p>";
	if($usuario[0]['U_msn']==1){	$html .= "msn: ".$usuario[0]['U_email_msn']." | ";}
	if($usuario[0]['U_disp']==1){	$html .= "dispositivos | ";}
	if($usuario[0]['U_net']==1){	$html .= "internet";}
	if($usuario[0]['U_net']==0 && $usuario[0]['U_disp']==0 && $usuario[0]['U_msn']==0){$html .= "Nenhuma liberação.";}
	$html .= "		</p>";
	//fim liberações
	$html .= "		<p class='nome'>Equipamentos:</p>";
	for($i=0;$i<count($equipamentos);$i++){
	$html .= "		<p class='setor'>".$equipamentos[$i]['C_nome'].": ".$equipamentos[$i]['E_nome']."</p>";
	$html .= "		<p>".nl2br($equipamentos[$i]['E_descricao'])."</p>";
	}
	$html .= "	</body>";
	$html .= "</html>";

	require_once("../dompdf/dompdf_config.inc.php");
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();
	$dompdf->stream("usuario.pdf");
?>