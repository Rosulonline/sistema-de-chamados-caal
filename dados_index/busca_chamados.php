<?php
	include_once '../funcoes.php';
	$login = protegePagina();
	
	if(isset($_GET['filial']))
		$filial = $_GET['filial'];
	else
		$filial = 0;
	if(isset($_GET['setor']))
		$setor = $_GET['setor'];
	else
		$setor = 0;
	if(isset($_GET['pendrive']))
		$pendrive = 1;
	else
		$pendrive = 0;
	
	if(isset($_GET['internet']))
		$internet = 1;
	else
		$internet = 0;
		
	if(isset($_GET['msn']))
		$msn = 1;
	else
		$msn = 0;
	
	$nome =  htmlspecialchars_decode($_GET['nome']);
	$nome = explode(" ", $nome);
	$Ch_consulta = "";
	$Ch_consulta .= "SELECT ";
	$Ch_consulta .= "	* ";
	$Ch_consulta .= "FROM ";
	$Ch_consulta .= "	dados_chamado ";
	$Ch_consulta .= "WHERE ";
		for($i=0;$i<count($nome);$i++){
			if($i>0){
				$Ch_consulta .= " AND ";
			}
			$Ch_consulta .= "	(Ch_assunto LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	Ch_id LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	Ch_descricao LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	R_nome LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	U_nome LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	U_email LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	U_usuario LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	U_msn LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	S_nome LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	F_nome LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	E_nome LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	E_descricao LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	C_nome LIKE '%".$nome[$i]."%' OR ";
			$Ch_consulta .= "	F_endereco LIKE '%".$nome[$i]."%')";
		}
	if($filial!=0)
		$Ch_consulta .= " AND F_id=".$filial;
	if($setor!=0)
		$Ch_consulta .= " AND S_id=".$setor;
	if($pendrive!=0)
		$Ch_consulta .= " AND U_disp=".$pendrive;
	if($internet!=0)
		$Ch_consulta .= " AND U_net=".$internet;
	if($msn!=0)
		$Ch_consulta .= " AND U_msn=".$msn;
	
	$Ch_consulta .= " ORDER BY Ch_id DESC;";
		
	$Ch_query = mysql_query($Ch_consulta)or die($Ch_consulta.": ERRO Usuário: ".mysql_error());
	$cont=0;
?>
<script>
	$( "#accordion-chamados" ).accordion({
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
<div id="accordion-chamados">
	<?php	while($row=mysql_fetch_array($Ch_query)){ ?>
	<?php		if($row['Ch_prioridade'] == NULL){ $vencimento = "Sem Urgência"; }else{ $vencimento = formata_data($row['Ch_prioridade']); } ?>
	<?php		$ver_data = verifica_data($row['Ch_prioridade'], date("Y-m-d H:i:s")); ?>
	<h3>ID: <?php echo str_pad($row['Ch_id'], 4, "0", STR_PAD_LEFT); ?> | <?php echo $row['Ch_assunto']; ?>: <?php echo $row['E_nome']; ?> | Vencimento: <?php echo  $vencimento; if($ver_data == true){ ?><span style="display: inline; float: right;" class="ui-icon ui-icon-alert">data expirada</span><?php	} ?></h3>
	<div>
		Assunto: <a><?php echo $row['Ch_assunto']; ?></a>
		Descrição: <a><?php echo $row['Ch_descricao']; ?></a>
		Usuário: <a href='usuario.php?id=<?php echo $row['U_id']; ?>' ><?php echo $row['U_nome']; ?></a>
		Técnico Responsável: <a><?php echo $row['R_nome']; ?></a>
		Data Abertura: <a><?php echo formata_data($row['Ch_data_abertura']); ?></a>
		Data Vencimento: <a><?php echo $vencimento; ?></a>
		<br/>
		<button class="button-detalhar" onclick="document.location.href='../chamado.php?id=<?php echo $row['Ch_id']; ?>'" >Detalhar Chamado</button>
		
	</div>
	<?php	$cont=1;} ?>
	<?php	if($cont == 0){ ?>
	<p>Nenhum Registro</p>
	<?php	} ?>
</div>
