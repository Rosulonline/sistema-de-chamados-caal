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
	
	$nome = str_replace("+", " ", htmlspecialchars_decode($_GET['nome']));
	//$nome = explode(" ", $nome);
	$U_consulta = "";
	$U_consulta .= "SELECT ";
	$U_consulta .= "	* ";
	$U_consulta .= "FROM";
	$U_consulta .= "	USUARIO_SETOR_FILIAL ";
	$U_consulta .= "WHERE ";
	$U_consulta .= "	(U_nome LIKE '%".$nome."%' OR ";
	$U_consulta .= "	U_ramal LIKE '%".$nome."%' OR ";
	$U_consulta .= "	U_email LIKE '%".$nome."%' OR ";
	$U_consulta .= "	U_usuario LIKE '%".$nome."%' OR ";
	$U_consulta .= "	U_email_msn LIKE '%".$nome."%' OR ";
	$U_consulta .= "	S_nome LIKE '%".$nome."%' OR ";
	$U_consulta .= "	F_nome LIKE '%".$nome."%' OR ";
	$U_consulta .= "	F_endereco LIKE '%".$nome."%')";
	if($filial!=0)
		$U_consulta .= " AND F_id=".$filial;
	if($setor!=0)
		$U_consulta .= " AND S_id=".$setor;
	if($pendrive!=0)
		$U_consulta .= " AND U_disp=".$pendrive;
	if($internet!=0)
		$U_consulta .= " AND U_net=".$internet;
	if($msn!=0)
		$U_consulta .= " AND U_msn=".$msn;
	
	$U_consulta .= " ORDER BY U_nome ASC;";
		
	$U_query = mysql_query($U_consulta)or die($U_consulta.": ERRO Usuário: ".mysql_error());
	$cont=0;
	echo "<!-- ".$U_consulta." -->";
?>
<script>
	$( "#accordion-usuarios" ).accordion({
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
<div id="accordion-usuarios">
	<?php	while($row=mysql_fetch_array($U_query)){ ?>
	
	<h3><a href="/dados_index/usuario.php?id_usuario=<?php echo $row['U_id']; ?>"><?php echo $row['U_nome']." - ".$row['S_nome']; ?></a></h3>
	<div>
		<p>Carregando...</p>
	</div>
	<?php	$cont=1;} ?>
	<?php	if($cont == 0){ ?>
	<p>Nenhum Registro</p>
	<?php	} ?>
</div>
