<?php
	include_once('funcoes.php');

	$login = protegePagina();

	$filiais = retorna_filiais(NULL);

	if(isset($_GET['filial'])){
		if(isset($_GET['setor'])){
			$usuarios = retorna_usuarios(NULL,$_GET['setor'],NULL,true);
		}else{
			$usuarios = retorna_usuarios($_GET['filial'],NULL,NULL,true);
		}
		$setor = retorna_setores($_GET['filial'],NULL);
		
	}else{
		$setor = retorna_setores(NULL,NULL);
		$usuarios = retorna_usuarios(NULL,NULL,NULL,true);
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title> CAAL - Chamados</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
		<link rel="stylesheet" type="text/css" href="../css/jquery.toastmessage.css" />
		<?php include_once '/dados_index/header.html'; ?>
		<script>
			$(function() {
				$( "#accordion-relatorios" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active : false,
					change: function (e, ui) {
						$url = $(ui.newHeader[0]).children('a').attr('href');
						$.get($url, function (data) {
							$(ui.newHeader[0]).next().html(data);
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
				<div id="accordion-relatorios" style="float: left; width: 99%;">
					<h3><a href="/dados_index/relatorio_quantidade_chamados_tipo_equip.php">Relatório Geral</a></h3>
					<div>
						<p>Carregando...</p>
					</div>
					<h3><a href="/dados_index/relatorio_usuariosxequipamentos.php">Relatório - Usuários X Equipamentos</a></h3>
					<div>
						<p>Carregando...</p>
					</div>
					<h3><a href="/dados_index/relatorio_soVersions.php">Relatório - Licenças</a></h3>
					<div>
						<p>Carregando...</p>
					</div>
				</div><!-- #accordion_dados -->
            </div>
            <div id="rodape"></div>
        </div>
        <?php include './dados_index/nav-bar.php'; ?>
    </body> 
</html>