<?php 
	include('../funcoes.php'); 
	$id_responsavel = NULL;
	$responsaveis = retorna_responsaveis($id_responsavel);
	$id_chamado = $_GET['id_chamado'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<link rel="stylesheet" type="text/css" href="/css/jquery.toastmessage.css" />
		<script type="text/javascript" src="/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/js/jquery.toastmessage.js"></script>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<form action="../novo/redirecionamento.php" method="post">
					<table id="tabela" style="width: 750px;">
						<caption>Redirecionar Chamado</caption>
						<tr>
							<td>Motivo: </td>
							<td><textarea name="motivo" cols="100" rows="10"></textarea></td>
							<input type="hidden" name="id_chamado" value="<?php echo $id_chamado; ?>">
						</tr>
						<tr>
							<td>Para:</td>
							<td>
								<select name="para">
									<option value="0">Selecione o responsável</option>
									<?php for($i=0;$i<count($responsaveis);$i++){ ?>
									
									<option value="<?php echo $responsaveis[$i]['R_id']; ?>"><?php echo $responsaveis[$i]['R_nome']; ?></option>
									<?php } ?>
									
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" value="Redirecionar"></td>
						</tr>
					</table>
				</form>
			</div>
			<div id="rodape"></div>
		</div>
		<div id='nav-bar'>
			<ul>
				<li><a href='/formularios/cadastro_chamado.php'>Novo Chamado</a></li>
				<li><a href='/index.php'>Página Inicial</a></li>
				<li><a href='/sair.php'>Encerra Sessão</a></li>
				<li><form action='/buscar.php' method='get'><input name='nome' type='text'/><input type='submit' value='Buscar'/></form></li>
			</ul>
		</div>
	</body> 
</html>