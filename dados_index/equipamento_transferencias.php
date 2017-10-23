<?php
	include_once '../funcoes.php';
	$login = protegePagina();
	
	$id_equipamento = $_GET['id_equipamento'];
	
	$transferencias = retorna_redirecionamentos_equipamento($id_equipamento);
	
	/*	FUNÇÃO QUE RETORNA MATRIZ COM DADOS DO REDIRECIONAMENTO OU NULO CASO não HAJA DADOS
	 *	Tem como entrada o ID do Equipamento ou nulo, caso seja informado o ID retorna nulo se não possuí
	 *	dados ou a matriz:
	 *	M[i]['RE_id'] -> ID do redirecionamento
	 *	M[i]['RE_data'] -> DATA do redirecionamento
	 *	M[i]['RE_motivo'] -> MOTIVO do redirecionamento
	 *	M[i]['E_nome'] -> nome do equipamento
	 *	M[i]['E_descricao'] -> descrição do equipamento
	 *	M[i]['E_mac'] -> endereço mac do equipamento
	 *	M[i]['E_ip'] -> endereço IP do equipamento
	 *	M[i]['E_nf'] -> Número da Nota Fiscal do equipamento
	 *	M[i]['E_ns'] -> Número de S�rie do equipamento
	 *	M[i]['E_descartado'] -> Equipamento descartado? 0-não, 1-Sim
	 *	M[i]['C_id'] -> id da classe a qual o equipamento pertence
	 *	M[i]['C_nome'] -> nome da classe a qual o equipamento pertence
	 *	M[i]['De_id'] -> ID do antigo Usuário do Equipamento
	 *	M[i]['De_nome'] -> NOME do antigo Usuário do Equipamento
	 *	M[i]['Para_id'] -> ID do novo Usuário do Equipamento
	 *	M[i]['Para_nome'] -> NOME do novo Usuário do Equipamento
	 */
?>
<script>
	$(function() {
		$( "#accordion-transferencias-sub" ).accordion({
			heightStyle: "content",
			collapsible: true,
			active: false
		});
	});
</script>
<?php	if($transferencias){ ?>
<div id="accordion-transferencias-sub">
	<?php 	for ($i = 0; $i < count($transferencias); $i++) { ?>
	<h3><?php echo formata_data($transferencias[$i]['RE_data']); ?></h3>
	<div>
		<p><b>Motivo:</b><?php echo nl2br($transferencias[$i]['RE_motivo']); ?></p>
		<p><b>De: </b><?php echo $transferencias[$i]['De_nome']; ?></p>
		<p><b>Para: </b><?php echo $transferencias[$i]['Para_nome']; ?></p>
	</div>
	<?php 	} ?>
</div>
<?php	}else{ ?>
<p>Sem Registros</p>
<?php	} ?>