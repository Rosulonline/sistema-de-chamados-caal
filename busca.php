<?php 
	include_once './funcoes.php' ;
	
	$login = protegePagina();
	
	$F_id = NULL;
	$S_id = NULL;
	
	if(isset($_GET['filial'])){
		if($_GET['filial']==0)
			$F_id = NULL;
		else
			$F_id = $_GET['filial'];
	}
	if(isset($_GET['setor'])){
		if($_GET['setor']==0)
			$S_id = NULL;
		else
			$S_id = $_GET['setor'];
	}
		
	$filial = retorna_filiais(NULL);
	$setor = retorna_setores($F_id,NULL);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		<script type="text/javascript">
			$(document).ready(
				function(){
					$('#auto').autocomplete({
						source: "busca_usuario.php",
						minLength: 2
					});
				});
		</script>
	</head>
	<body>
		<div id="corpo">
		<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
		<div id="conteudo">
			<form action='buscar.php' method='get'>
				<table id="tabela">
					<caption>Busca Avancada</caption>
					<tr>
						<td>Filial:</td>
						<td>Setor:</td>
						<td colspan='3'>Liberações:</td>		
						<td>Nome: </td>
						<td rowspan='2'><input type='submit' value='Buscar'/></td>
					</tr>
					<tr>
						<td>
							<select name="filial" onchange="window.location='busca.php?nome=<?php echo $_GET['nome']; ?>&filial='+this.value">
								<option value='0'>Selecione a filial</option>
								<?php for($i=0;$i<count($filial);$i++){ ?>
								<option <?php if($F_id==$filial[$i]['F_id']){echo "selected='selected' ";} ?> value='<?php echo $filial[$i]['F_id']; ?>'><?php echo $filial[$i]['F_nome']; ?></option>
								<?php } ?>
							</select>
						</td>
						<td>
							<select name="setor" onchange="window.location='busca.php?nome=<?php echo $_GET['nome']; ?>&filial=<?php echo $_GET['filial']; ?>&setor='+this.value">
								<option value='0'>Selecione o setor</option>
								<?php for($i=0;$i<count($setor);$i++){ ?>
								<option <?php if($S_id==$setor[$i]['S_id']){echo "selected='selected' ";} ?> value='<?php echo $setor[$i]['S_id']; ?>'><?php echo $setor[$i]['S_nome']; ?></option>
								<?php } ?>
							</select>
						</td>
						<td>Pendrive: <input type='checkbox' name='pendrive'/></td>
						<td>Internet: <input type='checkbox' name='internet'/></td>
						<td>MSN: <input type='checkbox' name='msn'/></td>
						<td><input name='nome' type='text' id='auto' <?php if(isset($_GET['nome'])){echo "value='".$_GET['nome']."'";} ?>/></td>
					</tr>

				</table>
			</form>
		</div>
		<div id="rodape">
		</div>
		</div>
		<div id='nav-bar'>
			<ul>
				<li><a href='/sair.php'>Encerrar Sessão</a></li>
				<li><a><?php echo $_SESSION['nome']; ?></a></li>
				<li><a href='/index.php'>Página Inicial</a></li>
				<li><a href='/formularios/cadastro_chamado.php'>Novo Chamado</a></li>
				<li><form action='buscar.php' method='get'><input name='nome' type='text'/><input type='submit' value='Buscar'/></form></li>
			</ul>
		</div>
	</body> 
</html>