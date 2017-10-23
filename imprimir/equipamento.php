<?php
	include("../funcoes.php");
	$id_equipamento = $_GET['id_equipamento'];
	$usuarios = retorna_usuarios_equipamento($id_equipamento);
	$equipamento = retorna_dados_equipamento($id_equipamento,0);
	$html = "
<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<title>CAAL - Chamados</title>
		<link rel='stylesheet' type='text/css' href='imprimir.css' />
	</head>
	<body>
		<h1>".$equipamento['E_nome']."</h1>
		<h2>".$equipamento['C_nome']."</h2>
		<p class='nome'>Descrição</p>
		<p>".nl2br($equipamento['E_descricao'])."</p>
		<p class='nome'>Número de Série</p>
		<p>".$equipamento['E_ns']."</p>
		<p class='nome'>Nota Fiscal</p>
		<p>".$equipamento['E_nf']."</p>
		<p class='nome'>MAC</p>
		<p>".$equipamento['E_mac']."</p>
		<p class='nome'>IP</p>
		<p>".$equipamento['E_ip']."</p>
		<p class='nome'>Usuários</p>";
		$setor = "";
		for($i=0;$i<count($usuarios);$i++){
			
			if($setor != $usuarios[$i]['S_nome']){
				$html .= "<p class='setor'>".$usuarios[$i]['S_nome']."</p>";
				$setor = $usuarios[$i]['S_nome'];
			}
			$html.= "<p>".$usuarios[$i]['U_nome']."</p>";
		}
	$html.= "
	</body> 
</html>
";

	require_once("../dompdf/dompdf_config.inc.php");
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('a4', 'portrait');
	$dompdf->render();
	$dompdf->stream("exemplo-01.pdf");
?>