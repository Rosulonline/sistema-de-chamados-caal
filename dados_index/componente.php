<?php
	include("../funcoes.php");
	$login = protegePagina();
	$classes = retorna_classes();
	if(isset($_GET['id_componente'])){
		$id_componente = $_GET['id_componente'];
		$componente = retorna_dados_equipamento($_GET['id_componente']);
	}
	if(isset($_GET['id_pc'])){
		$id_computador = $_GET['id_pc'];
	}
	
	
	if(isset($componente)){ ?>
<form id="atualiza-componente" action="/atualizar/equipamento.php?id_equipamento=<?php echo $componente['E_id']; ?>" method="post">
	<fieldset>
		<label for="nome">Marca:</label>
		<input type="text" name="nome" id="nome" value="<?php echo $componente['E_nome']; ?>" class="text ui-widget-content ui-corner-all">
	
		<label for="descricao">Versão:</label>
		<textarea id="descricao" name="descricao" rows="5" cols="100" class="text ui-widget-content ui-corner-all"><?php echo $componente['E_descricao']; ?></textarea>

		<label for="ns">Número de série:</label>
		<input type="text" name="ns" id="ns" value="<?php echo $componente['E_ns']; ?>" class="text ui-widget-content ui-corner-all" >

		<label for="nf">Nota fiscal:</label>
		<input type="text" name="nf" id="nf" value="<?php echo $componente['E_nf']; ?>" class="text ui-widget-content ui-corner-all" >

		<label for="mac">MAC:</label>
		<input type="text" name="mac" id="mac" value="<?php echo $componente['E_mac']; ?>" class="text ui-widget-content ui-corner-all" >

		<label for="ip">IP:</label>
		<input type="text" name="ip" id="ip" value="<?php echo $componente['E_ip']; ?>" class="text ui-widget-content ui-corner-all" >

		<label for="classe">Tipo Equipamento:</label>
		<select name="classe" id="classe">
			<option value="">Selecione o tipo</option>
<?php		for($i=0;$i<count($classes);$i++) { ?>

			<option <?php if($classes[$i]['C_id'] == $componente['C_id']){ echo "selected='selected'"; } ?> value="<?php echo $classes[$i]['C_id']; ?>"><?php echo $classes[$i]['C_nome']; ?></option>
<?php		} ?>
		</select>
	</fieldset>
</form>
<form style="display:none;" id="remove-componente" action="/novo/remover_componente.php" method="get">
	<input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>" >
	<input type="hidden" name="id_pc" value="<?php echo $id_computador; ?>" >
</form><!-- #remove-componente -->
<form style="display:none;" id="mover-componente" action="/dados_index/equipamento_transferir.php" method="post">
	<input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>" >
	<input type="hidden" name="id_pc" value="<?php echo $id_computador; ?>" >
</form><!-- #remove-componente -->
<?php	}else if(isset($id_computador)){ ?>
	

<form id="novo-componente" action="/novo/equipamento.php?id_pc=<?php echo $id_computador; ?>" method="post">
	<fieldset>
		<label for="classe">Tipo Equipamento:</label>
		<select name="classe" id="classe">
			<option value="">Selecione o tipo</option>
<?php		for($i=0;$i<count($classes);$i++) { ?>

			<option value="<?php echo $classes[$i]['C_id']; ?>"><?php echo $classes[$i]['C_nome']; ?></option>
<?php		} ?>
		</select>
		
		<label for="nome">Marca:</label>
		<input type="text" name="nome" id="nome" class="text ui-widget-content ui-corner-all">
	
		<label for="descricao">Versão/Modelo:</label>
		<textarea id="descricao" name="descricao" rows="5" cols="100" class="text ui-widget-content ui-corner-all"></textarea>

		<label for="ns">Número de série:</label>
		<input type="text" name="ns" id="ns" class="text ui-widget-content ui-corner-all" >

		<label for="nf">Nota fiscal:</label>
		<input type="text" name="nf" id="nf" class="text ui-widget-content ui-corner-all" >
		
		<!--
		<label for="mac">MAC:</label>
		<input type="text" name="mac" id="mac" class="text ui-widget-content ui-corner-all" >

		<label for="ip">IP:</label>
		<input type="text" name="ip" id="ip" class="text ui-widget-content ui-corner-all" >
		-->
		
	</fieldset>
</form><!-- #atualiza-componente -->
<?php	}else{ ?>
<p> Nenhum Registro </p>
<?php	} ?>