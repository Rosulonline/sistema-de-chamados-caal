<?php
	include("../funcoes.php");
	
	$login = protegePagina(true);
	
	$aberto = false;
	$por_pagina=15;
	
	
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
	
	if(isset($_GET['id_usuario'])){
		$id_usuario = $_GET['id_usuario'];
		if(isset($_GET['pagina'])){
			$pagina = $_GET['pagina'];
			$inicial = $por_pagina * ( $pagina - 1 );
		}else{
			$inicial = 0;
		}
	}else{
		$id_usuario = NULL;
	}
	
	if(isset($_GET['id_equipamento'])){
		$id_equipamento = $_GET['id_equipamento'];
	}else{
		$id_equipamento = NULL;
	}
	
	if(isset($_GET['id_chamado'])){
		$id_chamado = $_GET['id_chamado'];
	}else{
		$id_chamado = NULL;
	}
	
	if(isset($_GET['inicial'])){
		$inicial=$_GET['inicial'];
	}else{
		$inicial=NULL;
	}
		
	$chamados = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
?>
<script>
	$( "#accordion_chamados<?php echo $id_usuario; ?>" ).accordion({
		heightStyle: "content",
		collapsible: true,
		active : false
	});
	$( ".button-detalhar" ).button({
		icons: {
			primary: "ui-icon-pencil"
		}
	});
</script>
<div id="accordion_chamados<?php echo $id_usuario; ?>">
	
	<?php 	for ($i = 0; $i < count($chamados); $i++) { ?>
	<?php		if($chamados[$i]['Ch_prioridade'] == NULL){ $vencimento = "Sem Urgência"; }else{ $vencimento = formata_data($chamados[$i]['Ch_prioridade']); } ?>
	<?php		$ver_data = verifica_data($chamados[$i]['Ch_prioridade'], date("Y-m-d H:i:s")); ?>
	
	<h3>ID: <?php echo str_pad($chamados[$i]['Ch_id'], 4, "0", STR_PAD_LEFT); ?> | <?php echo $chamados[$i]['Ch_assunto']; ?>: <?php echo $chamados[$i]['E_nome']; ?> | Vencimento: <?php echo  $vencimento; ?>
		<?php	if($ver_data == true && $chamados[$i]['Ch_aberto'] == 1){ ?>
		<span style="display: inline; float: right;" class="ui-icon ui-icon-alert">data expirada</span>
		<?php	}else if($chamados[$i]['Ch_aberto'] == 0){ ?>
		<span style="display: inline; float: right;" class="ui-icon ui-icon-check">Finalizado</span>
		<?php	} ?>
	</h3>
	<div>
		Assunto: <a><?php echo $chamados[$i]['Ch_assunto']; ?></a>
		Descrição: <a><?php echo nl2br($chamados[$i]['Ch_descricao']); ?></a>
		Usuário: <a href='usuario.php?id=<?php echo $chamados[$i]['U_id']; ?>' ><?php echo $chamados[$i]['U_nome']; ?></a>
		Técnico Responsável: <a><?php echo $chamados[$i]['R_nome']; ?></a>
		Data Abertura: <a><?php echo formata_data($chamados[$i]['Ch_data_abertura']); ?></a>
		Data Vencimento: <a><?php echo $vencimento; ?></a>
		<br/>
		<button class="button-detalhar" onclick="document.location.href='../chamado.php?id=<?php echo $chamados[$i]['Ch_id']; ?>'" >Detalhar Chamado</button>
	</div>
	<?php 	} ?>
	
</div>
