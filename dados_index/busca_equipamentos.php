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
	
	$nome = htmlspecialchars_decode($_GET['equipamento']);
	$nome = explode(" ", $nome);
	$E_consulta = "";
	$E_consulta .= "SELECT ";
	$E_consulta .= "	* ";
	$E_consulta .= "FROM ";
	$E_consulta .= "	RELACAO_USUARIO_EQUIPAMENTO ";
	$E_consulta .= "WHERE ";
		for($i=0;$i<count($nome);$i++){
			if($i>0){
				$E_consulta .= " AND ";
			}
			$E_consulta .= "	(U_nome LIKE '%".$nome[$i]."%' OR ";
			$E_consulta .= "	E_nome LIKE '%".strtoupper($nome[$i])."%' OR ";
			$E_consulta .= "	E_descricao LIKE '%".strtoupper($nome[$i])."%' OR ";
			$E_consulta .= "	E_ip LIKE '%".strtoupper($nome[$i])."%' OR ";
			$E_consulta .= "	S_nome LIKE '%".strtoupper($nome[$i])."%' OR ";
			$E_consulta .= "	F_nome LIKE '%".strtoupper($nome[$i])."%')";
		}
	if($filial!=0)
		$E_consulta = $E_consulta." AND F_id=".$filial;
	if($setor!=0)
		$E_consulta = $E_consulta." AND S_id=".$setor;
	if($pendrive!=0)
		$E_consulta = $E_consulta." AND U_disp=".$pendrive;
	if($internet!=0)
		$E_consulta = $E_consulta." AND U_net=".$internet;
	if($msn!=0)
		$E_consulta = $E_consulta." AND U_msn=".$msn;
	
	$E_consulta .= " GROUP BY E_id";	
	$E_consulta .= " ORDER BY C_id ASC, E_nome ASC;";
	
	$E_query = mysql_query($E_consulta)or die($E_consulta.": ERRO Equipamento: ".mysql_error());
	$cont=0;
	echo "<!-- ".$E_consulta." -->";
?>
<script>
	$( "#accordion-equipamentos" ).accordion({
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
<div id="accordion-equipamentos">
	<?php	while($row=mysql_fetch_array($E_query)){ ?>
	
	<h3><a href="/dados_index/equipamento.php?id_equipamento=<?php echo $row['E_id']; ?>"><?php echo $row['E_nome']." - ".$row['C_nome']; ?></a></h3>
	<div>
		<p>Carregando...</p>
	</div>
	<?php	$cont=1;} ?>
	<?php	if($cont == 0){ ?>
	
	<p>Nenhum Registro</p>
	<?php	} ?>
	
</div>
