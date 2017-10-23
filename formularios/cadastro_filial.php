<?php include('../funcoes.php'); ?>
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
				<form action="/novo/filial.php" method="post">
					<fieldset>
						<legend><span>Cadastrar Filial</span></legend>
						
						<p>
							<label for="nome">Nome: </label>
							<input type="text" name="nome" id="nome"/>
							
							
							<label for="endereco">Endereço:</label>
							<input type="text" name="endereco" id="endereco"/>
							
							<label id="telefone">Telefone:</label>
							<input type="text" name="telefone" id="telefone"/>
							
							<br/>
							<br/>
							
							<input type="submit" value="Cadastrar">
						</p>
				</fieldset>
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