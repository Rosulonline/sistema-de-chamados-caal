<?php
	include("../funcoes.php");
	$login = protegePagina();
	
	$id_chamado = $_GET['id_chamado'];
	$redirecionamentos = retorna_redirecionamentos_chamado($id_chamado);
	$solucoes = retorna_chamado_solucoes($id_chamado);
?>
<script>
	$( "#accordion_historico" ).accordion({
		heightStyle: "content",
		collapsible: true,
		active : false
	});
	$( "#accordion_transferencias" ).accordion({
		heightStyle: "content",
		collapsible: true,
		active : false
	});
	$( "#accordion_solucoes" ).accordion({
		heightStyle: "content",
		collapsible: true,
		active : false
	});
</script>
<div id="accordion_historico">
	<h3>Soluções</h3>
	<div>
		<div id="accordion_solucoes">
		<?php	if(count($solucoes)>0){ ?>
		<?php		for($i=0; $i<count($solucoes); $i++){ ?>
		<?php			if($solucoes[$i]['So_solucionado']==0){ $solucao = "Previsão"; }else if($solucoes[$i]['So_solucionado']==1){ $solucao = "Finalizado"; }else if($solucoes[$i]['So_solucionado']==2){ $solucao = "Reaberto"; } ?>
			<h3><?php echo formata_data($solucoes[$i]['So_data_inicio']); ?> | <?php echo $solucao; ?> | Responsável: <?php echo $solucoes[$i]['R_nome']; ?></h3>
			<div>
				Motivo: <a><?php echo nl2br($solucoes[$i]['So_descricao']); ?></a>
				<?php	if($solucoes[$i]['So_solucionado'] == 1){ /*FINALIZADO*/ ?>
				Data Solucionado: <a><?php echo formata_data($solucoes[$i]['So_data_solucionado']); ?></a>
				<?php	}else if($solucoes[$i]['So_solucionado'] == 2){ /*REABERTO*/ ?>
				Data Reabertura: <a><?php echo formata_data($solucoes[$i]['So_data_inicio']); ?></a>
				<?php	}else{ ?>
				Data Inicial: <a><?php echo formata_data($solucoes[$i]['So_data_inicio']); ?></a>
				Data Prevista para solucionar: <a><?php echo formata_data($solucoes[$i]['So_previsao']); ?></a>
				<?php	} ?>
			</div>
		<?php		} ?>
		<?php	}else{ ?>
			<h3>Nenhum Registro</h3>
		<?php	} ?>
		</div><!-- #accordion_solucoes -->
	</div>
	<h3>Transferências</h3>
	<div>
		<div id="accordion_transferencias">
		<?php	if(count($redirecionamentos)>0){ ?>
		<?php		for($i=0; $i<count($redirecionamentos); $i++){ ?>
			<h3><?php echo formata_data($redirecionamentos[$i]['RC_data']); ?></h3>
			<div>
				Motivo: <a><?php echo nl2br($redirecionamentos[$i]['RC_motivo']); ?></a>
				De: <a><?php echo $redirecionamentos[$i]['DE_nome']; ?></a>
				Para: <a><?php echo $redirecionamentos[$i]['PARA_nome']; ?></a>
			</div>
		<?php		} ?>
		<?php	}else{ ?>
			<h3>Nenhum Registro</h3>
		<?php	} ?>
		</div><!-- #accordion_solucoes -->
	</div>
</div>