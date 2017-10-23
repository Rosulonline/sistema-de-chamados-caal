<?php
	include_once '../funcoes.php';
	$login = protegePagina();
		
	
	if(isset($_GET['id_setor'])){
		$id_setor = $_GET['id_setor'];
		$usuarios = retorna_usuarios(NULL,$id_setor,NULL,true);
	}
?>
<script>
	$( "#accordion_usuario<?php echo $id_setor; ?>" ).accordion({
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
</script>
<div id="accordion_usuario<?php echo $id_setor; ?>">
	<?php	if(count($usuarios)>0){ ?>
	<?php		for($i=0; $i<count($usuarios); $i++){ ?>
	<h3><a href="/dados_index/usuario.php?id_usuario=<?php echo $usuarios[$i]['U_id']; ?>"><?php echo $usuarios[$i]['U_nome']; ?></a></h3>
	<div>
		<p>Carregando...</p>
	</div>
	<?php		} ?>
	<?php	}else{ ?>
	<h3>Nenhum Registro</h3>
	<?php	} ?>
</div>
