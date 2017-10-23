<?php
	include_once 'funcoes.php';
	
	$login				= protegePagina();
	
	$id_filial			= null;
	$id_setor			= null;
	$id_usuario			= null;
	$id_equipamento		= null;
	$id_chamado			= null;
	$aberto				= TRUE;
	$inicial			= null;
	$por_pagina			= 30;
	$tempo_min			= 10; //tempo para refresh da pagina
	
	$total_registros	= retorna_quantidade_total_de_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto);
	$total_paginas		= Ceil($total_registros / 20);
	
	$chamados			= retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto,$inicial,$por_pagina);
	$filiais			= retorna_filiais($id_filial);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="pragma" content="no-cache" />
		<title> CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		<script>
			// Variável global do objeto temporizador.
			var timer;

			function parar() { 
				window.clearTimeout(this.timer);
			}

			function comecarReload(){
				var aux = 60*1000*<?php echo $tempo_min; ?>;
				timer = window.setTimeout("location.reload()", aux);
			}
			
			function forcarReload(){
				window.location.reload();
			}
		</script>
		<script>
			$(function() {
				/*TABS*/
				$( "#tabs" ).tabs({
					
					beforeLoad: function( event, ui ) {
						ui.jqXHR.error(function() {
							ui.panel.html(
								"<p>Carregando...</p>"
							);
						});
					}
				});
				/*FIM TABS*/
				$( "#accordion" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active: false
				});
				$( ".editar" ).button({
					icons: {
						primary: "ui-icon-pencil"
					}
				});
				
			});
		</script>
	</head>
	<body onload="comecarReload();">
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<div id="tabs">
					<ul>
						<li><a href="#accordion" OnClick="comecarReload();">Chamados Abertos</a></li>
						<li><a href="#filiais" OnClick="parar();">Filiais</a></li>
						<li><a href="#ultimos_chamados" OnClick="parar();">Ultimos chamados</a></li>
					</ul>
					<div id="filiais">
						<p>Carregando...</p>
						<script>
							$( "#filiais" ).ready(function(){
								$( "#filiais" ).load( "/dados_index/sub_index_filial.php" );
							});
						</script>
					</div>
					<div id="ultimos_chamados">
						<p>Carregando...</p>
						<script>
							$( "#ultimos_chamados" ).ready(function(){
								$( "#ultimos_chamados" ).load( "/dados_index/chamados_ultimos_geral.php" );
							});
						</script>
					</div>
					<?php
						if(count($chamados) > 0){
							echo "<div id='accordion'>";
							for ($i = 0; $i < count($chamados); $i++) {
								switch ($chamados[$i]['Ch_aberto']) {
									case 0:
										$estado = " | Fechado";
										break;
									case 1:
										$estado = "";
										break;
									case 2:
										$estado = " | Em andamento";
										break;
									default:
										$estado = " | Erro";
										break;
								}
								if($chamados[$i]['Ch_prioridade'] == NULL){
									 $vencimento = "Sem Urgência"; 
								}else{
									$vencimento = formata_data($chamados[$i]['Ch_prioridade']);
								}
								$ver_data = verifica_data($chamados[$i]['Ch_prioridade'], date("Y-m-d H:i:s"));
								echo "<h3>";
								echo "ID: ".str_pad($chamados[$i]['Ch_id'], 4, "0", STR_PAD_LEFT)." | ". $chamados[$i]['Ch_assunto'] . " : " . $chamados[$i]['E_nome']. " | Vencimento: "
								 . $vencimento . $estado." | ". $chamados[$i]['R_nome'];
								
								if($chamados[$i]['Ch_aberto'] == 2){
									echo "<span style='display: inline; float: right;' class='ui-icon ui-icon-seek-next'>data expirada</span>";									
								} if($ver_data == true){
									echo "<span style='display: inline; float: right;' class='ui-icon ui-icon-alert'>data expirada</span>";
								}
								
								echo "</h3>";
								echo "<div>";
								echo "Assunto: <a>".$chamados[$i]['Ch_assunto']."</a>";
								echo "Descrição: <a>". nl2br($chamados[$i]['Ch_descricao'])."</a>";
								echo "Usuário: <a href='usuario.php?id=".$chamados[$i]['U_id']."' >".$chamados[$i]['U_nome']."</a>";
								echo "Técnico Responsável: <a>" . $chamados[$i]['R_nome'] . "</a>";
								echo "Data Abertura: <a>" . formata_data($chamados[$i]['Ch_data_abertura']) . "</a>";
								echo "Data Vencimento: <a>".$vencimento."</a>";
								echo "<br/>";
								echo "<button class='editar' onclick='document.location.href=\"../chamado.php?id=".$chamados[$i]['Ch_id']."\"' >Detalhar Chamado</button>";
								echo "</div>";
							}
							echo "</div>";
						}else{
							echo "<p id='accordion'> Nenhum Chamado Aberto. </p>";
						}
						echo "</div>";
						echo "</div>";
						echo "<div id='rodape'>";
						echo "\n</div>";
						echo "</div>";
						include './dados_index/nav-bar.php';
				?>
	</body>
</html>