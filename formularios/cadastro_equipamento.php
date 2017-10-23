<?php
	include('../funcoes.php');
	
	$login = protegePagina();
	
	if(isset($_GET['id_usuario']))
		$aux = retorna_usuarios(NULL,NULL,$_GET['id_usuario']);
		
	$classes = retorna_classes();
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
			
				<form action="../novo/equipamento.php<?php if(isset($_GET['id_usuario'])){ echo "?id_usuario=".$_GET['id_usuario']; }else if(isset($_GET['id_pc'])){ echo "?id_pc=".$_GET['id_pc'];}?>" method="post">
					<fieldset>
						<legend><span>Cadastro de Equipamento</span></legend>
						<p>
							<label for="nome">Nome:</label>
							<input type="text" name="nome" id="nome"/>
							
							<label for="descricao">Descrição:</label>
							<textarea id="descricao" name="descricao" rows="10" cols="100"></textarea>
							
							<label for="ns">Número de série:</label>
							<input type="text" name="ns" id="ns"/>
							
							<label for="nf">Nota fiscal:</label>
							<input type="text" name="nf" id="ns"/>
							
							<label for="mac">MAC:</label>
							<input type="text" name="mac" id="mac"/>
							
							<label for="ip">IP:</label>
							<input type="text" name="ip" id="ip"/>
							
							<label for="classe">Tipo Equipamento:</label>
							<select name="classe" id="classe">
								<option value="">Selecione o tipo</option>
								<?php for($i=0;$i<count($classes);$i++) { ?>
									<option value="<?php echo $classes[$i]['C_id']; ?>"><?php echo $classes[$i]['C_nome']; ?></option>
								<?php } ?>
							</select>
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
				<?php if(isset($_GET['id_usuario'])){ ?>
				<li><a href='/sub_filial.php?filial=<?php echo $aux[0]['F_id']; ?>'><?php echo $aux[0]['F_nome']; ?></a></li>
				<li><a href='/setor.php?id=<?php echo $aux[0]['S_id']; ?>'><?php echo $aux[0]['S_nome']; ?></a></li>
				<li><a href='/usuario.php?id=<?php echo $aux[0]['U_id']; ?>'><?php echo $aux[0]['U_nome']; ?></a></li>
				<?php } ?>
				<li><a href='/sair.php'>Encerra Sessão</a></li>
				<li><form action='/buscar.php' method='get'><input name='nome' type='text'/><input type='submit' value='Buscar'/></form></li>
			</ul>
		</div>
	</body> 
</html>