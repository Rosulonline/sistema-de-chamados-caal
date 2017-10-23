<?php 
	include('../funcoes.php');
	$id_filial = NULL;
	$id_setor = NULL;
	$setores = retorna_setores($id_filial,$id_setor);
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
				<form action="/novo/usuario.php<?php if(isset($_GET['filial'])){ echo "?filial=".$_GET['filial']; }?>" method="post" style="width:500px;">
					<fieldset>
						<legend><span>Cadastro de Usuário</span></legend>
						
						<p>
							<label for="nome">Nome:</label>
							<input type="text" name="nome" id="nome">
						
							<label for="ramal">Ramal:</label>
							<input type="text" name="ramal" id="ramal">
							
							<label for="email">E-mail:</label>
							<input type="text" name="email" id="email">
							
							<label for="usuario">Usuário:</label>
							<input type="text" name="usuario" id="usuario">
							
							<label for="senha">Senha:</label>
							<input type="text" name="senha" id="senha">
							
							<label for="msn">MSN:</label>
							<input type="checkbox" name="msn" id="msn">
							
							<label for="net">Internet:</label>
							<input type="checkbox" name="net" id="net">
							
							<label for="disp">Pendrive:</label>
							<input type="checkbox" name="disp" id="disp">
						
							<label for="setor">Setor:</label>
							<select name="setor" id="setor">
								<option>Selecione o Setor</option>
								<?php	for($i=0;$i<count($setores);$i++){ ?>
								<option value="<?php echo $setores[$i]['S_id']; ?>"><?php echo $setores[$i]['S_nome']; ?></option>
								<?php	} ?>
							</select>								
							<label for="ativo">Ativo:</label>
							<input type="checkbox" checked name="ativo" id="ativo">
							
							<br>
							<br>
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