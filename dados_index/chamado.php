<?php
	include("../funcoes.php");
	$adm = verifica_login_adm();
	
	if ($adm == false){
		header("Location: sair.php");
	}
	$id_filial = NULL;
	$id_setor = NULL;
	$id_usuario = NULL;
	$id_equipamento = NULL;
	$id_chamado = NULL;
	$aberto = false;
	$id_chamado = $_GET['id_chamado'];
	
	$chamados = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, NULL, NULL);
?>
<script>
	$( "#accordion-chamado" ).accordion({
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
<div id="accordion">
	<?php 	for ($i = 0; $i < count($chamados); $i++) { ?>
	<?php		if($chamados[$i]['Ch_prioridade'] == NULL){ $vencimento = "Sem Urgência"; }else{ $vencimento = formata_data($chamados[$i]['Ch_prioridade']); } ?>
	<?php		$ver_data = verifica_data($chamados[$i]['Ch_prioridade'], date("Y-m-d H:i:s")); ?>
	<h3>ID: <?php echo str_pad($chamados[$i]['Ch_id'], 4, "0", STR_PAD_LEFT); ?> | <?php echo $chamados[$i]['Ch_assunto']; ?>: <?php echo $chamados[$i]['E_nome']; ?> | Vencimento: <?php echo  $vencimento; if($ver_data == true){ ?><span style="display: inline; float: right;" class="ui-icon ui-icon-alert">data expirada</span><?php	} ?></h3>
	<div>
		Assunto: <a><?php echo $chamados[$i]['Ch_assunto']; ?></a>
		Descrição: <a><?php echo $chamados[$i]['Ch_descricao']; ?></a>
		Usuário: <a href='usuario.php?id=<?php echo $chamados[$i]['U_id']; ?>' ><?php echo $chamados[$i]['U_nome']; ?></a>
		Técnico Responsável: <a><?php echo $chamados[$i]['R_nome']; ?></a>
		Data Abertura: <a><?php echo formata_data($chamados[$i]['Ch_data_abertura']); ?></a>
		Data Vencimento: <a><?php echo $vencimento; ?></a>
		<br/>
		<button class="editar" onclick="document.location.href='../chamado.php?id=<?php echo $chamados[$i]['Ch_id']; ?>'" >Detalhar Chamado</button>
		
	</div>
	<?php 	} ?>
</div>