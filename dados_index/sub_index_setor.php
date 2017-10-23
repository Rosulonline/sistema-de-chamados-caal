<?php
	include_once '../funcoes.php';
	$login = protegePagina();
	
	if(isset($_GET['id_filial'])){
		$id_filial = $_GET['id_filial'];
		$setores = retorna_setores($id_filial);
	}
?>
<script>
	$( "#accordion_setor<?php echo $id_filial; ?>" ).accordion({
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
<div id="accordion_setor<?php echo $id_filial; ?>">
	<?php	if(count($setores)>0){ ?>
	<?php		for($i=0; $i<count($setores); $i++){ ?>
	<h3><a href="/dados_index/sub_index_usuarios.php?id_setor=<?php echo $setores[$i]['S_id']; ?>"><?php echo $setores[$i]['S_nome']; ?></a></h3>
	<div>
		<p>Carregando...</p>
	</div>
	<?php		} ?>
	<?php	}else{ ?>
	<h3>Nenhum Registro</h3>
	<?php	} ?>
</div>
