<?php
	include_once('funcoes.php');
	
	$login = protegePagina();
	
	$id_filial = NULL;
	$id_setor = NULL;
	$id_usuario = $_SESSION['id_comum'];
	$id_equipamento = NULL;
	$id_chamado = NULL;
	$aberto = NULL;
	if(isset($_GET['quantidade'])){
		$por_pagina = $_GET['quantidade'];
	}else{
		$por_pagina = $_server['padraoItensPagina'];
	}	
	if(isset($_GET['pagina'])){
		$inicial = $_GET['pagina'];
	}else{
		$inicial = 1;
		header("Location: index_comum.php?pagina=".$inicial."&quantidade=".$por_pagina);
	}
	
	$chamados = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
	//$total_registros = retorna_quantidade_total_de_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto);
	$total_registros = count($chamados); 
	$total_paginas = Ceil($total_registros / $por_pagina);	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		<script>
			$(function() {
				/*TABS*/
				$( "#tabs-chamados-comum" ).tabs({
					beforeLoad: function( event, ui ) {
						ui.jqXHR.error(function() {
							ui.panel.html(
								"<p>Carregando...</p>"
							);
						});
					}
				});
				/*FIM TABS*/
				$( "#accordion-chamados-comum" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active: false
				});
			});
		</script>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<div id="tabs-chamados-comum">
					<ul>
						<li><a href="#chamados">Chamados</a></li>
					</ul>
					<div id="chamados">
						<p>Carregando...</p>
						<script>
							$( "#chamados" ).ready(function(){
								$( "#chamados" ).load( "/dados_index/chamados.php?id_usuario=<?php echo $id_usuario; ?>&pagina=<?php echo $inicial; ?>" );
							});
						</script>
					</div><!-- #chamados -->
					<td colspan='2'>Páginas: 
						
						<?php		for($i=1;$i<=$total_paginas;$i++){ ?>
						<?php			if(isset($_GET['pagina']) && $_GET['pagina'] == $i){ ?>
							<a>[<?php echo $i; ?>]</a>
						<?php			} else { ?>
							<a href='/index_comum.php?pagina=<?php echo $i; ?>'><?php echo $i; ?></a>
						<?php			} ?>
						<?php		} ?>
						</td>
				</div><!-- #tabs-chamados-comum -->
			</div><!-- #conteudo -->
			<div id="rodape"></div><!-- #rodape -->
		</div><!-- #corpo -->
		<?php include './dados_index/nav-bar.php'; ?>
	</body> 
</html>