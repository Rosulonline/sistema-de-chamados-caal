<?php
	include("../funcoes.php");
	$adm = verifica_login_adm();
	
	if ($adm == false){
		header("Location: sair.php");
	}
	
	$id_filial = NULL;
	$id_setor = NULL;
	if(isset($_GET['id_usuario'])){
		$id_usuario = $_GET['id_usuario'];
	}else{
		$id_usuario = NULL;
	}
	$id_equipamento = NULL;
	$id_chamado = NULL;
	$aberto = false;
	$inicial=NULL;
	$por_pagina=NULL;
	$chamados = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
?>
<style type="text/css">
	#buscar-chamado,
	#buscar-chamado form,
	#accordion-ultimos_chamados{
		clear:left;
		width: 100%;
	}
	.button-buscar{
		float:left;
		width: 25%;
	}
	#buscar-chamado form input{
		float: left;
		width: 73%;
	}
</style>
<script>
	$( "#accordion-chamados-usuario" ).accordion({
		heightStyle: "content",
		collapsible: true,
		active: false
	});
	$( ".button-buscar" ).button({
		icons: {
			primary: "ui-icon-search"
		}
	});
	$( ".button-detalhar" ).button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
</script>
<div id="buscar-chamado">
	<form action="buscar.php#chamados" method="GET">
		<input type="text" id="txt-chamado" name="nome" >
		<button class="button-buscar" >Buscar Chamado</button>
	</form>
</div>
<div id="accordion-chamados-usuario">
	<?php 	for ($i = 0; $i < count($chamados); $i++) { ?>
	<?php		if($chamados[$i]['Ch_prioridade'] == NULL){ $vencimento = "Sem Urgência"; }else{ $vencimento = formata_data($chamados[$i]['Ch_prioridade']); } ?>
	<?php		$ver_data = verifica_data($chamados[$i]['Ch_prioridade'], date("Y-m-d H:i:s")); ?>
	
	<h3>ID: <?php echo str_pad($chamados[$i]['Ch_id'], 4, "0", STR_PAD_LEFT); ?> | <?php echo $chamados[$i]['Ch_assunto']; ?>: <?php echo $chamados[$i]['E_nome']; ?> | Vencimento: <?php echo  $vencimento; ?>
			<?php	if($ver_data == true && $chamados[$i]['Ch_aberto'] == 1){ ?>
		<span style="display: inline; float: right;" class="ui-icon ui-icon-alert">data expirada</span>
			<?php	}else if($chamados[$i]['Ch_aberto'] == 0){ ?>
		<span style="display: inline; float: right;" class="ui-icon ui-icon-check">Finalizado</span>
			<?php	} ?>
	<div>
		Assunto: <a><?php echo $chamados[$i]['Ch_assunto']; ?></a>
		Descrição: <a><?php echo $chamados[$i]['Ch_descricao']; ?></a>
		Usuário: <a href='usuario.php?id=<?php echo $chamados[$i]['U_id']; ?>' ><?php echo $chamados[$i]['U_nome']; ?></a>
		Técnico Responsável: <a><?php echo $chamados[$i]['R_nome']; ?></a>
		Data Abertura: <a><?php echo formata_data($chamados[$i]['Ch_data_abertura']); ?></a>
		Data Vencimento: <a><?php echo $vencimento; ?></a>
		<br/>
		<button class="button-detalhar" onclick="document.location.href='../chamado.php?id=<?php echo $chamados[$i]['Ch_id']; ?>'" >Detalhar Chamado</button>
	</div>
	<?php 	} ?>
</div>