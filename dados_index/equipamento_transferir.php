<?php
	include("../funcoes.php");
	$login = protegePagina();
	
	if(isset($_GET['id_equipamento']) && isset($_GET['id_usuario'])){
		$id_equipamento = $_GET['id_equipamento'];
		$equipamento = retorna_dados_equipamento($id_equipamento);
		$equipamentos = retorna_equipamentos(true, false, NULL, NULL);
		$usuarios = retorna_usuarios(null,null,null,TRUE, 1);
	}else if(isset($_GET['id_componente']) && isset($_GET['id_pc'])){
		$id_componente = $_GET['id_componente'];
		$id_pc = $_GET['id_pc'];
		$equipamentos = retorna_equipamentos(true, false, NULL, NULL);
		$componente = retorna_dados_equipamento($id_componente);
	}
?>

<style type="text/css">
	#form-transferir-componente label,
	#form-transferir-equipamento label,
	#form-transferir-componente input,
	#form-transferir-equipamento input,
	#form-transferir-componente textarea,
	#form-transferir-equipamento textarea{
		display:block;
	}
	
	#form-transferir-componente select,
	#form-transferir-equipamento select,
	#form-transferir-componente textarea,
	#form-transferir-equipamento textarea,
	#form-transferir-componente input.text,
	#form-transferir-equipamento input.text{
		margin-bottom:12px;
		width:95%;
		padding: .4em;
	}
	
	#form-transferir-componente fieldset,
	#form-transferir-equipamento fieldset{
		padding:0;
		border:0;
		margin-top:25px;
	}
</style>


<?php	if(isset($id_equipamento)){ ?>

<form id="form-transferir-equipamento" action="/novo/transferir_equipamento.php" method="post">
	<fieldset>		
		<input type="hidden" name="id_equipamento" value="<?php echo $id_equipamento; ?>" >

		<label for="motivo">Motivo:</label>
		<textarea id="motivo" name="motivo" rows="5" cols="100"></textarea>
		
		<label for="usuarios"><b>Transferir: <?php echo $equipamento['E_nome'] ; ?></b></label>
		</br>
		
		<label for="usuarios">Para</label>
		<select name="usuario" id="usuarios">
			<option>Selecione o Usuário</option>
			<?php
				for($i=0;$i<count($usuarios);$i++){
					echo "<option value=\"".$usuarios[$i]['U_id']."\">".$usuarios[$i]['U_nome']." - ".$usuarios[$i]['S_nome']."</option>\n";
				} 
			?>
		</select>
	</fieldset>
</form>
<?php	}else if(isset($id_componente) && isset($id_pc)){ ?>

<form id="form-transferir-componente" action="/novo/transferir_equipamento.php" method="post">
	<fieldset>
		<input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>" >
		<input type="hidden" name="id_pc_antigo" value="<?php echo $id_pc; ?>" >
		<label for="motivo">Motivo:</label>
		<textarea id="motivo" name="motivo" rows="5" cols="100"></textarea>
		<label for="computador"><b>Transferir: <?php echo $componente['E_nome']." ".$componente['E_descricao'] ; ?></b></label>
		</br>
		<label for="computador">Para</label>
		<select name="id_pc_novo" id="computador">
			<option>Selecione o Equipamento</option>
			<?php	for($i=0;$i<count($equipamentos);$i++){ ?>
			
			<option value="<?php echo $equipamentos[$i]['E_id']; ?>"><?php echo $equipamentos[$i]['C_nome'].": ".$equipamentos[$i]['E_nome']; ?></option>
			<?php	} ?>
		</select>
	</fieldset>
</form>
<?php	}else{ ?>
<p>Dados incorretos: id_equipamento='<?php echo $_POST['id_equipamento']; ?>', id_usuario='<?php echo $_POST['id_usuario']; ?>' </p>
<?php	} ?>