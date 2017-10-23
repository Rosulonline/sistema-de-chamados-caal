<?php
	include("../funcoes.php");
	$login = protegePagina();
	
	if(isset($_GET['id_equipamento'])){
		$id_equipamento = $_GET['id_equipamento'];
		$componentes = retorna_equipamentos(false, true,NULL,NULL);
		$equipamentos = retorna_equipamentos(true,false,NULL,NULL);
	}else if(isset($_GET['id_usuario'])){
		$id_usuario = $_GET['id_usuario'];
		$equipamentos = retorna_equipamentos(true,false,NULL,NULL);
		$usuarios = retorna_usuarios();
	}	
?>

<style type="text/css">
	#atribuir-componente label,
	#atribuir-equipamento label,
	#atribuir-componente input,
	#atribuir-equipamento input,
	#atribuir-componente textarea,
	#atribuir-equipamento textarea{
		display:block;
	}
	
	#atribuir-componente select,
	#atribuir-equipamento select,
	#atribuir-componente textarea,
	#atribuir-equipamento textarea,
	#atribuir-componente input.text,
	#atribuir-equipamento input.text{
		margin-bottom:12px;
		width:95%;
		padding: .4em;
	}
	
	#atribuir-componente fieldset,
	#atribuir-equipamento fieldset{
		padding:0;
		border:0;
		margin-top:25px;
	}
</style>
<?php	if(isset($id_equipamento)){ ?>
<form id="atribuir-componente" action="/novo/relacionar_componente_equipamento.php" method="post">
	<fieldset>
		<label for="equipamento">Equipamento:</label>
		<select name="equipamento" id="equipamento">
			<option value='0'>Selecione o Equipamento</option>
			<?php 	for($i=0;$i<count($equipamentos);$i++){ ?>
			<option value='<?php echo $equipamentos[$i]['E_id'] ?>' <?php if($equipamentos[$i]['E_id'] == $id_equipamento){ echo "selected = 'selected' "; } ?> ><?php echo $equipamentos[$i]['C_nome'].": ".$equipamentos[$i]['E_nome']; ?></option>
			<?php	} ?>
		</select>
		<label for="componente">Componente:</label>
		<select name="componente" id="componente">
			<option value='0'>Selecione o Componente</option>
			<?php 	for($i=0;$i<count($componentes);$i++){ ?>
			<option value='<?php echo $componentes[$i]['E_id'] ?>'><?php echo $componentes[$i]['C_nome'].": ".$componentes[$i]['E_nome']." ".$componentes[$i]['E_descricao']; ?></option>
			<?php	} ?>
		</select>
		<input type="hidden" name="id_pc" value="<?php echo $id_equipamento; ?>" >
	</fieldset>
</form>
<?php	} else if(isset($id_usuario)){ ?>
<form id="atribuir-equipamento" action="/novo/relacionar_equipamento_usuario.php" method="post">
	<fieldset>
		<label for="usuario">Usuário:</label>
		<select name="nome" id="usuario">
			<option value='0'>Selecione o Usuário</option>
			<?php 	for($i=0;$i<count($usuarios);$i++){ ?>
			<option value='<?php echo $usuarios[$i]['U_id'] ?>' <?php if($usuarios[$i]['U_id'] == $id_usuario){ echo "selected = 'selected' "; } ?>><?php echo $usuarios[$i]['S_nome'].": ".$usuarios[$i]['U_nome']; ?></option>
			<?php	} ?>
		</select>
		<input type="hidden" name="id_pc" value="<?php echo $id_equipamento; ?>" >
		<label for="equipamento">Equipamento:</label>
		<select name="equipamento" id="equipamento">
			<option value='0'>Selecione o Equipamento</option>
			<?php 	for($i=0;$i<count($equipamentos);$i++){ ?>
			<option value='<?php echo $equipamentos[$i]['E_id'] ?>'><?php echo $equipamentos[$i]['C_nome'].": ".$equipamentos[$i]['E_nome']; ?></option>
			<?php	} ?>
		</select>
	</fieldset>
</form>
<?php	} ?>