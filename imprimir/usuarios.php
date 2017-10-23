<?php
	include("../funcoes.php");
	if(isset($_GET['filial']))
		$id_filial = $_GET['filial'];
	else
		$id_filial = NULL;
	
	if(isset($_GET['setor']))
		$id_setor = $_GET['setor'];
	else
		$id_setor = NULL;
	
	if(isset($_GET['usuario']))
		$id_usuario = $_GET['usuario'];
	else
		$id_usuario = NULL;
	
	/*	RETORNA UMA MATRIZ COM OS DADOS DE UM USU¡RIO
	 *	Tem como entrada o ID do usu·rio e como saÌda a matriz com os dados:
	 *	m[i]['U_id'] -> id do Usuario
	 *	m[i]['U_nome'] -> nome do Usuario
	 *	m[i]['U_ramal'] -> ramal do Usu·rio
	 *	m[i]['U_email'] -> email do Usu·rio
	 *	m[i]['U_usuario'] -> nome de usu·rio do Usu·rio
	 *	m[i]['U_senha'] -> senha do Usu·rio
	 *	m[i]['U_msn'] -> MSN do Usu·rio: 1-liberado, 0-n„o liberado
	 *	m[i]['U_email_msn'] -> endereÁo do MSN do Usu·rio	 
	 *	m[i]['U_disp'] -> Acesso a CD,Pendrive,etc do Usu·rio: 1-liberado, 0-n„o liberado
	 *	m[i]['U_net'] -> Acesso a internet do Usu·rio: 1-liberado, 0-n„o liberado
	 *	m[i]['U_ativo'] -> Usu·rio ativo: 1-ativo, 0-n„o ativo
	 *	m[i]['S_id'] -> id do Setor do Usu·rio
	 *	m[i]['S_nome'] -> nome do Setor do Usu·rio
	 *	m[i]['F_id'] -> id da Filial do Usu·rio	 
	 *	m[i]['F_nome'] -> nome da Filial do Usu·rio
	 *	m[i]['F_endereco'] -> endereÁo da Filial do Usu·rio
	 *	m[i]['F_telefone'] -> telefone da Filial do Usu·rio
	 */
	$usuarios = retorna_usuarios($id_filial,$id_setor,$id_usuario);
	
	$setor="";
	$filial="";	
	$html = "";
	
	$html .= "<html>";
	$html .= "	<head>";
	$html .= "		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
	$html .= "		<title>CAAL - Chamados</title>";
	$html .= "		<link rel='stylesheet' type='text/css' href='imprimir.css' />";
	$html .= "	</head>";
	$html .= "	<body>";
	$html .= "		<table>";
	for($i=0;$i<count($usuarios);$i++){
		if($filial != $usuarios[$i]['F_nome']){
			$html .= "			<tr><td colspan='7' class='filial'>".$usuarios[$i]['F_nome']."</td></tr>";
			$filial = $usuarios[$i]['F_nome'];
		}
		if($setor != $usuarios[$i]['S_nome']){
			$html .= "			<tr><td colspan='7' class='set'>".$usuarios[$i]['S_nome']."</td></tr>";
			$setor = $usuarios[$i]['S_nome'];
			$html .= "			<tr>";
			$html .= "				<th>Nome</th>";
			$html .= "				<th>Email</th>";
			$html .= "				<th>Usu√°rio</th>";
			$html .= "				<th>Senha</th>";
			$html .= "				<th>MSN</th>";
			$html .= "				<th>Dispositivos</th>";
			$html .= "				<th>Internet</th>";
			$html .= "			</tr>";
		}		
		$html .= "			<tr>";
		$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">".$usuarios[$i]['U_nome']."</td>";
		$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">".$usuarios[$i]['U_email']."</td>";
		$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">".$usuarios[$i]['U_usuario']."</td>";
		$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">".$usuarios[$i]['U_senha']."</td>";
		if($usuarios[$i]['U_msn']==0){
			$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">N</td>";
		}else{
			$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">S</td>";
		}
		if($usuarios[$i]['U_disp']==0){
			$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">N</td>";
		}else{
			$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">S</td>";
		}
		if($usuarios[$i]['U_net']==0){
			$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">N</td>";
		}else{
			$html .= "				<td";
									if($i % 2 ==0){$html .= " class='intercalado'";}
		$html .= ">S</td>";
		}
		$html .= "			</tr>";
	}
	$html .= "		</table>";
	$html .= "	</body> ";
	$html .= "</html>";

	require_once("../dompdf/dompdf_config.inc.php");
	
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('a4', 'landscape');
	$dompdf->render();
	$dompdf->stream("usuarios.pdf");
?>