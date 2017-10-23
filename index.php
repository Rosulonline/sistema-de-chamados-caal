<?php
	include("funcoes.php");
	
	$login = protegePagina(true);

	if($login == 2){
		header("Location: ../index_resp.php?pagina=1");
	} else if($login == 1){
		header("Location: ../index_comum.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		<script type="text/javascript">
			if(navigator.appVersion.indexOf("MSIE 7.")!=-1){
				alert("Navegador desatualizado ou executando em modo de compatibilidade. Entre em contato com o setor de informática!");
			}

			$(function(){				
				/*	 ------  TABS  ------  	*/
				$( "#tabs" ).tabs({
					beforeLoad: function( event, ui ) {
						ui.jqXHR.error(function() {
							ui.panel.html(
								"Carregando..."
							);
						});
					}
				});
				/*	 ------  fim: TABS  ------		*/
				
				
				/*	 ------  BUTTON  ------		*/
				$( "#button-login-entrar" ).button({
					icons:{
						primary: "ui-icon-key"
					}
				}).click(function(){
					$( this ).submit();
				});
		
				/*	 ------fim:BUTTON------		*/
				
				
			});
		</script>
	</head>
	<body onLoad="document.login_form.usuario.focus();">
		<div id='corpo'>
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id='conteudo'>
				<div id="tabs" style="margin-left:50%; left: -15%; float: left; width: 30%;" >
					<ul>
						<li>
							<a href="#login">Login</a>
						</li>
					</ul>
					<div id="login">
						<form name='login_form' action='../login.php' method='post'>
					

							<p>
								<label for='usuario'>Usuário:</label>
								<input type='text' id='usuario' name='usuario'>
							</p>
							<p>
								<label for='senha'>Senha:</label>
								<input type='password' id='senha' name='senha'>
							</p>
							<p>
								<button id="button-login-entrar" style="display: block; width: 100%;">Entrar</button>
							</p>
							<?php
								if(isset($_POST['erro'])){
									echo "<p class=\"warning\">".$_POST['erro']."</p>";
								}
							?>
						</form>
					</div>
				</div>				
			</div><!-- #conteudo -->
			<div id='rodape'></div><!-- #rodape -->
		</div><!-- #corpo -->
		<div id='nav-bar'>
			<ul>
				<li><a href='/formularios/cadastro_chamado.php'>Novo Chamado</a></li>
			</ul>
		</div>
	</body> 
</html>