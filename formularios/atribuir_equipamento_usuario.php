<?php 
	include('../funcoes.php'); 
	$equipamentos = retorna_equipamentos($com_restricao = true, $componentes = false, $inicial = 1, $por_pagina = 1000);
	$usuarios = retorna_usuarios();
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
				<form action="/novo/relacionar_equipamento_usuario.php" method="post">
					<fieldset>
						<legend><span>Relacionar Equipamento a Usuário</span></legend>
						<p>
							<label for="nome">Nome:</label>
							<select name="nome" id="nome">
								<option value='0'>Selecione o Nome</option>
								<?php 	for($i=0;$i<count($usuarios);$i++){ ?>
								<?php		if($usuarios[$i]['S_id'] != $id){ ?>
								<option>============================ <?php echo $usuarios[$i]['S_nome']; ?></option>
								<?php 			$id = $usuarios[$i]['S_id']; ?>
								<?php		} ?>
								<option value="<?php echo $usuarios[$i]['U_id']; ?>" <?php if($usuarios[$i]['U_id']==$_GET['id_usuario']){echo "selected='selected' ";} ?>><?php echo $usuarios[$i]['U_nome']; ?></option>
								<?php 	} ?>
							</select>
							<label for="equipamento">Equipamento:</label>
							<select name="equipamento" id="equipamento">
								<option value='0'>Selecione o Equipamento</option>
								<?php	for($i=0;$i<count($equipamentos);$i++){ ?>
								<option value="<?php echo $equipamentos[$i]['E_id']; ?>"><?php echo $equipamentos[$i]['C_nome'].": ".$equipamentos[$i]['E_nome']; ?></option>
								<?php	} ?>
							</select>
							<br/>
							<input type="submit" value="Relacionar">
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