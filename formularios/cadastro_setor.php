<?php 
	include('../funcoes.php');
	if(isset($_POST['filial'])){
		$post_id_filial = $_POST['filial'];
	}
	
	$id_filial = NULL;
	
	$filiais = retorna_filiais($id_filial);
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
				<form action="/novo/setor.php" method="post">
					<fieldset>
						<legend><span>Cadastro de Setor</span></legend>
						
						<p>
							<label for="nome">Nome:</label>
							<input type="text" name="nome" id="nome"/>
							
							<label for="filial">Filial:</label>
							<select name="filial" id="filial">
								<option>Selecione a Filial</option>
								<?php	for($i=0;$i<count($filiais);$i++){ ?>
								<option <?php if(isset($post_id_filial) && $post_id_filial == $filiais[$i]['F_id']){ echo "selected = selected "; } ?> value="<?php echo $filiais[$i]['F_id']; ?>"><?php echo $filiais[$i]['F_nome']; ?></option>
								<?php	} ?>
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
				<li><a href='/sair.php'>Encerra Sessão</a></li>
				<li><form action='/buscar.php' method='get'><input name='nome' type='text'/><input type='submit' value='Buscar'/></form></li>
			</ul>
		</div>
	</body> 
</html>