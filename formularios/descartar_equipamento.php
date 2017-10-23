<?php 
	include('../funcoes.php'); 
	$id_equipamento = $_GET['id_equipamento'];
	$restricao = 0;
	$dados = retorna_dados_equipamento($id_equipamento,$restricao);
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
		<script>
			function select_all(obj){
				var text_val=eval(obj);
				text_val.focus();
				text_val.select();
				if(!document.all)
					return; // IE only
				//r= text_val.createTextRange();
				//r.execCommand('copy');
			}
		</script>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<form action="/novo/descartar_equipamento.php<?php if(isset($_GET['id_equipamento'])){ echo "?id_equipamento=".$_GET['id_equipamento']; }?>" method="post">
					<fieldset>
					
					<legend><span>Descartar Equipamento</span></legend>
					<p>
						<label for="nome">Nome:</label>
						<input id="nome" type="text" readOnly="true" onClick="select_all(this);" value="<?php echo $dados['C_nome'].": ".$dados['E_nome']; ?>">
						
						<label for="descricao">Descrição:</label>
						<textarea id="descricao" cols='100' readOnly="true" onClick="select_all(this);"><?php echo $dados['E_descricao']; ?></textarea>
						
						<label for="ns">Número de Série:</label>
						<input id="ns" type="text" readOnly="true" onClick="select_all(this);" value="<?php echo $dados['E_ns']; ?>" >
						
						<label for="ip">IP:</label>
						<input id="ip" type="text" readOnly="true" onClick="select_all(this);" value="<?php echo $dados['E_ip']; ?>" >
						
						<label for="mac">MAC:</label>
						<input id="mac" type="text" readOnly="true" onClick="select_all(this);" value="<?php echo $dados['E_mac']; ?>" >						
						
						<label for='motivo'>Motivo:</label>
						<textarea id='motivo' name='motivo' cols='100' rows='10'></textarea>
						
						<br>
						<br>
						<input type="submit" value="Descartar">
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