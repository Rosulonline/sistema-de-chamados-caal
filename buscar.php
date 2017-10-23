<?php 
	include_once './funcoes.php' ;
	$busca = htmlspecialchars(strtolower(trim($_GET['nome'])),  ENT_QUOTES);
	$login = protegePagina();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		<script>
			$(function() {
				$( "#tabs-busca" ).tabs({
					beforeLoad: function( event, ui ) {
						ui.panel.html(
							"<p>Carregando...</p>"
						);
						ui.jqXHR.error(function() {
							ui.panel.html(
								"<p>Erro...</p>"
							);
						});
					}
				});
				
			});
		</script>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<div id="tabs-busca">
					<ul>
						<li><a href="/dados_index/busca_usuarios.php?nome=<?php echo $busca; ?>">Usuários</a></li>
						<li><a href="/dados_index/busca_equipamentos.php?equipamento=<?php echo $busca; ?>">Equipamentos, Softwares etc.</a></li>
						<li><a href="/dados_index/busca_chamados.php?nome=<?php echo $busca; ?>">Chamados</a></li>
						<li style="float: right;">Pesquisa: "<?php echo $busca; ?>"</li>
					</ul>
				</div>
			</div>
			<div id="rodape"></div>
		</div>
		<?php include_once './dados_index/nav-bar.php'; ?>
	</body> 
</html>