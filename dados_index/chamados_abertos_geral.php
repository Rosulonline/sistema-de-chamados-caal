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
	$inicial=NULL;
	$por_pagina=NULL;
	$chamados = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
?>
<div id="accordion_todos">
<?php 	for ($i = 0; $i < count($chamados); $i++) { ?>
<h3>Assunto: <a><?php echo $chamados[$i]['Ch_assunto']; ?></a></h3>
<div>
Descrição: <a><?php echo $chamados[$i]['Ch_descricao']; ?></a>
Usuário: <a href='usuario.php?id=<?php echo $chamados[$i]['U_id']; ?>' ><?php echo $chamados[$i]['U_nome']; ?></a>
Técnico Responsável: <a><?php echo $chamados[$i]['R_nome']; ?></a>
Data Abertura: <a><?php echo formata_data($chamados[$i]['Ch_data_abertura']); ?></a>
<br/>
<button class="editar" >Detalhar Chamado</button>
</div>
</div>
<?php 	} ?>