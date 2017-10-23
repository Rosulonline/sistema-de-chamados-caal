<?php
	include_once '../funcoes.php';
	
	$login = protegePagina();
		
	if(isset($_GET['id_usuario'])){
		$id_usuario = $_GET['id_usuario'];
	}else{
		$id_usuario = NULL;
	}
	
	if(isset($_GET['id_filial'])){
		$id_filial = $_GET['id_filial'];
	}else{
		$id_filial = NULL; 
	}
	
	if(isset($_GET['id_setor'])){
		$id_setor = $_GET['id_setor'];
	}else{
		$id_setor = NULL;
	}
	
	if(isset($_GET['id_equipamento'])){
		$filial = 0;
		$setor = 0;
		$usuario_equipamento = retorna_usuarios_equipamento($_GET['id_equipamento']);
		$total = count($usuario_equipamento);
	}else{
		$usuario = retorna_usuarios($id_filial,$id_setor,$id_usuario,FALSE);
	}
	
?>
<script>
	$(function() {
		$( ".button-datalhar-usuario" ).button({
			icons: {
				primary: "ui-icon-pencil"
			}
		});
		$( ".button-usuario-chamado" ).button({
			icons: {
				primary: "ui-icon-circle-plus"
			}
		}).click(function(event){
			event.preventDefault();
			$.post("../novo/dados_temp_formulario.php", {novo: true, usuario: $(".button-usuario-chamado").attr('title')})
				.done(function(){
					location.replace($(".button-usuario-chamado" ).attr('href'));
				});
		});
		
		
		$( "#accordion-filiais" ).accordion({
			collapsible: true,
			heightStyle: "content"
		});		
		$( "#accordion-chamados<?php echo $id_usuario; ?>" ).accordion({
			heightStyle: "content",
			collapsible: false,
			active: true,
			change: function (e, ui) {
				$url = $(ui.newHeader[0]).children('a').attr('href');
				$.get($url, function (data) {
					//console.log(data);
					$(ui.newHeader[0]).next().html(data);
				});
			}
		});
	});
</script>
<style type="text/css">
	.button-datalhar-usuario,
	.button-usuario-chamado{
		float:none;
		width:100%;
	}

</style>
<?php	if(isset($usuario_equipamento)){ ?>
<div id="accordion-filiais">
	<?php	for($i=0;$i<$total;$i++){ ?>
	<?php		if($usuario_equipamento[$i]['F_id'] != $filial){ ?>
	<?php			$filial = $usuario_equipamento[$i]['F_id']; ?>
	<?php			$aux = $i; ?>
	<?php			if($i>0){ ?>
		
			</div><!-- #accordion-usuarios -->
		</div><!-- #accordion-setores  -->
	</div><!-- content filial -->
	<?php			} ?>
	
	<script type="text/javascript">
		$( "#accordion-setores<?php echo $usuario_equipamento[$i]['F_id']; ?>" ).accordion({
			collapsible: true,
			heightStyle: "content"
		});
	</script>
	<h3><?php echo $usuario_equipamento[$i]['F_nome']; ?></h3>
	<div>
		<div id="accordion-setores<?php echo $usuario_equipamento[$i]['F_id']; ?>">
	<?php		} ?>
	<?php		if($usuario_equipamento[$i]['S_id'] != $setor){ ?>
	<?php			$setor = $usuario_equipamento[$i]['S_id']; ?>
	<?php			if(($i>0) && ($aux != $i)){ ?>
	
			</div><!-- #accordion-usuarios -->
	<?php			} ?>
			
			<script type="text/javascript">
				$( "#accordion-usuarios<?php echo $usuario_equipamento[$i]['S_id']; ?>" ).accordion({
					heightStyle: "content",
					collapsible: false,
					active: true,
					change: function (e, ui) {
						$url = $(ui.newHeader[0]).children('a').attr('href');
						$.get($url, function (data) {
							//console.log(data);
							$(ui.newHeader[0]).next().html(data);
						});
					}
				});
			</script>	
			<h3><?php echo $usuario_equipamento[$i]['S_nome']; ?></h3>
			<div id="accordion-usuarios<?php echo $usuario_equipamento[$i]['S_id']; ?>">
	<?php		} ?>
	
				<h3><a href="/dados_index/usuario.php?id_usuario=<?php echo $usuario_equipamento[$i]['U_id'] ?>"><?php echo $usuario_equipamento[$i]['U_nome']; ?></a></h3>
				<div><p>Carregando...</p></div><!-- USUARIO -->
	<?php		if($i == ($total-1)){ ?>
		
			</div><!-- #accordion-usuarios -->
		</div><!-- #accordion-setores  -->
	</div><!-- content filial -->
	<?php		} ?>
	<?php	} ?>
	
</div><!-- #accordion-filiais -->
<?php	}else{ ?>
<?php		for($i=0; $i<count($usuario); $i++){ ?>
<p>Nome: <a><?php echo $usuario[$i]['U_nome']; ?></a></p>
<p>Email: <a href="mailto:<?php echo $usuario[$i]['U_email']; ?>"><?php echo $usuario[$i]['U_email']; ?></a></p>
<p>Ramal: <a><?php echo $usuario[$i]['U_ramal']; ?></a></p>
<p>Matrícula: <a><?php echo $usuario[$i]['U_matricula']; ?></a></p>
<p>Usuário: <a><?php echo $usuario[$i]['U_usuario']; ?></a></p>
<p>Senha: <a><?php echo $usuario[$i]['U_senha']; ?></a></p>
<p>Setor: <a><?php echo $usuario[$i]['S_nome']; ?></a></p>
<p>Filial: <a><?php echo $usuario[$i]['F_nome']; ?></a></p>
<?php			if($login == 2){ ?>
<a class="button-usuario-chamado"  title="<?php echo $usuario[$i]['U_id']; ?>" href="./formularios/cadastro_chamado.php" >Abrir Chamado</a>
<a class="button-datalhar-usuario" href="/usuario.php?id=<?php echo $usuario[$i]['U_id']; ?>">Detalhar Usuário</a>
<?php			} ?>
<?php		} ?>
<?php	} ?>