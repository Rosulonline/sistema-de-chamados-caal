<?php
	include_once '../funcoes.php';
	$adm = protegePagina();
	
	if ($adm == false){
		header("Location: sair.php");
	}
		
	if(isset($_GET['id_filial'])){
		$id_filial = $_GET['id_filial'];
	}else{
		$id_filial = NULL; 
	}
	
	if(isset($_GET['id_setor'])){
		$id_setor = $_GET['id_setor'];
	}else{
		$id_setor = NULL;
	}
	
	$filiais = retorna_filiais();
	$setores = retorna_setores();
	
?>
<style type="text/css">
	#form-usuario-cadastro fieldset{
		padding:0;
		border:0;
		margin-top:25px;
	}
	#form-usuario-cadastro label,
	#form-usuario-cadastro input,
	#form-usuario-cadastro textarea{
		display:block;
	}	
	#form-usuario-cadastro select,
	#form-usuario-cadastro textarea,
	#form-usuario-cadastro input.text{
		margin-bottom:12px;
		width:95%;
		padding: .4em;
	}
</style>
<form id="form-usuario-cadastro" action="/novo/usuario.php" method="post">
	<fieldset>
		<label for="nome">Nome:</label>
		<input type="text" name="nome" id="nome"  class="text ui-widget-content ui-corner-all">
		
		<label for="ramal">Ramal:</label>
		<input type="text" name="ramal" id="ramal" class="text ui-widget-content ui-corner-all">
		
		<label for="email">E-mail:</label>
		<input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all">
		
		<label for="usuario">Usuário:</label>
		<input type="text" name="usuario" id="usuario" class="text ui-widget-content ui-corner-all">
		
		<label for="senha">Senha:</label>
		<input type="text" name="senha" id="senha" class="text ui-widget-content ui-corner-all">
		
		<label for="msn">MSN:</label>
		<input type="checkbox" name="msn" id="msn">
		
		<label for="net">Internet:</label>
		<input type="checkbox" name="net" id="net">
		
		<label for="disp">Pendrive:</label>
		<input type="checkbox" name="disp" id="disp">
		
		<label for="setor">Setor:</label>
		<select name="setor" id="setor">
			<option>Selecione o Setor</option>
			<?php	for($i=0;$i<count($setores);$i++){ ?>
			
			<option value="<?php echo $setores[$i]['S_id']; ?>"><?php echo $setores[$i]['S_nome']; ?></option>
			<?php	} ?>
			
		</select>								
		
		<input type="hidden" name="ativo" value="true" id="ativo">		
	</fieldset>
</form>