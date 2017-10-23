<?php 
	include('../funcoes.php'); 
	$adm = verifica_login_adm();
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
				<form action="../novo/reabrir_chamado.php" method="post" style="width: 750px;">
					<legend><span>Reabrir Chamado</span></legend>
					<p>
						<label for="descricao">Motivo:</label>
						<textarea id="descricao" name="descricao" cols="100" rows="10"></textarea>
						<input name='id_chamado' value="<?php echo $_GET['id_chamado']; ?>" type="hidden" >
						<input type="submit" value="Reabrir" >
					</p>
				</form>
			</div>
			<div id="rodape"></div>
		</div>
		<div id='nav-bar'>
			<ul>
				<li><a href='/formularios/cadastro_chamado.php'>Novo Chamado</a></li>
				<li><a href='/index.php'>Página Inicial</a></li>
				<li><a href='/sair.php'>Encerra Sessão</a></li>
				<?php 	if($adm){ ?>
				<li><form action='buscar.php' method='get'><input name='nome' type='text'/><input type='submit' value='Buscar'/></form></li>
				<?php 	} ?>
			</ul>
		</div>
	</body> 
</html>