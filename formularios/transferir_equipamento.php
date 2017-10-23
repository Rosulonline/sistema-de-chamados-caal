<?php 
	include('../funcoes.php');
	
	
	$id_equipamento = $_GET['id_equipamento'];
	$id_filial = NULL;
	$id_setor = NULL;
	$id_usuario = NULL;
	$equipamentos = NULL;
	$restricao = 0 ;
	
	$setor = NULL;
	
	$equipamento = retorna_dados_equipamento($id_equipamento,$restricao);
	$usuarios = retorna_usuarios($id_filial,$id_setor,$id_usuario);
	
	if($equipamento['C_id']==2 || $equipamento['C_id']==4 || $equipamento['C_id']==5 || 
		$equipamento['C_id']==8 || $equipamento['C_id']==9 || $equipamento['C_id']==10 ||
		$equipamento['C_id']==11 || $equipamento['C_id']==12 || $equipamento['C_id']==13 ||
		$equipamento['C_id']==14 || $equipamento['C_id']==15 || $equipamento['C_id']==16){
		$com_restricao = true;
		$equipamentos = retorna_equipamentos($com_restricao);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<link rel="stylesheet" type="text/css" href="/css/jquery.toastmessage.css" />
		<script type="text/javascript" src="/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/js/jquery.toastmessage.js"></script>
		<script>
			function select_all(obj){
				var text_val=eval(obj);
				text_val.focus();
				text_val.select();
				if(!document.all)
					return; // IE only
				//r= text_val.createTextRange();
				//r.execCommand('copy');
			}
		</script>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<form action="/novo/transferir_equipamento.php<?php if(isset($_GET['id_equipamento'])){ echo "?id_equipamento=".$_GET['id_equipamento']; }	?>" method="post">
					<fieldset>
						<legend><span>Transferir Equipamento</span></legend>
						<p>
							<label for='equipamento'>Equipamento:</label>
							<input id='equipamento' readOnly="true" onClick="select_all(this);" value="<?php echo $equipamento['C_nome']." - ".$equipamento['E_nome']; ?>">
							
							<label for='descricao'>Descrição:</label>
							<textarea id="descricao" cols="100" readOnly="true" onClick="select_all(this);"><?php echo nl2br($equipamento['E_descricao']); ?></textarea>
							
							<label for="motivo">Motivo:</label>
							<textarea id="motivo" name="motivo" rows="10" cols="100"></textarea>
							
							<?php 	if($equipamentos == NULL){ ?>
							<label for='usuario'>Usuários:</label>
							<select id='usuario' name='usuario'>
								<option>Selecione o Usuário</option>
								<?php	for($i=0;$i<count($usuarios);$i++){ ?>
								<?php 		if($usuarios[$i]['S_nome'] != $setor){ ?>
								<?php			$setor = $usuarios[$i]['S_nome']; ?>
								<option> =============================== <?php echo $setor; ?></option>
								<?php		} ?>
								<option value="<?php echo $usuarios[$i]['U_id']; ?>"><?php echo $usuarios[$i]['U_nome']; ?></option>
								<?php	} ?>
							</select>
							<?php	}else{ ?>
							
							<label for='equipamentos'>Equipamentos:</label>
							<select id='equipamentos' name='equipamentos'>
								<option>Selecione o Equipameto</option>
								<?php	for($i=0;$i<count($equipamentos);$i++){ ?>
								<option value="<?php echo $id_equipamento."-".$equipamentos[$i]['E_id']; ?>"><?php echo $equipamentos[$i]['C_nome'].": ".$equipamentos[$i]['E_nome']; ?></option>
								<?php	} ?>
							</select>
							<?php	} ?>
							<br>
							<br>
							<input type="submit" value="Transferir">
						</p>
					</fieldset>
				</form>
			</div>
			<div id="rodape"></div>
		</div>
		<div id='nav-bar'>
			<ul>
				<li><a href='/formularios/cadastro_chamado.php'>Novo Chamado</a></li>
				<li><a href='/index.php'>Página Inicial</a></li>
				<li><a href='/sair.php'>Encerra Sessão</a></li>
				<li><form action='/buscar.php' method='get'><input name='nome' type='text'/><input type='submit' value='Buscar'/></form></li>
			</ul>
		</div>
	</body>
</html>